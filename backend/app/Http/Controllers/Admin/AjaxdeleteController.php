<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AjaxdeleteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('admin');
    }
    public function index(Request $request)
    {
        $db_pre = DB::getTablePrefix();

        $tbl = Common_function::decrypt($request->input('tbl'));
        
        $id = $request->input('id');
      
        DB::table($tbl)->where('id',$id)->update(['deleted_at' => Carbon::now(), 'deleted_by'=>Auth::guard('admin')->user()->id]);
      
        // DB::statement("UPDATE ".$tbl." SET updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$request->input('id'));
        return response()->json(['code' => '1']);exit;
    }
}
