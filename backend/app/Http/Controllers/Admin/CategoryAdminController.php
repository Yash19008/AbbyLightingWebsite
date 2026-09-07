<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\CategoryImages;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CategoryAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Category';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Categories", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('categories');

        return view('admin.category.categories',$data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
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
                                        <a href="' . route('category_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="' . route('category_admin.information', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Information"><i class="ft-eye font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['title','status','action'])
                ->make(true);
        }
    }
    public function add(){
        $data = array('title'=>"Add Category",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/categories/insert'),'frn_id'=>'frm_category');
        return view('admin.category.category_edit', $data);
    }
    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required',
            'slug'=>'required',
        );
        $rules = [
            'title.required' => 'Title is required',
            'slug.required'=>'Slug is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        $categoryVal = [
            'title'=>$request->title,
            'uri'=>$request->uri,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'sheet_title'=>$request->sheet_title,
            'in_menu'=>$request->in_menu,
            'created_at'=>$this->currentDateTime,
            'created_by'=>Auth::guard('admin')->user()->id,
        ];
       
        $featured_image = $request->featured_image;     
        
        if($featured_image) {
            $fileNamePhotoImage =time().'_'.trim($featured_image->getClientOriginalName());
            
            $filePath = $featured_image->storeAs('uploads/categories', $fileNamePhotoImage, 'public');
            // $fileModel->save();

            $categoryVal['featured_image'] = $fileNamePhotoImage;
        }
        $display_icon = $request->display_icon;     
        
        if($display_icon) {
            $fileNamePhoto =time().'_'.trim($display_icon->getClientOriginalName());
            
            $filePath = $display_icon->storeAs('uploads/categories', $fileNamePhoto, 'public');
            // $fileModel->save();

            $categoryVal['display_icon'] = $fileNamePhoto;
        }
      
        $category = Category::create($categoryVal);
        
        if($request->hasFile('gallary')){
            foreach ($request->file('gallary') as $key => $value) {
                // \Storage::delete('uploads/products'.$productimage->images);
                $fileNamePhotoGall =time().'_'.trim($value->getClientOriginalName());
                
                $filePath = $value->storeAs('uploads/categories', $fileNamePhotoGall, 'public');
                // $fileModel->save();
                $insertImage = [
                    'category_id'=>$category->id,
                    'image' => $fileNamePhotoGall,
                    'created_by'=>Auth::guard('admin')->user()->id,
                    'created_at'=>$this->currentDateTime,
                ];
                 
                CategoryImages::create($insertImage);
            }
        }
       
        $newData =[
            'title'=>$request->title,
            'uri'=>$request->uri,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'sheet_title'=>$request->sheet_title,
            'in_menu'=>$request->in_menu,
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
            'module'=>'Category',
            'message'=>' category newly added.',
            'old_data'=>'',
            'new_data'=> $newDataArr,
            'other_info'=>'',
        ];
        
        AuditLog::create($auditInfo);
       
        return redirect()->route('category_admin')->withInput()->withSuccess('Category has been added successfully.');
    }
    public function edit($id){
        $data = array('title'=>" Edit Category", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/categories/update/'.$id),'frn_id'=>'frm_category_edit');
        $data['category'] = Category::where('id',$id)->first();
        return view('admin.category.category_edit', $data);
    }
    public function update(Request $request,$id){
        $oldCategory = Category::where('id',$id)->first();
       
        $oldData =  [
            'title'=>$oldCategory->title,
            'slug' => ($oldCategory->slug != '') ? $oldCategory->slug : NULL,
            'uri'=>$oldCategory->uri,
            'sheet_title'=>$oldCategory->sheet_title,
            'in_menu'=>$oldCategory->in_menu,
            'is_active'=>$oldCategory->is_active,
            'created_by'=> $oldCategory->created_by,
            'created_at'=>$oldCategory->created_at
        ];
        $oldDataArr = json_encode($oldData);
        
        // UPDATE ARRAY
        $update_array = array(
            'title'=>$request->title,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'uri'=>$request->uri,
            'sheet_title'=>$request->sheet_title,
            'in_menu'=>$request->in_menu,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,            
        );
       
        $featured_image = $request->featured_image;     
        
        if($featured_image) {
            //\Storage::delete('uploads/categories'.$oldCategory->featured_image);
            $fileNamePhotoImage =time().'_'.trim($featured_image->getClientOriginalName());
            
            $filePath = $featured_image->storeAs('uploads/categories', $fileNamePhotoImage, 'public');
            // $fileModel->save();

            $update_array['featured_image'] = $fileNamePhotoImage;
        }
        $display_icon = $request->display_icon;     
        
        if($display_icon) {
            //\Storage::delete('uploads/categories'.$oldCategory->display_icon);
            $fileNamePhoto =time().'_'.trim($display_icon->getClientOriginalName());
            
            $filePath = $display_icon->storeAs('uploads/categories', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['display_icon'] = $fileNamePhoto;
        }
      
        if($request->hasFile('gallary')){
            CategoryImages::where('category_id',$id)->delete();
            foreach ($request->file('gallary') as $key => $value) {
                //\Storage::delete('uploads/categories'.$oldCategory->image);
                $fileNamePhotoGall =time().'_'.trim($value->getClientOriginalName());
                
                $filePath = $value->storeAs('uploads/categories', $fileNamePhotoGall, 'public');
                // $fileModel->save();
                $insertImage = [
                    'category_id'=>$id,
                    'image' => $fileNamePhotoGall,
                    'created_by'=>Auth::guard('admin')->user()->id,
                    'created_at'=>$this->currentDateTime,
                ];
                 
                CategoryImages::create($insertImage);
            }
        }

        Category::where('id','=',$id)
                ->update($update_array);

        $newData =  [
            'title'=>$request->title,
            'uri'=>$request->uri,
            'sheet_title'=>$request->sheet_title,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'in_menu'=>$request->in_menu,
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
            'message' => 'Category has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('category_admin')->with('success','Category has been updated successfully.');
    }
    public function information($id)
    {
        $data = array('title' => "Category Information", 'main_module' => $this->main_module, 'method' => 'Information');
        $data['category'] = Category::where('id', $id)->first();
        $data['category_image'] = CategoryImages::where('is_active','yes')->where('category_id',$id)->get();
        if (!empty($data['category'])) {
            return view('admin.category.category_info', $data);
        } else {
            return redirect(route('category_admin'));
        }
    }
}
