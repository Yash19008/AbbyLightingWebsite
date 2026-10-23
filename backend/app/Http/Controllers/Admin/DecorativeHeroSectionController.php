<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DecorativeHeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DecorativeHeroSectionController extends Controller
{
    /**
     * Display the form for editing the decorative hero section
     */
    public function edit()
    {
        $section = DecorativeHeroSection::first();

        // If no record exists, create default one
        if (!$section) {
            $section = DecorativeHeroSection::create([
                'heading' => 'Decorative',
                'styled_heading' => 'Lights',
                'description' => 'Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order.',
                'is_active' => true,
            ]);
        }

        $main_module = 'Decorative';

        return view('admin.decorative_hero.edit', compact('section', 'main_module'));
    }

    /**
     * Update the decorative hero section
     */
    public function update(Request $request)
    {
        $section = DecorativeHeroSection::first();

        if (!$section) {
            $section = new DecorativeHeroSection();
        }

        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'styled_heading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image_desktop' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:8192',
            'image_tablet' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:8192',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:8192',
        ]);

        // Handle desktop image upload
        if ($request->hasFile('image_desktop')) {
            if ($section->image_desktop && Storage::disk('public')->exists($section->image_desktop)) {
                Storage::disk('public')->delete($section->image_desktop);
            }
            $section->image_desktop = $request->file('image_desktop')->store('uploads/decorative_hero', 'public');
        }

        // Handle tablet image upload
        if ($request->hasFile('image_tablet')) {
            if ($section->image_tablet && Storage::disk('public')->exists($section->image_tablet)) {
                Storage::disk('public')->delete($section->image_tablet);
            }
            $section->image_tablet = $request->file('image_tablet')->store('uploads/decorative_hero', 'public');
        }

        // Handle mobile image upload
        if ($request->hasFile('image_mobile')) {
            if ($section->image_mobile && Storage::disk('public')->exists($section->image_mobile)) {
                Storage::disk('public')->delete($section->image_mobile);
            }
            $section->image_mobile = $request->file('image_mobile')->store('uploads/decorative_hero', 'public');
        }

        // Update text fields
        $section->heading = $validated['heading'];
        $section->styled_heading = $validated['styled_heading'] ?? null;
        $section->description = $validated['description'] ?? null;
        $section->is_active = $request->has('is_active');
        $section->updated_by = Auth::guard('admin')->id() ?? Auth::id();
        $section->save();

        return redirect()->route('admin.decorative_hero.edit')->with('success', 'Decorative Hero Banner updated successfully');
    }
}
