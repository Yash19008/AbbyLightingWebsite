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
        // Debug: Log what we're editing
        \Log::info('Editing tone family', [
            'collection_id' => $collection->id,
            'family_id' => $family->id,
            'family_title' => $family->title,
            'colors_count' => $family->colors->count()
        ]);

        $colors = ColorMaster::active()->ordered()->get();
        $selectedColorIds = $family->colors->pluck('id')->toArray();
        
        \Log::info('Edit tone family data', [
            'available_colors' => $colors->count(),
            'selected_color_ids' => $selectedColorIds
        ]);
        
        return view('admin.collections.tone-family-edit', compact('collection', 'family', 'colors', 'selectedColorIds'));
    }

    public function updateToneFamily(Request $request, Collection $collection, CollectionToneFamily $family)
    {
        \Log::info('=== TONE FAMILY UPDATE START ===', [
            'family_id' => $family->id,
            'family_title' => $family->title,
            'request_method' => $request->method(),
            'request_all' => $request->all(),
            'colors_before_count' => $family->colors->count(),
            'colors_before_ids' => $family->colors->pluck('id')->toArray(),
        ]);

        try {
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'title' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'is_active' => 'nullable',
                'colors' => 'required|array|min:1',
                'colors.*' => 'exists:color_masters,id',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            $updateData = [
                'title' => $validated['title'],
                'order' => $validated['order'],
                'is_active' => $request->has('is_active'),
            ];

            // Handle image upload if new image provided
            if ($request->hasFile('image')) {
                \Log::info('New image uploaded');
                // Delete old image if exists
                if ($family->image) {
                    Storage::disk('public')->delete($family->image);
                }
                // Store new image
                $updateData['image'] = $request->file('image')->store('collections/tones', 'public');
            } else {
                \Log::info('No new image, keeping existing');
            }

            \Log::info('About to update family record', ['update_data' => $updateData]);
            
            // Update family
            $family->update($updateData);

            \Log::info('Family record updated successfully');

            // Prepare color data with order
            $colorData = [];
            foreach ($validated['colors'] as $index => $colorId) {
                $colorData[$colorId] = [
                    'order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            \Log::info('About to sync colors', [
                'color_data' => $colorData,
                'colors_count' => count($colorData)
            ]);
            
            // Sync colors - this will properly update the pivot table
            $syncResult = $family->colors()->sync($colorData);
            
            \Log::info('Colors synced successfully', [
                'sync_result' => $syncResult,
                'colors_after_count' => $family->fresh()->colors->count(),
                'colors_after_ids' => $family->fresh()->colors->pluck('id')->toArray(),
            ]);

            \Log::info('=== TONE FAMILY UPDATE SUCCESS ===');

            $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=tones';

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Tone family "' . $family->title . '" updated successfully!', 'redirect_url' => $redirectUrl]);
            }

            return redirect($redirectUrl)->with('success', 'Tone family "' . $family->title . '" updated successfully!');

        } catch (\Exception $e) {
            \Log::error('=== TONE FAMILY UPDATE FAILED ===', [
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

    public function debugToneFamilies()
    {
        $families = CollectionToneFamily::with(['colors', 'tonesSection.collection'])->get();
        return response()->json([
            'families' => $families->map(function($family) {
                return [
                    'id' => $family->id,
                    'title' => $family->title,
                    'collection' => $family->tonesSection->collection->name ?? 'N/A',
                    'colors_count' => $family->colors->count(),
                    'colors' => $family->colors->pluck('name'),
                    'is_active' => $family->is_active,
                    'order' => $family->order,
                ];
            })
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function debugEditToneFamily(CollectionToneFamily $family)
    {
        $colors = ColorMaster::active()->ordered()->get();
        
        return response()->json([
            'family' => [
                'id' => $family->id,
                'title' => $family->title,
                'colors_count' => $family->colors->count(),
                'selected_colors' => $family->colors->pluck('name', 'id'),
                'is_active' => $family->is_active,
                'order' => $family->order,
            ],
            'available_colors' => $colors->pluck('name', 'id'),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function debugUpdateToneFamily(Request $request, CollectionToneFamily $family)
    {
        \Log::info('DEBUG: Starting tone family update', [
            'family_id' => $family->id,
            'request_method' => $request->method(),
            'request_data' => $request->all(),
            'colors_before' => $family->colors->pluck('id')->toArray()
        ]);

        try {
            $colorsBefore = $family->colors->pluck('name', 'id')->toArray();
            
            // Simple update without validation for testing
            $family->update([
                'title' => $request->get('title', $family->title),
                'order' => $request->get('order', $family->order),
                'is_active' => $request->get('is_active', $family->is_active),
            ]);

            $colors = $request->get('colors', []);
            if (!empty($colors)) {
                $colorData = [];
                foreach ($colors as $index => $colorId) {
                    $colorData[$colorId] = [
                        'order' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                $syncResult = $family->colors()->sync($colorData);
                
                \Log::info('DEBUG: Sync completed', [
                    'sync_result' => $syncResult,
                    'colors_after' => $family->fresh()->colors->pluck('id')->toArray()
                ]);
            }

            $colorsAfter = $family->fresh()->colors->pluck('name', 'id')->toArray();

            return response()->json([
                'success' => true,
                'message' => 'Family updated successfully',
                'family' => [
                    'id' => $family->id,
                    'title' => $family->title,
                    'colors_before' => $colorsBefore,
                    'colors_after' => $colorsAfter,
                    'colors_count_before' => count($colorsBefore),
                    'colors_count_after' => count($colorsAfter),
                ]
            ], 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            \Log::error('DEBUG: Error in tone family update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
}
