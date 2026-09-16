<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCatalogueSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeCatalogueSectionController extends Controller
{
    /**
     * Display the catalogue section editing page
     */
    public function index()
    {
        return redirect()->route('admin.home_catalogue.edit', 1);
    }

    /**
     * Display the form for editing the catalogue section
     */
    public function edit($id = 1)
    {
        $section = HomeCatalogueSection::first();

        // If no record exists, create default one
        if (!$section) {
            $section = HomeCatalogueSection::create([
                'title' => 'Find the right catalogue.',
                'title_highlight' => 'catalogue.',
                'description' => 'Explore our complete collection of architectural, decorative and outdoor lighting, with detailed specifications for every luminaire.',
                'button_text' => 'Browse the Library',
                'button_link' => '/#contact',
                'is_active' => 'yes'
            ]);
        }

        $main_module = 'Pages';

        return view('admin.home_catalogue.edit', compact('section', 'main_module'));
    }

    /**
     * Update the home catalogue section
     */
    public function update(Request $request)
    {
        $section = HomeCatalogueSection::first();

        if (!$section) {
            $section = new HomeCatalogueSection();
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'is_active' => 'nullable|string'
        ]);

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($section->background_image && Storage::disk('public')->exists($section->background_image)) {
                Storage::disk('public')->delete($section->background_image);
            }

            // Store new image
            $imagePath = $request->file('background_image')->store('uploads/home_catalogue', 'public');
            $section->background_image = $imagePath;
        }

        // Update fields
        $section->title = $validated['title'];
        $section->title_highlight = $validated['title_highlight'] ?? null;
        $section->description = $validated['description'] ?? null;
        $section->button_text = $validated['button_text'] ?? null;
        $section->button_link = $validated['button_link'] ?? null;
        $section->is_active = $request->has('is_active') ? 'yes' : 'no';
        $section->updated_by = Auth::id();
        $section->save();

        return redirect()->route('admin.home_catalogue.edit', 1)->with('success', 'Home Catalogue section updated successfully');
    }
}
