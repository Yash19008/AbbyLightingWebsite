<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Http\Request;

class CompositionApiController extends Controller
{
    /**
     * Get compositions that are marked to be showcased in the inspiration page.
     */
    public function showcase()
    {
        $compositions = Composition::where('is_showcase', 1)
            ->with(['category_rel', 'products.category', 'products.collection', 'products.colors.colorMaster'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($comp) {
                
                $productsUsed = $comp->products->map(function($product) {
                    $firstColor = $product->colors->first();
                    
                    // Image priority: lighton_image -> main_image
                    $image = null;
                    if ($firstColor) {
                        if ($firstColor->lighton_image) {
                            $image = asset('storage/uploads/decorative/' . $firstColor->lighton_image);
                        } elseif ($firstColor->main_image) {
                            $image = asset('storage/uploads/decorative/' . $firstColor->main_image);
                        }
                    }
                    if (!$image && $product->featured_image) {
                        $image = asset('storage/uploads/decorative/' . $product->featured_image);
                    }

                    // Get colors and images from colors
                    $variants = [];
                    $seenColors = [];
                    foreach ($product->colors as $color) {
                        $cImage = null;
                        if ($color->lighton_image) {
                            $cImage = asset('storage/uploads/decorative/' . $color->lighton_image);
                        } elseif ($color->main_image) {
                            $cImage = asset('storage/uploads/decorative/' . $color->main_image);
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

                return [
                    'id' => $comp->id,
                    'title' => $comp->title,
                    'kicker' => $comp->kicker,
                    'category' => $comp->category_rel ? $comp->category_rel->name : $comp->category,
                    'image' => $comp->image ? asset('storage/uploads/compositions/' . $comp->image) : null,
                    'products' => $productsUsed
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $compositions
        ]);
    }
}
