<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Inquiry;
use App\Models\Subscription;
use Carbon\Carbon;
use App\Helpers\Common_function;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;

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
        $data = array('title' => "Enquiries", 'main_module' => $this->main_module);
        $data['search'] = $request->input('search');
        $data['results'] = new Inquiry;

        if ($data['search'] != '') {
            \Log::info($data['search']);
            // $data['results'] = $data['results']->where(function ($query) use ($data) {
            //     $query->where('full_name', 'LIKE', '%' . $data['search'] . '%');
            // });
        }
        $data['results'] =  $data['results']->orderBy('id', 'DESC')->paginate(10); //config('custom_config.settings.admin_pagination_limit')
        // $data['tbl'] =  Crypt::encryptString('inquiries');
        $data['tbl'] = Common_function::encrypt('inquiries');

        return view('admin.contact_form', $data); //inquiry
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

    public function subscriptions(Request $request)
    {
        $data = array('title' => "Subscriptions", 'main_module' => 'Subscriptions');
        $data['search'] = $request->input('search');
        $data['results'] = new Subscription;

        if ($data['search'] != '') {
            \Log::info($data['search']);
            // $data['results'] = $data['results']->where(function ($query) use ($data) {
            //     $query->where('full_name', 'LIKE', '%' . $data['search'] . '%');
            // });
        }
        $data['results'] =  $data['results']->orderBy('id', 'DESC')->paginate(10); //config('custom_config.settings.admin_pagination_limit')
        // $data['tbl'] =  Crypt::encryptString('inquiries');
        $data['tbl'] = Common_function::encrypt('inquiries');

        return view('admin.subscriptions', $data); //inquiry
    }


    public function subscriptions_list(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscription::latest()->get();
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
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('created_at', function ($row) {
                    if ($row->created_at) {
                        return date("d M Y H:i", strtotime($row->created_at));
                    }
                    return '';
                })
                ->addColumn('created_at_row', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('verified_at', function ($row) {
                    if ($row->verified_at) {
                        return date("d M Y H:i", strtotime($row->verified_at));
                    }
                    return '';
                })
                ->addColumn('verified_at_row', function ($row) {
                    return $row->verified_at;
                })
                ->rawColumns(['name', 'email', 'verified_at'])
                ->make(true);
        }
    }
}
