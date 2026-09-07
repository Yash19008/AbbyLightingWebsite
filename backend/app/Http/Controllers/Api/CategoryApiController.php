<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
    /**
     * Get architectural categories for menu / dropdown
     */
    public function index(Request $request)
    {
        try {
            $query = Category::where('is_active', 'yes');

            if (!$request->has('all')) {
                $query->where('in_menu', 'yes');
            }

            $categories = $query->orderBy('id', 'ASC')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                        'name' => $category->title,
                        'slug' => $category->slug,
                        'uri' => $category->uri,
                        'in_menu' => $category->in_menu,
                        'is_active' => $category->is_active,
                        'image_url' => $category->featured_image ? asset('storage/uploads/categories/' . $category->featured_image) : null,
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
}
