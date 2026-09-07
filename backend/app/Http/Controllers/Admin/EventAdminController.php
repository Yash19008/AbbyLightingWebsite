<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\EventImage;
use DataTables;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventAdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Events';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Events", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('events');

        return view('admin.event.events', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
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
                                        <a href="' . route('event_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit"><i class="ft-edit-2 font-medium-3 mr-2"></i></a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '"  title="Delete"><i class="icon ft-trash-2 font-medium-3 mr-2"></i></a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['name', 'status', 'slug', 'action'])
                ->make(true);
        }
    }
    public function add()
    {
        $data = array('title' => "Add Event", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/events/insert'), 'frn_id' => 'frm_event');
        $data['eventImages'] = [];
        return view('admin.event.event_edit', $data);
    }
    public function insert(Request $request)
    {

        $request->validate([
            'slug' => 'required|unique:events',
        ]);

        $Val = [
            'name' => $request->name,
            'slug' => $request->slug,
            'source' => $request->source,
            'source_link' => $request->source_link,
            'location' => $request->location,
            'description' => $request->description,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $event = Event::create($Val);

        if (isset($request->fileName)) {
            $fileNames = $request->fileName;
            foreach ($fileNames as $fileName) {
                $insertImage = [
                    'event_id' => $event->id,
                    'image' => $fileName,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                EventImage::create($insertImage);
            }
        }

        $newData = [
            'name' => $request->name,
            'location' => $request->location,
            'slug'=> $request->slug,
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
            'module' => 'Event',
            'message' => ' Event newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('event_admin')->withInput()->withSuccess('Event has been added successfully.');
    }
    public function edit($id)
    {
        $data = array('title' => " Edit Event", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/events/update/' . $id), 'frn_id' => 'frm_event_edit');
        $data['event'] = Event::where('id', $id)->first();
        $data['eventImages'] = EventImage::where('event_id', $id)->where('is_active', 'yes')->get();
        return view('admin.event.event_edit', $data);
    }
    public function update(Request $request, $id)
    {
        $oldEvent = Event::where('id', $id)->first();

        $request->validate([
            'slug' => 'required|unique:events,slug,'.$id
        ]);

        $oldData =  [
            'name' => $oldEvent->name,
            'slug'=> $oldEvent->slug,
            'location' => $oldEvent->location,
            'description' => $oldEvent->description,
            'is_active' => $oldEvent->is_active,
            'created_by' => $oldEvent->created_by,
            'created_at' => $oldEvent->created_at
        ];
        $oldDataArr = json_encode($oldData);

        // UPDATE ARRAY
        $update_array = array(
            'name' => $request->name,
            'slug'=> $request->slug,
            'source' => $request->source,
            'source_link' => $request->source_link,
            'location' => $request->location,
            'description' => $request->description,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );
        Event::where('id', '=', $id)
            ->update($update_array);


        EventImage::where('event_id', $id)->delete();
        if (isset($request->fileName)) {
            $fileNames = $request->fileName;
            foreach ($fileNames as $fileName) {
                $insertImage = [
                    'event_id' => $id,
                    'image' => $fileName,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                EventImage::create($insertImage);
            }
        }

        if ($request->has('eventImages') && $request->eventImages !== null) {
            $eventImages = $request->eventImages;
            if (count($eventImages) > 0) {
                foreach ($eventImages as $eventImage) {
                    $fileNamePhoto = time() . '_' . trim($eventImage['item']->getClientOriginalName());
                    $eventImage['item']->storeAs('uploads/events', $fileNamePhoto, 'public');
                    $insertImage = [
                        'event_id' => $id,
                        'image' => $fileNamePhoto,
                        'created_by' => Auth::guard('admin')->user()->id,
                        'created_at' => $this->currentDateTime,
                    ];
                    EventImage::create($insertImage);
                }
            }
        }

        $newData =  [
            'name' => $request->name,
            'slug'=> $request->slug,
            'location' => $request->location,
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
            'message' => 'Event has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('event_admin')->with('success', 'Event has been updated successfully.');
    }
}
