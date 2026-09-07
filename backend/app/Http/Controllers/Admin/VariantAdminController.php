<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\VariantAttribute;
use App\Models\VariantMaster;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VariantAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Variant Master';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
  
    public function index(Request $request)
    {
        $data = array('title' => "Variant Master", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('variant_masters');
        return view('admin.variant_master.variant_attributes', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = VariantMaster::latest()->get();
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
    public function variant_attribute_index(Request $request)
    {
        $data = array('title' => "Variant Atrribute", 'main_module' => 'Variant Attributes');
        $data['tbl'] = Common_function::encrypt('variant_attributes');
        return view('admin.variant_master.variant_attributes', $data);
    }
    public function variant_attr_list(Request $request)
    {
        if ($request->ajax()) {
            $data = VariantAttribute::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('sheet_title', function ($row) {
                    return $row->sheet_title;
                })
                ->addColumn('is_file', function ($row) {
                    return ucfirst($row->is_file);
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
                                        <a href="' . route('variant_attribute_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['name','sheet_title','is_file','status','action'])
                ->make(true);
        }
    }
    public function variant_attr_add(Request $request){
        $data = array('title'=>" Add Variant Attribute",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/variant-attributes/insert'),'frn_id'=>'frm_variant_attr');
        return view('admin.variant_master.variant_attribute_edit', $data);
    }
    public function variant_attr_insert(Request $request){
          // VALIDATION RULE
        $validation_array = array(
            'name'=>'required',
        );
        $rules = [
            'name.required' => 'The name is required',
            'sheet_title.required' => 'The sheet title is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        $attributeValues = [
            'name' =>  $request->name,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
            'is_file' => ($request->is_file != '') ? $request->is_file : 'no',
            'is_active'=>'yes',
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
        $attribute = VariantAttribute::create($attributeValues);

        $newData =[
            'name' =>  $request->name,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
            'is_file' => ($request->is_file != '') ? $request->is_file : 'no',
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
            'module'=>'Variant Attribute',
            'message'=>'Variant Attribute newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
       
        return redirect()->route('variant_attribute_admin')->withInput()->withSuccess('Variant Attribute has been added successfully.');
    }
    public function variant_attr_edit(Request $request,$id){
        $data = array('title'=>"Edit Variant Attribute",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/variant-attributes/update/'.$id),'frn_id'=>'frm_variant_attr');

        $data['variant_attr'] = VariantAttribute::where('id','=',$id)
        ->limit(1)->first();

        if(!empty($data['variant_attr'])) {
            return view('admin.variant_master.variant_attribute_edit', $data);
        }
        else{
            return redirect()->route('variant_attribute_admin');
        }
    }
    public function variant_attr_update(Request $request,$id){
        $oldVariantAttr = VariantAttribute::where('id',$id)->first();
        $oldData =  [
            'name'=>$oldVariantAttr->name,
            'sheet_title' => ($oldVariantAttr->sheet_title != '') ? $oldVariantAttr->sheet_title : NULL,
            'is_file' => ($oldVariantAttr->is_file ),
            'created_by'=> $oldVariantAttr->created_by,
            'created_at'=>$oldVariantAttr->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'name'=>$request->name,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
            'is_file' => ($request->is_file != '') ? $request->is_file : 'no',
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
      
        VariantAttribute::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'name'=>$request->name,
            'sheet_title' => ($request->sheet_title != '') ? $request->sheet_title : NULL,
            'is_file' => ($request->is_file != '') ? $request->is_file : 'no',
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
            'message' => 'Variant Attribute has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect('admin/variant-attributes')->with('success','Variant Attribute has been updated successfully.');
    }
}
