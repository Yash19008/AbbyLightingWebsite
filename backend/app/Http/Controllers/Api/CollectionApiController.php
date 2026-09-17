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
                'compositions',
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
                'items' => ($collection->compositionsSection->items && $collection->compositionsSection->items->count() > 0)
                    ? $collection->compositionsSection->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'image' => $item->image_url ?? ($item->image ? asset('storage/' . $item->image) : ''),
                            'title' => $item->products ?? '',
                            'category' => '',
                            'kicker' => $item->description ?? '',
                        ];
                    })
                    : $collection->compositions->map(function ($comp) {
                        $img = $comp->image;
                        if ($img && !str_starts_with($img, 'http')) {
                            $img = str_starts_with($img, 'uploads/compositions/')
                                ? asset('storage/' . $img)
                                : asset('storage/uploads/compositions/' . $img);
                        }
                        return [
                            'id' => $comp->id,
                            'image' => $img,
                            'title' => $comp->title,
                            'category' => $comp->category ?? '',
                            'kicker' => $comp->kicker ?? '',
                        ];
                    }),
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
}
