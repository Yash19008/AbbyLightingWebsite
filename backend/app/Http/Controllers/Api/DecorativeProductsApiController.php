<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DecorativeProduct;
use App\Models\DecorativeAttribute;
use Illuminate\Http\Request;

class DecorativeProductsApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Find the "finish" attribute once (by slug, fallback to first)
            $finishAttr = DecorativeAttribute::where('slug', 'finish')->first()
                ?? DecorativeAttribute::first();

            $sort = $request->get('sort', 'popular');

            $query = DecorativeProduct::with([
                'categories.parent',
                'primaryImage',
                'lightOnImage',
                'attributes.attribute',
                'attributes.values.attributeValue',
                'variations.attributeValues',
                'variations.galleryImages',
            ])
            ->where('status', 'active');

            if ($sort === 'new') {
                $query->orderBy('is_new_arrival', 'desc')->orderBy('created_at', 'desc');
            } elseif ($sort === 'name_asc') {
                $query->orderBy('title', 'asc');
            } elseif ($sort === 'name_desc') {
                $query->orderBy('title', 'desc');
            } else {
                $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
            }

            $products = $query->get();

            $data = $products->map(function ($product) use ($finishAttr) {
                // Category: prefer child category (has parent_id), fall back to first
                $childCategory = $product->categories->firstWhere('parent_id', '!=', null)
                    ?? $product->categories->first();
                $categoryName  = $childCategory?->name ?? '';
                $type          = $this->resolveType($categoryName);

                // Primary image URL
                $imageUrl = null;
                if ($product->primaryImage && $product->primaryImage->image) {
                    $imageUrl = url('storage/decorative_products/' . $product->primaryImage->image);
                }

                // Light on image URL
                $lightOnImageUrl = null;
                if ($product->lightOnImage && $product->lightOnImage->image) {
                    $lightOnImageUrl = url('storage/decorative_products/' . $product->lightOnImage->image);
                }

                // Finishes & palette: check variations first to get color-assigned variation images & gallery
                $finishes = [];
                $palette  = [];

                if ($product->relationLoaded('variations')) {
                    foreach ($product->variations as $var) {
                        if ($var->status === 'active') {
                            $varImg = $var->image ? url('storage/decorative_products/' . $var->image) : $imageUrl;
                            $varGallery = [];
                            if ($varImg) $varGallery[] = $varImg;
                            foreach ($var->galleryImages as $gImg) {
                                if ($gImg->image) {
                                    $varGallery[] = url('storage/decorative_products/' . $gImg->image);
                                }
                            }

                            foreach ($var->attributeValues as $av) {
                                $attrName = strtolower($av->attribute?->name ?? '');
                                if (str_contains($attrName, 'finish') || str_contains($attrName, 'color') || str_contains($attrName, 'colour') || ($av->hex_code && $av->hex_code !== '#cccccc')) {
                                    if (!collect($palette)->contains('name', $av->name)) {
                                        $finishes[] = $av->name;
                                        $palette[] = [
                                            'name'          => $av->name,
                                            'colour'        => $av->hex_code ?? '#cccccc',
                                            'image_url'     => $varImg,
                                            'gallery_images'=> $varGallery,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }

                // Fallback to product attributes if no variation finishes found
                if (empty($palette)) {
                    foreach ($product->attributes as $productAttr) {
                        if ($finishAttr && $productAttr->decorative_attribute_id == $finishAttr->id) {
                            foreach ($productAttr->values as $val) {
                                $av = $val->attributeValue;
                                if ($av) {
                                    $finishes[] = $av->name;
                                    $palette[]  = [
                                        'name'     => $av->name,
                                        'colour'   => $av->hex_code ?? '#cccccc',
                                        'image_url'=> $imageUrl,
                                    ];
                                }
                            }
                            break;
                        }
                    }
                }

                return [
                    'key'               => $product->slug,
                    'name'              => $product->title,
                    'type'              => $type,
                    'category'          => $categoryName,
                    'href'              => '/product-detail/' . $product->slug,
                    'finishes'          => $finishes,
                    'palette'           => $palette,
                    'isNew'             => (bool) ($product->is_new_arrival ?? false),
                    'image_url'         => $imageUrl,
                    'light_on_image_url'=> $lightOnImageUrl,
                    'short_description' => $product->short_description,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch decorative products',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Map category name → frontend tab type.
     */
    private function resolveType(string $categoryName): string
    {
        $lower = strtolower($categoryName);
        if (str_contains($lower, 'pendant')) return 'Pendant';
        if (str_contains($lower, 'wall'))    return 'Wall';
        if (str_contains($lower, 'floor'))   return 'Floor';
        if (str_contains($lower, 'table'))   return 'Table';
        return 'Pendant';
    }

    /**
     * Get single product details by slug.
     */
    public function show($slug)
    {
        try {
            $product = DecorativeProduct::with([
                'categories.parent',
                'primaryImage',
                'lightOnImage',
                'galleryImages',
                'attributes.attribute',
                'attributes.values.attributeValue',
                'variations.attributeValues.attribute',
                'variations.galleryImages',
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $childCategory = $product->categories->firstWhere('parent_id', '!=', null)
                ?? $product->categories->first();
            $categoryName  = $childCategory?->name ?? '';

            $primaryImageUrl = $product->primaryImage && $product->primaryImage->image
                ? url('storage/decorative_products/' . $product->primaryImage->image)
                : null;
            $lightOnImageUrl = $product->lightOnImage && $product->lightOnImage->image
                ? url('storage/decorative_products/' . $product->lightOnImage->image)
                : null;

            $galleryUrls = [];
            if ($primaryImageUrl) $galleryUrls[] = $primaryImageUrl;
            if ($lightOnImageUrl) $galleryUrls[] = $lightOnImageUrl;

            foreach ($product->galleryImages as $gImg) {
                if ($gImg->image) {
                    $galleryUrls[] = url('storage/decorative_products/' . $gImg->image);
                }
            }

            $swatches = [];
            $sizes = [];

            foreach ($product->attributes as $prodAttr) {
                $attrName = strtolower($prodAttr->attribute?->name ?? '');
                if (str_contains($attrName, 'finish') || str_contains($attrName, 'color') || str_contains($attrName, 'colour')) {
                    foreach ($prodAttr->values as $val) {
                        $av = $val->attributeValue;
                        if ($av) {
                            $swatches[] = [
                                'name'       => $av->name,
                                'hex'        => $av->hex_code ?? '#cccccc',
                                'code'       => strtoupper(substr($av->name, 0, 3)),
                                'stage_image'=> $primaryImageUrl,
                            ];
                        }
                    }
                } elseif (str_contains($attrName, 'size')) {
                    foreach ($prodAttr->values as $val) {
                        $av = $val->attributeValue;
                        if ($av) {
                            $sizes[] = [
                                'label' => $av->name,
                                'value' => $av->name,
                            ];
                        }
                    }
                }
            }

            // Fallback: If swatches or sizes are empty, extract them directly from variation attribute values
            foreach ($product->variations as $var) {
                if ($var->status === 'active') {
                    $varImg = $var->image ? url('storage/decorative_products/' . $var->image) : $primaryImageUrl;
                    foreach ($var->attributeValues as $av) {
                        $valName = $av->name;
                        $attrType = strtolower($av->attribute?->name ?? '');

                        if (is_numeric($valName) || str_contains($attrType, 'size')) {
                            if (!collect($sizes)->contains('value', $valName)) {
                                $sizes[] = ['label' => $valName, 'value' => $valName];
                            }
                        } else {
                            if (!collect($swatches)->contains('name', $valName)) {
                                $swatches[] = [
                                    'name'       => $valName,
                                    'hex'        => $av->hex_code ?? '#cccccc',
                                    'code'       => strtoupper(substr($valName, 0, 3)),
                                    'stage_image'=> $varImg,
                                ];
                            }
                        }
                    }
                }
            }

            $variationList = [];
            foreach ($product->variations as $var) {
                if ($var->status === 'active') {
                    $varAttrNames = $var->attributeValues->pluck('name')->toArray();
                    $varImg = $var->image ? url('storage/decorative_products/' . $var->image) : $primaryImageUrl;
                    $variationList[] = [
                        'id'        => $var->id,
                        'sku'       => $var->sku,
                        'attributes'=> $varAttrNames,
                        'image_url' => $varImg,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'                => $product->id,
                    'slug'              => $product->slug,
                    'title'             => $product->title,
                    'sku'               => $product->sku,
                    'category'          => $categoryName,
                    'short_description' => $product->short_description,
                    'description'       => $product->description,
                    'primary_image'     => $primaryImageUrl,
                    'light_on_image'    => $lightOnImageUrl,
                    'gallery_images'    => $galleryUrls,
                    'swatches'          => $swatches,
                    'sizes'             => $sizes,
                    'variations'        => $variationList,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching product detail',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
