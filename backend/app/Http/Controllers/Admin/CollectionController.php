<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionParameterItem;
use App\Models\CollectionCompositionItem;
use App\Models\CollectionToneFamily;
use App\Models\CollectionPlaceItem;
use App\Models\ColorMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Helpers\Common_function;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index()
    {
        $collections = Collection::with('heroSection', 'parametersSection', 'compositionsSection')->ordered()->get();
        $title = 'Collections';
        $main_module = 'Collections';
        $tbl = Common_function::encrypt('collections');
        return view('admin.collections.index', compact('collections', 'title', 'main_module', 'tbl'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return view('admin.collections.create');
    }

    /**
     * Store a newly created collection in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'show_in_menu' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $collection = Collection::create($validated);

        return redirect()
            ->route('admin.collections.edit', $collection->slug)
            ->with('success', 'Collection created successfully! Now add hero section.');
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        $collection->load('heroSection', 'parametersSection.items', 'compositionsSection.items', 'productsSection', 'tonesSection.families.colors', 'placesSection.items', 'catalogueSection', 'spreadDropSection');
        return view('admin.collections.edit', compact('collection'));
    }

    /**
     * Update the specified collection in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:collections,slug,' . $collection->id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'show_in_menu' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_menu'] = $request->has('show_in_menu');
        $collection->update($validated);

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=general';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Collection updated successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Collection updated successfully!');
    }

    /**
     * Remove the specified collection from storage.
     */
    public function destroy(Collection $collection)
    {
        $collection->delete();

        return redirect()
            ->route('admin.collections.index')
            ->with('success', 'Collection deleted successfully!');
    }

    /**
     * Toggle collection active status.
     */
    public function toggleActive(Collection $collection)
    {
        $collection->update(['is_active' => !$collection->is_active]);

        return redirect()
            ->back()
            ->with('success', 'Collection status updated!');
    }

    /**
     * Store or update hero section for a collection.
     */
    public function storeHeroSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'title_prefix' => 'nullable|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'breadcrumb_parent_text' => 'nullable|string|max:255',
            'breadcrumb_parent_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($collection->heroSection && $collection->heroSection->background_image) {
                Storage::delete($collection->heroSection->background_image);
            }

            $path = $request->file('background_image')->store('collections/hero', 'public');
            $validated['background_image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        // Update or create hero section
        $collection->heroSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            $validated
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Hero section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=hero')
            ->with('success', 'Hero section saved successfully!');
    }

    /**
     * Store or update parameters section for a collection.
     */
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

    /**
     * Show form to add a parameter item.
     */
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

    /**
     * Store a new parameter item.
     */
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

    /**
     * Show form to edit a parameter item.
     */
    public function editParameterItem(Collection $collection, CollectionParameterItem $item)
    {
        $colorMasters = \App\Models\ColorMaster::orderBy('name')->get();
        return view('admin.collections.parameter-edit', compact('collection', 'item', 'colorMasters'));
    }

    /**
     * Update a parameter item.
     */
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

    /**
     * Delete a parameter item.
     */
    public function deleteParameterItem(Collection $collection, CollectionParameterItem $item)
    {
        $item->delete();

        $redirectUrl = route('admin.collections.edit', $collection->slug) . '?tab=parameters';

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Parameter card deleted successfully!', 'redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl)->with('success', 'Parameter card deleted successfully!');
    }

    /**
     * Store or update compositions section for a collection.
     */
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

    /**
     * Show form to add a composition item.
     */
    public function addCompositionItem(Collection $collection)
    {
        if (!$collection->compositionsSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create compositions section first!');
        }

        return view('admin.collections.composition-add', compact('collection'));
    }

    /**
     * Store a new composition item.
     */
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

    /**
     * Show form to edit a composition item.
     */
    public function editCompositionItem(Collection $collection, CollectionCompositionItem $item)
    {
        return view('admin.collections.composition-edit', compact('collection', 'item'));
    }

    /**
     * Update a composition item.
     */
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

    /**
     * Delete a composition item.
     */
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

    /**
     * Store products section data.
     */
    public function storeProductsSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'heading' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string',
            'view_more_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $collection->productsSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'heading' => $validated['heading'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'view_more_text' => $validated['view_more_text'] ?? 'View all',
                'is_active' => $request->has('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Products section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=products')
            ->with('success', 'Products section saved successfully!');
    }

    /**
     * Store or update tones section for a collection.
     */
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

    /**
     * Show form to add a tone family.
     */
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

    /**
     * Store a new tone family.
     */
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

    /**
     * Show form to edit a tone family.
     */
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

    /**
     * Update a tone family.
     */
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

    /**
     * Delete a tone family.
     */
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

    /**
     * Store or update places section for a collection.
     */
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

    /**
     * Store catalogue section data.
     */
    public function storeCatalogueSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:100',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'title_highlight' => $validated['title_highlight'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('collections/catalogue', 'public');
        }

        $collection->catalogueSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            $data
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Catalogue section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=catalogue')
            ->with('success', 'Catalogue section saved successfully!');
    }

    /**
     * Show form to add a place item.
     */
    public function addPlaceItem(Collection $collection)
    {
        if (!$collection->placesSection) {
            return redirect()
                ->route('admin.collections.edit', $collection->slug)
                ->with('error', 'Please create places section first!');
        }

        return view('admin.collections.place-add', compact('collection'));
    }

    /**
     * Store a new place item.
     */
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

    /**
     * Show form to edit a place item.
     */
    public function editPlaceItem(Collection $collection, CollectionPlaceItem $item)
    {
        return view('admin.collections.place-edit', compact('collection', 'item'));
    }

    /**
     * Update a place item.
     */
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

    /**
     * Delete a place item.
     */
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

    /**
     * Store or update spread & drop section for a collection.
     */
    public function storeSpreadDropSection(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $collection->spreadDropSection()->updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'is_active' => $request->boolean('is_active'),
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Spread & Drop section saved successfully!']);
        }

        return redirect()
            ->to(route('admin.collections.edit', $collection->slug) . '?tab=spread-drop')
            ->with('success', 'Spread & Drop section saved successfully!');
    }

    // DEBUG METHODS - REMOVE IN PRODUCTION
    
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
