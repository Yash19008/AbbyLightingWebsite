<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = \App\Models\MenuItem::with('children')->whereNull('parent_id')->active()->ordered();
            
            if ($request->has('location')) {
                $query->where('location', $request->get('location'));
            }

            return response()->json([
                'success' => true,
                'data' => $query->get()
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('MenuApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load menu items'
            ], 500);
        }
    }
}
