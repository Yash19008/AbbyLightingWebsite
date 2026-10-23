<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MenuItem::with('children')->whereNull('parent_id')->active()->ordered();
            
            if ($request->has('location')) {
                $query->where('location', $request->get('location'));
            }

            return response()->json([
                'success' => true,
                'data' => $query->get()
            ]);
        } catch (\Exception $e) {
            Log::error('MenuApiController::index - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu items'
            ], 500);
        }
    }
}
