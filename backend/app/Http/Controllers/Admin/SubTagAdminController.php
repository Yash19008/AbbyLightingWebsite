<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\SubTag;
use App\Models\SubTagRelation;
use App\Models\Tag;
use App\Models\Project;                     
use App\Models\ProjectSubTag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\SubTagProjectImage;
use App\Models\SubTagMapping;
use App\Exports\ExportSubTags;

class SubTagAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Sub Tags';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Tags", 'main_module' => $this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = new SubTag;

        if ($data['search'] != '') {
            $data['results'] = $data['results']->where(function ($query) use ($data) {
                $query->where('name', 'LIKE', '%' . $data['search'] . '%');
            });
        }
        $data['results'] =  $data['results']->orderBy('id', 'DESC')->get(); //config('custom_config.settings.admin_pagination_limit')
        $data['tbl'] = Common_function::encrypt('sub_tags');
        $data['col'] = Common_function::encrypt('show_on_home_page');
        /* $data['results']->appends(['search'=>$data['search']]); */

        return view('admin.sub_tags.sub_tags', $data);
    }
    
    public function exports(){
        $subTags = SubTag::all();
        foreach ($subTags as $subTag) {
            $tags = SubTagMapping::with('tag')->where('sub_tag_id', $subTag->id)->get();
            $commaSeparated = '';
            foreach ($tags  as $index => $tag) {
                if ($index != 0) {
                    $commaSeparated = $commaSeparated . ', ';
                }
                $commaSeparated = $commaSeparated . $tag->tag->name;
            }
            $subTag['tags'] = $commaSeparated;


            $projects = SubTagProjectImage::with('project')->where('sub_tag_id', $subTag->id)->get();
            $commaSeparated = '';
            foreach ($projects  as $index => $project) {
                if ($index != 0) {
                    $commaSeparated = $commaSeparated . ', ';
                }
                $commaSeparated = $commaSeparated . $project->project->name;
            }
            $subTag['projects'] = $commaSeparated;
        }
        $now = Carbon::now()->format('d-m-Y_H:i:s');
        $exports = new ExportSubTags($subTags);
        return \Excel::download($exports, 'sub_tags_'.$now.'.xlsx');
    }

    public function add()
    {
        $data = array('title' => "Add Sub Tag", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/sub_tags/insert'), 'frn_id' => 'frm_sub_tag');
        $data['tags'] = Tag::where('is_active', 'yes')->get();
        $data['projectImages'] = SubTagProjectImage::where('sub_tag_id', '-1')->get();
        $data['project'] = Project::where('is_active', 'yes')->get();
        $data['subTagsMapping'] = [];
        $data['subtags'] = SubTag::where('is_active', 'yes')->get();
        $data['linkedSubTags'] = [];
        $data['tag'] = null;
        return view('admin.sub_tags.sub_tags_edit', $data);
    }
    public function insert(Request $request)
    {
        
        // VALIDATION RULE
        $validation_array = array(
            'display_name' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'tags' => 'required',
            'file' => 'required',
            'product_catalog' => 'nullable|mimes:pdf|max:10240', // ✅ 10MB max
        );
        $rules = [
            'display_name.required' => 'The Display name is required',
            'name.required' => 'The name is required',
            'slug.required' => 'Slug is required',
            'tags.required' => 'Tag is required',
            'file.required' => 'Image is required',
            'product_catalog.mimes' => 'Only PDF files are allowed',
            'product_catalog.max' => 'PDF must be less than 10MB',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request, $validation_array, $rules);
        $values = [
            'display_name' =>  $request->display_name,
            'name' => $request->name,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'is_active' => 'yes',
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $values['youtube_url'] = $request->youtube_url; // ✅ Add this line
        $values['youtube_url_link_2'] = $request->youtube_url_link_2;
        $values['youtube_url_link_3'] = $request->youtube_url_link_3;
        // Handle PDF upload
        if ($request->hasFile('product_catalog')) {
            $file = $request->file('product_catalog');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('storage/uploads/product_catalogs'), $fileName);
            $values['product_catalog'] = $fileName;

        
        }               
    
        $file = $request->file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            // $fileModel->save();

            $values['image'] = $fileNamePhoto;
        }

        $file = $request->hover_file;

        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            // $fileModel->save();

            $values['hover_image'] = $fileNamePhoto;
        }

        $file = $request->banner;
        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            // $fileModel->save();

            $values['banner_image'] = $fileNamePhoto;
        }

        $bannerNoArr = [2, 3, 4, 5];

        foreach ($bannerNoArr as $bannerNo) {
            $file = $request['banner_' . $bannerNo];
            if ($file) {
                $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

                $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
                // $fileModel->save();

                $values['banner_image_' . $bannerNo] = $fileNamePhoto;
            }
        }

        $subTag = SubTag::create($values);

        $request->validate([
            'linked_sub_tags' => 'array',
            'linked_sub_tags.*' => 'integer|exists:sub_tags,id',
        ]);

        if ($request->linked_sub_tags) {
            foreach ($request->linked_sub_tags as $linkedSubTagId) {
                SubTagRelation::firstOrCreate([
                    'sub_tag_id' => $subTag->id,
                    'linked_sub_tag_id' => $linkedSubTagId,
                ]);
            }
        }
        
        if ($request->tags) {
            $tags = $request->tags;
            foreach ($tags as $tag) {
                $subTagMapping = [
                    'sub_tag_id' => $subTag->id,
                    'tag_id' => $tag
                ];
                SubTagMapping::create($subTagMapping);
            }
        }

        if ($request->has('project_images') && $request->project_images !== null && $request->project_images !== 'null') {
            $projectImages = json_decode($request->project_images, true);
            foreach ($projectImages as $projectImage) {
                $insertImage = [
                    'sub_tag_id' => $subTag->id,
                    'project_id' => $projectImage['project_id'],
                    'project_image_id' => $projectImage['project_image_id'],
                    'project_name' => $projectImage['project_name'],
                    'project_slug' => $projectImage['project_slug'],
                    'project_image_name' => $projectImage['project_image_name'],
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                SubTagProjectImage::create($insertImage);
            }
        }

        $newData = [
            'name' => $request->name,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'is_active' => 'yes',
            'image' => $file ? $fileNamePhoto : NULL,
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
            'module' => 'Sub Tag',
            'message' => 'Sub Tag newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('sub_tag_admin')->withInput()->withSuccess('Sub Tag has beens   added successfully.');
    }
    public function edit($id)
    {
        $data = array('title' => "Edit Sub Tag", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/sub_tags/update/' . $id), 'frn_id' => 'frm_sub_tag_edit');
        $data['tag'] = SubTag::where('id', $id)->first();
        $data['tags'] = Tag::where('is_active', 'yes')->get();
        $data['subtags'] = SubTag::where('is_active', 'yes')
            ->where('id', '!=', $id) // prevent self-linking
            ->get();

        $data['linkedSubTags'] = SubTagRelation::where('sub_tag_id', $id)
            ->pluck('linked_sub_tag_id')
            ->toArray();
        $data['projectImages'] = SubTagProjectImage::where('sub_tag_id', $id)->get();
        $project_ids = ProjectSubTag::where('sub_tag_id', $id)->groupBy('project_id')->pluck('project_id','project_id');
        $data['project'] = Project::where('is_active', 'yes')->whereIn('id', $project_ids)->get();
        $data['subTagsMapping'] = SubTagMapping::where('sub_tag_id', $id)->pluck('tag_id')->toArray();
        return view('admin.sub_tags.sub_tags_edit', $data);
    }
    public function update(Request $request, $id)
    {
        $oldTag = SubTag::where('id', $id)->first();
        $oldData =  [
            'display_name' => ($oldTag->display_name != '') ? $oldTag->display_name : NULL,
            'name' => ($oldTag->name != '') ? $oldTag->name : NULL,
            'image' => ($oldTag->image != '') ? $oldTag->image : NULL,
            'hover_image' => ($oldTag->hover_image != '') ? $oldTag->hover_image : NULL,
            'banner_image' => ($oldTag->banner_image != '') ? $oldTag->banner_image : NULL,
            'slug' => ($oldTag->slug != '') ? $oldTag->slug : NULL,
            'product_catalog' => $oldTag->product_catalog ?: null,
            'created_by' => $oldTag->created_by,
            'created_at' => $oldTag->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'display_name' => ($request->display_name != '') ? $request->display_name : NULL,
            'name' => ($request->name != '') ? $request->name : NULL,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );
        $update_array['youtube_url'] = $request->youtube_url; // ✅ Add this line
        $update_array['youtube_url_link_2'] = $request->youtube_url_link_2;
        $update_array['youtube_url_link_3'] = $request->youtube_url_link_3;
        // Handle Product Catalog PDF upload
        if ($request->hasFile('product_catalog')) {
            $file = $request->file('product_catalog');
            $fileName = $file->getClientOriginalName();

            $destinationPath = public_path('storage/uploads/product_catalogs');

            // Move new file
            $file->move($destinationPath, $fileName);

            // Delete old file if exists
            if (!empty($oldTag->product_catalog) && file_exists($destinationPath . '/' . $oldTag->product_catalog)) {
                @unlink($destinationPath . '/' . $oldTag->product_catalog);
            }

            $update_array['product_catalog'] = $fileName;
        }


        $request->validate([
            'linked_sub_tags' => 'array',
            'linked_sub_tags.*' => 'integer|exists:sub_tags,id',
        ]);


        // Remove old relations
        SubTagRelation::where('sub_tag_id', $id)->delete();

        // Insert new ones
        if ($request->linked_sub_tags) {
            foreach ($request->linked_sub_tags as $linkedSubTagId) {
                SubTagRelation::firstOrCreate([
                    'sub_tag_id' => $id,
                    'linked_sub_tag_id' => $linkedSubTagId,
                ]);
            }
        }


        $file = $request->file;
        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            $update_array['image'] = $fileNamePhoto;
        }

        $file = $request->hover_file;
        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            $update_array['hover_image'] = $fileNamePhoto;
        }

        $file = $request->banner;
        if ($file) {
            $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

            $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
            $update_array['banner_image'] = $fileNamePhoto;
        }

        $bannerNoArr = [2, 3, 4, 5];
        foreach ($bannerNoArr as $bannerNo) {
            $file = $request['banner_' . $bannerNo];

            $remove_input = $request['banner_' . $bannerNo . "_remove"];
            if ($remove_input) {
                $update_array['banner_image_' . $bannerNo] = NULL;
            }

            if ($file) {
                $fileNamePhoto = time() . '_' . trim($file->getClientOriginalName());

                $filePath = $file->storeAs('uploads/sub_tags', $fileNamePhoto, 'public');
                $update_array['banner_image_' . $bannerNo] = $fileNamePhoto;
            }
        }

        if($request->remove_image == 1)
            $update_array['image'] = NULL;
        if($request->remove_hover_image == 1)
            $update_array['hover_image'] = NULL;
        if($request->remove_banner_image == 1)
            $update_array['banner_image'] = NULL;
        foreach($bannerNoArr as $num){
            if($request->{'remove_banner_image_'.$num} == 1)
                $update_array['banner_image_' . $num] = NULL;
        }

        SubTag::where('id', '=', $id)
            ->update($update_array);

        
        SubTagMapping::where('sub_tag_id', $id)->delete();
        if ($request->tags) {
            $tags = $request->tags;
            foreach ($tags as $tag) {
                $subTagMapping = [
                    'sub_tag_id' => $id,
                    'tag_id' => $tag
                ];
                SubTagMapping::create($subTagMapping);
            }
        }

        $existingProjectImageIds = [];
        if ($request->has('project_images') && $request->project_images !== null && $request->project_images !== 'null') {
            $projectImages = json_decode($request->project_images, true);

            foreach ($projectImages as $projectImage) {
                $newId = SubTagProjectImage::updateOrCreate(
                [
                    'id' => $projectImage['id'] ?? NULL,
                ],
                [
                    'sub_tag_id' => $id,
                    'project_id' => $projectImage['project_id'],
                    'project_image_id' => $projectImage['project_image_id'],
                    'project_name' => $projectImage['project_name'],
                    'project_slug' => $projectImage['project_slug'],
                    'project_image_name' => $projectImage['project_image_name'],
                    'updated_by' => Auth::guard('admin')->user()->id,
                    'updated_at' => $this->currentDateTime,
                ]
              )->id;
              $existingProjectImageIds[] = $newId;
            }
        }

        SubTagProjectImage::where('sub_tag_id', $id)->whereNotIn('id', $existingProjectImageIds)->delete();

        $newData =  [
            'display_name' => ($request->display_name != '') ? $request->display_name : NULL,
            'name' => ($request->name != '') ? $request->name : NULL,
            'image' => ($request->image != '') ? $request->image : NULL,
            'slug' => ($request->slug != '') ? $request->slug : NULL,
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
            'message' => 'Sub Tag has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('sub_tag_admin')->with('success', 'Sub Tag has been updated successfully.');
    }

    public function uploadBanner(Request $request)
    {
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
        $file->storeAs('uploads/banners', 'banner_image-sub-tags.jpg', 'public');
        return redirect()->back();
    }

    public function destroySubtagImage(Request $request, SubTag $sub_tag)
    {
        dd($sub_tag);
    }
}
