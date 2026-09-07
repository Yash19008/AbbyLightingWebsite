<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DecorativeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DecorativeCategoryController extends Controller
{
    public function index()
    {
        $title = "Decorative Categories";
        $main_module = 'Decorative Product';
        $categories = DecorativeCategory::with('parent')->orderBy('sort_order', 'asc')->get();
        return view('admin.decorative-categories.index', compact('title', 'main_module', 'categories'));
    }

    public function add()
    {
        $title = "Add Decorative Category";
        $main_module = 'Decorative Product';
        $parent_categories = DecorativeCategory::whereNull('parent_id')->orderBy('name', 'asc')->get();
        return view('admin.decorative-categories.add', compact('title', 'main_module', 'parent_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:decorative_categories,slug',
            'parent_id' => 'nullable|exists:decorative_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/decorative_categories'), $name);
            $imagePath = $name;
        }

        DecorativeCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
            'show_in_mega_dropdown' => $request->has('show_in_mega_dropdown') ? (bool)$request->show_in_mega_dropdown : true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('decorative_category_admin')->with('success', 'Category added successfully.');
    }

    public function edit($id)
    {
        $title = "Edit Decorative Category";
        $main_module = 'Decorative Product';
        $category = DecorativeCategory::findOrFail($id);
        $parent_categories = DecorativeCategory::whereNull('parent_id')->where('id', '!=', $id)->orderBy('name', 'asc')->get();
        $action = route('decorative_category_admin.update', $id);
        return view('admin.decorative-categories.edit', compact('title', 'main_module', 'category', 'parent_categories', 'action'));
    }

    public function update(Request $request, $id)
    {
        $category = DecorativeCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:decorative_categories,slug,' . $id,
            'parent_id' => 'nullable|exists:decorative_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $name = time() . '-' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/decorative_categories'), $name);
            $imagePath = $name;
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
            'show_in_mega_dropdown' => $request->has('show_in_mega_dropdown') ? (bool)$request->show_in_mega_dropdown : true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('decorative_category_admin')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = DecorativeCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('decorative_category_admin')->with('success', 'Category deleted successfully.');
    }
}
