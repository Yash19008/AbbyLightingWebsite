<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Composition;
use Illuminate\Http\Request;

class CompositionApiController extends Controller
{
    /**
     * Get compositions that are marked to be showcased in the inspiration page.
     */
    public function showcase()
    {
        $compositions = Composition::where('is_showcase', 1)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($comp) {
                return [
                    'id' => $comp->id,
                    'title' => $comp->title,
                    'kicker' => $comp->kicker,
                    'category' => $comp->category,
                    'image' => $comp->image ? asset('storage/uploads/compositions/' . $comp->image) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $compositions
        ]);
    }
}
