<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Common_function;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AjaxStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index(Request $request)
    {
        $db_pre = DB::getTablePrefix();

        $tbl = $db_pre.''.Common_function::decrypt($request->input('tbl'));

        $col = $request->filled('col') ? $db_pre.''.Common_function::decrypt($request->input('col')) : 'is_active';
        if ($col == 'is_active') {
            DB::statement("UPDATE ".$tbl." SET is_active =  (CASE WHEN is_active = 'yes' THEN 'no' ELSE 'yes' End), updated_at = '".Carbon::now()."' Where id=".$request->input('id'));
            return response()->json(['code' => '1']);exit;
        }
        DB::statement("UPDATE ".$tbl." SET $col = 1 - $col, updated_at = '".Carbon::now()."' Where id=".$request->input('id'));
        return response()->json(['code' => '1']);exit;
    }
}
