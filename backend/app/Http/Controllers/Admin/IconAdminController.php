<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\Icon;
use DataTables;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IconAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Icons';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Icons", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('icons');

        return view('admin.icon.icons',$data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Icon::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('icon', function ($row) {
                    $img_url = $row->icon ?  asset('storage/uploads/icons/'.@$row->icon) : '-';

                    return '<div class="no-sort roundimg-col">
                                <div class="bg-image">
                                    <img src="' . $img_url . '" class="list-image-prof" style="width:auto;height: 100%;">
                                </div>
                            </div>';
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
              
                ->addColumn('status', function ($row) {
                    $temp_status = $row->is_active == 'yes' ? "Checked" : "";
                    return '<div class="custom-control custom-switch text-center">
                                <input type="checkbox" class="custom-control-input knob switch" id="customSwitch' . $row->id . '" ' . $temp_status . '>
                                <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                            </div>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $actions_html = '<div class="text-center list-action actBtn-td">
                                        <a href="' . route('icon_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['name','icon','status','action'])
                ->make(true);
        }
    }
    public function add(){
        $data = array('title'=>"Add Icon",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/icons/insert'),'frn_id'=>'frm_icon');
        return view('admin.icon.icon_edit', $data);
    }
    public function insert(Request $request){
        $Val = [
            'name'=>$request->name,
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
        $file = $request->file;     
       
        if($file) {
            $fileNamePhoto =time().'_'.trim($file->getClientOriginalName());
            
            $filePath = $file->storeAs('uploads/icons', $fileNamePhoto, 'public');
            // $fileModel->save();

            $Val['icon'] = $fileNamePhoto;
        }
        $icon = Icon::create($Val);
    
        $newData =[
            'name'=>$request->name,
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];

        $newDataArr = json_encode($newData);
        
        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id'=>Auth::guard('admin')->user()->id,
            'timestamp'=>$this->currentDateTime,
            'ip_address'=>ip2long(\Request::ip()),
            'action'=>'Add',
            'module'=>'Icon',
            'message'=>' Icon newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
       
        return redirect()->route('icon_admin')->withInput()->withSuccess('Icon has been added successfully.');
    }
    public function edit($id){
        $data = array('title'=>" Edit Icon", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/icons/update/'.$id),'frn_id'=>'frm_icon_edit');
        $data['icon'] = Icon::where('id',$id)->first();
        return view('admin.icon.icon_edit', $data);
    }
    public function update(Request $request,$id){
        $oldIcon = Icon::where('id',$id)->first();
       
        $oldData =  [
            'name'=>$oldIcon->name,
            'is_active'=>$oldIcon->is_active,
            'created_by'=> $oldIcon->created_by,
            'created_at'=>$oldIcon->created_at
        ];
        $oldDataArr = json_encode($oldData);
        
        // UPDATE ARRAY
        $update_array = array(
            'name'=>$request->name,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
        $file = $request->file;     
       
        if($file) {
           
            $image_path = public_path("/storage/uploads/icons/".$oldIcon->icon);  
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $fileNamePhoto =time().'_'.trim($file->getClientOriginalName());
            
            $filePath = $file->storeAs('uploads/icons', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['icon'] = $fileNamePhoto;
        }
        Icon::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'name'=>$request->name,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,             
        ];

        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime ,
            'ip_address' => \Request::ip(),
            'action' => 'Update',
            'module' => $this->main_module,
            'message' => 'Icon has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('icon_admin')->with('success','Icon has been updated successfully.');
    }
}
