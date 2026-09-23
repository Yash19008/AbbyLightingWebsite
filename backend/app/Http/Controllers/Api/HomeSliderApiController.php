<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeSliderApiController extends Controller
{
    /**
     * Get all active home sliders
     */
    public function index()
    {
        try {
            $sliders = HomeSlider::where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->get();

            // Add full image URLs
            $sliders = $sliders->map(function ($slider) {
                return [
                    'id'                => $slider->id,
                    'path'              => $slider->path,
                    'mobile_path'       => $slider->mobile_path,
                    'tablet_path'       => $slider->tablet_path,
                    'image_url'         => $slider->path ? asset('storage/' . $slider->path) : null,
                    'mobile_image_url'  => $slider->mobile_path ? asset('storage/' . $slider->mobile_path) : null,
                    'tablet_image_url'  => $slider->tablet_path ? asset('storage/' . $slider->tablet_path) : null,
                    'for_mobile'        => $slider->for_mobile,
                    'sort_order'        => $slider->sort_order,
                    'url'               => $slider->url,
                    'heading'           => $slider->heading,
                    'heading_highlight' => $slider->heading_highlight,
                    'description'       => $slider->description,
                    'button_text'       => $slider->button_text,
                    'button_link'       => $slider->button_link,
                    'created_at'        => $slider->created_at,
                    'updated_at'        => $slider->updated_at,
                ];
            });

            // Separate desktop and mobile sliders
            $webSliders    = $sliders->where('for_mobile', 0)->values();
            $mobileSliders = $sliders->where('for_mobile', 1)->values();

            return response()->json([
                'success' => true,
                'data'    => [
                    'web'    => $webSliders,
                    'mobile' => $mobileSliders,
                    'all'    => $sliders,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('HomeSliderApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sliders',
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
                    'mobile_path' => $slider->mobile_path,
                    'tablet_path' => $slider->tablet_path,
                    'image_url' => $slider->path ? asset('storage/' . $slider->path) : null,
                    'mobile_image_url' => $slider->mobile_path ? asset('storage/' . $slider->mobile_path) : null,
                    'tablet_image_url' => $slider->tablet_path ? asset('storage/' . $slider->tablet_path) : null,
                    'for_mobile' => $slider->for_mobile,
                    'sort_order' => $slider->sort_order,
                    'url' => $slider->url,
                    'heading' => $slider->heading,
                    'heading_highlight' => $slider->heading_highlight,
                    'description' => $slider->description,
                    'button_text' => $slider->button_text,
                    'button_link' => $slider->button_link,
                    'created_at' => $slider->created_at,
                    'updated_at' => $slider->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('HomeSliderApiController::show — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Slider not found',
            ], 404);
        }
    }
}
