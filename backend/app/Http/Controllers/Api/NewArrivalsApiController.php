<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProduct;
use App\Models\ProductMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewArrivalsApiController extends Controller
{
    /**
     * Get new arrival products grouped by featured categories.
     * Generates tabs dynamically based on featured categories from both Architectural and Decorative.
     */
    public function index(Request $request)
    {
        try {
            $result = [];
            $tabId = 1;

            // 1. Fetch Featured Architectural Categories
            $featuredArchCategories = \App\Models\Category::where('is_featured', 1)
                ->where('is_active', 'yes')
                ->orderBy('title', 'asc')
                ->get();

            foreach ($featuredArchCategories as $cat) {
                // Fetch products for this category
                $products = ProductMaster::where('is_active', 'yes')
                    ->where('category_id', $cat->id)
                    ->latest()
                    ->take(12)
                    ->get()
                    ->map(function ($p) use ($cat) {
                        $imageUrl = $p->featured_image
                            ? (str_starts_with($p->featured_image, 'http')
                                ? $p->featured_image
                                : asset('storage/uploads/products/' . $p->featured_image))
                            : null;

                        $subTagSlug = null;
                        if (!empty($p->sub_tag_ids)) {
                            $ids = explode(',', $p->sub_tag_ids);
                            if (count($ids) > 0) {
                                $subTag = \App\Models\SubTag::find($ids[0]);
                                if ($subTag) {
                                    $subTagSlug = $subTag->slug;
                                }
                            }
                        }

                        return [
                            'id' => $p->id,
                            'name' => $p->title,
                            'slug' => $p->slug,
                            'sub_tag_slug' => $subTagSlug,
                            'category' => $cat->title,
                            'parent_category' => 'Architectural',
                            'image_url' => $imageUrl,
                            'price' => null,
                            'description' => null,
                        ];
                    });

                if ($products->isNotEmpty()) {
                    $result[] = [
                        'id' => $tabId++,
                        'name' => $cat->title,
                        'slug' => $cat->slug,
                        'products' => $products->all(),
                    ];
                }
            }

            // 2. Fetch Featured Decorative Categories
            $featuredDecCategories = \App\Models\Decorative\DecCategory::where('is_featured', 1)
                ->orderBy('name', 'asc')
                ->get();

            foreach ($featuredDecCategories as $cat) {
                $products = DecProduct::where('status', 'published')
                    ->where('category_id', $cat->id)
                    ->with(['colors'])
                    ->orderBy('order', 'asc')
                    ->latest()
                    ->take(12)
                    ->get()
                    ->map(function ($p) use ($cat) {
                        $imageUrl = null;
                        if ($p->featured_image) {
                            $imageUrl = str_starts_with($p->featured_image, 'http')
                                ? $p->featured_image
                                : (str_contains($p->featured_image, '/')
                                    ? asset('storage/' . $p->featured_image)
                                    : asset('storage/uploads/decorative/' . $p->featured_image));
                        } elseif ($p->colors->isNotEmpty() && $p->colors->first()->main_image) {
                            $vImg = $p->colors->first()->main_image;
                            $imageUrl = str_starts_with($vImg, 'http')
                                ? $vImg
                                : (str_contains($vImg, '/')
                                    ? asset('storage/' . $vImg)
                                    : asset('storage/uploads/decorative/' . $vImg));
                        }

                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'slug' => $p->slug,
                            'category' => $cat->name,
                            'parent_category' => 'Decorative',
                            'image_url' => $imageUrl,
                            'price' => null,
                            'description' => $p->short_description ?? ($p->description ? strip_tags($p->description) : null),
                        ];
                    });

                if ($products->isNotEmpty()) {
                    $result[] = [
                        'id' => $tabId++,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'products' => $products->all(),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'New arrival products fetched successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('NewArrivalsApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch new arrival products',
            ], 500);
        }
    }
}
