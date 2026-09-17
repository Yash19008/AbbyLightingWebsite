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
            'variants' => function($q) { 
                $q->where('status', 'active')->orderBy('order'); 
            }, 
            'variants.colorMaster', 
            'galleries' => function($q) { 
                $q->orderBy('order')->limit(3); 
            }
        ])->where('status', 'published');

        if ($request->has('category')) {
            $categories = array_filter(explode(',', $request->get('category')));
            if (count($categories) > 0) {
                $query->whereHas('category', function($q) use ($categories) {
                    $q->whereIn('name', $categories);
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
        if ($sort === 'new') {
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
            $variants = $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'color' => $variant->colorMaster ? $variant->colorMaster->css_value : '#e0e0e0',
                    'imageOff' => $this->getImagePath($variant->main_image, 'uploads/decorative'),
                    'imageOn' => $this->getImagePath($variant->lighton_image, 'uploads/decorative')
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
        $categories = \App\Models\Decorative\DecCategory::where('is_featured', true)
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
        if (str_starts_with($filename, 'http')) return $filename;
        if (str_contains($filename, '/')) return url('/') . '/' . ltrim($filename, '/'); // Already has a path
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
            'variants' => function ($query) {
                $query->where('status', 'active')->orderBy('order');
            },
            'variants.colorMaster',
            'variants.specRows' => function ($query) {
                $query->orderBy('order');
            },
            'variants.specRows.attribute',
            'galleries' => function ($query) {
                $query->orderBy('order');
            },
            'relatedProducts.category',
            'relatedProducts.collection',
            'relatedProducts.variants' => function ($q) {
                $q->where('status', 'active')->orderBy('order');
            },
            'relatedProducts.variants.colorMaster',
            'relatedProducts.galleries' => function ($q) {
                $q->orderBy('order')->limit(3);
            }
        ])->where('slug', $slug)
          ->where('status', 'published')
          ->firstOrFail();

        // Transform response for frontend
        $variants = $product->variants->map(function ($variant) {
            $basicSpecs = [];
            $dimensions = [];
            
            foreach ($variant->specRows as $row) {
                $specData = [
                    'label' => $row->attribute ? $row->attribute->name : '',
                    'value' => $row->value,
                    'note' => $row->note
                ];
                
                if ($row->section === 'basic_specifications') {
                    $basicSpecs[] = $specData;
                } else if ($row->section === 'dimensions') {
                    $dimensions[] = $specData;
                }
            }
            
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'sku' => $variant->sku,
                'size' => $variant->size,
                'main_image' => $this->getImagePath($variant->main_image, 'uploads/decorative'),
                'lighton_image' => $this->getImagePath($variant->lighton_image, 'uploads/decorative'),
                'color_master' => $variant->colorMaster ? [
                    'name' => $variant->colorMaster->name,
                    'type' => $variant->colorMaster->type,
                    'hex_code' => $variant->colorMaster->hex_code,
                    'gradient_start' => $variant->colorMaster->gradient_start,
                    'gradient_end' => $variant->colorMaster->gradient_end,
                ] : null,
                'spec_rows' => [
                    'basic_specifications' => $basicSpecs,
                    'dimensions' => $dimensions
                ]
            ];
        });



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
                'short_description' => $product->collection->heroSection ? $product->collection->heroSection->description : $product->collection->short_description,
                'band_image' => $product->collection->heroSection ? $this->getImagePath($product->collection->heroSection->background_image, 'collections/hero') : null
            ] : null,
            'category' => $product->category ? [
                'name' => $product->category->name
            ] : null,
            'variants' => $variants,
            'galleries' => $product->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $this->getImagePath($gallery->image, 'uploads/decorative_gallery'),
                    'caption' => $gallery->caption,
                    'order' => $gallery->order
                ];
            }),
            'related_products' => $product->relatedProducts->map(function ($related) {
                $variants = $related->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'color' => $variant->colorMaster ? $variant->colorMaster->css_value : '#e0e0e0',
                        'imageOff' => $this->getImagePath($variant->main_image, 'uploads/decorative'),
                        'imageOn' => $this->getImagePath($variant->lighton_image, 'uploads/decorative')
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
}
