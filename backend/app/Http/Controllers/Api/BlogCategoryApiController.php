<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = BlogCategory::where('status', 'active');

            // If only_used parameter is passed (or true), only return categories with at least one published blog
            if ($request->has('only_used') && ($request->only_used === '1' || $request->only_used === 'true')) {
                $query->whereHas('blogs', function ($q) {
                    $q->where('status', 'published');
                });
            }

            $categories = $query->withCount(['blogs' => function ($q) {
                    $q->where('status', 'published');
                }])
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($slug)
    {
        try {
            $category = BlogCategory::where('slug', $slug)
                ->where('status', 'active')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Blog category not found',
            ], 404);
        }
    }
}
