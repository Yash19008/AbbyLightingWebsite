<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionCompositionController extends Controller
{
    public function storeCompositionsSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Create or update compositions section
        $collection->compositionsSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'is_active' => $request->has('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Compositions section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=compositions')
            ->with('success', 'Compositions section saved successfully!');
    }
}
