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
        $col = $request->filled('col') ? Common_function::decrypt($request->input('col')) : 'is_active';

        $id = $request->input('id');
        $row = DB::table($tbl)->where('id', $id)->first();
        if ($row) {
            $curr = $row->$col ?? null;
            if ($curr === 'yes') {
                $newVal = 'no';
            } elseif ($curr === 'no') {
                $newVal = 'yes';
            } elseif ($curr === 'active') {
                $newVal = 'inactive';
            } elseif ($curr === 'inactive') {
                $newVal = 'active';
            } elseif ($curr === 'published') {
                $newVal = 'draft';
            } elseif ($curr === 'draft') {
                $newVal = 'published';
            } else {
                $newVal = $curr ? 0 : 1;
            }
            DB::table($tbl)->where('id', $id)->update([
                $col => $newVal,
                'updated_at' => Carbon::now()
            ]);
            return response()->json(['code' => '1', 'status' => true]);
        }
        return response()->json(['code' => '0', 'message' => 'Record not found']);
    }
}
