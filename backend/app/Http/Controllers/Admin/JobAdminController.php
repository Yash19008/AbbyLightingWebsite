<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use App\Models\JobOpening;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Job';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Jobs", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('job_openings');

        return view('admin.jobs.jobs', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = JobOpening::latest()->get();
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

                ->addColumn('short_description', function ($row) {
                    return $row->short_description;
                })
                ->addColumn('action', function ($row) {
                    $actions_html = '<div class="text-center list-action actBtn-td">
                                        <a href="' . route('job_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="' . route('job_admin.information', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Information"><i class="ft-eye font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['title', 'short_description', 'action'])
                ->make(true);
        }
    }
    public function add()
    {
        $data = array('title' => "Add Job", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/jobs/insert'), 'frn_id' => 'frm_job');
        return view('admin.jobs.job_edit', $data);
    }
    public function insert(Request $request)
    {
        $jobVal = [
            'title' => $request->title,
            'location' => $request->location,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        JobOpening::create($jobVal);

        $newData = [
            'title' => $request->title,
            'location' => $request->location,
            'short_description' => $request->short_description,
            'description' => $request->description,
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
            'module' => 'Job',
            'message' => ' job newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('job_admin')->withInput()->withSuccess('Job has been added successfully.');
    }

    public function edit($id)
    {
        $data = array('title' => " Edit Job", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/jobs/update/' . $id), 'frn_id' => 'frm_job_edit');
        $data['job'] = JobOpening::where('id', $id)->first();
        return view('admin.jobs.job_edit', $data);
    }

    public function update(Request $request, $id)
    {
        $oldJob = JobOpening::where('id', $id)->first();

        $oldData =  [
            'title' => $oldJob->title,
            'location' => $oldJob->location,
            'short_description' => $oldJob->short_description,
            'description' => $oldJob->description,
            'created_by' => $oldJob->created_by,
            'created_at' => $oldJob->created_at
        ];
        $oldDataArr = json_encode($oldData);

        // UPDATE ARRAY
        $update_array = array(
            'title' => $request->title,
            'location' => $request->location,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );

        JobOpening::where('id', '=', $id)
            ->update($update_array);

        $newData =  [
            'title' => $request->title,
            'location' => $request->location,
            'short_description' => $request->short_description,
            'description' => $request->description,
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
            'message' => 'Job has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('job_admin')->with('success', 'Job has been updated successfully.');
    }
    public function information($id)
    {
        $data = array('title' => "Job Information", 'main_module' => $this->main_module, 'method' => 'Information');
        $data['job'] = JobOpening::where('id', $id)->first();
        if (!empty($data['job'])) {
            return view('admin.jobs.job_info', $data);
        } else {
            return redirect(route('job_admin'));
        }
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
        $file->storeAs('uploads/banners', 'banner_image-career.jpg', 'public');
        return redirect()->back();
    }
}
