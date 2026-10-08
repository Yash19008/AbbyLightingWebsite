<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProduct;
use Illuminate\Http\Request;

use App\Traits\ResolvesImagePath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DecorativeProductApiController extends Controller
{
    use ResolvesImagePath;

    /**
     * Display a listing of decorative products.
     */
    public function index(Request $request)
    {
        try {
            $query = DecProduct::with([
                'category', 
                'collection', 
                'colors' => function($q) { 
                    $q->orderBy('order'); 
                }, 
                'colors.colorMaster', 
                'galleries' => function($q) { 
                    $q->orderBy('order')->limit(3); 
                }
            ])->where('status', 'published');

            if ($request->has('category')) {
                $categories = array_filter(explode(',', $request->get('category')));
                if (count($categories) > 0) {
                    $query->whereHas('category', function($q) use ($categories) {
                        $q->whereIn('name', $categories)
                          ->orWhereIn('slug', $categories);
                    });
                }
            }

            if ($request->has('collection')) {
                $collections = array_filter(explode(',', $request->get('collection')));
                if (count($collections) > 0) {
                    $query->whereHas('collection', function($q) use ($collections) {
                        $q->whereIn('name', $collections);
                    });
                }
            }

            if ($request->has('finish')) {
                $finishes = array_filter(explode(',', $request->get('finish')));
                if (count($finishes) > 0) {
                    $query->whereHas('colors.colorMaster', function($q) use ($finishes) {
                        $q->whereIn('name', $finishes)
                          ->orWhereIn('code', $finishes);
                    });
                }
            }

            $sort = $request->get('sort', 'new');
            if ($sort === 'popular' || $sort === 'most_popular') {
                $query->orderBy('order', 'asc')->orderBy('id', 'desc');
            } elseif ($sort === 'new') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sort === 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($sort === 'name_desc') {
                $query->orderBy('name', 'desc');
            } else {
                $query->orderBy('order', 'asc');
            }

            $perPage = min((int) $request->get('per_page', 8), 50);
            $products = $query->paginate($perPage);

            $products->getCollection()->transform(function ($product) {
                $variants = $product->colors->map(function ($color) {
                    return [
                        'id' => $color->id,
                        'name' => $color->colorMaster ? $color->colorMaster->name : '',
                        'color' => $color->colorMaster ? $color->colorMaster->css_value : '#e0e0e0',
                        'imageOff' => $this->resolveImagePath($color->main_image, 'uploads/decorative'),
                        'imageOn' => $this->resolveImagePath($color->lighton_image, 'uploads/decorative')
                    ];
                });

                $galleries = $product->galleries->map(function ($gallery) {
                    return [
                        'id' => $gallery->id,
                        'image' => $this->resolveImagePath($gallery->image, 'uploads/decorative_gallery')
                    ];
                });

                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : 'Uncategorized',
                    'collection' => $product->collection ? $product->collection->name : 'General',
                    'isNew' => $product->created_at ? $product->created_at->diffInDays(now()) <= 30 : false,
                    'variants' => $variants,
                    'galleries' => $galleries
                ];
            });

            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('DecorativeProductApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch decorative products',
            ], 500);
        }
    }

    /**
     * Get featured categories for frontend tabs
     */
    public function categories()
    {
        // Strictly return only categories marked as is_featured = 1 for the frontend category tabs
        $categories = \App\Models\Decorative\DecCategory::where('is_featured', 1)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get all collections for frontend filter
     */
    public function collections()
    {
        $collections = \App\Models\Collection::where('is_active', true)->orderBy('name', 'asc')->get();
            
        return response()->json([
            'success' => true,
            'data' => $collections
        ]);
    }

    /**
     * Get all available finishes (colors) used by published decorative products
     */
    public function finishes()
    {
        $colors = \App\Models\ColorMaster::whereHas('decProductColors.product', function($q) {
                $q->where('status', 'published');
            })
            ->active()
            ->ordered()
            ->get(['id', 'name', 'code', 'type', 'hex_code', 'gradient_start', 'gradient_end']);

        // Since css_value is an appended attribute, it will be automatically included in the JSON representation
        return response()->json([
            'success' => true,
            'data' => $colors
        ]);
    }



    /**
     * Display the specified decorative product by slug.
     */
    public function show($slug)
    {
        try {
            $product = DecProduct::with([
                'category',
                'collection.heroSection', // Load collection hero section for band image
                'colors' => function ($query) {
                    $query->orderBy('order');
                },
                'colors.colorMaster',
                'specRows' => function ($query) {
                    $query->orderBy('order');
                },
                'specRows.attribute',
                'sizes' => function ($query) {
                    $query->orderBy('order');
                },
                'sizes.specRows' => function ($query) {
                    $query->orderBy('order');
                },
                'sizes.specRows.attribute',
                'galleries' => function ($query) {
                    $query->orderBy('order');
                }
            ])->where('slug', $slug)
              ->where('status', 'published')
              ->firstOrFail();

            // Transform response for frontend
            $variants = $product->colors->map(function ($color) {
                return [
                    'id' => $color->id,
                    'name' => $color->colorMaster ? $color->colorMaster->name : '',
                    'sku' => null,
                    'main_image' => $this->resolveImagePath($color->main_image, 'uploads/decorative'),
                'lighton_image' => $this->resolveImagePath($color->lighton_image, 'uploads/decorative'),
                'color_master' => $color->colorMaster ? [
                    'name' => $color->colorMaster->name,
                    'type' => $color->colorMaster->type,
                    'hex_code' => $color->colorMaster->hex_code,
                    'gradient_start' => $color->colorMaster->gradient_start,
                    'gradient_end' => $color->colorMaster->gradient_end,
                    'css_value' => $color->colorMaster->css_value
                ] : null,
            ];
        });

        $globalBasicSpecs = [];
        $globalDimensions = [];
        
        foreach ($product->specRows as $row) {
            $specData = [
                'label' => $row->attribute ? $row->attribute->name : '',
                'value' => $row->value,
                'value_type' => $row->value_type,
                'note' => $row->note
            ];
            if ($row->section === 'basic_specifications') {
                $globalBasicSpecs[] = $specData;
            } else if ($row->section === 'dimensions') {
                $globalDimensions[] = $specData;
            }
        }

        $sizes = collect();
        if ($product->sizes->count() > 0) {
            $sizes = $product->sizes->map(function ($size) use ($globalBasicSpecs) {
                $dimensions = [];
                
                foreach ($size->specRows as $row) {
                    if ($row->section === 'dimensions') {
                        $dimensions[] = [
                            'label' => $row->attribute ? $row->attribute->name : '',
                            'value' => $row->value,
                            'value_type' => $row->value_type,
                            'note' => $row->note
                        ];
                    }
                }
                
                return [
                    'id' => $size->id,
                    'label' => $size->label,
                    'code' => $size->code,
                    'spec_rows' => [
                        'basic_specifications' => $globalBasicSpecs,
                        'dimensions' => $dimensions
                    ]
                ];
            });
        } else if (count($globalBasicSpecs) > 0 || count($globalDimensions) > 0) {
            $sizes->push([
                'id' => 0,
                'label' => 'Standard',
                'spec_rows' => [
                    'basic_specifications' => $globalBasicSpecs,
                    'dimensions' => $globalDimensions
                ]
            ]);
        }



        $relatedManualIds = DB::table('dec_product_related')->where('product_id', $product->id)->pluck('related_product_id');

        $relatedProducts = DecProduct::with([
            'category',
            'collection',
            'colors' => function ($q) {
                $q->orderBy('order');
            },
            'colors.colorMaster',
            'galleries' => function ($q) {
                $q->orderBy('order')->limit(3);
            }
        ])
        ->where('status', 'published')
        ->where('id', '!=', $product->id)
        ->where(function($q) use ($product, $relatedManualIds) {
            if ($product->collection_id) {
                $q->where('collection_id', $product->collection_id);
            }
            if ($relatedManualIds->isNotEmpty()) {
                $q->orWhereIn('id', $relatedManualIds);
            }
        })
        ->orderBy('order', 'asc')
        ->limit(10)
        ->get();

        $response = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'featured_image' => $this->resolveImagePath($product->featured_image, 'uploads/decorative'),
            'installation_guide' => $this->resolveImagePath($product->installation_guide, 'uploads/decorative/downloads'),
            'care_instructions' => $this->resolveImagePath($product->care_instructions, 'uploads/decorative/downloads'),
            'show_family_section' => $product->show_family_section,
            'collection' => $product->collection ? [
                'name' => $product->collection->name,
                'slug' => $product->collection->slug,
                'short_description' => ($product->collection->heroSection && $product->collection->heroSection->description) 
                    ? $product->collection->heroSection->description 
                    : ($product->collection->short_description ?: $product->collection->description),
                'band_image' => ($product->collection->heroSection && $product->collection->heroSection->background_image) 
                    ? $this->resolveImagePath($product->collection->heroSection->background_image, 'collections/hero') 
                    : null
            ] : null,
            'category' => $product->category ? [
                'name' => $product->category->name
            ] : null,
            'variants' => $variants,
            'sizes' => $sizes,
            'galleries' => $product->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $this->resolveImagePath($gallery->image, 'uploads/decorative_gallery'),
                    'caption' => $gallery->caption,
                    'order' => $gallery->order
                ];
            }),
            'related_products' => $relatedProducts->map(function ($related) {
                $variants = $related->colors->map(function ($color) {
                    $cssColor = $color->colorMaster ? $color->colorMaster->css_value : '#e0e0e0';
                    return [
                        'id' => $color->id,
                        'name' => $color->colorMaster ? $color->colorMaster->name : '',
                        'color' => $cssColor,
                        'imageOff' => $this->resolveImagePath($color->main_image, 'uploads/decorative'),
                        'imageOn' => $this->resolveImagePath($color->lighton_image, 'uploads/decorative')
                    ];
                });

                $galleries = $related->galleries->map(function ($gallery) {
                    return [
                        'id' => $gallery->id,
                        'image' => $this->resolveImagePath($gallery->image, 'uploads/decorative_gallery')
                    ];
                });

                return [
                    'id' => $related->id,
                    'slug' => $related->slug,
                    'name' => $related->name,
                    'category' => $related->category ? $related->category->name : 'Uncategorized',
                    'collection' => $related->collection ? $related->collection->name : 'General',
                    'isNew' => false,
                    'variants' => $variants,
                    'galleries' => $galleries
                ];
            })
        ];

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Decorative product not found',
            ], 404);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DecorativeProductApiController::show � ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch decorative product',
            ], 500);
        }
    }

    /**
     * Get paginated related products for a product.
     */
    public function relatedProducts(Request $request, $slug)
    {
        try {
            $product = DecProduct::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedManualIds = DB::table('dec_product_related')->where('product_id', $product->id)->pluck('related_product_id');

        $query = DecProduct::with([
            'category',
            'collection',
            'colors' => function ($q) {
                $q->orderBy('order');
            },
            'colors.colorMaster',
            'galleries' => function ($q) {
                $q->orderBy('order')->limit(3);
            }
        ])
        ->where('status', 'published')
        ->where('id', '!=', $product->id)
        ->where(function($q) use ($product, $relatedManualIds) {
            if ($product->collection_id) {
                $q->where('collection_id', $product->collection_id);
            }
            if ($relatedManualIds->isNotEmpty()) {
                $q->orWhereIn('id', $relatedManualIds);
            }
        })
        ->orderBy('order', 'asc');

        $paginator = $query->paginate($request->get('per_page', 10));

        $paginator->getCollection()->transform(function ($related) {
            $variants = $related->colors->map(function ($color) {
                $cssColor = $color->colorMaster ? $color->colorMaster->css_value : '#e0e0e0';
                return [
                    'id' => $color->id,
                    'name' => $color->colorMaster ? $color->colorMaster->name : '',
                    'color' => $cssColor,
                    'imageOff' => $this->resolveImagePath($color->main_image, 'uploads/decorative'),
                    'imageOn' => $this->resolveImagePath($color->lighton_image, 'uploads/decorative')
                ];
            });

            $galleries = $related->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $this->resolveImagePath($gallery->image, 'uploads/decorative_gallery')
                ];
            });

            return [
                'id' => $related->id,
                'slug' => $related->slug,
                'name' => $related->name,
                'category' => $related->category ? $related->category->name : 'Uncategorized',
                'collection' => $related->collection ? $related->collection->name : 'General',
                'isNew' => false,
                'variants' => $variants,
                'galleries' => $galleries
            ];
        });

            return response()->json([
                'success' => true,
                'data' => $paginator
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Decorative product not found',
            ], 404);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('DecorativeProductApiController::relatedProducts � ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch related decorative products',
            ], 500);
        }
    }
}
