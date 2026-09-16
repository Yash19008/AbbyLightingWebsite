<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LightWorld;

class LightWorldApiController extends Controller
{
    public function index()
    {
        try {
            $worlds = LightWorld::orderBy('sort_order', 'ASC')->get();

            $data = $worlds->map(function ($world) {
                // helper to resolve either public/static assets or user uploads
                $resolveUrl = function ($path) {
                    if (!$path) return null;
                    if (str_starts_with($path, 'http') || str_starts_with($path, 'images/') || str_starts_with($path, '/images/')) {
                        return $path;
                    }
                    return asset('storage/' . $path);
                };

                return [
                    'id' => $world->id,
                    'name' => $world->name,
                    'link' => $world->link,
                    'light_of_image_url' => $resolveUrl($world->light_of_image),
                    'light_on_image_url' => $resolveUrl($world->light_on_image),
                    'sort_order' => $world->sort_order,
                    'created_at' => $world->created_at,
                    'updated_at' => $world->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch light worlds',
                            ], 500);
        }
    }
}
