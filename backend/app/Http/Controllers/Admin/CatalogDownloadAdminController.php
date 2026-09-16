<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Common_function;
use App\Models\CatalogDownload;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;

class CatalogDownloadAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Catalog';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Catalog Downloads",'main_module'=>$this->main_module);
        $data['tbl'] = Common_function::encrypt('catalog_downloads');
        return view('admin.catalog_download',$data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = CatalogDownload::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('catalogue_name', function ($row) {
                    $catName = $row->catalogue_name ?: 'General Catalog';
                    return '<span class="font-weight-bold text-dark">' . e($catName) . '</span>';
                })
                ->addColumn('name', function ($row) {
                    return '<span class="text-dark">' . e($row->name) . '</span>';
                })
                ->addColumn('email', function ($row) {
                    return '<a href="mailto:' . e($row->email) . '" style="color:#0284c7; text-decoration:none; font-weight:500;">' . e($row->email) . '</a>';
                })
                ->addColumn('mobile', function ($row) {
                    return e($row->mobile ?? '-');
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('M d, Y h:i A') : '-';
                })
                ->addColumn('action', function ($row) {
                    $jsonPayload = htmlspecialchars(json_encode([
                        'id' => $row->id,
                        'catalogue_name' => $row->catalogue_name ?: 'General Catalog',
                        'name' => $row->name,
                        'email' => $row->email,
                        'mobile' => $row->mobile ?: 'N/A',
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
                ->rawColumns(['catalogue_name', 'name', 'email', 'action'])
                ->make(true);
        }
    }

    public function destroy($id)
    {
        try {
            $item = CatalogDownload::findOrFail($id);
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Download lead deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadCatalog(Request $request){
         // VALIDATION RULE
         $validation_array = array(
            'catalog_file' => 'required',
        );
        $rules = [
            'catalog_file.required' => 'File is required',
        ];
        // Optional: remove old catalog if exists
        Storage::disk('public')->delete('uploads/Abby_Lighting_Product_Catalog.pdf');
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request, $validation_array, $rules);
        $file = $request->catalog_file;
        $file->storeAs('uploads/catalog', 'Abby_Lighting_Product_Catalog.pdf', 'public');
        return redirect()->back();
    }
}
