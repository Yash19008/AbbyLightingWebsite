<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Models\User;

class LoginAdminController extends Controller
{
   // use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'admin/contact-forms';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $maxAttempts;

    protected $decayMinutes;


    public function __construct()
    {        
        $this->middleware('admin.guest', ['except' => 'logout']);     
        $this->maxAttempts  = config('custom_config.maxAttempts');
        $this->decayMinutes = config('custom_config.decayMinutes');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $data = array('title'=>"Login");
        return view('admin.auth.login', $data);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function login(Request $request){
       //print_r($request->all());exit;
        if (Auth::guard('admin')->check()) {
            return redirect('admin/contact-forms');
        }    
        // VALIDATION RULE
        $validation_array = array(
            'username' => 'required|string',
            'password' => 'required|string',
        );
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);
        
        // GET RECORDS RECORDS
        $user = User::where('user_name','=',$request->username)
            ->orderBy('id','DESC')
            ->get();

        $login_data = array(
            'user_name' => $request->username,
            'password' => $request->password,
        );
    
    
        if($user->count()>0){
            $login_data['id'] = $user[0]->id;
            if(Auth::guard('admin')->attempt($login_data)){          
                if(Auth::guard('admin')->user()->is_active == '0') { 
                    // DISABLE
                    Auth::guard('admin')->logout();
                    return redirect('admin/login')
                        ->withErrors('Your account has been disabled by administrator.')
                        ->withInput();
                }
                else{
                    // SUCCESS LOGIN
                // $token = explode('|', Auth::guard('admin')->user()->createToken('auth_token')->plainTextToken, 2); 
                    //User::where('id',Auth::guard('admin')->user()->id)->update(['session'=>$token]);
                    if (Auth::guard('admin')->user()->id == 3) {
                        // Specgen user → only Products menu
                        return redirect('admin/product');
                    } else {
                        // All other admins → default dashboard
                    return redirect('admin/contact-forms');
                }
            }
            }else{
                // INVALID PASSWORD
                return redirect('admin/login')
                ->withErrors('Enter valid password.')->withInput();
            }
        }
        else{
            // NO ACCOUNT EXISTS
            return redirect('admin/login')
                ->withErrors('Account does not exists with given email address and password.')->withInput();
        }
    }

    public function logoutToPath() {
        return '/admin/login';
    }

    public function logout(Request $request) {        
        Session::flush();
        
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }
}
