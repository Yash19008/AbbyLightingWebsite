<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionToneFamily;
use App\Models\ColorMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CollectionToneController extends Controller
{
    public function storeTonesSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $collection->tonesSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'is_active' => $request->has('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tones section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=tones')
            ->with('success', 'Tones section saved successfully!');
    }

    public function addToneFamily(Collection $collection)
    {
        if (!$collection->tonesSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create tones section first!');
        }

        $colors = ColorMaster::active()->ordered()->get();
        return view('admin.collections.tone-family-add', compact('collection', 'colors'));
    }

    public function storeToneFamily(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'nullable',
            'colors' => 'required|array|min:1',
            'colors.*' => 'exists:color_masters,id',
        ]);

        // Upload image
        $imagePath = $request->file('image')->store('collections/tones', 'public');

        // Create family
        $family = $collection->tonesSection->families()->create([
            'image' => $imagePath,
            'title' => $validated['title'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ]);

        // Attach colors with order
        foreach ($validated['colors'] as $index => $colorId) {
            $family->colors()->attach($colorId, [
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=tones';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tone family "' . $family->title . '" added successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Tone family "' . $family->title . '" added successfully!');
    }

    public function editToneFamily(Collection $collection, CollectionToneFamily $family)
    {
        $colors = ColorMaster::active()->ordered()->get();
        $selectedColorIds = $family->colors->pluck('id')->toArray();

        return view('admin.collections.tone-family-edit', compact('collection', 'family', 'colors', 'selectedColorIds'));
    }

    public function updateToneFamily(Request $request, Collection $collection, CollectionToneFamily $family)
    {
        try {
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'title' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'is_active' => 'nullable',
                'colors' => 'required|array|min:1',
                'colors.*' => 'exists:color_masters,id',
            ]);

            $updateData = [
                'title' => $validated['title'],
                'order' => $validated['order'],
                'is_active' => $request->has('is_active'),
            ];

            // Handle image upload if new image provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($family->image) {
                    Storage::disk('public')->delete($family->image);
                }
                // Store new image
                $updateData['image'] = $request->file('image')->store('collections/tones', 'public');
            }

            // Update family
            $family->update($updateData);

            // Prepare color data with order
            $colorData = [];
            foreach ($validated['colors'] as $index => $colorId) {
                $colorData[$colorId] = [
                    'order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Sync colors - this will properly update the pivot table
            $family->colors()->sync($colorData);

            $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=tones';

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Tone family "' . $family->title . '" updated successfully!', 'redirect_url' => $redirectUrl]);
            }

            return redirect($redirectUrl)->with('success', 'Tone family "' . $family->title . '" updated successfully!');

        } catch (\Exception $e) {
            Log::error('Tone family update failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update tone family: ' . $e->getMessage());
        }
    }

    public function deleteToneFamily(Collection $collection, CollectionToneFamily $family)
    {
        // Delete image
        if ($family->image) {
            Storage::delete($family->image);
        }

        // Detach colors
        $family->colors()->detach();

        // Delete family
        $family->delete();

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=tones';

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tone family deleted successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Tone family deleted successfully!');
    }
}
