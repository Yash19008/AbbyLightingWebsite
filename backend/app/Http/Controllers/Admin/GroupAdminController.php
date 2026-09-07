<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\GroupMaster;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GroupAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Group Master';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
  
    public function index(Request $request)
    {
        $data = array('title' => "Group Master", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('group_masters');
        return view('admin.group_master.groups', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = GroupMaster::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
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
                                        <a href="' . route('group_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['title','status','action'])
                ->make(true);
        }
    }
    public function add()
    {
        $data = array('title'=>" Add Group",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/groups/insert'),'frn_id'=>'frm_group');
        return view('admin.group_master.group_edit', $data);
    }
    public function insert(Request $request)
    {
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        $groupValues = [
            'title' =>  $request->title,
            'is_active'=>'yes',
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
     
        $group = GroupMaster::create($groupValues);

        $newData =[
            'title' =>  $request->title,
            'is_active'=>'yes',
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
            'module'=>'Group',
            'message'=>'Group newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
       
        return redirect()->route('group_admin')->withInput()->withSuccess('Group has been added successfully.');
    }
    public function edit($id){
        $data = array('title'=>" Edit Group", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/groups/update/'.$id),'frn_id'=>'frm_group');
        $data['group'] = GroupMaster::where('id','=',$id)->limit(1)->first();
        if(!empty($data['group'])) {
            return view('admin.group_master.group_edit', $data);
        }
        else{
            return redirect('admin/groups');
        }
    }
    public function update(Request $request,$id){
      
        $oldGroup = GroupMaster::where('id',$id)->first();
        $oldData =  [
            'title' =>  $oldGroup->title,
            'created_by'=> $oldGroup->created_by,
            'created_at'=>$oldGroup->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'title' =>  $request->title,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
        
        GroupMaster::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'title' =>  $request->title,
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
            'message' => 'Group has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect('admin/groups')->with('success','Group has been updated successfully.');
    }
}
