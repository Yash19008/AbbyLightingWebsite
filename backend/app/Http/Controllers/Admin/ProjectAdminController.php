<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Common_function;
use App\Models\Project;
use App\Models\ProjectSubTag;
use App\Models\SubTag;
use App\Models\ProjectImage;
use App\Models\AuditLog;
use App\Models\SubTagProjectImage;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportProject;

class ProjectAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Project';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Projects", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('projects');
        return view('admin.project.projects', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Project::latest()->get();
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
                ->addColumn('project_type', function ($row) {
                    return $row->type;
                })
                ->addColumn('description', function ($row) {
                    return ($row->description ? $row->description : '-');
                })
                ->addColumn('slug', function ($row) {
                    return $row->slug;
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
                                        <a href="' . route('project_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="' . route('project_admin.information', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Information"><i class="ft-eye font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['name', 'project_type', 'description', 'slug', 'status', 'action'])
                ->make(true);
        }
    }
    
    public function exports(){
        $projects = Project::all();
        foreach ($projects as $project) {
            $projectSubTags = ProjectSubTag::with('sub_tag')->where('project_id', $project->id)->get();
            $commaSeparatedSubTag = '';
            foreach ($projectSubTags  as $index => $projectSubTag) {
                if ($index != 0) {
                    $commaSeparatedSubTag = $commaSeparatedSubTag . ', ';
                }
                if (isset($projectSubTag->sub_tag) && $projectSubTag->sub_tag != null) {
                    $commaSeparatedSubTag = $commaSeparatedSubTag . $projectSubTag->sub_tag->name;
                }
            }
            $project['sub_tags'] = $commaSeparatedSubTag;
        }
        $now = Carbon::now()->format('d-m-Y_H:i:s');
        $exports = new ExportProject($projects);
        return \Excel::download($exports, 'projects_'.$now.'.xlsx');
    }

    public function add()
    {
        $data = array('title' => "Add New Project", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/project/insert'), 'frn_id' => 'frm_project');
        $data['subtags'] = SubTag::where('is_active', 'yes')->get();
        $data['arr'] = [];
        $data['projectImages'] = [];
        return view('admin.project.project_edit', $data);
    }
    public function insert(Request $request)
    {

        $request->validate([
            'slug' => 'required|unique:projects',
        ]);

        $value = [
            'name' => $request->name,
            'location' => $request->location,
            'type_id' => $request->type_id ?? null, // Nullable now
            'type' => $request->type,
            'description' => $request->description,
            'sequence' => $request->sequence ?? 1,
            'slug' => $request->slug,
            'block_column' => $request->block_column,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];


        $project = Project::create($value);
        if (isset($request->sub_tag_id)) {
            foreach ($request->sub_tag_id as $key => $value) {
                $val = [
                    'project_id' => $project->id,
                    'sub_tag_id' => $value,
                    'is_active' => 'yes',
                    'created_by' => Auth::guard('admin')->user()->id
                ];
                ProjectSubTag::create($val);
            }
        }

        if (isset($request->fileName)) {
            $fileNames = $request->fileName;
            foreach ($fileNames as $fileName) {
                $insertImage = [
                    'project_id' => $project->id,
                    'image' => $fileName,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProjectImage::create($insertImage);
            }
        }

        $newData = [
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'description' => $request->description,
            'sequence' => $request->sequence ?? 1,
            'slug' => $request->slug,
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
            'module' => 'Project',
            'message' => ' Project newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('project_admin')->withInput()->withSuccess('Project has been added successfully.');
    }
    public function edit($id)
    {
        $data = array('title' => "Edit Project", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/project/update/' . $id), 'frn_id' => 'frm_project');
        $data['project'] = Project::where('id', $id)->first();
        $data['subtags'] = SubTag::where('is_active', 'yes')->get();
        $data['project_sub_tag'] = ProjectSubTag::where('project_id', $id)->where('is_active', 'yes')->get();
        $data['arr'] = [];
        foreach ($data['project_sub_tag'] as $key => $value) {
            array_push($data['arr'], $value->sub_tag_id);
        }
        $data['projectImages'] = ProjectImage::where('project_id', $id)->where('is_active', 'yes')->get();
        return view('admin.project.project_edit', $data);
    }
    public function update(Request $request, $id)
    {
        $oldProject = Project::where('id', $id)->first();

        $request->validate([
            'slug' => 'required|unique:projects,slug,' . $id
        ]);

        $oldData =  [
            'name' => $oldProject->name,
            'location' => $oldProject->location,
            'type' => $oldProject->type,
            'description' => $oldProject->description,
            'slug' => $oldProject->slug,
            'sequence' => $oldProject->sequence,
            'block_column' => $oldProject->block_column,
            'is_active' => $oldProject->is_active,
            'created_by' => $oldProject->created_by,
            'created_at' => $oldProject->created_at
        ];
        $oldDataArr = json_encode($oldData);

        // UPDATE ARRAY
        $update_array = array(
            'name' => $request->name,
            'location' => $request->location,
            'type_id' => $request->type_id ?? null, // Nullable
            'type' => $request->type,
            'block_column' => $request->block_column,
            'description' => $request->description,
            'slug' => $request->slug,
            'sequence' => $request->sequence ?? $oldProject->sequence,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );

        ProjectImage::where('project_id', $id)->delete();

        if (isset($request->fileName)) {
            $fileNames = $request->fileName;
            foreach ($fileNames as $fileName) {
                $insertImage = [
                    'project_id' => $id,
                    'image' => $fileName,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProjectImage::create($insertImage);
            }
        }

        Project::where('id', '=', $id)
            ->update($update_array);

        $deleteableSubtagImages = ProjectSubTag::where('project_id', $id)->whereNotIn('sub_tag_id', $request->sub_tag_id)->get();
        SubTagProjectImage::where('project_id', $id)->whereIn('sub_tag_id', $deleteableSubtagImages->pluck('sub_tag_id')->toArray())->delete();
        ProjectSubTag::where('project_id', $id)->forceDelete();

        if (isset($request->sub_tag_id)) {
            foreach ($request->sub_tag_id as $key => $value) {
                $val = [
                    'project_id' => $id,
                    'sub_tag_id' => $value,
                    'is_active' => 'yes',
                    'created_by' => Auth::guard('admin')->user()->id
                ];
                ProjectSubTag::create($val);
            }
        }


        $newData =  [
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'description' => $request->description,
            'slug' => $request->slug,
            'sequence' => $request->sequence ?? $oldProject->sequence,
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
            'message' => 'Project has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('project_admin')->with('success', 'Project has been updated successfully.');
    }
    public function information($id)
    {
        $data = array('title' => "Project Information", 'main_module' => $this->main_module, 'method' => 'Information');
        $data['project'] = Project::where('id', $id)->first();
        $data['project_image'] = ProjectImage::where('is_active', 'yes')->where('project_id', $id)->get();
        if (!empty($data['project'])) {
            return view('admin.project.project_info', $data);
        } else {
            return redirect(route('project_admin'));
        }
    }
    public function project_images($id)
    {
        $data = array('title' => "Project Images");
        $data['project_images'] = ProjectImage::where('project_id', $id)->get();
        return response()->json($data);
    }
}
