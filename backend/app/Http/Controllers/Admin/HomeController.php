<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->main_module = 'Dashboard';
    }

    public function index(Request $request){
        $data = array('title'=>"Dashboard",'main_module'=>$this->main_module);
        return view('admin.dashboard',$data);
    }
}
