<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CollectionApiController extends Controller
{
    /**
     * Get all active collections (list view)
     */
    public function index()
    {
        try {
            $collections = Collection::with('heroSection')
                ->active()
                ->ordered()
                ->get()
                ->map(function ($collection) {
                    return [
                        'id'                => $collection->id,
                        'slug'              => $collection->slug,
                        'name'              => $collection->name,
                        'short_description' => $collection->short_description,
                        'description'       => $collection->description,
                        'hero_section'      => $collection->heroSection ? [
                            'background_image' => $collection->heroSection->background_image
                                ? asset('storage/' . $collection->heroSection->background_image)
                                : null,
                            'title_prefix'    => $collection->heroSection->title_prefix,
                            'title_highlight' => $collection->heroSection->title_highlight,
                            'description'     => $collection->heroSection->description,
                        ] : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => $collections,
            ]);
        } catch (\Exception $e) {
            Log::error('CollectionApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch collections',
            ], 500);
        }
    }

    /**
     * Get single collection with all sections (detail view)
     */
    public function show($slug)
    {
        try {
            $collection = Collection::where('slug', $slug)
                ->with([
                'heroSection',
                'parametersSection.items' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'compositionsSection.items' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'compositions.category_rel',
                'compositions.products.category',
                'compositions.products.collection',
                'compositions.products.colors.colorMaster',
                'tonesSection.families' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'tonesSection.families.colors' => function ($query) {
                    $query->orderBy('collection_tone_family_colors.order');
                },
                'placesSection.items' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'spreadDropSection',
                'products'
            ])
            ->active()
            ->firstOrFail();

        $data = [
            'id' => $collection->id,
            'slug' => $collection->slug,
            'name' => $collection->name,
            'short_description' => $collection->short_description,
            'description' => $collection->description,
            'meta_title' => $collection->meta_title,
            'meta_description' => $collection->meta_description,
            
            // Combined Products (Standard) linked to this collection
            'products' => $collection->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'featured_image' => $product->featured_image 
                        ? asset('storage/uploads/products/' . $product->featured_image) 
                        : null,
                    'is_decorative' => false,
                ];
            }),
            
            // Hero Section
            'hero_section' => $collection->heroSection && $collection->heroSection->is_active ? [
                'background_image' => $collection->heroSection->background_image 
                    ? asset('storage/' . $collection->heroSection->background_image) 
                    : null,
                'title_prefix' => $collection->heroSection->title_prefix,
                'title_highlight' => $collection->heroSection->title_highlight,
                'description' => $collection->heroSection->description,
                'breadcrumb_parent_text' => $collection->heroSection->breadcrumb_parent_text,
                'breadcrumb_parent_link' => $collection->heroSection->breadcrumb_parent_link,
            ] : null,
            
            // Parameters Section
            'parameters_section' => $collection->parametersSection && $collection->parametersSection->is_active ? [
                'title' => $collection->parametersSection->title,
                'subtitle' => $collection->parametersSection->subtitle,
                'items' => $collection->parametersSection->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'small_text' => $item->small_text,
                        'title' => $item->title,
                        'description' => $item->description,
                        'bg_color' => $item->bg_color,
                        'hover_bg_color' => $item->hover_bg_color,
                        'order' => $item->order,
                    ];
                })
            ] : null,
            
            // Compositions Section
            'compositions_section' => ($collection->compositionsSection && $collection->compositionsSection->is_active) ? [
                'title' => $collection->compositionsSection->title,
                'subtitle' => $collection->compositionsSection->subtitle,
                'items' => ($collection->compositions && $collection->compositions->count() > 0)
                    ? $collection->compositions->map(function ($comp) {
                        $img = $comp->image;
                        if ($img && !str_starts_with($img, 'http')) {
                            $img = str_starts_with($img, 'uploads/compositions/')
                                ? asset('storage/' . $img)
                                : asset('storage/uploads/compositions/' . $img);
                        }

                        $productsUsed = $comp->products->map(function($product) {
                            $firstColor = $product->colors->first();
                            
                            $pImg = null;
                            if ($firstColor) {
                                if ($firstColor->lighton_image) {
                                    $pImg = asset('storage/uploads/decorative/' . $firstColor->lighton_image);
                                } elseif ($firstColor->main_image) {
                                    $pImg = asset('storage/uploads/decorative/' . $firstColor->main_image);
                                }
                            }
                            if (!$pImg && $product->featured_image) {
                                $pImg = asset('storage/uploads/decorative/' . $product->featured_image);
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
                                    $cImage = $pImg; 
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
                                'image' => $pImg,
                                'colors' => $seenColors,
                                'variants' => $variants,
                                'collection' => $product->collection ? $product->collection->name : null,
                                'link' => '/product-detail/' . $product->slug,
                            ];
                        });

                        return [
                            'id' => $comp->id,
                            'image' => $img,
                            'title' => $comp->title,
                            'category' => $comp->category_rel ? $comp->category_rel->name : $comp->category,
                            'kicker' => $comp->kicker ?? '',
                            'products' => $productsUsed
                        ];
                    })
                    : ($collection->compositionsSection->items ? $collection->compositionsSection->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'image' => $item->image_url ?? ($item->image ? asset('storage/' . $item->image) : ''),
                            'title' => $item->products ?? '',
                            'category' => '',
                            'kicker' => $item->description ?? '',
                        ];
                    }) : []),
            ] : null,
            
            // Tones Section
            'tones_section' => $collection->tonesSection && $collection->tonesSection->is_active ? [
                'title' => $collection->tonesSection->title,
                'subtitle' => $collection->tonesSection->subtitle,
                'families' => $collection->tonesSection->families->map(function ($family) {
                    return [
                        'id' => $family->id,
                        'title' => $family->title,
                        'image' => asset('storage/' . $family->image),
                        'order' => $family->order,
                        'colors' => $family->colors->map(function ($color) {
                            return [
                                'id' => $color->id,
                                'name' => $color->name,
                                'code' => $color->code,
                                'css_value' => $color->css_value,
                                'type' => $color->type,
                            ];
                        })
                    ];
                })
            ] : null,
            
            // Places Section
            'places_section' => $collection->placesSection && $collection->placesSection->is_active ? [
                'title' => $collection->placesSection->title,
                'subtitle' => $collection->placesSection->subtitle,
                'items' => $collection->placesSection->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'place_name' => $item->place_name,
                        'image' => asset('storage/' . $item->image),
                        'description' => $item->description,
                        'products' => $item->products,
                        'order' => $item->order,
                    ];
                })
            ] : null,

            // Spread & Drop Section
            'spread_drop_section' => $collection->spreadDropSection
                ? ['is_active' => $collection->spreadDropSection->is_active]
                : ['is_active' => true], // default visible if never configured
        ];

        return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('CollectionApiController::show — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Collection not found',
            ], 404);
        }
    }

    /**
     * Get paginated parameter items for a collection
     */
    public function getParameters(Request $request, $slug)
    {
        try {
            $collection = Collection::where('slug', $slug)->active()->firstOrFail();

            if (!$collection->parametersSection || !$collection->parametersSection->is_active) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'items' => [],
                        'has_more' => false,
                        'total' => 0,
                    ]
                ]);
            }

            $page = (int) $request->query('page', 1);
            $limit = (int) $request->query('limit', 8);
            $offset = ($page - 1) * $limit;

            $query = $collection->parametersSection->items()->where('is_active', true)->orderBy('order');
            $total = $query->count();

            $items = $query->skip($offset)->take($limit)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'small_text' => $item->small_text,
                    'title' => $item->title,
                    'description' => $item->description,
                    'bg_color' => $item->bg_color,
                    'hover_bg_color' => $item->hover_bg_color,
                    'order' => $item->order,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $items,
                    'has_more' => ($offset + $items->count()) < $total,
                    'total' => $total,
                    'page' => $page,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('CollectionApiController::getParameters — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch parameter items',
            ], 500);
        }
    }
}
