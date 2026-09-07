<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyAdminController extends Controller
{
    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Families';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Families",'main_module'=>$this->main_module);

        return view('admin.families',$data);
    }
    public function add(){ 
        $data = array('title'=>"Add Family",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/families/insert'),'frn_id'=>'frm_family_add');
        return view('admin.family_add', $data);
    }
    public function add_product(){ 
        $data = array('title'=>"Add Product",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/families/insert'),'frn_id'=>'frm_family_add');
        return view('admin.family_product_add', $data);
    }
    public function edit(){ 
        $data = array('title'=>"Family",'main_module'=>$this->main_module,'method'=>'Add','action'=>'','frn_id'=>'frm_family_edit');
        return view('admin.families_edit', $data);
    }
}
