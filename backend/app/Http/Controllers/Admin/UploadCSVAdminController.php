<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadCSVAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Upload CSV';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Upload CSV",'main_module'=>$this->main_module);

        return view('admin.upload_csv',$data);
    }
}
