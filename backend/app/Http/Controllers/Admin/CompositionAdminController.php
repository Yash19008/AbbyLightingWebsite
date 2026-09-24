<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\Composition;
use App\Models\CompositionCategory;
use App\Models\Decorative\DecProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CompositionAdminController extends Controller
{
    public function __construct()
    {
        $this->main_module = 'Compositions';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }

    public function index(Request $request)
    {
        $data = array('title' => "Compositions", 'main_module' => $this->main_module);

        $data['results'] = Composition::with('category_rel')->orderBy('id', 'DESC')->get();
        $data['tbl'] = Common_function::encrypt('compositions');
        
        return view('admin.compositions', $data);
    }

    public function add()
    {
        $data = array('title' => "Add Composition", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/compositions/insert'), 'frn_id' => 'frm_composition');
        $data['categories'] = CompositionCategory::orderBy('name', 'asc')->get();
        $data['products'] = DecProduct::where('status', 'published')->orderBy('name', 'asc')->get();
        return view('admin.composition_edit', $data);
    }

    public function insert(Request $request)
    {
        $validation_array = array(
            'title' => 'required',
            'file' => 'required',
        );
        $rules = [
            'title.required' => 'The Title is required',
            'file.required' => 'Image is required',
        ];
        $this->validate($request, $validation_array, $rules);
        
        $values = [
            'title' => $request->title,
            'kicker' => $request->kicker,
            'category_id' => $request->category_id,
            'is_showcase' => $request->has('is_showcase') ? 1 : 0,
            'created_at' => $this->currentDateTime,
        ];
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());
            $filePath = $file->storeAs('uploads/compositions', $fileNamePhoto, 'public');
            $values['image'] = $fileNamePhoto;
        }

        $composition = Composition::create($values);

        if ($request->has('product_ids')) {
            $composition->products()->sync($request->product_ids);
        }

        return redirect()->route('composition_admin')->with('success', 'Composition Created Successfully.');
    }

    public function edit($id)
    {
        $data = array('title' => "Edit Composition", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/compositions/update/' . $id), 'frn_id' => 'frm_composition');
        $data['result'] = Composition::with('products')->find($id);
        $data['categories'] = CompositionCategory::orderBy('name', 'asc')->get();
        $data['products'] = DecProduct::where('status', 'published')->orderBy('name', 'asc')->get();
        return view('admin.composition_edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validation_array = array(
            'title' => 'required',
        );
        $rules = [
            'title.required' => 'The Title is required',
        ];
        $this->validate($request, $validation_array, $rules);

        $values = [
            'title' => $request->title,
            'kicker' => $request->kicker,
            'category_id' => $request->category_id,
            'is_showcase' => $request->has('is_showcase') ? 1 : 0,
            'updated_at' => $this->currentDateTime,
        ];
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());
            $filePath = $file->storeAs('uploads/compositions', $fileNamePhoto, 'public');
            $values['image'] = $fileNamePhoto;
        }

        $composition = Composition::find($id);
        $composition->update($values);

        if ($request->has('product_ids')) {
            $composition->products()->sync($request->product_ids);
        } else {
            $composition->products()->sync([]);
        }

        return redirect()->route('composition_admin')->with('success', 'Composition Updated Successfully.');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $composition = Composition::findOrFail($id);
        
        // Remove image if exists
        if ($composition->image && file_exists(storage_path('app/public/uploads/compositions/' . $composition->image))) {
            unlink(storage_path('app/public/uploads/compositions/' . $composition->image));
        }

        // Delete pivot table data via relations if needed, but onDelete('cascade') usually handles it.
        // Sync to empty just to be safe.
        $composition->products()->sync([]);
        
        $composition->delete();

        return redirect()->route('composition_admin')->with('success', 'Composition Deleted Successfully');
    }
}
