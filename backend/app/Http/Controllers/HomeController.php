<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProductMaster;
use App\Models\SubTag;
use App\Models\CatalogDownload;
use App\Models\HomeSlider;
use Illuminate\Support\Facades\Response;
use App\Rules\GoogleCaptcha;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\Verify;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $data = array('title' => "Home", 'catalogDownloadForm' => url('catalog-download-user-form'));
        $data['products'] = ProductMaster::with('variants')->where('is_active', 'yes')->where('show_as_new_arrival', 1)->orderBy('created_by', 'desc')->limit(4)->get();
        $data['projects'] = Project::with('projectImages')->where('is_active', 'yes')->orderBy('id', 'DESC')->limit(2)->get();
        $data['subtags'] = SubTag::where('is_active', 'yes')->where('show_on_home_page', 1)->orderBy('created_by', 'desc')->limit(4)->get();
        $data['home_sliders_web'] = HomeSlider::where('for_mobile', 0)->orderBy('sort_order', 'ASC')->get();
        $data['home_sliders_mob'] = HomeSlider::where('for_mobile', 1)->orderBy('sort_order', 'ASC')->get();

        return view('pages.home', $data);
    }

    public function catalogDownloadUserForm(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'nullable|numeric',
            'g-recaptcha-response' => ['required', new GoogleCaptcha]
        ]);
        CatalogDownload::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' =>  $request->mobile
        ]);
        $file= public_path(). "/storage/uploads/catalog/Abby_Lighting_Product_Catalog.pdf";
        $headers = array(
                  'Content-Type: application/pdf',
                );
    
        return Response::download($file, 'Abby_Lighting_Product_Catalog.pdf', $headers);
    }
}
