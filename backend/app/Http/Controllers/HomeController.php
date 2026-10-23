<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProductMaster;
use App\Models\SubTag;
use App\Models\HomeSlider;

class HomeController extends Controller
{
    public function index()
    {
        $data = array('title' => "Home");
        $data['products'] = ProductMaster::with('variants')->where('is_active', 'yes')->where('show_as_new_arrival', 1)->orderBy('created_by', 'desc')->limit(4)->get();
        $data['projects'] = Project::with('projectImages')->where('is_active', 'yes')->orderBy('id', 'DESC')->limit(2)->get();
        $data['subtags'] = SubTag::where('is_active', 'yes')->where('show_on_home_page', 1)->orderBy('created_by', 'desc')->limit(4)->get();
        $data['home_sliders_web'] = HomeSlider::where('for_mobile', 0)->orderBy('sort_order', 'ASC')->get();
        $data['home_sliders_mob'] = HomeSlider::where('for_mobile', 1)->orderBy('sort_order', 'ASC')->get();

        return view('pages.home', $data);
    }
}
