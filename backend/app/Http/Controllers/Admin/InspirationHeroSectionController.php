<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InspirationHeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InspirationHeroSectionController extends Controller
{
    /**
     * Display the inspiration hero section editing page
     */
    public function index()
    {
        return redirect()->route('admin.inspiration_hero.edit', 1);
    }

    /**
     * Display the form for editing the inspiration hero section
     */
    public function edit($id = 1)
    {
        $section = InspirationHeroSection::first();

        // If no record exists, create default one
        if (!$section) {
            $section = InspirationHeroSection::create([
                'title' => 'Ideas, stories & inspiration',
                'title_highlight' => 'Insights',
                'is_active' => true,
            ]);
        }

        $main_module = 'Inspiration';

        return view('admin.inspiration_hero.edit', compact('section', 'main_module'));
    }

    /**
     * Update the inspiration hero section
     */
    public function update(Request $request)
    {
        $section = InspirationHeroSection::first();

        if (!$section) {
            $section = new InspirationHeroSection();
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($section->background_image && Storage::disk('public')->exists($section->background_image)) {
                Storage::disk('public')->delete($section->background_image);
            }

            // Store new image
            $imagePath = $request->file('background_image')->store('uploads/inspiration', 'public');
            $section->background_image = $imagePath;
        }

        // Update fields
        $section->title = $validated['title'];
        $section->title_highlight = $validated['title_highlight'] ?? null;
        $section->is_active = $request->has('is_active');
        $section->updated_by = Auth::id();
        $section->save();

        return redirect()->route('admin.inspiration_hero.edit', 1)->with('success', 'Inspiration Hero Banner updated successfully');
    }
}
