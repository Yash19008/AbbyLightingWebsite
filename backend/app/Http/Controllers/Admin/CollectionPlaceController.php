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

class CollectionPlaceController extends Controller
{
    public function storePlacesSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $collection->placesSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'is_active' => $request->has('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Places section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=places')
            ->with('success', 'Places section saved successfully!');
    }

    public function addPlaceItem(Collection $collection)
    {
        if (!$collection->placesSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create places section first!');
        }

        return view('admin.collections.place-add', compact('collection'));
    }

    public function storePlaceItem(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'place_name' => 'required|string|max:255',
            'description' => 'required|string',
            'products' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        $imagePath = $request->file('image')->store('collections/places', 'public');

        $collection->placesSection->items()->create([
            'image' => $imagePath,
            'place_name' => $validated['place_name'],
            'description' => $validated['description'],
            'products' => $validated['products'] ?? '',
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=places';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Place item "' . $validated['place_name'] . '" added successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Place item "' . $validated['place_name'] . '" added successfully!');
    }

    public function editPlaceItem(Collection $collection, CollectionPlaceItem $item)
    {
        return view('admin.collections.place-edit', compact('collection', 'item'));
    }

    public function updatePlaceItem(Request $request, Collection $collection, CollectionPlaceItem $item)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'place_name' => 'required|string|max:255',
            'description' => 'required|string',
            'products' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image) {
                Storage::delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('collections/places', 'public');
        }

        $item->update([
            'image' => $validated['image'] ?? $item->image,
            'place_name' => $validated['place_name'],
            'description' => $validated['description'],
            'products' => $validated['products'] ?? $item->products,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=places';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Place item "' . $item->place_name . '" updated successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Place item "' . $item->place_name . '" updated successfully!');
    }

    public function deletePlaceItem(Collection $collection, CollectionPlaceItem $item)
    {
        // Delete image file
        if ($item->image) {
            Storage::delete($item->image);
        }

        $item->delete();

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=places';

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Place item deleted successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Place item deleted successfully!');
    }
}
