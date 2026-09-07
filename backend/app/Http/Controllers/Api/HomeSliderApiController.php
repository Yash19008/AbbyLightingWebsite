<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Illuminate\Http\Request;

class HomeSliderApiController extends Controller
{
    /**
     * Get all home sliders
     */
    public function index()
    {
        try {
            $sliders = HomeSlider::orderBy('sort_order', 'ASC')->get();
            
            // Add full image URLs
            $sliders = $sliders->map(function ($slider) {
                return [
                    'id' => $slider->id,
                    'path' => $slider->path,
                    'image_url' => $slider->path ? asset('storage/' . $slider->path) : null,
                    'for_mobile' => $slider->for_mobile,
                    'sort_order' => $slider->sort_order,
                    'url' => $slider->url,
                    'heading' => $slider->heading,
                    'description' => $slider->description,
                    'button_text' => $slider->button_text,
                    'button_link' => $slider->button_link,
                    'created_at' => $slider->created_at,
                    'updated_at' => $slider->updated_at,
                ];
            });

            // Separate desktop and mobile sliders
            $webSliders = $sliders->where('for_mobile', 0)->values();
            $mobileSliders = $sliders->where('for_mobile', 1)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'web' => $webSliders,
                    'mobile' => $mobileSliders,
                    'all' => $sliders
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sliders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single slider
     */
    public function show($id)
    {
        try {
            $slider = HomeSlider::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $slider->id,
                    'path' => $slider->path,
                    'image_url' => $slider->path ? asset('storage/' . $slider->path) : null,
                    'for_mobile' => $slider->for_mobile,
                    'sort_order' => $slider->sort_order,
                    'url' => $slider->url,
                    'heading' => $slider->heading,
                    'description' => $slider->description,
                    'button_text' => $slider->button_text,
                    'button_link' => $slider->button_link,
                    'created_at' => $slider->created_at,
                    'updated_at' => $slider->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Slider not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
