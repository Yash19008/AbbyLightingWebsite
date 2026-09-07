<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DecorativeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewArrivalsApiController extends Controller
{
    /**
     * Get new arrival products grouped by tabs (Architectural, Decorative, Outdoor)
     */
    public function index(Request $request)
    {
        try {
            // Initialize result structure with 3 tabs
            $result = [
                [
                    'id' => 1,
                    'name' => 'Architectural',
                    'slug' => 'architectural',
                    'products' => [] // Empty - static tab
                ],
                [
                    'id' => 2,
                    'name' => 'Decorative',
                    'slug' => 'decorative',
                    'products' => [] // Will be filled from decorative_products table
                ],
                [
                    'id' => 3,
                    'name' => 'Outdoor',
                    'slug' => 'outdoor',
                    'products' => [] // Empty - static tab
                ]
            ];

            // Fetch Decorative products (new arrivals)
            $hasNewArrivalColumn = \Schema::hasColumn('decorative_products', 'is_new_arrival');
            
            $query = DecorativeProduct::with(['categories', 'primaryImage'])
                ->where('status', 'active');
            
            // If is_new_arrival column exists, filter by it
            if ($hasNewArrivalColumn) {
                $decorativeProducts = (clone $query)
                    ->where('is_new_arrival', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(8) // Show 8 products max
                    ->get();
                
                // If no products marked as new arrival, get recent products
                if ($decorativeProducts->isEmpty()) {
                    $decorativeProducts = $query
                        ->orderBy('created_at', 'desc')
                        ->limit(8)
                        ->get();
                }
            } else {
                // If column doesn't exist, just get recent products
                $decorativeProducts = $query
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            }

            // Transform decorative products
            foreach ($decorativeProducts as $product) {
                // Get primary image
                $imageUrl = null;
                if ($product->primaryImage && $product->primaryImage->image) {
                    $imageUrl = url('storage/decorative_products/' . $product->primaryImage->image);
                }

                // Get category name for display
                $categoryName = 'Decorative';
                if ($product->categories->isNotEmpty()) {
                    $firstCategory = $product->categories->first();
                    $categoryName = $firstCategory->name;
                }

                $result[1]['products'][] = [
                    'id' => $product->id,
                    'name' => $product->title,
                    'slug' => $product->slug,
                    'category' => $categoryName,
                    'parent_category' => 'Decorative',
                    'image_url' => $imageUrl,
                    'price' => $product->price ?? null,
                    'description' => $product->short_description ?? $product->description
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'New arrival products fetched successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch new arrival products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
