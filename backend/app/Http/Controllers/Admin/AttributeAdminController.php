<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\GroupAttributeMaster;
use App\Models\AuditLog;
use App\Models\GroupMaster;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttributeAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Attributes';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
  
    public function index(Request $request)
    {
        $data = array('title' => "Attributes", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('group_attribute_masters');
        return view('admin.attribute.attributes', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = GroupAttributeMaster::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
              
                ->addColumn('attribute_name', function ($row) {
                    return $row->attribute_name;
                })
                ->addColumn('values', function ($row) {
                    return $row->values;
                })
                ->addColumn('group_name', function ($row) {
                    return $row->group_id ?  $row->group->title : '-';
                })
                ->addColumn('sheet_title', function ($row) {
                    return $row->sheet_title ?  ($row->sheet_title) : '-';
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
                                        <a href="' . route('attribute_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['attribute_name','group_name', 'sheet_title','status','action'])
                ->make(true);
        }
    }
    public function add()
    {
        $data = array('title'=>" Add Attribute",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/attributes/insert'),'frn_id'=>'frm_attribute');
        $data['group'] = GroupMaster::where('is_active','yes')->get();
        return view('admin.attribute.attribute_edit', $data);
    }
    public function insert(Request $request)
    {
        // VALIDATION RULE
        $validation_array = array(
            'attribute_name'=>'required',
            'group_id'=>'required'
        );
        $rules = [
            'attribute_name.required' => 'The attribute name is required',
            'group_id.required' => 'The group name is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        $attributeValues = [
            'attribute_name' =>  $request->attribute_name,
            'values' =>  $request->values,
            'codes' =>  $request->codes,
            'is_visible' =>  $request->is_visible,
            'group_id' => $request->group_id ,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
            'is_active'=>'yes',
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
        $attribute = GroupAttributeMaster::create($attributeValues);

        $newData =[
            'attribute_name' =>  $request->attribute_name,
            'values' =>  $request->values,
            'codes' =>  $request->codes,
            'is_visible' =>  $request->is_visible,
            'group_id' => $request->group_id ,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
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
            'module'=>'Attribute',
            'message'=>'Attribute newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
       
        return redirect()->route('attribute_admin')->withInput()->withSuccess('Attribute has been added successfully.');
    }
    public function edit($id){
        $data = array('title'=>" Edit Attribute", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/attributes/update/'.$id),'frn_id'=>'frm_attribute');
        $data['attribute'] = GroupAttributeMaster::where('id','=',$id)->limit(1)->first();
        $data['group'] = GroupMaster::where('is_active','yes')->get();
        if(!empty($data['attribute'])) {
            return view('admin.attribute.attribute_edit', $data);
        }
        else{
            return redirect('admin/attributes');
        }
    }
    public function update(Request $request,$id){
      
        $oldGroup = GroupAttributeMaster::where('id',$id)->first();
        $oldData =  [
            'group_id' =>  $oldGroup->group_id,
            'attribute_name' =>  $oldGroup->attribute_name,
            'values' =>  $oldGroup->values,
            'codes' =>  $oldGroup->codes,
            'is_visible' =>  $oldGroup->is_visible,
            'sheet_title' =>  $oldGroup->sheet_title,
            'created_by'=> $oldGroup->created_by,
            'created_at'=>$oldGroup->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'group_id' =>  $request->group_id,
            'attribute_name' =>  $request->attribute_name,
            'values' =>  $request->values,
            'codes' =>  $request->codes,
            'is_visible' =>  $request->is_visible,
            'sheet_title' =>  $request->sheet_title ? $request->sheet_title : NULL,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
         
        GroupAttributeMaster::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'group_id' =>  $request->group_id,
            'attribute_name' =>  $request->attribute_name,
            'values' =>  $request->values,
            'codes' =>  $request->codes,
            'is_visible' =>  $request->is_visible,
            'sheet_title' =>  $request->sheet_title ? $request->sheet_title : NULL,
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
        return redirect('admin/attributes')->with('success','Attribute has been updated successfully.');
    }
}
