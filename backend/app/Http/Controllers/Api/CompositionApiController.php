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
     * Sorted by latest updated_at with pagination (default 8 per page).
     */
    public function showcase(Request $request)
    {
        try {
            $query = Composition::where('is_showcase', 1)
                ->with(['category_rel', 'collections', 'products.category', 'products.collection', 'products.colors.colorMaster'])
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'desc');

            if ($request->filled('category') && strtolower($request->get('category')) !== 'all') {
                $category = trim($request->get('category'));
                $query->where(function ($q) use ($category) {
                    $q->whereHas('category_rel', function ($catQ) use ($category) {
                        $catQ->whereRaw('LOWER(name) = ?', [strtolower($category)])
                             ->orWhere('slug', strtolower($category));
                    })->orWhereRaw('LOWER(category) = ?', [strtolower($category)]);
                });
            }

            // Retrieve all distinct showcase categories so front-end filter menu always has all room categories
            $allCategories = Composition::where('is_showcase', 1)
                ->with('category_rel')
                ->get()
                ->map(function ($c) {
                    return $c->category_rel ? $c->category_rel->name : $c->category;
                })
                ->filter()
                ->unique()
                ->values();

            $transformComp = function ($comp) {
                $productsUsed = $comp->products->map(function ($product) {
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
                        'link' => '/decorative-lights/' . $product->slug,
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
            };

            // Support fetching all without pagination if explicitly requested
            if ($request->boolean('all') || (int) $request->get('per_page') === -1) {
                $compositions = $query->get()->map($transformComp);
                return response()->json([
                    'success' => true,
                    'data' => $compositions,
                    'categories' => $allCategories,
                    'pagination' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => $compositions->count(),
                        'total' => $compositions->count(),
                        'has_more' => false,
                    ]
                ]);
            }

            $perPage = min((int) $request->get('per_page', 8), 50);
            $paginator = $query->paginate($perPage);
            $compositions = collect($paginator->items())->map($transformComp);

            return response()->json([
                'success' => true,
                'data' => $compositions,
                'categories' => $allCategories,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'has_more' => $paginator->hasMorePages(),
                ]
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
