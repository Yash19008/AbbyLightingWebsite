<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->main_module = 'Dashboard';
    }

    public function index(Request $request){
        return redirect()->route('inquiries_admin');
    }
}
