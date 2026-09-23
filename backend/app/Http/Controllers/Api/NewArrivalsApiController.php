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
     * Get new arrival products grouped by tabs (Architectural, Decorative, Outdoor)
     * Only returns featured products (is_featured = 1 for Decorative, show_as_new_arrival = 1 for Architectural)
     */
    public function index(Request $request)
    {
        try {
            // 1. Fetch Featured Decorative Products (where is_featured = 1)
            $decProducts = DecProduct::where('status', 'published')
                ->where('is_featured', 1)
                ->with(['category', 'variants'])
                ->orderBy('order', 'asc')
                ->latest()
                ->take(12)
                ->get()
                ->map(function ($p) {
                    $imageUrl = null;
                    if ($p->featured_image) {
                        $imageUrl = str_starts_with($p->featured_image, 'http')
                            ? $p->featured_image
                            : (str_contains($p->featured_image, '/')
                                ? asset('storage/' . $p->featured_image)
                                : asset('storage/uploads/decorative/' . $p->featured_image));
                    } elseif ($p->variants->isNotEmpty() && $p->variants->first()->main_image) {
                        $vImg = $p->variants->first()->main_image;
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
                        'category' => $p->category ? $p->category->name : 'Decorative',
                        'parent_category' => 'Decorative',
                        'image_url' => $imageUrl,
                        'price' => null,
                        'description' => $p->short_description ?? ($p->description ? strip_tags($p->description) : null),
                    ];
                });

            // 2. Fetch Featured Architectural Products (where show_as_new_arrival = 1)
            $archProducts = ProductMaster::where('is_active', 'yes')
                ->where('show_as_new_arrival', 1)
                ->with(['category'])
                ->latest()
                ->take(12)
                ->get()
                ->map(function ($p) {
                    $imageUrl = $p->featured_image
                        ? (str_starts_with($p->featured_image, 'http')
                            ? $p->featured_image
                            : asset('storage/uploads/products/' . $p->featured_image))
                        : null;

                    return [
                        'id' => $p->id,
                        'name' => $p->title,
                        'slug' => $p->slug,
                        'category' => $p->category ? $p->category->title : 'Architectural',
                        'parent_category' => 'Architectural',
                        'image_url' => $imageUrl,
                        'price' => null,
                        'description' => null,
                    ];
                });

            // 3. Construct categories array
            $result = [
                [
                    'id' => 1,
                    'name' => 'Architectural',
                    'slug' => 'architectural',
                    'products' => $archProducts->values()->all(),
                ],
                [
                    'id' => 2,
                    'name' => 'Decorative',
                    'slug' => 'decorative',
                    'products' => $decProducts->values()->all(),
                ],
                [
                    'id' => 3,
                    'name' => 'Outdoor',
                    'slug' => 'outdoor',
                    'products' => [], // For future outdoor products
                ]
            ];

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
