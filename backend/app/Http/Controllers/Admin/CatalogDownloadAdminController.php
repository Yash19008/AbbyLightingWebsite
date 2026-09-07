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
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('mobile', function ($row) {
                    return $row->mobile;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->rawColumns(['name','email','created_at','action'])
                ->make(true);
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
