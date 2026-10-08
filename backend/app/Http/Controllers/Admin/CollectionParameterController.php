<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionParameterItem;
use App\Models\CollectionCompositionItem;
use App\Models\CollectionToneFamily;
use App\Models\CollectionPlaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionParameterController extends Controller
{
    public function storeParametersSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Create or update parameters section
        $collection->parametersSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'is_active' => $request->has('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameters section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=parameters')
            ->with('success', 'Parameters section saved successfully!');
    }

    public function addParameterItem(Collection $collection)
    {
        // Ensure parameters section exists
        if (!$collection->parametersSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create parameters section first!');
        }

        $colorMasters = \App\Models\ColorMaster::orderBy('name')->get();
        return view('admin.collections.parameter-add', compact('collection', 'colorMasters'));
    }

    public function storeParameterItem(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'small_text' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'bg_color' => 'nullable|string|max:20',
            'hover_bg_color' => 'nullable|string|max:20',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $collection->parametersSection->items()->create([
            'small_text' => $validated['small_text'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'bg_color' => $validated['bg_color'] ?? null,
            'hover_bg_color' => $validated['hover_bg_color'] ?? null,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=parameters';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameter card added successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Parameter card added successfully!');
    }

    public function editParameterItem(Collection $collection, CollectionParameterItem $item)
    {
        $colorMasters = \App\Models\ColorMaster::orderBy('name')->get();
        return view('admin.collections.parameter-edit', compact('collection', 'item', 'colorMasters'));
    }

    public function updateParameterItem(Request $request, Collection $collection, CollectionParameterItem $item)
    {
        $validated = $request->validate([
            'small_text' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'bg_color' => 'nullable|string|max:20',
            'hover_bg_color' => 'nullable|string|max:20',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $item->update([
            'small_text' => $validated['small_text'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'bg_color' => $validated['bg_color'] ?? null,
            'hover_bg_color' => $validated['hover_bg_color'] ?? null,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=parameters';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameter card updated successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Parameter card updated successfully!');
    }

    public function deleteParameterItem(Collection $collection, CollectionParameterItem $item)
    {
        $item->delete();

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=parameters';

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameter card deleted successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Parameter card deleted successfully!');
    }
}
