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
        $query = ColorMaster::active()->ordered();

        // Filter by type if provided
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        // Filter by category if provided
        if ($request->has('category')) {
            $query->inCategory($request->category);
        }

        $colors = $query->get();

        return response()->json($colors);
    }

    /**
     * Get a single color by code.
     */
    public function show($code)
    {
        $color = ColorMaster::where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json($color);
    }

    /**
     * Get colors grouped by category.
     */
    public function byCategory()
    {
        $colors = ColorMaster::active()
            ->ordered()
            ->get()
            ->groupBy('category');

        return response()->json($colors);
    }

    /**
     * Get all available categories.
     */
    public function categories()
    {
        $categories = ColorMaster::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return response()->json($categories);
    }
}
