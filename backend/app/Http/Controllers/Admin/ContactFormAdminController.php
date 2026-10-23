<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Carbon\Carbon;
use App\Helpers\Common_function;
use DataTables;

class ContactFormAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Contact Form';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        return redirect()->route('inquiries_admin');
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
        $file->storeAs('uploads/banners', 'banner_image-contacts.jpg', 'public');
        return redirect()->back();
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Inquiry::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('full_name', function ($row) {
                    return $row->full_name;
                })
                ->addColumn('profession', function ($row) {
                    return $row->profession . '</br>' . $row->company . '</br>' . $row->city;
                })
                ->addColumn('email', function ($row) {
                    return $row->email . '</br>' . $row->website . '</br>' . $row->phone;
                })
                ->addColumn('i_message', function ($row) {
                    return $row->i_message;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->rawColumns(['full_name', 'profession', 'email', 'i_message'])
                ->make(true);
        }
    }

}
