<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use app\Models\User;
use Session;
use Config;
use Request;

class VerifyAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $message = 'Your session has been expired !';
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();

            // $permission  = config('custom_config.admin_module_permission');
            // $curClass = Request::segment(2);
            // $curMethod = Request::segment(3);

            // if($curClass == 'home'){$curClass = 'notices';}
            // if($curMethod == ''){$curMethod = 'index';}

            //echo $curClass.' + '.$curMethod.' + '.$curUserType; die;


            if ($user->is_active != 'no') {
                return $next($request);
            }
            $message = 'Your account has been disabled by administrator.';
        }
        Auth::guard('admin')->logout();
        return redirect('admin/login')->with('error', $message);
    }
}
