<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecSpecAttribute;
use Illuminate\Http\Request;

class DecorativeSpecAttributeController extends Controller
{
    public function index(Request $request)
    {
        $query = DecSpecAttribute::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $attributes = $query->orderBy('name', 'asc')->paginate(20);
        return view('admin.decorative_spec_attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.decorative_spec_attributes.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:dec_spec_attributes,name',
        ]);

        DecSpecAttribute::create([
            'name' => $request->name,
        ]);

        return redirect()->route('decorative_spec_attributes_admin')->with('success', 'Attribute created successfully');
    }

    public function edit($id)
    {
        $attribute = DecSpecAttribute::findOrFail($id);
        return view('admin.decorative_spec_attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $attribute = DecSpecAttribute::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:dec_spec_attributes,name,'.$id,
        ]);

        $attribute->update([
            'name' => $request->name,
        ]);

        return redirect()->route('decorative_spec_attributes_admin')->with('success', 'Attribute updated successfully');
    }

    public function destroy($id)
    {
        $attribute = DecSpecAttribute::findOrFail($id);
        
        // Prevent deleting if spec rows are attached
        if (\App\Models\Decorative\DecProductSpecRow::where('dec_spec_attribute_id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete attribute because it is used in products.'
            ]);
        }

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully'
        ]);
    }
}
