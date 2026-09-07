<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManufacturingSection;

class ManufacturingSectionApiController extends Controller
{
    /**
     * Get manufacturing section data
     */
    public function index()
    {
        try {
            // Get the first active manufacturing section
            $section = ManufacturingSection::where('is_active', 'yes')->first();
            
            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Manufacturing section not found',
                ], 404);
            }
            
            $data = [
                'id' => $section->id,
                'title' => $section->title,
                'title_highlight' => $section->title_highlight,
                'description' => $section->description,
                'button_text' => $section->button_text,
                'button_link' => $section->button_link,
                'background_image_url' => $section->background_image 
                    ? asset('storage/' . $section->background_image) 
                    : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch manufacturing section',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
