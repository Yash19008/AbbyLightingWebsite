<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use Validator;
use DB;

class AppUser
{
    public $api_fail;

    public function __construct()
    {
        $this->api_success = config('custom_config.api_success_res');
        $this->api_forcefully_logout = config('custom_config.api_forcefully_logout_res');
        $this->api_error = config('custom_config.api_error_res');   
    }

    protected function c_logout($request){
        Auth::guard('api')->user()->tokens->map(function ($token) use ($request){
            $token->revoke();
            $token->delete();
        });
        DB::table('employee_device_tokens')->where('user_id','=',Auth::guard('api')->user()->id)->delete();
    }

	public function handle($request,Closure $next){

        $user = Auth::guard('api')->user();
        
        if($user->status === 'disable'){
            $this->c_logout($request);
            return response()->json(['message'=>trans('custom.error_account_disable')],$this->api_forcefully_logout);
        }
        else if($user->status === 'delete'){
            $this->c_logout($request);
            return response()->json(['message'=>trans('custom.error_account_delete')],$this->api_forcefully_logout);
        }

        return $next($request);
	}
}
