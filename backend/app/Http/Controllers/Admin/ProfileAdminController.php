<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ProfileAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Users';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index()
    {
        // $data = array('title'=>"Profile",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/profile/update/'),'frn_id'=>'frm_user');
        // return view('admin.user_edit', $data);
        $data = array('title'=>"Change Password",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/change-password/update/'),'frn_id'=>'frm_changepass');
        return view('admin.changepass', $data);
    }
    public function update(Request $request){
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        #Match The Old Password
        if(!Hash::check($request->old_password, Auth::guard('admin')->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }
        #Update the new Password
        User::whereId(Auth::guard('admin')->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("success", "Password changed successfully!");
       
    }
}
