<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Http\Request;

use App\Traits\ResolvesImagePath;
use Illuminate\Support\Facades\Log;

class CompositionApiController extends Controller
{
    use ResolvesImagePath;

    /**
     * Get compositions that are marked to be showcased in the inspiration page.
     */
    public function showcase()
    {
        try {
            $compositions = Composition::where('is_showcase', 1)
                ->with(['category_rel', 'collections', 'products.category', 'products.collection', 'products.colors.colorMaster'])
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($comp) {
                    
                    $productsUsed = $comp->products->map(function($product) {
                        $firstColor = $product->colors->first();
                        
                        // Image priority: lighton_image -> main_image
                        $image = null;
                        if ($firstColor) {
                            if ($firstColor->lighton_image) {
                                $image = $this->resolveImagePath($firstColor->lighton_image, 'uploads/decorative');
                            } elseif ($firstColor->main_image) {
                                $image = $this->resolveImagePath($firstColor->main_image, 'uploads/decorative');
                            }
                        }
                        if (!$image && $product->featured_image) {
                            $image = $this->resolveImagePath($product->featured_image, 'uploads/decorative');
                        }

                    // Get colors and images from colors
                    $variants = [];
                    $seenColors = [];
                    foreach ($product->colors as $color) {
                        $cImage = null;
                        if ($color->lighton_image) {
                            $cImage = $this->resolveImagePath($color->lighton_image, 'uploads/decorative');
                        } elseif ($color->main_image) {
                            $cImage = $this->resolveImagePath($color->main_image, 'uploads/decorative');
                        }
                        
                        // Fallback to default product image if color doesn't have one
                        if (!$cImage) {
                            $cImage = $image; 
                        }

                        $colorHex = null;
                        if ($color->colorMaster && $color->colorMaster->css_value) {
                            $colorHex = $color->colorMaster->css_value;
                        } elseif ($color->colorMaster && $color->colorMaster->code) {
                            $colorHex = $color->colorMaster->code;
                        }

                        if ($colorHex && !in_array($colorHex, $seenColors)) {
                            $seenColors[] = $colorHex;
                            $variants[] = [
                                'color' => $colorHex,
                                'image' => $cImage
                            ];
                        }
                    }

                    return [
                        'name' => $product->name,
                        'type' => $product->category ? $product->category->name : 'Decorative',
                        'image' => $image, // default image
                        'colors' => $seenColors, // legacy, can keep
                        'variants' => $variants,
                        'collection' => $product->collection ? $product->collection->name : null,
                        'link' => '/product-detail/' . $product->slug,
                    ];
                });

                $coll = $comp->collections ? $comp->collections->first() : null;

                return [
                    'id' => $comp->id,
                    'title' => $comp->title,
                    'kicker' => $comp->kicker,
                    'category' => $comp->category_rel ? $comp->category_rel->name : $comp->category,
                    'collection' => $coll ? $coll->name : null,
                    'image' => $this->resolveImagePath($comp->image, 'uploads/compositions'),
                    'products' => $productsUsed
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $compositions
            ]);
        } catch (\Exception $e) {
            Log::error('CompositionApiController::showcase — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load showcase compositions'
            ], 500);
        }
    }
}
