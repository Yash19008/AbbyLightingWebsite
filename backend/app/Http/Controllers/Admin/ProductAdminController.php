<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\GroupAttributeMaster;
use App\Models\ProductMaster;
use App\Models\Icon;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantFile;
use App\Models\Category;
use App\Models\SubTag;
use App\Models\Project;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportProduct;
use App\Mail\Product;

class ProductAdminController extends Controller
{

    public function __construct()
    {
        // $this->middleware('admin');
        $this->main_module = 'Product';
        $current = Carbon::now();
        $this->currentDateTime = $current->toDateTimeString();
    }
    public function index(Request $request)
    {
        $data = array('title' => "Product", 'main_module' => $this->main_module);
        $data = array('title' => "Products", 'main_module' =>  $this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = new ProductMaster;

        if ($data['search'] != '') {
            $data['results'] = $data['results']->where(function ($query) use ($data) {
                $query->where('title', 'LIKE', '%' . $data['search'] . '%');
            });
        }

        $data['results'] =  $data['results']->orderBy('id', 'DESC')->paginate(10); //config('custom_config.settings.admin_pagination_limit')


        $data['tbl'] = Common_function::encrypt('product_masters');

        return view('admin.products', $data);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductMaster::latest()->get();
            $showAsNewArrival = Common_function::encrypt("show_as_new_arrival");
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->addColumn('status', function ($row) {
                    $temp_status = $row->is_active == 'yes' ? "Checked" : "";
                    return '<div class="custom-control custom-switch text-center">
                                <input type="checkbox" class="custom-control-input knob switch" id="customSwitch' . $row->id . '" ' . $temp_status . '>
                                <label class="custom-control-label" for="customSwitch' . $row->id . '"></label>
                            </div>
                    ';
                })
                ->addColumn('show_as_new_arrival', function ($row) use ($showAsNewArrival) {
                    $temp_status = $row->show_as_new_arrival ? "Checked" : "";
                    return '<div class="custom-control custom-switch text-center">
                                <input type="checkbox" class="custom-control-input knob switch" data-col="'.$showAsNewArrival.'" id="customSwitch2' . $row->id . '" ' . $temp_status . '>
                                <label class="custom-control-label" for="customSwitch2' . $row->id . '"></label>
                            </div>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $actions_html = '<div class="text-center list-action actBtn-td">
                                        <a href="' . route('product_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit">
                                            <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                        </a>
                                        <a href="' . route('product_admin.information', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Information">
                                            <i class="ft-eye font-medium-3 mr-2"></i>
                                        </a>
                                        <a href="javascript:;" class="duplicate mx-1" data-toggle="tooltip" title="Duplicate"
                                        onclick="confirmDuplicate('.$row->id.', \''.e($row->title).'\')">
                                            <i class="ft-copy font-medium-3 mr-2"></i>
                                        </a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" data-module="' . $this->main_module . '" title="Delete">
                                            <i class="icon ft-trash-2 font-medium-3 mr-2"></i>
                                        </a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['title','slug', 'status', 'show_as_new_arrival', 'action'])
                ->make(true);
        }
    }
    
    
    public function exports(){
        $products = ProductMaster::with('category', 'attributes')
            ->leftJoin('product_variants', function($join) {
                $join->on('product_masters.id', '=', 'product_variants.product_id');
            })->get();

        
        $attributes = GroupAttributeMaster::where('is_active', 'yes')->orderBy('group_id', 'asc')->get();
        foreach ($products as $product) {
            $product['sub_tags'] = SubTag::whereIn('id', explode(',', $product->sub_tag_ids))->pluck('name')->implode(',');
            $product['icons'] = Icon::whereIn('id', explode(',', $product->icons))->pluck('name')->implode(',');
            $product['optional_icons'] = Icon::whereIn('id', explode(',', $product->optional_icons))->pluck('name')->implode(',');

            $obj = array();
            foreach ($product->attributes as $product_attribute) {
                $obj[$product_attribute['attribute_id']] = $product_attribute;
            }
            $product['attribute_objects'] = $obj;
        }
        $now = Carbon::now()->format('d-m-Y_H:i:s');
        $exports = new ExportProduct($products, $attributes);
        return \Excel::download($exports, 'products_'.$now.'.xlsx');
    }

    public function add()
    {
        $data = array('title' => "Add Product", 'main_module' => $this->main_module, 'method' => 'Add', 'action' => url('admin/product/insert'), 'frn_id' => 'frm_product');
        $data['category'] = Category::where('is_active', 'yes')->get();
        $data['attr'] = GroupAttributeMaster::where('is_active', 'yes')->orderBy('group_id', 'asc')->get();
        $data['tags'] = SubTag::where('is_active', 'yes')->get();
        $data['project'] = Project::where('is_active', 'yes')->get();
        $data['icons'] = Icon::where('is_active', 'yes')->get();
        $data['productImages'] = [];
        $data['collections'] = \App\Models\Collection::where('is_active', true)->orderBy('order', 'asc')->get();
        $data['selected_collections'] = [];
        return view('admin.product_edit', $data);
    }
    public function insert(Request $request)
    {
        if ($request->sub_tag_ids) {
            $sub_tag_ids = implode(',', $request->sub_tag_ids);
        }
        if ($request->icons) {
            $icons = implode(',', $request->icons);
        }
        if ($request->optional_icons) {
            $optional_icons = implode(',', $request->optional_icons);
        }

        $productVal = [
            'title' => $request->title,
            'sub_tag_ids' => $request->sub_tag_ids ? $sub_tag_ids : NULL,
            'category_id' => $request->category_id,
            'icons' => $request->icons ? $icons : NULL,
            'optional_icons' => $request->optional_icons ? $optional_icons : NULL,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];

        $featured_image = $request->featured_image;

        if ($featured_image) {
            $fileNamePhoto = time() . '_' . trim($featured_image->getClientOriginalName());

            $filePath = $featured_image->storeAs('uploads/products', $fileNamePhoto, 'public');
            // $fileModel->save();

            $productVal['featured_image'] = $fileNamePhoto;
        }

        $product = ProductMaster::create($productVal);

        if ($request->has('collection_ids')) {
            $product->collections()->sync($request->collection_ids);
        }

        if ($request->has('productImages') && $request->productImages !== null && $request->productImages !== 'null') {
            $productImages = $request->productImages;
            foreach ($productImages as $productImage) {
                $fileNamePhoto = time() . '_' . trim($productImage['item']->getClientOriginalName());
                $productImage['item']->storeAs('uploads/products', $fileNamePhoto, 'public');
                $insertImage = [
                    'product_id' => $product->id,
                    'image' => $fileNamePhoto,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductImage::create($insertImage);
            }
        }
        $data['attr'] = GroupAttributeMaster::where('is_active', 'yes')->orderBy('group_id', 'asc')->get();
        foreach ($data['attr'] as $key => $value) {
            $valuee = null;
            if (!empty($request['attribute_id_' . $key]) && !empty($request['attribute_id_' . $key][0])) {
                $valuee = join(",", $request['attribute_id_' . $key]);
            }
            $prodAttVal = [
                'product_id' => $product->id,
                'attribute_id' => $value->id,
                'value' => $valuee,
                'is_active' => 'yes'
            ];
            ProductAttribute::create($prodAttVal);
        }

        $newData = [
            'title' => $request->title,
            'sub_tag_ids' => $request->sub_tag_ids,
            'category_id' => $request->category_id,
            'icons' => $request->icons ? $icons : NULL,
            'optional_icons' => $request->optional_icons ? $optional_icons : NULL,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => ip2long(\Request::ip()),
            'action' => 'Add',
            'module' => 'Marketing',
            'message' => 'Marketing category newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('product_admin')->withInput()->withSuccess('Product has been added successfully.');
    }
    public function edit($id)
    {

        $data = array('title' => "Edit Product", 'main_module' => $this->main_module, 'method' => 'Edit', 'action' => url('admin/product/update/' . $id), 'frn_id' => 'frm_product_edit');
        $data['product'] = ProductMaster::where('id', $id)->first();
        $data['variant_no'] = ProductVariant::where('product_id', $id)->groupBy('product_id')->count();
        $data['category'] = Category::where('is_active', 'yes')->get();
        $data['project'] = Project::where('is_active', 'yes')->get();
        $data['icons'] = Icon::where('is_active', 'yes')->get();
        $data['attr'] = GroupAttributeMaster::where('is_active', 'yes')->orderBy('id', 'asc')->get();
        /* GROUP ATTRIBUTE MASTER HAVING ALL ATTRIBUTES NAMES..AND PRODUCT ATTRIBUTES HAVING ATTRIBUTES VALUES PRODUCT WISE. */
        $data['outerjoin'] = ProductAttribute::where('product_attributes.is_active', 'yes')->leftJoin('group_attribute_masters', 'group_attribute_masters.id', '=', 'product_attributes.attribute_id')->leftJoin('group_masters', 'group_masters.id', '=', 'group_attribute_masters.group_id')->where('product_id', $id)->orderBy('group_id', 'asc')->orderBy('group_attribute_masters.id', 'asc')->get();
       // dd($data['outerjoin']);
        $data['tags'] = SubTag::where('is_active', 'yes')->get();
        $data['productImages'] = ProductImage::where('product_id', $id)->where('is_active', 'yes')->get();
        $data['collections'] = \App\Models\Collection::where('is_active', true)->orderBy('order', 'asc')->get();
        $data['selected_collections'] = $data['product']->collections->pluck('id')->toArray();
        return view('admin.product_edit', $data);
    }
    public function update(Request $request, $id)
    {

        $oldProduct = ProductMaster::where('id', $id)->first();
        $request->validate([
            'slug' => 'required|unique:product_masters,slug,'.$id
        ]);
        

        $oldData =  [
            'title' => $oldProduct->title,
            'slug' => $oldProduct->slug,
            'category_id' => $oldProduct->category_id,
            'sub_tag_ids' => $oldProduct->sub_tag_ids,
            'number_of_variants' => $oldProduct->number_of_variants,
            'icons' => $oldProduct->icons,
            'optional_icons' => $oldProduct->optional_icons,
            'is_active' => $oldProduct->is_active,
            'created_by' => $oldProduct->created_by,
            'created_at' => $oldProduct->created_at
        ];
       
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY

        ProductAttribute::where('product_id', $id)->delete();
        $data['attr'] = GroupAttributeMaster::where('is_active', 'yes')->orderBy('group_id', 'asc')->orderBy('id', 'asc')->get();

        foreach ($data['attr'] as $key => $value) {
            $valuee = null;
            $key = $value->id;
            \Log::info("=======START=======");
            \Log::info($key);
            \Log::info($request['attribute_id_' . $key]);
            if (!empty($request['attribute_id_' . $key]) && !empty($request['attribute_id_' . $key][0])) {
                $valuee = join(",", $request['attribute_id_' . $key]);
            }
            \Log::info(!empty($request['attribute_id_' . $key]) && !empty($request['attribute_id_' . $key][0]));
            \Log::info($valuee);
            \Log::info([
                'product_id' => $id,
                'attribute_id' => $value->id,
                'value' => $valuee,
                'is_active' => 'yes'
            ]);
            \Log::info("========END========");
            $prodAttVal = [
                'product_id' => $id,
                'attribute_id' => $value->id,
                'value' => $valuee,
                'is_active' => 'yes'
            ];
            ProductAttribute::create($prodAttVal);
        }
        if ($request->sub_tag_ids) {
            $sub_tag_ids = implode(',', $request->sub_tag_ids);
        }
        if ($request->icons) {
            $icons = implode(',', $request->icons);
        }
        if ($request->optional_icons) {
            $optional_icons = implode(',', $request->optional_icons);
        }

        $update_array = array(
            'title' => $request->title,
            'slug'=>$request->slug,
            'category_id' => $request->category_id,
            'sub_tag_ids' => $request->sub_tag_ids ? $sub_tag_ids : NULL,
            'icons' => $request->icons ? $icons : NULL,
            'optional_icons' => $request->optional_icons ? $optional_icons : NULL,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );
        

        $featured_image = $request->featured_image;

        if ($featured_image) {
            $fileNamePhoto = time() . '_' . trim($featured_image->getClientOriginalName());

            $filePath = $featured_image->storeAs('uploads/products', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['featured_image'] = $fileNamePhoto;
        }

        ProductMaster::where('id', '=', $id)
            ->update($update_array);

        if ($request->has('collection_ids')) {
            $oldProduct->collections()->sync($request->collection_ids);
        } else {
            $oldProduct->collections()->sync([]);
        }

        ProductImage::where('product_id', $id)->delete();
        if ($request->has('existingProductImages') && $request->existingProductImages !== null) {
            $productImages = $request->existingProductImages;
            foreach ($productImages as $productImage) {
                $insertImage = [
                    'product_id' => $id,
                    'image' => $productImage,
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductImage::create($insertImage);
            }
        }

        if ($request->has('productImages') && $request->productImages !== null) {
            $productImages = $request->productImages;
            if (count($productImages) > 0) {
                foreach ($productImages as $productImage) {
                    $fileNamePhoto = time() . '_' . trim($productImage['item']->getClientOriginalName());
                    $productImage['item']->storeAs('uploads/products', $fileNamePhoto, 'public');
                    $insertImage = [
                        'product_id' => $id,
                        'image' => $fileNamePhoto,
                        'created_by' => Auth::guard('admin')->user()->id,
                        'created_at' => $this->currentDateTime,
                    ];
                    ProductImage::create($insertImage);
                }
            }
        }

        $newData =  [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'sub_tag_ids' => $request->sub_tag_ids ? $sub_tag_ids : NULL,
            'icons' => $request->icons ? $icons : NULL,
            'optional_icons' => $request->optional_icons ? $optional_icons : NULL,
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        ];

        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => \Request::ip(),
            'action' => 'Update',
            'module' => $this->main_module,
            'message' => 'Product hase been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return redirect()->route('product_admin')->with('success', 'Product has been updated successfully.');
    }
    public function information($id)
    {
        $data = array('title' => "Product Information", 'main_module' => $this->main_module, 'method' => 'Information');
        $variants = ProductVariant::where('product_id', $id)->where('is_active', '=', 'yes')->get();
        $data['variant_no'] = $variants->count();
        $data['product_image'] = ProductImage::where('product_id', $id)->where('is_active', 'yes')->get();
        $data['product_att'] = ProductAttribute::where('product_attributes.is_active', 'yes')->leftJoin('group_attribute_masters', 'group_attribute_masters.id', '=', 'product_attributes.attribute_id')->leftJoin('group_masters', 'group_masters.id', '=', 'group_attribute_masters.group_id')->where('product_id', $id)->orderBy('group_id', 'asc')->get();
        $data['product'] = ProductMaster::where('id', $id)->first();

        //GET TAGS
        $ids = explode(',', $data['product']->sub_tag_ids);
        $tag = SubTag::whereIn('id', $ids)->pluck('name');
        $arrTag = [];
        foreach ($tag as $key => $value) {
            array_push($arrTag, $value);
        }
        $data['tags'] = implode(',', $arrTag);

        //GET ICONS
        $ids = explode(',', $data['product']->icons);
        $data['icons'] = Icon::whereIn('id', $ids)->pluck('icon');

        //GET OPTIONAL ICONS
        $ids = explode(',', $data['product']->optional_icons);
        $data['optional_icons'] = Icon::whereIn('id', $ids)->pluck('icon');

        $data['variant'] = ProductVariant::where('is_active', 'yes')->where('product_id', $id)->get();

        foreach ($data['variant'] as $variant) {
            $variant->vectorImages = ProductVariantFile::where('product_variant_id', $variant->id)->where('file_type', 'image')->where('is_active', 'yes')->get();
            $variant->iesFiles = ProductVariantFile::where('product_variant_id', $variant->id)->where('file_type', 'ies')->where('is_active', 'yes')->get();
            foreach ($variant->iesFiles as $iesFile) {
                $str = $iesFile->file;
                if ($pos = strpos($str, '_'))
                    $str = substr($str, $pos + 1);
                if (!$str) $str = '';
                $iesFile->file_name = $str;
            }
        }

        \Log::info($data['variant']);

        $data['tbl'] = Common_function::encrypt('product_variants');
        if (!empty($data['product'])) {
            return view('admin.product_info', $data);
        } else {
            return redirect(route('product_admin'));
        }
    }
    public function product_variant_insert(Request $request)
    {
        $request->validate([
            'slug' => 'required|unique:product_variants,slug'
        ]);

        $productVal = [
            'product_id' => $request->product_id,
            'slug'=>$request->slug,
            'led_fitted' => $request->led_fitted,
            'co_related_color' => $request->co_related_color,
            'co_related_color_code' => $request->co_related_color_code,
            'lumens' => $request->lumens,
            'efficacy' => $request->efficacy,
            'beam_angle' => $request->beam_angle,
            'beam_angle_code' => $request->beam_angle_code,
            'led_power_watts' => $request->led_power_watts,
            'system_power_watts' => $request->system_power_watts,
            'operating_voltage' => $request->operating_voltage,
            'power_factor' => $request->power_factor,
            'variant_name' => $request->variant_name,
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $line_diagram = $request->line_diagram;

        if ($line_diagram) {
            $fileNamePhoto = time() . '_' . trim($line_diagram->getClientOriginalName());

            $filePath = $line_diagram->storeAs('uploads/products', $fileNamePhoto, 'public');
            // $fileModel->save();

            $productVal['line_diagram'] = $fileNamePhoto;
        }
        $photometry_file = $request->photometry_file;

        if ($photometry_file) {
            $fileNamePhotometry = time() . '_' . trim($photometry_file->getClientOriginalName());

            $filePath = $photometry_file->storeAs('uploads/products', $fileNamePhotometry, 'public');
            // $fileModel->save();

            $productVal['photometry_file'] = $fileNamePhotometry;
        }
        $custom_specsheet = $request->custom_specsheet;

        if ($custom_specsheet) {
            $fileNameSpecSheet = time() . '_' . trim($custom_specsheet->getClientOriginalName());

            $filePath = $custom_specsheet->storeAs('uploads/products', $fileNameSpecSheet, 'public');
            // $fileModel->save();

            $productVal['custom_specsheet'] = $fileNameSpecSheet;
        }

        $product = ProductVariant::create($productVal);

        if ($request->has('varientImages') && $request->varientImages !== null && $request->varientImages !== 'null') {
            $varientImages = $request->varientImages;
            foreach ($varientImages as $varientImage) {
                $fileNamePhoto = time() . '_' . trim($varientImage['item']->getClientOriginalName());
                $varientImage['item']->storeAs('uploads/product_variant_images', $fileNamePhoto, 'public');
                $insertImage = [
                    'product_variant_id' => $product->id,
                    'file' => $fileNamePhoto,
                    'file_type' => 'image',
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductVariantFile::create($insertImage);
            }
        }

        if ($request->has('iesFiles') && $request->iesFiles !== null && $request->iesFiles !== 'null') {
            $iesFiles = $request->iesFiles;
            foreach ($iesFiles as $iesFile) {
                $fileNamePhoto = time() . '_' . trim($iesFile['item']->getClientOriginalName());
                $iesFile['item']->storeAs('uploads/product_variant_ies_files', $fileNamePhoto, 'public');
                $insertImage = [
                    'product_variant_id' => $product->id,
                    'file' => $fileNamePhoto,
                    'file_type' => 'ies',
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductVariantFile::create($insertImage);
            }
        }

        $newData = [
            'product_id' => $request->product_id,
            'slug'=>$request->slug,
            'led_fitted' => $request->led_fitted,
            'co_related_color' => $request->co_related_color,
            'co_related_color_code' => $request->co_related_color_code,
            'lumens' => $request->lumens,
            'efficacy' => $request->efficacy,
            'beam_angle' => $request->beam_angle,
            'beam_angle_code' => $request->beam_angle_code,
            'led_power_watts' => $request->led_power_watts,
            'system_power_watts' => $request->system_power_watts,
            'operating_voltage' => $request->operating_voltage,
            'power_factor' => $request->power_factor,
            'variant_name' => $request->variant_name,
            'line_diagram' => $line_diagram ? $fileNamePhoto : '',
            'custom_specsheet' => $custom_specsheet ? $fileNameSpecSheet : '',
            'photometry_file' => $photometry_file ? $fileNamePhotometry : '',
            'created_at' => $this->currentDateTime,
            'created_by' => Auth::guard('admin')->user()->id,
        ];
        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => ip2long(\Request::ip()),
            'action' => 'Add',
            'module' => 'Product',
            'message' => 'Product variant newly added.',
            'old_data' => '',
            'new_data' => $newDataArr,
            'other_info' => '',
        ];

        AuditLog::create($auditInfo);

        return redirect()->route('product_admin.information', $request->product_id)->withInput()->withSuccess('Product variant has been added successfully.');
    }
    public function delete_variant(Request $request)
    {
        $db_pre = DB::getTablePrefix();

        $tbl = Common_function::decrypt($request->input('tbl'));

        $id = $request->input('id');

        DB::table($tbl)->where('id', $id)->update(['deleted_at' => Carbon::now(), 'is_active' => 'no', 'deleted_by' => Auth::guard('admin')->user()->id]);

        return response()->json(['code' => '1']);
        exit;
    }
    public function edit_variant(Request $request)
    {
        $id = $request->id;
        $product = ProductVariant::find($id);
        $product->vectorImages = ProductVariantFile::where('product_variant_id', $id)->where('file_type', 'image')->where('is_active', 'yes')->get();
        $product->iesFiles = ProductVariantFile::where('product_variant_id', $id)->where('file_type', 'ies')->where('is_active', 'yes')->get();

        foreach ($product->iesFiles as $iesFile) {
            $str = $iesFile->file;
            if ($pos = strpos($str, '_'))
                $str = substr($str, $pos + 1);
            if (!$str) $str = '';
            $iesFile->file_name = $str;
        }

        return response()->json(['product' => $product, 'code' => 1]);
    }
    public function update_variant(Request $request)
    {
        $id = $request->id;
        $oldVariant = ProductVariant::where('id', $id)->first();
        $request->validate([
            'slug' => 'required|unique:product_variants,slug,'.$id
        ]);
        $oldData =  [
            'slug'=>$request->slug,
            'led_fitted' => $oldVariant->led_fitted,
            'co_related_color' => ($oldVariant->co_related_color != '') ? $oldVariant->co_related_color : NULL,
            'co_related_color_code' => ($oldVariant->co_related_color_code != '') ? $oldVariant->co_related_color_code : NULL,
            'lumens' => ($oldVariant->lumens),
            'efficacy' => ($oldVariant->efficacy),
            'beam_angle' => ($oldVariant->beam_angle),
            'beam_angle_code' => ($oldVariant->beam_angle_code),
            'led_power_watts' => ($oldVariant->led_power_watts),
            'system_power_watts' => ($oldVariant->system_power_watts),
            'operating_voltage' => ($oldVariant->operating_voltage),
            'power_factor' => ($oldVariant->power_factor),
            'line_diagram' => ($oldVariant->line_diagram),
            'custom_specsheet' => ($oldVariant->custom_specsheet),
            'variant_name' => ($oldVariant->variant_name),
            'photometry_file' => ($oldVariant->photometry_file),
            'created_by' => $oldVariant->created_by,
            'created_at' => $oldVariant->created_at
        ];
        $oldDataArr = json_encode($oldData);
        // UPDATE ARRAY
        $update_array = array(
            'slug'=>$request->slug,
            'led_fitted' => $request->led_fitted,
            'co_related_color' => ($request->co_related_color != '') ? $request->co_related_color : NULL,
            'co_related_color_code' => ($request->co_related_color_code != '') ? $request->co_related_color_code : NULL,
            'lumens' => ($request->lumens),
            'efficacy' => ($request->efficacy),
            'beam_angle' => ($request->beam_angle),
            'beam_angle_code' => ($request->beam_angle_code),
            'led_power_watts' => ($request->led_power_watts),
            'system_power_watts' => ($request->system_power_watts),
            'operating_voltage' => ($request->operating_voltage),
            'power_factor' => ($request->power_factor),
            'variant_name' => ($request->variant_name),
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        );
        $line_diagram = $request->line_diagram;

        if ($line_diagram) {
            $fileNamePhoto = time() . '_' . trim($line_diagram->getClientOriginalName());

            $filePath = $line_diagram->storeAs('uploads/products', $fileNamePhoto, 'public');
            // $fileModel->save();

            $update_array['line_diagram'] = $fileNamePhoto;
        }
        $photometry_file = $request->photometry_file;

        if ($photometry_file) {
            $fileNamePhotoFile = time() . '_' . trim($photometry_file->getClientOriginalName());

            $filePath = $photometry_file->storeAs('uploads/products', $fileNamePhotoFile, 'public');
            // $fileModel->save();

            $update_array['photometry_file'] = $fileNamePhotoFile;
        }
        $custom_specsheet = $request->custom_specsheet;

        if ($custom_specsheet) {
            $fileNameSpecSheet = time() . '_' . trim($custom_specsheet->getClientOriginalName());

            $filePath = $custom_specsheet->storeAs('uploads/products', $fileNameSpecSheet, 'public');
            // $fileModel->save();

            $update_array['custom_specsheet'] = $fileNameSpecSheet;
        }
        ProductVariant::where('id', '=', $id)
            ->update($update_array);


        if ($request->deleted_vectorImages) {
            $deletedIds = explode(",", $request->deleted_vectorImages);
            ProductVariantFile::whereIn('id', $deletedIds)->delete();
        }

        if ($request->has('varientImages') && $request->varientImages !== null && $request->varientImages !== 'null') {
            $varientImages = $request->varientImages;
            foreach ($varientImages as $varientImage) {
                $fileNamePhoto = time() . '_' . trim($varientImage['item']->getClientOriginalName());
                $varientImage['item']->storeAs('uploads/product_variant_images', $fileNamePhoto, 'public');
                $insertImage = [
                    'product_variant_id' => $id,
                    'file' => $fileNamePhoto,
                    'file_type' => 'image',
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductVariantFile::create($insertImage);
            }
        }

        if ($request->deleted_ies) {
            $deletedIds = explode(",", $request->deleted_ies);
            ProductVariantFile::whereIn('id', $deletedIds)->delete();
        }
        if ($request->has('iesFiles') && $request->iesFiles !== null && $request->iesFiles !== 'null') {
            $iesFiles = $request->iesFiles;
            foreach ($iesFiles as $iesFile) {
                $fileNamePhoto = time() . '_' . trim($iesFile['item']->getClientOriginalName());
                $iesFile['item']->storeAs('uploads/product_variant_ies_files', $fileNamePhoto, 'public');
                $insertImage = [
                    'product_variant_id' => $id,
                    'file' => $fileNamePhoto,
                    'file_type' => 'ies',
                    'created_by' => Auth::guard('admin')->user()->id,
                    'created_at' => $this->currentDateTime,
                ];
                ProductVariantFile::create($insertImage);
            }
        }

        $newData =  [
            
            'led_fitted' => $request->led_fitted,
            'co_related_color' => ($request->co_related_color != '') ? $request->co_related_color : NULL,
            'co_related_color_code' => ($request->co_related_color_code != '') ? $request->co_related_color_code : NULL,
            'lumens' => ($request->lumens),
            'efficacy' => ($request->efficacy),
            'beam_angle' => ($request->beam_angle),
            'beam_angle_code' => ($request->beam_angle_code),
            'led_power_watts' => ($request->led_power_watts),
            'system_power_watts' => ($request->system_power_watts),
            'operating_voltage' => ($request->operating_voltage),
            'power_factor' => ($request->power_factor),
            'line_diagram' => ($request->line_diagram ? $fileNamePhoto : NULL),
            'custom_specsheet' => ($request->custom_specsheet ? $fileNameSpecSheet : NULL),
            'variant_name' => ($request->variant_name),
            'photometry_file' => ($request->photometry_file ? $fileNamePhotoFile : NULL),
            'updated_by' => Auth::guard('admin')->user()->id,
            'updated_at' => $this->currentDateTime,
        ];

        $newDataArr = json_encode($newData);

        //AUDIT LOG  ENTRY FOR ACTIONS
        $auditInfo = [
            'user_id' => Auth::guard('admin')->user()->id,
            'timestamp' => $this->currentDateTime,
            'ip_address' => \Request::ip(),
            'action' => 'Update',
            'module' => $this->main_module,
            'message' => 'Product variant has been updated',
            'old_data' => $oldDataArr,
            'new_data' => $newDataArr,
            'other_info' => '',
        ];
        AuditLog::create($auditInfo);
        return response()->json(['code' => 1]);
    }
    public function duplicate($id)
    {
    try {
        $product = ProductMaster::with(['variants', 'attributes'])->findOrFail($id);

        // Duplicate the base product
        $newProduct = $product->replicate();
        $newProduct->title = $product->title . ' - copy';
        $newProduct->slug = $product->slug . '-copy';
        // force inactive + not new arrival
        $newProduct->is_active = 'no';
        $newProduct->show_as_new_arrival = 0;
        $newProduct->featured_image = null;
        $newProduct->save();

        

        // Duplicate product variants
        foreach ($product->variants as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->product_id = $newProduct->id;
            $newVariant->slug = $variant->slug . '-copy-' . $newProduct->id;
            // reset line diagram
            $newVariant->line_diagram = null;
            $newVariant->save();
        }

        // Duplicate product attributes
        foreach ($product->attributes as $attribute) {
            $newAttribute = $attribute->replicate();
            $newAttribute->product_id = $newProduct->id;
            $newAttribute->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Product duplicated successfully!',
            'new_id'  => $newProduct->id,
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Duplication failed: ' . $e->getMessage(),
            ], 500);
        }
    }


}
