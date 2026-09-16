<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
                    'products' => []
                ],
                [
                    'id' => 3,
                    'name' => 'Outdoor',
                    'slug' => 'outdoor',
                    'products' => [] // Empty - static tab
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
