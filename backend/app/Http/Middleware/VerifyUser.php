<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Session;
use Config;
use Request;
use Lang;
use Redirect;
use Carbon\Carbon;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */ protected $session;
     
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user(); 
            if($user->is_active=='0'){
                Auth::guard('web')->logout();
                return redirect('/auth/login')->with('error','Your account has been disabled by administrator.');
            }
            return $next($request);
        }
        else{
        
            return redirect('/');
           
        }
    }
}