<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufacturingSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManufacturingSectionController extends Controller
{
    /**
     * Display the manufacturing section editing page
     */
    public function index()
    {
        // Redirect to edit page instead
        return redirect()->route('admin.manufacturing.edit', 1);
    }

    /**
     * Display the form for editing the manufacturing section
     */
    public function edit($id = 1)
    {
        $section = ManufacturingSection::first();
        
        // If no record exists, create a default one
        if (!$section) {
            $section = ManufacturingSection::create([
                'title' => 'Built on Manufacturing Excellence',
                'title_highlight' => 'Manufacturing Excellence',
                'description' => 'Every Abby luminaire begins long before it reaches a project. Designed, engineered, manufactured and tested entirely in-house, our vertically integrated facility brings every stage of production under one roof.',
                'button_text' => 'See How It\'s Made',
                'button_link' => '/#manufacturing',
                'is_active' => true
            ]);
        }
        
        $main_module = 'Settings';
        
        return view('admin.manufacturing.edit', compact('section', 'main_module'));
    }

    /**
     * Update the manufacturing section
     */
    public function update(Request $request)
    {
        $section = ManufacturingSection::first();
        
        if (!$section) {
            return redirect()->route('admin.manufacturing.index')->with('error', 'Section not found');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:500',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($section->background_image && Storage::disk('public')->exists($section->background_image)) {
                Storage::disk('public')->delete($section->background_image);
            }

            // Store new image
            $imagePath = $request->file('background_image')->store('uploads/manufacturing', 'public');
            $section->background_image = $imagePath;
        }

        // Update other fields
        $section->title = $validated['title'];
        $section->title_highlight = $validated['title_highlight'] ?? null;
        $section->description = $validated['description'] ?? null;
        $section->button_text = $validated['button_text'] ?? null;
        $section->button_link = $validated['button_link'] ?? null;
        $section->is_active = $request->has('is_active');
        $section->updated_by = Auth::id();
        $section->save();

        return redirect()->route('admin.manufacturing.edit', 1)->with('success', 'Manufacturing section updated successfully');
    }
}
