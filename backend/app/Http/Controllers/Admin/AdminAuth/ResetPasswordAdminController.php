<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use DB;

class ResetPasswordAdminController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'admin/login';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token)
    {
        if($token!='') {

            $forgot_res = PasswordReset::where('token','=',$token)->first();
            if(isset($forgot_res) && $forgot_res->user->is_active=='1') {
                
                $data = array('action' => url('admin/password/reset/' . $token), 'title'=>"Reset Password");
                $data['token'] = $token;
                return view('admin.auth.reset_password', $data);
            }
            if(isset($forgot_res) && $forgot_res->user->is_active=='0') {
                
                return redirect('/admin')->with('error','Your account has been disabled by administrator.');
            }
            return redirect('/admin')->with('error','Reset password token is invalid');
        }
    }

    public function reset(Request $request,$token){
        if($token!='') {
            $validator_rule = [
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ];
            $this->validate($request, $validator_rule);

            $forgot_res = PasswordReset::where('token','=',$token)->first();
            if(isset($forgot_res) && $forgot_res->user->is_active=='1') {
                if($forgot_res->user->email == $request->email) {

                    User::where('id','=',$forgot_res->user_id)
                        ->update(['password'=>bcrypt($request->password)]);
                    
                    PasswordReset::where('token','=',$token)->delete();

                    return redirect('/admin')->with('success', 'Your password has been reset successfully. You can login with your new password.');
                }
                else{
                    return back()->withInput()->with('error','Email id does not exists.');
                }
            }
            else {
                return redirect('/admin')->with('error','Token mismatched');
            }
        }
        else{
            return redirect('/admin');
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
