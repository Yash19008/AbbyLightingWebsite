<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

            if ($request->filled('page') || $request->filled('per_page')) {
                // limit() is intentionally NOT applied in the paginated path
                $perPage = min((int) $request->get('per_page', 6), 50);
                $paginated = $query->paginate($perPage);

                return response()->json([
                    'success' => true,
                    'data' => $paginated->items(),
                    'pagination' => [
                        'current_page' => $paginated->currentPage(),
                        'last_page' => $paginated->lastPage(),
                        'per_page' => $paginated->perPage(),
                        'total' => $paginated->total(),
                        'has_more' => $paginated->hasMorePages(),
                    ],
                ]);
            }

            if ($request->filled('limit')) {
                $query->take((int) $request->limit);
            }

            $blogs = $query->get();

            return response()->json([
                'success' => true,
                'data' => $blogs,
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $blogs->count(),
                    'total' => $blogs->count(),
                    'has_more' => false,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('BlogApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blogs',
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
