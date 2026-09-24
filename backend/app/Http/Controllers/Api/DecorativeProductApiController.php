<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProduct;
use Illuminate\Http\Request;

class DecorativeProductApiController extends Controller
{
    /**
     * Display a listing of decorative products.
     */
    public function index(Request $request)
    {
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

        $products = $query->paginate($request->get('per_page', 8));

        $products->getCollection()->transform(function ($product) {
            $variants = $product->colors->map(function ($color) {
                return [
                    'id' => $color->id,
                    'name' => $color->colorMaster ? $color->colorMaster->name : '',
                    'color' => $color->colorMaster ? $color->colorMaster->css_value : '#e0e0e0',
                    'imageOff' => $this->getImagePath($color->main_image, 'uploads/decorative'),
                    'imageOn' => $this->getImagePath($color->lighton_image, 'uploads/decorative')
                ];
            });

            $galleries = $product->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $this->getImagePath($gallery->image, 'uploads/decorative_gallery')
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

    private function getImagePath($filename, $directory) {
        if (!$filename) return null;
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) return $filename;
        if (str_starts_with($filename, 'storage/')) return asset($filename);
        if (str_starts_with($filename, 'collections/')) return asset('storage/' . $filename);
        if (str_starts_with($filename, 'uploads/')) return asset($filename);
        if (str_contains($filename, '/')) return asset($filename);
        return asset('storage/' . $directory . '/' . $filename);
    }

    /**
     * Display the specified decorative product by slug.
     */
    public function show($slug)
    {
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
        // Transform response for frontend
        $variants = $product->colors->map(function ($color) {
            return [
                'id' => $color->id,
                'name' => $color->colorMaster ? $color->colorMaster->name : '',
                'sku' => null,
                'main_image' => $this->getImagePath($color->main_image, 'uploads/decorative'),
                'lighton_image' => $this->getImagePath($color->lighton_image, 'uploads/decorative'),
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
                            'note' => $row->note
                        ];
                    }
                }
                
                return [
                    'id' => $size->id,
                    'label' => $size->label,
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



        $relatedManualIds = \DB::table('dec_product_related')->where('product_id', $product->id)->pluck('related_product_id');

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
            'featured_image' => $this->getImagePath($product->featured_image, 'uploads/decorative'),
            'installation_guide' => $this->getImagePath($product->installation_guide, 'uploads/decorative/downloads'),
            'care_instructions' => $this->getImagePath($product->care_instructions, 'uploads/decorative/downloads'),
            'show_family_section' => $product->show_family_section,
            'collection' => $product->collection ? [
                'name' => $product->collection->name,
                'slug' => $product->collection->slug,
                'short_description' => ($product->collection->heroSection && $product->collection->heroSection->description) 
                    ? $product->collection->heroSection->description 
                    : ($product->collection->short_description ?: $product->collection->description),
                'band_image' => ($product->collection->heroSection && $product->collection->heroSection->background_image) 
                    ? $this->getImagePath($product->collection->heroSection->background_image, 'collections/hero') 
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
                    'image' => $this->getImagePath($gallery->image, 'uploads/decorative_gallery'),
                    'caption' => $gallery->caption,
                    'order' => $gallery->order
                ];
            }),
            'related_products' => $relatedProducts->map(function ($related) {
                $variants = $related->colors->map(function ($color) {
                    $cssColor = '#e0e0e0';
                    if ($color->colorMaster) {
                        if ($color->colorMaster->type === 'gradient') {
                            $cssColor = 'linear-gradient(135deg, ' . $color->colorMaster->gradient_start . ', ' . $color->colorMaster->gradient_end . ')';
                        } else {
                            $cssColor = $color->colorMaster->hex_code ?: '#e0e0e0';
                        }
                    }
                    return [
                        'id' => $color->id,
                        'name' => $color->colorMaster ? $color->colorMaster->name : '',
                        'color' => $cssColor,
                        'imageOff' => $this->getImagePath($color->main_image, 'uploads/decorative'),
                        'imageOn' => $this->getImagePath($color->lighton_image, 'uploads/decorative')
                    ];
                });

                $galleries = $related->galleries->map(function ($gallery) {
                    return [
                        'id' => $gallery->id,
                        'image' => $this->getImagePath($gallery->image, 'uploads/decorative_gallery')
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

        return response()->json($response);
    }

    /**
     * Get paginated related products for a product.
     */
    public function relatedProducts(Request $request, $slug)
    {
        $product = DecProduct::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedManualIds = \DB::table('dec_product_related')->where('product_id', $product->id)->pluck('related_product_id');

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
                $cssColor = '#e0e0e0';
                if ($color->colorMaster) {
                    if ($color->colorMaster->type === 'gradient') {
                        $cssColor = 'linear-gradient(135deg, ' . $color->colorMaster->gradient_start . ', ' . $color->colorMaster->gradient_end . ')';
                    } else {
                        $cssColor = $color->colorMaster->hex_code ?: '#e0e0e0';
                    }
                }
                return [
                    'id' => $color->id,
                    'name' => $color->colorMaster ? $color->colorMaster->name : '',
                    'color' => $cssColor,
                    'imageOff' => $this->getImagePath($color->main_image, 'uploads/decorative'),
                    'imageOn' => $this->getImagePath($color->lighton_image, 'uploads/decorative')
                ];
            });

            $galleries = $related->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $this->getImagePath($gallery->image, 'uploads/decorative_gallery')
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

        return response()->json($paginator);
    }
}
