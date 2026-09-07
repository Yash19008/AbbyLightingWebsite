<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Common_function;
use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;

class HomeSliderController extends Controller
{

    protected $main_module;
    protected $currentDateTime;

    public function __construct()
    {
       // $this->middleware('admin');
        $this->main_module = 'Settings';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Home Sliders",'main_module'=>$this->main_module);
        
        $data['search'] = $request->input('search');
        $data['results'] = new HomeSlider;
        
        if($data['search']!=''){
            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->where('name','LIKE','%'.$data['search'].'%');
            });  
        }
        $data['tbl'] = Common_function::encrypt('home_sliders');
        $data['col'] = Common_function::encrypt('show_on_home_page');
        $data['results'] =  $data['results']->orderBy('sort_order','ASC')->get();//config('custom_config.settings.admin_pagination_limit')
       
        /* $data['results']->appends(['search'=>$data['search']]); */
        
        return view('admin.homeslider.homeslider',$data);
    }
    public function add() { 
        $data = array('title' => "Add Banner",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/homeslider/insert'),'frn_id'=>'homeslider_form');
        $data[''] = HomeSlider::all();
        return view('admin.homeslider.homeslider_edit', $data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'path'=>'required|image',
            'for_mobile'=>'required|boolean',
            'sort_order'=>'required|numeric',
            'heading'=>'nullable|string|max:255',
            'description'=>'nullable|string',
            'button_text'=>'nullable|string|max:100',
            'button_link'=>'nullable|string|max:255'
        );

        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);

        $data = [
            'path' => $request->path->store('/uploads/homeslider','public'),
            'for_mobile' => $request->for_mobile,
            'url' => $request->url ? $request->url : NULL,
            'sort_order' => $request->sort_order,
            'heading' => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'created_at' => $this->currentDateTime,
            'updated_at' => $this->currentDateTime
        ];

        HomeSlider::create($data);
        
        return redirect()->route('homeslider_admin')->withInput()->withSuccess('Banner has been added successfully.');
    }
    
    public function edit($id)
    {
        $data = array('title' => "Edit Banner", 'main_module' => $this->main_module, 'method' => 'Edit', 'action'=>url('admin/homeslider/update/' . $id), 'frn_id' => 'homeslider_form_edit');
        $data['slider'] = HomeSlider::where('id', $id)->first();
        return view('admin.homeslider.homeslider_edit', $data);
    }

    
    public function update(Request $request, $id)
    {
        $update_array = array(
            'for_mobile' => $request->for_mobile,
            'url' => $request->url ? $request->url : NULL,
            'sort_order' => $request->sort_order,
            'heading' => $request->heading,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'updated_at' => $this->currentDateTime
        );

        $file = $request->path;
        if ($file) {
            $update_array['path'] = $request->path->store('/uploads/homeslider','public');
        }

        HomeSlider::where('id', '=', $id)
            ->update($update_array);

        return redirect()->route('homeslider_admin')->with('success', 'Banner has been updated successfully.');
    }

}