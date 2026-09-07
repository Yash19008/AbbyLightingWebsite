<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $title = "Blog Categories";
        $main_module = 'Blog Categories';
        $categories = BlogCategory::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.blog-categories.index', compact('title', 'main_module', 'categories'));
    }

    public function add()
    {
        $title = "Add Blog Category";
        $main_module = 'Blog Categories';
        return view('admin.blog-categories.add', compact('title', 'main_module'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'nullable|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/blog_categories');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }
            $name = time() . '-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $name);
            $imagePath = $name;
        }

        BlogCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category added successfully.');
    }

    public function edit($id)
    {
        $title = "Edit Blog Category";
        $main_module = 'Blog Categories';
        $category = BlogCategory::findOrFail($id);
        return view('admin.blog-categories.edit', compact('title', 'main_module', 'category'));
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'status' => 'nullable|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (BlogCategory::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/blog_categories');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }
            
            // Remove old image
            if ($category->image && File::exists($uploadDir . '/' . $category->image)) {
                File::delete($uploadDir . '/' . $category->image);
            }

            $name = time() . '-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $name);
            $imagePath = $name;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category updated successfully.');
    }

    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        
        if ($category->image) {
            $imagePath = public_path('uploads/blog_categories/' . $category->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category deleted successfully.');
    }
}
