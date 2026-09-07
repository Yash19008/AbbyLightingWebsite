<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DecorativeCategory;
use Illuminate\Http\Request;

class DecorativeCategoryApiController extends Controller
{
    /**
     * Get all decorative categories (for menu)
     * Returns only categories that have products assigned
     */
    public function index(Request $request)
    {
        try {
            $query = DecorativeCategory::where('status', 'active');

            if (!$request->has('all')) {
                $query->where('show_in_mega_dropdown', true);
            }

            $categories = $query->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'image_url' => $category->image ? asset('uploads/decorative_categories/' . $category->image) : null,
                        'show_in_mega_dropdown' => (bool)$category->show_in_mega_dropdown,
                        'sort_order' => $category->sort_order,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get category with subcategories
     */
    public function show($slug)
    {
        try {
            $category = DecorativeCategory::where('status', 'active')
                ->where('slug', $slug)
                ->firstOrFail();
            
            $subcategories = DecorativeCategory::where('status', 'active')
                ->where('parent_id', $category->id)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get()
                ->map(function ($subcat) {
                    return [
                        'id' => $subcat->id,
                        'name' => $subcat->name,
                        'slug' => $subcat->slug,
                        'image_url' => $subcat->image ? asset('storage/' . $subcat->image) : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image_url' => $category->image ? asset('storage/' . $category->image) : null,
                    'subcategories' => $subcategories,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
