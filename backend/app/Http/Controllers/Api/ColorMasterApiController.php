<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ColorMaster;
use Illuminate\Http\Request;

class ColorMasterApiController extends Controller
{
    /**
     * Get all active colors.
     */
    public function index(Request $request)
    {
        try {
            $query = ColorMaster::active()->ordered();

            if ($request->has('type')) {
                $query->ofType($request->type);
            }

            if ($request->has('category')) {
                $query->inCategory($request->category);
            }

            $colors = $query->get();

            return response()->json([
                'success' => true,
                'data' => $colors
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ColorMasterApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch colors',
            ], 500);
        }
    }

    /**
     * Get a single color by code.
     */
    public function show($code)
    {
        try {
            $color = ColorMaster::where('code', $code)
                ->where('is_active', true)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $color
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Color not found',
            ], 404);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ColorMasterApiController::show — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch color',
            ], 500);
        }
    }

    /**
     * Get colors grouped by category.
     */
    public function byCategory()
    {
        try {
            $colors = ColorMaster::active()
                ->ordered()
                ->get()
                ->groupBy('category');

            return response()->json([
                'success' => true,
                'data' => $colors
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ColorMasterApiController::byCategory — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch colors by category',
            ], 500);
        }
    }

    /**
     * Get all available categories.
     */
    public function categories()
    {
        try {
            $categories = ColorMaster::active()
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ColorMasterApiController::categories — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch color categories',
            ], 500);
        }
    }
}
