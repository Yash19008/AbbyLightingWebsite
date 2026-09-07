<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\User;
use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;

class StudentAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Student';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Student",'main_module'=>$this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = new User;
        
        if($data['search']!=''){
            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->orWhere('first_name','LIKE','%'.$data['search'].'%')
                    ->orWhere('last_name','LIKE','%'.$data['search'].'%');
            });  
            // $data['results'] = $data['results']->where('first_name','LIKE','%'.$data['search'].'%')
            //                 ->orWhere('last_name','LIKE','%'.$data['search'].'%')
            //                 ->orWhereHas('student_qualification', function($query) use($data) {
            //      return $query->where('title', 'LIKE','%'.$data['search'].'%');
            //  });
        }
        
        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(10);//config('custom_config.settings.admin_pagination_limit')
        $data['tbl'] = Common_function::encrypt('users');

        $data['results']->appends(['search'=>$data['search']]);
        return view('admin.students',$data);
    }
    public function add(){ 
        $data = array('title'=>"Add Student",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('admin/students/insert'),'frn_id'=>'frm_student_add');
        $data['city'] = City::get();
        $data['state'] = State::get();
        return view('admin.student_edit', $data);
    }
    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'first_name' => 'required|max:255',
            'last_name'=>'required|max:255',
            'phone_no'=>'required|max:10',
            'email' => 'required|email|max:100',
            'password' => 'required|min:2|max:10',
            'address' => 'required|max:1000',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'pincode'=>'required',
            'photo' => 'nullable',
        );
     
        $rules = [
            'first_name.required' => 'The first name is required.',
            'first_name.max' => 'Maximum 255 characters allowed for first name.',
            'last_name.required' => 'The last name is required.',
            'last_name.max' => 'Maximum 255 characters allowed for last name.',
            'email.required' => 'The email is required.',
            'email.max' => 'Maximum 255 characters allowed for email.',
            'password.required' => 'The password is required.',
            'address.required' => 'The address is required.',
            'address.max' => 'Maximum 1000 characters allowed for address.',
            'city_id.required' => 'The city is required.',
            'country_id.required' => 'The country is required.',
            'state_id.required' => 'The state is required.',
        ];
       
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        $user = User::get();

        $emails=array();
        foreach ($user as $key => $value) {
            array_push($emails,$value->email);
        }
        $user_email = $request->email;
        
        if($user_email == $request->email){
            if(in_array($request->email, $emails)){
                return redirect('admin/students/add')->with('error','The email is already exists.');
            }
        } 
    
        // INSERT ARRAY
        $insert_array = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_no'=>$request->phone_no,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'pincode'=>$request->pincode,
            'is_active' => '1',
            'created_at'=>Carbon::now(),
        );
      
        $photo = $request->file;     
                  
        if($photo) {
            $fileNamePhoto = time().'_'.$photo->getClientOriginalName();
            
            $filePath = $photo->storeAs('uploads/user_photo', $fileNamePhoto, 'public');

            $insert_array['photo'] = $fileNamePhoto;
        }
       // echo '<pre>';print_r($insert_array);echo '</pre>';exit();
        
        User::insert($insert_array);      

        return redirect('/admin/students')->withInput()->withSuccess('Student has been added successfully.');
    }
    public function edit($id){
        $data = array('title'=>" Edit Student", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('admin/students/update/'.$id),'frn_id'=>'frm_student_edit');

        $data['student'] = User::where('id','=',$id)->limit(1)->first();
        $data['city'] = City::get();
        $data['state'] = State::get();

        if(!empty($data['student'])) {
            return view('admin.student_edit', $data);
        }
        else{
            return redirect('/admin/students');
        }
    }

    public function update(Request $request,$id){
        // VALIDATION RULE
        $validation_array = array(
            'first_name' => 'required|max:255',
            'last_name'=>'required|max:255',
            'pincode'=>'required',
            'email' => 'required|email|max:100',
            'phone_no'=>'required|max:10',
            'password' => 'required|min:2|max:10',
            'address' => 'required|max:1000',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'photo' => 'nullable',
        );
        $rules = [
            'first_name.required' => 'The first name is required.',
            'first_name.max' => 'Maximum 255 characters allowed for first name.',
            'last_name.required' => 'The last name is required.',
            'last_name.max' => 'Maximum 255 characters allowed for last name.',
            'email.required' => 'The email is required.',
            'email.max' => 'Maximum 255 characters allowed for email.',
            'password.required' => 'The password is required.',
            'address.required' => 'The address is required.',
            'address.max' => 'Maximum 1000 characters allowed for address.',
            'city_id.required' => 'The city is required.',
            'country_id.required' => 'The country is required.',
            'state_id.required' => 'The state is required.',
        ];

        $user_exists = User::where('id','=',$id)->first();
        $user = User::where('email','!=',$user_exists->email)->get();

        $emails=array();
        foreach ($user as $key => $value) {
            array_push($emails,$value->email);
        }
        $user_email = $user_exists->email;
      
        if($user_email != $request->email){
            if(in_array($request->email, $emails)){
                return redirect('admin/students/edit/'.$id)->with('error','The email is already exists.');
            }
        }
        
        // UPDATE ARRAY
        $update_array = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'pincode'=>$request->pincode,
            'phone_no'=>$request->phone_no,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'country_id' => $request->country_id,
            'updated_at' => Carbon::now(),            
        );
        $photo = $request->file;     
                  
        if($photo) {
            $fileNamePhoto = time().'_'.$photo->getClientOriginalName();
            
            $filePath = $photo->storeAs('uploads/user_photo', $fileNamePhoto, 'public');

            $update_array['photo'] = $fileNamePhoto;
        }
        User::where('id','=',$id)
                ->update($update_array);

        return redirect('/admin/students')->with('success','Student has been updated successfully.');
    }
    public function information($id){
        $data = array('title'=>"Student Information", 'main_module'=>$this->main_module,'method'=>'Information');

        $data['student'] = User::where('id','=',$id)->limit(1)->first();

        if(!empty($data['student'])) {
            return view('admin.student_info', $data);
        }
        else{
            return redirect('/admin/students');
        }
    }
    public function getCities(State $state){
        return $state->city()->select('id', 'city')->get();
    }
    public function getAllCities(City $city){
        return $city->select('city', 'city')->get();
    }
}
