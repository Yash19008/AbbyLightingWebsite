<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Blog::with('category')->where('status', 'published');

            if ($request->has('category') && $request->category !== 'all') {
                $categorySlug = $request->category;
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            if ($request->has('featured') && $request->featured === '1') {
                $query->where('is_featured', true);
            }

            if ($request->filled('exclude_slug')) {
                $query->where('slug', '!=', $request->exclude_slug);
            }

            $sort = $request->get('sort', 'popular');
            if ($sort === 'new') {
                $query->orderBy('published_at', 'desc')->orderBy('id', 'desc');
            } elseif ($sort === 'az') {
                $query->orderBy('title', 'asc');
            } elseif ($sort === 'za') {
                $query->orderBy('title', 'desc');
            } else {
                $query->orderBy('views_count', 'desc')->orderBy('sort_order', 'asc');
            }

            if ($request->filled('limit')) {
                $query->take((int)$request->limit);
            }

            $blogs = $query->get();

            return response()->json([
                'success' => true,
                'data' => $blogs,
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
            $blog = Blog::with('category')
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();

            // Increment view counter
            $blog->increment('views_count');

            // Latest published blogs excluding the currently open blog
            $latestBlogs = Blog::with('category')
                ->where('status', 'published')
                ->where('id', '!=', $blog->id)
                ->orderBy('published_at', 'desc')
                ->orderBy('id', 'desc')
                ->take(3)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $blog,
                'latest_blogs' => $latestBlogs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Blog article not found',
            ], 404);
        }
    }
}
