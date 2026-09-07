<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsSectionController extends Controller
{
    /**
     * Display the form for editing the news section
     */
    public function edit($id = 1)
    {
        $section = NewsSection::first();
        
        // If no record exists, create a default one
        if (!$section) {
            $section = NewsSection::create([
                'title' => 'Latest News & Events',
                'subtitle' => 'Stay Updated',
                'link' => '/news',
                'is_active' => true
            ]);
        }
        
        $main_module = 'News Section';
        
        return view('admin.news-section.edit', compact('section', 'main_module'));
    }

    /**
     * Update the news section
     */
    public function update(Request $request)
    {
        $section = NewsSection::first();
        
        if (!$section) {
            return redirect()->route('admin.news-section.edit', 1)->with('error', 'Section not found');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($section->image && Storage::disk('public')->exists($section->image)) {
                Storage::disk('public')->delete($section->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('uploads/news-section', 'public');
            $section->image = $imagePath;
        }

        // Update other fields
        $section->title = $validated['title'];
        $section->subtitle = $validated['subtitle'] ?? null;
        $section->link = $validated['link'] ?? null;
        $section->is_active = $request->has('is_active');
        $section->updated_by = Auth::id();
        $section->save();

        return redirect()->route('admin.news-section.edit', 1)->with('success', 'News section updated successfully');
    }
}
