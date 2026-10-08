<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Helpers\Common_function;
use Yajra\DataTables\Facades\DataTables;

class InquiryAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Inquiries';
    }

    public function index(Request $request)
    {
        $data = array('title' => "Inquiries", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('inquiries');

        return view('admin.inquiries', $data);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Inquiry::query()->latest();
            return Datatables::eloquent($query)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('type', function ($row) {
                    return ucfirst($row->type);
                })
                ->addColumn('reference', function ($row) {
                    return $row->reference ?? '-';
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('contact', function ($row) {
                    return $row->email . '<br/>' . $row->phone;
                })
                ->addColumn('details', function ($row) {
                    return $row->role . '<br/>' . $row->company . '<br/>' . $row->city;
                })
                ->addColumn('message', function ($row) {
                    return $row->message;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
                })
                ->addColumn('action', function ($row) {
                    $jsonPayload = htmlspecialchars(json_encode([
                        'id' => $row->id,
                        'type' => ucfirst($row->type),
                        'reference' => $row->reference ?: '-',
                        'name' => $row->name,
                        'email' => $row->email,
                        'mobile' => $row->phone ?: 'N/A',
                        'city' => $row->city ?: 'N/A',
                        'company' => $row->company ?: 'N/A',
                        'role' => $row->role ?: 'N/A',
                        'message' => $row->message ?: 'No message provided.',
                        'created_at' => $row->created_at ? $row->created_at->format('F d, Y - h:i A') : 'N/A',
                    ]), ENT_QUOTES, 'UTF-8');

                    return '<div class="text-center list-action actBtn-td" style="white-space: nowrap;">
                                <a href="javascript:;" class="view-lead-btn mx-1 text-primary" data-lead="' . $jsonPayload . '" data-toggle="tooltip" title="View Details"><i class="ft-eye font-medium-3"></i></a>
                                <a href="javascript:;" class="delete-lead-btn mx-1 text-danger" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                            </div>';
                })
                ->rawColumns(['type', 'reference', 'name', 'contact', 'details', 'message', 'action'])
                ->make(true);
        }
    }

    public function destroy($id)
    {
        try {
            $item = Inquiry::findOrFail($id);
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Inquiry deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }
}
