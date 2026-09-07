<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Clients';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Clients", 'main_module' => $this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = new Client;
        $data['results'] =  $data['results']->orderBy('id', 'DESC')->paginate(10); //config('custom_config.settings.admin_pagination_limit')
        $data['tbl'] = Common_function::encrypt('clients');

        $data['results']->appends(['search' => $data['search']]);
        return view('admin.clients.clients', $data);
    }
    public function add()
    {
        $data = array('title' => "Add Client", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/clients/insert'), 'frn_id' => 'frm_client');
        return view('admin.clients.client_edit', $data);
    }
    public function insert(Request $request)
    {
        // VALIDATION RULE
        $validation_array = array(
            'file' => 'required',
        );
        $rules = [
            'file.required' => 'Image is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request, $validation_array, $rules);
        $values = [
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/clients', $fileNamePhoto, 'public');
            // $fileModel->save();

            $values['path'] = $fileNamePhoto;
        }

        $values = Client::create($values);

        $newData = [
            'path' => $file ? $fileNamePhoto : NULL,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => ip2long(\Request::ip()),
            'action' => 'Add',
            'module' => 'Client',
            'message' => 'Client newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('client_admin')->withInput()->withSuccess('Client has been added successfully.');
    }
    public function edit($id)
    {
        $data = array('title' => "Edit Client", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/clients/update/' . $id), 'frn_id' => 'frm_client_edit');
        $data['client'] = Client::where('id', $id)->first();
        return view('admin.clients.client_edit', $data);
    }
    public function update(Request $request, $id)
    {
        $oldClient = Client::where('id', $id)->first();
        $oldData =  [
            'path' => ($oldClient->path != '') ? $oldClient->path : NULL,
            'created_by' => $oldClient->created_by,
            'created_at' => $oldClient->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/clients', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['path'] = $fileNamePhoto;
        }
        Client::where('id', '=', $id)
            ->update($update_array);

        $newData =  [
            'path' => ($request->path != '') ? $request->path : NULL,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        ];

        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => \Request::ip(),
            'action' => 'Update',
            'module' => $this->main_module,
            'message' => 'Client has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('client_admin')->with('success', 'Client has been updated successfully.');
    }

    public function uploadBanner(Request $request){
         // VALIDATION RULE
         $validation_array = array(
            'banner_image' => 'required',
        );
        $rules = [
            'banner_image.required' => 'Banner image is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request, $validation_array, $rules);
        $file = $request->banner_image;
        $file->storeAs('uploads/banners', 'banner_image-clients.jpg', 'public');
        return redirect()->back();
    }
}
