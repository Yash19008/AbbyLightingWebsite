<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeCatalogueSection;

class HomeCatalogueSectionApiController extends Controller
{
    /**
     * Get home catalogue section data
     */
    public function index()
    {
        try {
            // Get the first active catalogue section
            $section = HomeCatalogueSection::where('is_active', 'yes')->first();

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catalogue section not found',
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
                'is_active' => $section->is_active === 'yes',
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch catalogue section',
                            ], 500);
        }
    }
}
