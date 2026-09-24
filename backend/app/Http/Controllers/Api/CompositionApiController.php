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
            ->with(['category_rel', 'products.category', 'products.collection', 'products.variants.colorMaster'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($comp) {
                
                $productsUsed = $comp->products->map(function($product) {
                    $firstVariant = $product->variants->first();
                    
                    // Image priority: lighton_image -> main_image
                    $image = null;
                    if ($firstVariant) {
                        if ($firstVariant->lighton_image) {
                            $image = asset('storage/uploads/decorative/' . $firstVariant->lighton_image);
                        } elseif ($firstVariant->main_image) {
                            $image = asset('storage/uploads/decorative/' . $firstVariant->main_image);
                        }
                    }
                    if (!$image && $product->featured_image) {
                        $image = asset('storage/uploads/decorative/' . $product->featured_image);
                    }

                    // Get colors and images from variants
                    $variants = [];
                    $seenColors = [];
                    foreach ($product->variants as $variant) {
                        $vImage = null;
                        if ($variant->lighton_image) {
                            $vImage = asset('storage/uploads/decorative/' . $variant->lighton_image);
                        } elseif ($variant->main_image) {
                            $vImage = asset('storage/uploads/decorative/' . $variant->main_image);
                        }
                        
                        // Fallback to default product image if variant doesn't have one
                        if (!$vImage) {
                            $vImage = $image; 
                        }

                        $colorHex = null;
                        if ($variant->colorMaster && $variant->colorMaster->css_value) {
                            $colorHex = $variant->colorMaster->css_value;
                        } elseif ($variant->colorMaster && $variant->colorMaster->code) {
                            $colorHex = $variant->colorMaster->code;
                        }

                        if ($colorHex && !in_array($colorHex, $seenColors)) {
                            $seenColors[] = $colorHex;
                            $variants[] = [
                                'color' => $colorHex,
                                'image' => $vImage
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
                        'link' => '/decorative-products/' . $product->slug,
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
