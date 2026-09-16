<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DecorativeCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = DecCategory::withCount('products');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name', 'asc')->paginate(20);
        return view('admin.decorative_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.decorative_categories.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dec_categories,slug',
        ]);

        DecCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
        ]);

        return redirect()->route('decorative_category_admin')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = DecCategory::findOrFail($id);
        return view('admin.decorative_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = DecCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dec_categories,slug,'.$id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
        ]);

        return redirect()->route('decorative_category_admin')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = DecCategory::findOrFail($id);
        
        // Prevent deleting if products are attached
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category because it has products attached.'
            ]);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
