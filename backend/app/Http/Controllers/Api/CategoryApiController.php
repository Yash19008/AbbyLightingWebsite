<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryApiController extends Controller
{
    /**
     * Get architectural categories (Tags) for menu / dropdown
     */
    public function index(Request $request)
    {
        try {
            $query = Tag::where('is_active', 'yes');

            // Default behavior: filter by is_featured unless ?all=1 is explicitly requested
            if (!$request->boolean('all')) {
                $query->where('is_featured', 1);
            }

            $categories = $query->orderBy('id', 'ASC')
                ->get()
                ->map(function ($tag) {
                    $slug = $tag->slug ?? '';
                    $title = $tag->display_name ?: $tag->name;
                    $url = '/products/category/' . $slug;

                    return [
                        'id' => $tag->id,
                        'title' => $title,
                        'name' => $title,
                        'slug' => $slug,
                        'uri' => $url,
                        'url' => $url,
                        'is_featured' => (bool)$tag->is_featured,
                        'show_categories' => (bool)$tag->show_categories,
                        'is_active' => $tag->is_active,
                        'image_url' => $tag->image ? asset('storage/uploads/tags/' . $tag->image) : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            Log::error('CategoryApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
            ], 500);
        }
    }
}

