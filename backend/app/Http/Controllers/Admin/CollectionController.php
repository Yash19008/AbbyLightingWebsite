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
     * Duplicate the specified collection.
     */
    public function duplicate(Collection $collection)
    {
        $collection->load([
            'heroSection',
            'parametersSection.items',
            'compositionsSection.items',
            'tonesSection.families.colors',
            'placesSection.items',
            'catalogueSection',
            'spreadDropSection',
            'productsSection'
        ]);

        $newCollection = $collection->replicate();
        $newCollection->name = $collection->name . ' (Copy)';
        $newCollection->slug = $collection->slug . '-' . time();
        $newCollection->is_active = false;
        $newCollection->save();

        if ($collection->heroSection) {
            $newHero = $collection->heroSection->replicate();
            $newHero->collection_id = $newCollection->id;
            $newHero->save();
        }

        if ($collection->parametersSection) {
            $newParams = $collection->parametersSection->replicate();
            $newParams->collection_id = $newCollection->id;
            $newParams->save();

            foreach ($collection->parametersSection->items as $item) {
                $newItem = $item->replicate();
                $newItem->parameters_section_id = $newParams->id;
                $newItem->save();
            }
        }

        if ($collection->compositionsSection) {
            $newCompositions = $collection->compositionsSection->replicate();
            $newCompositions->collection_id = $newCollection->id;
            $newCompositions->save();

            foreach ($collection->compositionsSection->items as $item) {
                $newItem = $item->replicate();
                $newItem->compositions_section_id = $newCompositions->id;
                $newItem->save();
            }
        }

        if ($collection->tonesSection) {
            $newTones = $collection->tonesSection->replicate();
            $newTones->collection_id = $newCollection->id;
            $newTones->save();

            foreach ($collection->tonesSection->families as $family) {
                $newFamily = $family->replicate();
                $newFamily->tones_section_id = $newTones->id;
                $newFamily->save();
                
                // Duplicate colors relation
                foreach ($family->colors as $color) {
                    $newFamily->colors()->attach($color->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if ($collection->placesSection) {
            $newPlaces = $collection->placesSection->replicate();
            $newPlaces->collection_id = $newCollection->id;
            $newPlaces->save();

            foreach ($collection->placesSection->items as $item) {
                $newItem = $item->replicate();
                $newItem->places_section_id = $newPlaces->id;
                $newItem->save();
            }
        }

        if ($collection->catalogueSection) {
            $newCat = $collection->catalogueSection->replicate();
            $newCat->collection_id = $newCollection->id;
            $newCat->save();
        }

        if ($collection->spreadDropSection) {
            $newSpread = $collection->spreadDropSection->replicate();
            $newSpread->collection_id = $newCollection->id;
            $newSpread->save();
        }

        if ($collection->productsSection) {
            $newProdSection = $collection->productsSection->replicate();
            $newProdSection->collection_id = $newCollection->id;
            $newProdSection->save();
        }

        return redirect()->route('admin.collections.edit', $newCollection->slug)->with('success', 'Collection duplicated successfully.');
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


    /**
     * Show form to add a parameter item.
     */


    /**
     * Store a new parameter item.
     */


    /**
     * Show form to edit a parameter item.
     */


    /**
     * Update a parameter item.
     */


    /**
     * Delete a parameter item.
     */


    /**
     * Store or update compositions section for a collection.
     */


    /**
     * Show form to add a composition item.
     */


    /**
     * Store a new composition item.
     */


    /**
     * Show form to edit a composition item.
     */


    /**
     * Update a composition item.
     */


    /**
     * Delete a composition item.
     */


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


    /**
     * Show form to add a tone family.
     */


    /**
     * Store a new tone family.
     */


    /**
     * Show form to edit a tone family.
     */


    /**
     * Update a tone family.
     */


    /**
     * Delete a tone family.
     */


    /**
     * Store or update places section for a collection.
     */


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


    /**
     * Store a new place item.
     */


    /**
     * Show form to edit a place item.
     */


    /**
     * Update a place item.
     */


    /**
     * Delete a place item.
     */


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
    





}
