<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompositionCategory;
use Illuminate\Support\Str;

class CompositionCategoryController extends Controller
{
    public function __construct()
    {
        $this->main_module = 'Composition Categories';
    }

    public function index(Request $request)
    {
        $data = array('title' => "Composition Categories", 'main_module' => $this->main_module);
        
        $data['results'] = CompositionCategory::orderBy('id', 'DESC')->get();
        $data['tbl'] = \App\Helpers\Common_function::encrypt('composition_categories');
        
        return view('admin.composition_categories.index', $data);
    }

    public function add()
    {
        $data = array('title' => "Add Composition Category", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/composition-categories/insert'));
        return view('admin.composition_categories.edit', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:composition_categories,name',
        ]);

        CompositionCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('composition_categories_admin')->with('success', 'Category Created Successfully.');
    }

    public function edit($id)
    {
        $data = array('title' => "Edit Composition Category", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/composition-categories/update/' . $id));
        $data['result'] = CompositionCategory::findOrFail($id);
        return view('admin.composition_categories.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:composition_categories,name,' . $id,
        ]);

        $category = CompositionCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('composition_categories_admin')->with('success', 'Category Updated Successfully.');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $category = CompositionCategory::findOrFail($id);
        
        $category->delete();

        return redirect()->route('composition_categories_admin')->with('success', 'Category Deleted Successfully');
    }
}
