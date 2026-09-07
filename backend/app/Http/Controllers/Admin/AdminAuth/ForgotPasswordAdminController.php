<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PasswordReset;
use DB;
use App\Mail\ForgotMail;

use App\Helpers\Common_function;

class ForgotPasswordAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $data = array('title'=>"Forgot Password");
        return view('admin.auth.forgot_password', $data);
    }
    public function sendResetLinkEmail(Request $request)
    {
        // VALIDATION RULE
        $validation_array = array(
            'email' => 'required|email',
        );
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);
        
        // GET RECORDS RECORDS
        $user = User::where('email','=',$request->email)
            ->orderBy('id','DESC')
            ->get();
    
        // CHECK RECORD
        if($user->count()>0){
            $user = $user[0];
           
            if($user->is_active=='0'){
                // DISABLE
                auth()->guard('admin')->logout();
                return redirect()->route('forgotpassword_admin.email')
                    ->withErrors('Your account has been disabled by administrator.')
                    ->withInput();
            }
            else{
               
                $user->token = Str::random(10).  time().uniqid().Str::random(10);
                
                PasswordReset::updateOrInsert(
                    ['user_id' => $user->id],
                    ['user_id' => $user->id, 'token' => $user->token,'created_by' => $user->id ]
                );
                
                $user->link = 'admin/password/reset/'.$user->token;
               
                //PARAMTERS TO SEND IN MAIL DATA DYNAMICALLY
                $email_data = [
                    'USER_NAME' => $user->first_name.($user->last_name != '' ? (' '.$user->last_name) : ''),
                    'LOGO_LINK' =>  url('images/logo2.png'),
                    'SITE_NAME' => 'AIELD',
                    'YEAR'=> date('Y'),
                    'CONTACT_EMAIL'=>'email@email.com',
                    'RESET_LINK'=>url('admin/password/reset/'.$user->token),
                ];
                 
                Mail::to($request->email)->send(new ForgotMail($email_data));
                
                return redirect()->route('forgotpassword_admin.reset')
                ->withSuccess('Password reset instruction has been sent on your email address.');
            }
        }
        else{
            // NO ACCOUNT EXISTS
            return redirect()->route('forgotpassword_admin.reset')
                ->withErrors('Account does not exists with given email address and password.')
                ->withInput();
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
}
