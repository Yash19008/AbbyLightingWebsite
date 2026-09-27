<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspirationHeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspirationHeroSectionApiController extends Controller
{
    /**
     * Get the inspiration hero banner section data
     */
    public function index()
    {
        try {
            $section = InspirationHeroSection::first();

            if (!$section) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'title' => 'Ideas, stories & inspiration',
                        'title_highlight' => 'Insights',
                        'breadcrumb_parent_text' => 'Home',
                        'breadcrumb_parent_link' => '/',
                        'breadcrumb_current_text' => 'Inspiration',
                        'background_image' => null,
                        'is_active' => true,
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $section->id,
                    'title' => $section->title ?? 'Ideas, stories & inspiration',
                    'title_highlight' => $section->title_highlight ?? 'Insights',
                    'breadcrumb_parent_text' => $section->breadcrumb_parent_text ?? 'Home',
                    'breadcrumb_parent_link' => $section->breadcrumb_parent_link ?? '/',
                    'breadcrumb_current_text' => $section->breadcrumb_current_text ?? 'Inspiration',
                    'background_image' => $section->background_image ? asset('storage/' . $section->background_image) : null,
                    'is_active' => (bool) $section->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('InspirationHeroSectionApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch inspiration banner data',
            ], 500);
        }
    }
}
