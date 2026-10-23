<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Icon;
use App\Models\ProductAttribute;
use App\Models\ProductMaster;
use App\Models\ProductVariant;
use App\Models\SubTag;
use App\Models\SubTagMapping;
use App\Models\SubTagProjectImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Models\Decorative\DecProduct;
use App\Models\Collection;
use App\Models\Project;
use App\Models\Catalogue;
use App\Models\Blog;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{
    public function subTags(Request $request, $tag_id = null)
    {
        $data = array('title' => 'All');
        $data['subTags'] = SubTag::where('is_active', 'yes')->orderBy('display_name', 'ASC')->get();
        foreach ($data['subTags'] as $subTag) {
            $subTag['tags'] = SubTagMapping::where('sub_tag_id', $subTag->id)->pluck('tag_id')->toArray();
        }
        $data['tags'] = Tag::where('is_active', 'yes')->get();
        $data['tag_id'] = $tag_id;
        $data['categories'] = Category::where('is_active', 'yes')->get();
        $data['default_data_filter'] = '';

        if ($tag_id && $tag_id != 'All') {
            $tag = Tag::find($tag_id);
            if ($tag) {
                $data['title'] = $tag->display_name;
                $data['default_data_filter'] = $tag->id;
            }
        }
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
        $data['tag_id'] = null;
        $data['categories'] = Category::where('is_active', 'yes')->get();
        $data['default_data_filter'] = '';
        if ($filter !== null && $filter !== 'null' && strtolower($filter) !== 'all') {
            foreach ($data['tags'] as $tag) {
                if ($this->getSlugWithout($tag->slug) == $this->getSlugWithout($filter)) {
                    $data['default_data_filter'] = $tag->id;
                    $data['tag_id'] = $tag->id;
                    $data['title'] = $tag->display_name;
                    break;
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
        $sub_tag = SubTag::where('slug', $slug)->with('linkedSubTags')->first();
        if (!$sub_tag) {
            $productMaster = ProductMaster::where('slug', $slug)->first();
            if ($productMaster && $productMaster->sub_tag_ids) {
                $firstSubTagId = (int) trim(explode(',', $productMaster->sub_tag_ids)[0] ?? '');
                if ($firstSubTagId) {
                    $sub_tag = SubTag::find($firstSubTagId);
                    if ($sub_tag) {
                        return redirect('/products/' . $sub_tag->slug);
                    }
                }
            }
            abort(404);
        }
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
        // 1. Backwards compatibility for /category/{any} route
        if ($category) {
            $visible_tags = Tag::where('show_categories', 1)->pluck('id')->toArray();
            $visible_sub_tags = SubTagMapping::whereIn('tag_id', $visible_tags)->pluck('sub_tag_id')->toArray();
            $visible_sub_tags = implode('|', $visible_sub_tags);

            $data = [];
            $data['new_categories'] = Category::where('is_active', 'yes')->get();
            $categoryModel = Category::where('slug', $category)->first();
            if ($categoryModel) {
                $category_title = ucwords(strtolower($categoryModel->title));
                $data['title'] = "Showing results for : \"$category_title\"";
                $query = ProductMaster::with('variants', 'category')
                    ->where('is_active', 'yes')
                    ->where('category_id', $categoryModel->id)
                    ->whereRaw("CONCAT(',', `sub_tag_ids`, ',') REGEXP ',(" . $visible_sub_tags . "),' ")
                    ->orderBy('title')
                    ->orderBy('id');
                $data['products'] = $query->get();
            } else {
                $data['title'] = "Showing results";
                $data['products'] = collect();
            }
            $data['onSearchPage'] = true;
            return view('pages.products', $data);
        }

        $q = trim((string) $request->input('q', ''));
        $perPage = 8;

        // 2. AJAX Load More for a specific category
        if ($request->ajax() || $request->input('action') === 'load_more' || $request->wantsJson()) {
            $catKey = (string) $request->input('category', '');
            $page = max(1, (int) $request->input('page', 2));

            $queries = $this->buildSearchQueries($q);
            if (!isset($queries[$catKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category specified.'
                ], 400);
            }

            $catConfig = $queries[$catKey];
            $total = (clone $catConfig['query'])->count();
            $items = $catConfig['query']->forPage($page, $perPage)->get();

            $html = '';
            foreach ($items as $item) {
                $html .= view($catConfig['card_view'], ['item' => $item])->render();
            }

            $loadedSoFar = ($page - 1) * $perPage + $items->count();
            $remaining = max(0, $total - $loadedSoFar);
            $hasMore = $remaining > 0;

            return response()->json([
                'success' => true,
                'category' => $catKey,
                'html' => $html,
                'items_count' => $items->count(),
                'remaining' => $remaining,
                'has_more' => $hasMore,
                'next_page' => $page + 1,
            ]);
        }

        // 3. Full Search Page Load
        $queries = $this->buildSearchQueries($q);
        $categoriesData = [];

        foreach ($queries as $key => $catConfig) {
            $total = (clone $catConfig['query'])->count();
            $items = $total > 0 ? $catConfig['query']->forPage(1, $perPage)->get() : collect();

            $categoriesData[$key] = [
                'key' => $key,
                'name' => $catConfig['name'],
                'total' => $total,
                'items' => $items,
                'card_view' => $catConfig['card_view'],
                'has_more' => $total > $perPage,
                'remaining' => max(0, $total - $perPage),
                'next_page' => 2,
            ];
        }

        $totalResults = array_sum(array_column($categoriesData, 'total'));

        return view('pages.search', [
            'q' => $q,
            'title' => !empty($q) ? "Search: \"$q\"" : "Search",
            'categories' => $categoriesData,
            'totalResults' => $totalResults,
        ]);
    }

    /**
     * Build categorized search queries across all dynamic elements.
     */
    protected function buildSearchQueries(string $q): array
    {
        $clean = str_replace(' ', '', $q);

        // 1. Architectural Products (SubTags - representing product families on /products/{slug})
        $tagSubTagIds = [];
        $prodSubTagIds = [];

        if ($q !== '') {
            $matchingTagIds = Tag::where(function ($tq) use ($q, $clean) {
                $tq->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('display_name', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`name`, ' ', '') LIKE ?", ['%' . $clean . '%']);
            })->pluck('id')->toArray();

            if (!empty($matchingTagIds)) {
                $tagSubTagIds = SubTagMapping::whereIn('tag_id', $matchingTagIds)->pluck('sub_tag_id')->toArray();
            }

            // Products whose title or variant matches $q
            $matchingProducts = ProductMaster::where('is_active', 'yes')
                ->where(function ($pq) use ($q, $clean) {
                    $pq->where('title', 'LIKE', '%' . $q . '%')
                        ->orWhereRaw("REPLACE(`title`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                        ->orWhereHas('category', function ($cq) use ($q) {
                            $cq->where('title', 'LIKE', '%' . $q . '%');
                        })
                        ->orWhereHas('variants', function ($vq) use ($q, $clean) {
                            $vq->where('variant_name', 'LIKE', '%' . $q . '%')
                                ->orWhere('slug', 'LIKE', '%' . $q . '%')
                                ->orWhereRaw("REPLACE(`variant_name`, ' ', '') LIKE ?", ['%' . $clean . '%']);
                        });
                })->pluck('sub_tag_ids')->toArray();

            foreach ($matchingProducts as $subIds) {
                if ($subIds) {
                    foreach (explode(',', $subIds) as $sId) {
                        $sId = trim($sId);
                        if ($sId !== '') {
                            $prodSubTagIds[] = (int) $sId;
                        }
                    }
                }
            }
        }

        $allMatchedSubTagIds = array_unique(array_merge($tagSubTagIds, $prodSubTagIds));

        $archQ = SubTag::with(['subTagMappings.tag'])
            ->where('is_active', 'yes');

        if ($q !== '') {
            $archQ->where(function ($sq) use ($q, $clean, $allMatchedSubTagIds) {
                $sq->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhere('display_name', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`name`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('slug', 'LIKE', '%' . $q . '%');

                if (!empty($allMatchedSubTagIds)) {
                    $sq->orWhereIn('id', $allMatchedSubTagIds);
                }
            });
        }
        $archQ->orderBy('display_name', 'asc');

        // 2. Decorative Products
        $decQ = DecProduct::with(['category', 'collection', 'colors'])
            ->where('status', 'published');
        if ($q !== '') {
            $decQ->where(function ($sub) use ($q, $clean) {
                $sub->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`name`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('short_description', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('category', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%');
                    })
                    ->orWhereHas('collection', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%');
                    });
            });
        }
        $decQ->orderBy('order', 'asc')->orderBy('id', 'desc');

        // 3. Collections
        $colQ = Collection::where('is_active', true);
        if ($q !== '') {
            $colQ->where(function ($sub) use ($q, $clean) {
                $sub->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`name`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('short_description', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%');
            });
        }
        $colQ->orderBy('order', 'asc')->orderBy('id', 'asc');

        // 4. Projects
        $projQ = Project::with('projectImages')
            ->where('is_active', 'yes');
        if ($q !== '') {
            $projQ->where(function ($sub) use ($q, $clean) {
                $sub->where('name', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`name`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('location', 'LIKE', '%' . $q . '%')
                    ->orWhere('type', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%');
            });
        }
        $projQ->orderBy('sequence', 'asc')->orderBy('id', 'desc');

        // 5. Catalogues
        $catQ = Catalogue::with('category')
            ->where('status', 'active');
        if ($q !== '') {
            $catQ->where(function ($sub) use ($q, $clean) {
                $sub->where('title', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`title`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('description', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('category', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%');
                    });
            });
        }
        $catQ->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        // 6. Stories & Inspiration Blogs
        $blogQ = Blog::with('category')
            ->where('status', 'published');
        if ($q !== '') {
            $blogQ->where(function ($sub) use ($q, $clean) {
                $sub->where('title', 'LIKE', '%' . $q . '%')
                    ->orWhereRaw("REPLACE(`title`, ' ', '') LIKE ?", ['%' . $clean . '%'])
                    ->orWhere('dek', 'LIKE', '%' . $q . '%')
                    ->orWhere('content', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('category', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%');
                    });
            });
        }
        $blogQ->orderBy('published_at', 'desc')->orderBy('id', 'desc');

        return [
            'architectural' => [
                'name' => 'Architectural Lighting',
                'query' => $archQ,
                'card_view' => 'partials.search.card-architectural',
            ],
            'decorative' => [
                'name' => 'Decorative Lighting',
                'query' => $decQ,
                'card_view' => 'partials.search.card-decorative',
            ],
            'collections' => [
                'name' => 'Collections',
                'query' => $colQ,
                'card_view' => 'partials.search.card-collection',
            ],
            'projects' => [
                'name' => 'Projects & Case Studies',
                'query' => $projQ,
                'card_view' => 'partials.search.card-project',
            ],
            'catalogues' => [
                'name' => 'Catalogues & Downloads',
                'query' => $catQ,
                'card_view' => 'partials.search.card-catalogue',
            ],
            'blogs' => [
                'name' => 'Stories & Inspiration',
                'query' => $blogQ,
                'card_view' => 'partials.search.card-blog',
            ],
        ];
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
