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

    public function addCompositionItem(Collection $collection)
    {
        if (!$collection->compositionsSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create compositions section first!');
        }

        return view('admin.collections.composition-add', compact('collection'));
    }

    public function storeCompositionItem(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'required|string',
            'products' => 'required|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'nullable',
        ]);

        // Handle image upload
        $imagePath = $request->file('image')->store('collections/compositions', 'public');

        $collection->compositionsSection->items()->create([
            'image' => $imagePath,
            'description' => $validated['description'],
            'products' => $validated['products'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=compositions';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Composition card added successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Composition card added successfully!');
    }

    public function editCompositionItem(Collection $collection, CollectionCompositionItem $item)
    {
        return view('admin.collections.composition-edit', compact('collection', 'item'));
    }

    public function updateCompositionItem(Request $request, Collection $collection, CollectionCompositionItem $item)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'required|string',
            'products' => 'required|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'nullable',
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('collections/compositions', 'public');
        }

        $item->update([
            'image' => $validated['image'] ?? $item->image,
            'description' => $validated['description'],
            'products' => $validated['products'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=compositions';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Composition card updated successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Composition card updated successfully!');
    }

    public function deleteCompositionItem(Collection $collection, CollectionCompositionItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=compositions';

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Composition card deleted successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Composition card deleted successfully!');
    }
}
