<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Tag;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TagAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Tags';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Tags",'main_module'=>$this->main_module);
        
        $data['search'] = $request->input('search');
        $data['results'] = new Tag;
        
        if($data['search']!=''){
            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->where('name','LIKE','%'.$data['search'].'%');
            });  
        }
        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(10);//config('custom_config.settings.admin_pagination_limit')
        $data['tbl'] = Common_function::encrypt('tags');
        $data['col'] = Common_function::encrypt('show_categories');
      
        $data['results']->appends(['search'=>$data['search']]);
        return view('admin.tags',$data);
    }
    public function add(){ 
        $data = array('title'=>"Add Tag",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/tags/insert'),'frn_id'=>'frm_tag');
        return view('admin.tag_edit', $data);
    }
    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'display_name'=>'required',
            'name'=>'required',
            'slug'=>'required',
            'file'=>'required',
        );
        $rules = [
            'display_name.required' => 'The Display name is required',
            'name.required' => 'The name is required',
            'slug.required'=>'Slug is required',
            'file.required'=>'Image is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        $values = [
            'display_name' =>  $request->display_name,
            'name' => $request->name ,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'is_active'=>'yes',
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
        $file = $request->file;     
        
        if($file) {
            $fileNamePhoto =time().'_'.trim($file->getClientOriginalName());
            
            $filePath = $file->storeAs('uploads/tags', $fileNamePhoto, 'public');
            // $fileModel->save();

            $values['image'] = $fileNamePhoto;
        }
      
        $values = Tag::create($values);

        $newData =[
            'name' => $request->name ,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'is_active'=>'yes',
            'image'=> $file ? $fileNamePhoto : NULL,
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
            'module'=>'Tag',
            'message'=>'Tag newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
        
        return redirect()->route('tag_admin')->withInput()->withSuccess('Tag has been added successfully.');
    }
    public function edit($id){ 
        $data = array('title'=>"Edit Tag",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/tags/update/'.$id),'frn_id'=>'frm_tag_edit');
        $data['tag'] = Tag::where('id',$id)->first();
        return view('admin.tag_edit', $data);
    }
    public function update(Request $request,$id){
        $oldTag = Tag::where('id',$id)->first();
        $oldData =  [
            'display_name' => ($oldTag->display_name != '') ? $oldTag->display_name : NULL,
            'name' => ($oldTag->name != '') ? $oldTag->name : NULL,
            'image' => ($oldTag->image != '') ? $oldTag->image : NULL,
            'slug' => ($oldTag->slug != '') ? $oldTag->slug : NULL,
            'created_by'=> $oldTag->created_by,
            'created_at'=>$oldTag->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'display_name' => ($request->display_name != '') ? $request->display_name : NULL,
            'name' => ($request->name != '') ? $request->name : NULL,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
        $file = $request->file;     
       
        if($file) {
            $fileNamePhoto =time().'_'.trim($file->getClientOriginalName());
            
            $filePath = $file->storeAs('uploads/tags', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['image'] = $fileNamePhoto;
        }
        Tag::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'display_name' => ($request->display_name != '') ? $request->display_name : NULL,
            'name' => ($request->name != '') ? $request->name : NULL,
            'image' => ($request->image != '') ? $request->image : NULL,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
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
            'message' => 'Tag has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('tag_admin')->with('success','Tag has been updated successfully.');
    }
}
