<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\SubTag;
use App\Models\ProductMaster;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\SubTagProjectImage;
use App\Models\Icon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Models\Category;
use App\Models\SubTagMapping;
use Response;
use Spatie\Browsershot\Browsershot;

class ProductController extends Controller
{
    public function subTags(Request $request, $tag_id = null)
    {
        if ($tag_id && $tag_id != 'All') {
            $tag = Tag::find($tag_id);
            $data = array('title' => $tag->display_name);
            $data['subTags'] = SubTag::where('tag_id', $tag_id)->where('is_active', 'yes')->orderBy('display_name', 'ASC')->get();
        } else {
            $data = array('title' => 'All');
            $data['subTags'] = SubTag::where('is_active', 'yes')->orderBy('display_name', 'ASC')->get();
        }
        foreach ($data['subTags'] as $subTag) {
            $subTag['tags'] = SubTagMapping::where('sub_tag_id', $subTag->id)->pluck('tag_id')->toArray();
        }
        $data['tags'] = Tag::where('is_active', 'yes')->get();
        $data['tag_id'] = $tag_id;
        $data['categories'] = Category::where('is_active', 'yes')->get();
        $data['default_data_filter'] = '';
        return view('pages.sub-tags', $data);
    }

    public function subTagsByFilter(Request $request, $filter = null)
    {
        $data = array('title' => 'All');
        $data['subTags'] = SubTag::where('is_active', 'yes')->orderBy('display_name', 'ASC')->get();
        foreach ($data['subTags'] as $subTag) {
            $subTag['tags'] = SubTagMapping::where('sub_tag_id', $subTag->id)->pluck('tag_id')->toArray();
        }
        $data['tags'] = Tag::where('is_active', 'yes')->get();
        $data['tag_id'] = NULL;
        $data['categories'] = Category::where('is_active', 'yes')->get();
        $data['default_data_filter'] = '';
        if ($filter !== null && $filter !== 'null') {
            foreach ($data['tags'] as $tag) {
                if ($this->getSlugWithout($tag->slug) == $this->getSlugWithout($filter)) {
                    $data['default_data_filter'] = $tag->id;
                }
            }
        }
        return view('pages.sub-tags', $data);
    }

    function getSlugWithout($name) {
        $name = str_replace("-", "", $name);
        $name = str_replace(" ", "", $name);
        $name = strtolower($name);
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name); // Removes special chars.
        return $name;
    }

    public function products($slug)
    {
        $sub_tag = SubTag::where('slug', $slug)->with('linkedSubTags')->firstOrFail();
        $sub_tag_id = $sub_tag->id;
        $tagIds = SubTagMapping::where('sub_tag_id', $sub_tag->id)->pluck('tag_id')->toArray();
        $show_categories_count = Tag::whereIn('id', $tagIds)->where('show_categories', 1)->count();
        $data = array('title' => $sub_tag->display_name);
        $data['subTag'] = $sub_tag;
        if ($show_categories_count >= 1) {
            $data['categories'] = Category::where('is_active', 'yes')->get();
        }
        $data['products'] = ProductMaster::with('variants', 'category')
            ->whereRaw('FIND_IN_SET(' . $sub_tag_id . ',sub_tag_ids)')
            ->where('is_active', 'yes')
            ->orderBy(DB::raw(
                "REPLACE(title,RIGHT(title,LOCATE(' ',REVERSE(title))),'')"
            ))
            ->orderBy(DB::raw(
                "CAST(SUBSTRING_INDEX(title, ' ', -1) AS SIGNED)"
            ))
            ->orderBy('title')
            ->orderBy('id')
            ->get();


        $data['banner_images'] = [];
        if ($sub_tag->banner_image) {
            $data['banner_images'][] = 'uploads/sub_tags/' . $sub_tag->banner_image;
        }
        if ($sub_tag->banner_image_2) {
            $data['banner_images'][] = 'uploads/sub_tags/' . $sub_tag->banner_image_2;
        }
        if ($sub_tag->banner_image_3) {
            $data['banner_images'][] = 'uploads/sub_tags/' . $sub_tag->banner_image_3;
        }
        if ($sub_tag->banner_image_4) {
            $data['banner_images'][] = 'uploads/sub_tags/' . $sub_tag->banner_image_4;
        }
        if ($sub_tag->banner_image_5) {
            $data['banner_images'][] = 'uploads/sub_tags/' . $sub_tag->banner_image_5;
        }

        $data['sub_tag_project_images'] = SubTagProjectImage::where('sub_tag_id', $sub_tag->id)->get();

        return view('pages.products', $data);
    }

    public function search(Request $request, $category = null)
    {
        if (!$request->filled('q') && !$category) {
            return redirect("/");
        }
        // $sub_tag = SubTag::find($sub_tag_id);
        $data = array('title' => "Searching \"$request->q\"");
        /* $data['subTag'] = $sub_tag; */
        $query = ProductMaster::with('variants', 'category')
            ->where('is_active', 'yes')
            ->whereRaw("REPLACE(`title`, ' ' ,'') LIKE ?", ['%'.str_replace(' ', '', $request->q).'%'])
            ->orderBy(DB::raw(
                "REPLACE(title,RIGHT(title,LOCATE(' ',REVERSE(title))),'')"
            ))
            ->orderBy(DB::raw(
                "CAST(SUBSTRING_INDEX(title, ' ', -1) AS SIGNED)"
            ))
            ->orderBy('title')
            ->orderBy('id');

        if ($category) {
            $visible_tags = Tag::where('show_categories', 1)->pluck('id')->toArray();
            $visible_sub_tags = SubTagMapping::whereIn('tag_id', $visible_tags)->pluck('sub_tag_id')->toArray();
            $visible_sub_tags = implode('|', $visible_sub_tags);

            $data['new_categories'] = Category::where('is_active', 'yes')->get();
            $category = Category::where('slug', $category)->first();
            $category_title = ucwords(strtolower($category->title));
            $data['title'] = "Showing results for : \"$category_title\"";
            $query = $query->where('category_id', $category->id);
            $query = $query->whereRaw("CONCAT(',', `sub_tag_ids`, ',')  REGEXP ',(" . $visible_sub_tags . "),' ");
            $products = $query->get();
            $data['products'] = $products;

            // $visible_tags = Tag::where('show_categories', 1)->get()->pluck('id');
            // $visible_sub_tags = SubTag::whereIn('tag_id', $visible_tags)->get()->pluck('id');
            // $data['products'] = [];
            // foreach ($products as $product) {
            //     $isPresent = false;
            //     try {
            //         $sub_tag_ids = explode(',', $product->sub_tag_ids);
            //         foreach ($sub_tag_ids as $sub_tag_id) {
            //             foreach ($visible_sub_tags as $visible_sub_tag) {
            //                 if ($visible_sub_tag == $sub_tag_id) {
            //                     $isPresent = true;
            //                 }
            //             }
            //         }
            //     } catch (\Exception $e) {
            //         $isPresent = true;
            //         \Log::error("Error in search by category");
            //         \Log::error($e);
            //     }
            //     if ($isPresent) {
            //         $data['products'][] = $product;
            //     }
            // }
        } else {
            $query = $query->whereRaw("REPLACE(`title`, ' ' ,'') LIKE ?", ['%'.str_replace(' ', '', $request->q).'%']);
            $data['products'] = $query->get();
        }

        $data['onSearchPage'] = true;
        return view('pages.products', $data);
    }

    private function getProductDetails($variant_id)
    {
        $variant_details_and_attributes_mapping = array(
            33 => "led_fitted",
            34 => "led_power_watts",
            35 => "system_power_watts",
            36 => "operating_voltage",
            37 => "power_factor",
        );

        $variant = ProductVariant::with("variantFiles")->find($variant_id);
        $data = array('title' => $variant?->variant_name);
        $data['variant'] = $variant;
        $data['product'] = ProductMaster::with('productImages')->find($variant->product_id);
        $data['product_att'] = ProductAttribute::where('product_attributes.is_active', 'yes')->leftJoin('group_attribute_masters', 'group_attribute_masters.id', '=', 'product_attributes.attribute_id')->leftJoin('group_masters', 'group_masters.id', '=', 'group_attribute_masters.group_id')->where('product_id', $variant->product_id)->orderBy('group_id', 'asc')->orderBy('group_attribute_masters.id', 'asc')->get();
        if (isset($data['product']->icons)) {
            $data['icons'] = Icon::whereIn('id', explode(',', $data['product']->icons))->where('is_active', 'yes')->get();
        }
        if (isset($data['product']->optional_icons)) {
            $data['optional_icons'] = Icon::whereIn('id', explode(',', $data['product']->optional_icons))->where('is_active', 'yes')->get();
        }
        $attr_table = array();
        $attr_table['BODY_FINISH'] = [''];
        $attr_table['BODY_FINISH_CODE'] = [''];
        $attr_table['CRI'] = [''];
        $attr_table['CRI_CODE'] = [''];
        $attr_table['DIMMING_OPTION'] = [''];
        $attr_table['DIMMING_OPTION_CODE'] = [''];
        $attr_table['LUMENS'] = [''];
        $attr_table['EFFICACY'] = [''];

        foreach ($data['product_att'] as $attr) {

            try {
                if (isset($variant_details_and_attributes_mapping[$attr->attribute_id])) {
                    $attr->value = $variant[$variant_details_and_attributes_mapping[$attr->attribute_id]];
                }
            } catch (\Exception $e) {
            }

            if ($attr->attribute_id == 7 || $attr->attribute_id == 12 || $attr->attribute_id == 32) {
                $values = $attr->values ? explode(',', $attr->values) : [''];
                $codes = $attr->codes ? explode(',', $attr->codes) : [''];
            }
            if ($attr->attribute_id == 7) {
                $attr_table['BODY_FINISH'] = $attr->value ? explode(',', $attr->value) : [''];
                $attr_table['BODY_FINISH_CODE'] = $this->setCodes($attr_table['BODY_FINISH'], $values, $codes);
            }
            if ($attr->attribute_id == 12) {
                $attr_table['CRI'] = $attr->value ? explode(',', $attr->value) : [''];
                // $attr_table['CRI_CODE'] = $this->setCodes($attr_table['CRI'], $values, $codes);
            }
            if ($attr->attribute_id == 32) {
                $attr_table['DIMMING_OPTION'] = $attr->value ? explode(',', $attr->value) : [''];
                $attr_table['DIMMING_OPTION_CODE'] = $this->setCodes($attr_table['DIMMING_OPTION'], $values, $codes);
            }
        }
        $attr_table['LUMENS'] = $variant->lumens ? explode(',', $variant->lumens) : [''];

        $attr_table['EFFICACY'] = $variant->efficacy ? explode(',', $variant->efficacy) : [''];

        $attr_table['CCT'] = $variant->co_related_color ? explode(',', $variant->co_related_color) : [''];
        $attr_table['CCT_CODE'] = $variant->co_related_color_code ? explode(',', $variant->co_related_color_code) : [''];

        $attr_table['ANGLE'] = $variant->beam_angle ? explode(',', $variant->beam_angle) : [''];
        $attr_table['ANGLE_CODE'] = $variant->beam_angle_code ? explode(',', $variant->beam_angle_code) : [''];

        $maxRows = count($attr_table['BODY_FINISH']);
        $maxRows = $maxRows > count($attr_table['CRI']) ? $maxRows : count($attr_table['CRI']);
        $maxRows = $maxRows > count($attr_table['DIMMING_OPTION']) ? $maxRows : count($attr_table['DIMMING_OPTION']);
        $maxRows = $maxRows > count($attr_table['CCT']) ? $maxRows : count($attr_table['CCT']);
        $maxRows = $maxRows > count($attr_table['ANGLE']) ? $maxRows : count($attr_table['ANGLE']);
        $maxRows = $maxRows > count($attr_table['LUMENS']) ? $maxRows : count($attr_table['LUMENS']);
        $maxRows = $maxRows > count($attr_table['EFFICACY']) ? $maxRows : count($attr_table['EFFICACY']);

        $data['maxRows'] = $maxRows;
        $data['attr_table'] = $attr_table;
        return $data;
    }


    public function setCodes($select_values, $values, $codes)
    {
        $dest_arr = array();
        foreach ($select_values as $select_value) {
            foreach ($values  as $key => $value) {
                if ($select_value == $value) {
                    array_push($dest_arr, $codes[$key]);
                }
            }
        }
        return count($dest_arr) != 0 ? $dest_arr : [''];
    }

    public function product($variant_slug)
    {
        $variant = ProductVariant::where('slug', $variant_slug)->first();
        $data = $this->getProductDetails($variant->id);

        $subtag = SubTag::where('id', $variant->product->sub_tag_ids)->first();
        if ($subtag) {
            $data['subtag_display_name'] = $subtag->display_name;
            $data['subtag_slug'] = $subtag->slug;
        } else {
            $data['subtag_display_name'] = '';
            $data['subtag_slug'] = NULL;
        }
        return view('pages.product', $data);
    }

    public function pdf($variant_id)
    {
        $data = $this->getProductDetails($variant_id);
        return view('pages.product-pdf', $data);
    }

    public function downloadPdfOld($variant_id)
    {
        // Define the command to run your Node.js script
        if (config('app.env') == 'local') {
            // $node = 'C:\\Users\\djdiv\\AppData\\Roaming\\nvm\\v16.13.0\\node';
            $node = 'node';
        } else {
            $node = '/home/abbynew.clarusinfosolutions.com/.nvm/versions/node/v16.18.1/bin/node';
        }
        $command = [$node, 'generate-pdf.js', config('app.url') . "/product/$variant_id/pdf", "storage/app/public/product-$variant_id.pdf"];

        // Execute the command
        $process = new Process($command);
        $process->setWorkingDirectory(base_path(''));
        $process->mustRun();

        // Check for any errors during execution
        if (!$process->isSuccessful()) {
            return response('Error generating PDF', 500);
        }

        // Get the path to the generated PDF
        $pdfPath = "product-$variant_id.pdf";

        // Return the PDF file as a response
        return response()->file($pdfPath);
    }

    public function downloadPdf($variant_id)
    {
        $node = (config('app.env') == 'local') ? 'node' : '/home/yashsoni-abylght/.nvm/versions/node/v22.22.3/bin/node';
        $npm = (config('app.env') == 'local') ? 'npm' : '/home/yashsoni-abylght/.nvm/versions/node/v22.22.3/bin/npm';
        
        $data = $this->getProductDetails($variant_id);
        $view = view('pages.product-pdf', $data);
        $html = $view->render();

        $pdf =  Browsershot::html($html)
            ->waitUntilNetworkIdle(true)
            ->setNodeBinary($node)
            ->setNpmBinary($npm)
            ->newHeadless()
            ->noSandbox()
            ->pdf();
        // $title = $data['product'] . ' ' .  $data['variant_name'];
        $variant = ProductVariant::with('product')->find($variant_id);
        $title = $variant->product->title . " " . $variant->variant_name;
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header("Content-Disposition", "inline;filename=$title.pdf");
    }
}
