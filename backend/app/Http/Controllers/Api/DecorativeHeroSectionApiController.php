<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DecorativeHeroSection;
use Illuminate\Support\Facades\Log;

class DecorativeHeroSectionApiController extends Controller
{
    /**
     * Get the decorative hero banner section data
     */
    public function index()
    {
        try {
            $section = DecorativeHeroSection::first();

            if (!$section) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'heading' => 'Decorative',
                        'styled_heading' => 'Lights',
                        'description' => 'Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order.',
                        'image_desktop' => null,
                        'image_tablet' => null,
                        'image_mobile' => null,
                        'is_active' => true,
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $section->id,
                    'heading' => $section->heading ?: 'Decorative',
                    'styled_heading' => $section->styled_heading ?: 'Lights',
                    'description' => $section->description,
                    'image_desktop' => $section->image_desktop ? asset('storage/' . $section->image_desktop) : null,
                    'image_tablet' => $section->image_tablet ? asset('storage/' . $section->image_tablet) : null,
                    'image_mobile' => $section->image_mobile ? asset('storage/' . $section->image_mobile) : null,
                    'is_active' => (bool) $section->is_active,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('DecorativeHeroSectionApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch decorative hero banner data',
            ], 500);
        }
    }
}
