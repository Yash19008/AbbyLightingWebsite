<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\Composition;
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

        $data['search'] = $request->input('search');
        $query = Composition::query();

        if (!empty($data['search'])) {
            $query->where(function ($q) use ($data) {
                $q->where('title', 'LIKE', '%' . $data['search'] . '%')
                  ->orWhere('category', 'LIKE', '%' . $data['search'] . '%');
            });
        }
        $data['results'] = $query->orderBy('id', 'DESC')->get();
        $data['tbl'] = Common_function::encrypt('compositions');
        
        return view('admin.compositions', $data);
    }

    public function add()
    {
        $data = array('title' => "Add Composition", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/compositions/insert'), 'frn_id' => 'frm_composition');
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
            'category' => $request->category,
            'is_showcase' => $request->has('is_showcase') ? 1 : 0,
            'created_at' => $this->currentDateTime,
        ];
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());
            $filePath = $file->storeAs('uploads/compositions', $fileNamePhoto, 'public');
            $values['image'] = $fileNamePhoto;
        }

        Composition::create($values);

        return redirect()->route('composition_admin')->with('success', 'Composition Created Successfully.');
    }

    public function edit($id)
    {
        $data = array('title' => "Edit Composition", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/compositions/update/' . $id), 'frn_id' => 'frm_composition');
        $data['result'] = Composition::find($id);
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
            'category' => $request->category,
            'is_showcase' => $request->has('is_showcase') ? 1 : 0,
            'updated_at' => $this->currentDateTime,
        ];
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());
            $filePath = $file->storeAs('uploads/compositions', $fileNamePhoto, 'public');
            $values['image'] = $fileNamePhoto;
        }

        Composition::where('id', $id)->update($values);

        return redirect()->route('composition_admin')->with('success', 'Composition Updated Successfully.');
    }
}
