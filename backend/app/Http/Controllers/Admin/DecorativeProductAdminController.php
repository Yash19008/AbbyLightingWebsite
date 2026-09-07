<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DecorativeProduct;
use App\Models\DecorativeProductImage;
use App\Helpers\Common_function;
use DataTables;
use Illuminate\Support\Str;

class DecorativeProductAdminController extends Controller
{
    public function __construct()
    {
        $this->main_module = 'Decorative Product';
    }

    public function index(Request $request)
    {
        $data = array('title' => "Decorative Products", 'main_module' => $this->main_module);
        return view('admin.decorative-products.index', $data);
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = DecorativeProduct::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('sku', function ($row) {
                    return $row->sku;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success">Active</span>';
                    }
                    return '<span class="badge badge-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $actions_html = '<div class="text-center list-action actBtn-td">
                                        <a href="' . route('decorative_product_admin.edit', $row->id) . '" class="mx-1" data-toggle="tooltip" title="Edit">
                                            <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                        </a>
                                        <a href="javascript:;" class="delete mx-1" data-toggle="tooltip" title="Delete" data-id="' . $row->id . '">
                                            <i class="ft-trash-2 font-medium-3 mr-2 text-danger"></i>
                                        </a>
                                    </div>';
                    return $actions_html;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function add()
    {
        $data = [
            'title' => "Add Decorative Product",
            'main_module' => $this->main_module,
            'method' => 'Add',
            'action' => route('decorative_product_admin.insert'),
            'global_attributes' => \App\Models\DecorativeAttribute::with('values')->get(),
            'categories' => \App\Models\DecorativeCategory::with('parent')->orderBy('name', 'asc')->get(),
            'collections' => \App\Models\Collection::where('is_active', true)->orderBy('order', 'asc')->get(),
            'selected_collections' => []
        ];
        return view('admin.decorative-products.add', $data);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sku' => 'required|string|unique:decorative_products,sku',
        ]);

        $product = DecorativeProduct::create([
            'title' => $request->title,
            'sku' => $request->sku,
            'slug' => Str::slug($request->title . '-' . $request->sku),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $this->saveRelations($request, $product);

        return redirect()->route('decorative_product_admin')->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        $product = DecorativeProduct::with(['images', 'primaryImage', 'lightOnImage', 'galleryImages', 'attributes.values', 'variations.attributeValues', 'variations.specificationSections.specifications', 'collections'])->findOrFail($id);
        $data = [
            'title' => "Edit Decorative Product",
            'main_module' => $this->main_module,
            'method' => 'Edit',
            'action' => route('decorative_product_admin.update', $id),
            'product' => $product,
            'global_attributes' => \App\Models\DecorativeAttribute::with('values')->get(),
            'categories' => \App\Models\DecorativeCategory::with('parent')->orderBy('name', 'asc')->get(),
            'collections' => \App\Models\Collection::where('is_active', true)->orderBy('order', 'asc')->get(),
            'selected_collections' => $product->collections->pluck('id')->toArray()
        ];
        return view('admin.decorative-products.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sku' => 'required|string|unique:decorative_products,sku,' . $id,
        ]);

        $product = DecorativeProduct::findOrFail($id);
        $product->update([
            'title' => $request->title,
            'sku' => $request->sku,
            'slug' => Str::slug($request->title . '-' . $request->sku),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $this->saveRelations($request, $product);

        return redirect()->route('decorative_product_admin')->with('success', 'Product updated successfully.');
    }

    private function saveRelations(Request $request, DecorativeProduct $product)
    {
        // 0. Save Categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->sync([]);
        }

        // Save Collections
        if ($request->has('collection_ids')) {
            $product->collections()->sync($request->collection_ids);
        } else {
            $product->collections()->sync([]);
        }

        // 1. Save Product Attributes & Values
        $product->attributes()->delete();
        
        if ($request->has('product_attributes')) {
            foreach ($request->product_attributes as $attr_data) {
                if (isset($attr_data['attribute_id']) && !empty($attr_data['attribute_id'])) {
                    $prod_attr = $product->attributes()->create([
                        'decorative_attribute_id' => $attr_data['attribute_id'],
                        'is_variation' => isset($attr_data['is_variation']) ? 1 : 0,
                        'display_order' => $attr_data['display_order'] ?? 0,
                    ]);

                    if (isset($attr_data['values']) && is_array($attr_data['values'])) {
                        foreach ($attr_data['values'] as $value_id) {
                            $prod_attr->values()->create([
                                'decorative_attribute_value_id' => $value_id
                            ]);
                        }
                    }
                }
            }
        }

        // 2. Save Variations
        // Get existing variations to retain images if not re-uploaded
        $existingVariations = $product->variations()->with('galleryImages')->get()->keyBy('id');
        $product->variations()->forceDelete();
        
        if ($request->has('variations')) {
            foreach ($request->variations as $index => $var_data) {
                // Variations can now be saved even without an SKU
                if (true) {
                    
                    // Handle variation images upload (1st file = primary variation image, rest = gallery)
                    $imagePath = null;
                    if (isset($var_data['existing_id']) && isset($existingVariations[$var_data['existing_id']])) {
                        $imagePath = $existingVariations[$var_data['existing_id']]->image;
                    }

                    $files = [];
                    if ($request->hasFile("variations.{$index}.gallery_images")) {
                        $uploadedFiles = $request->file("variations.{$index}.gallery_images");
                        if (is_array($uploadedFiles) && count($uploadedFiles) > 0) {
                            if (!$imagePath) {
                                $imagePath = $this->uploadImage($uploadedFiles[0], 'decorative_products');
                                $files = array_slice($uploadedFiles, 1);
                            } else {
                                $files = $uploadedFiles;
                            }
                        }
                    }

                    // Create variation
                    $variation = $product->variations()->create([
                        'sku' => $var_data['sku'] ?? null,
                        'image' => $imagePath,
                        'status' => $var_data['status'] ?? 'active',
                        'sort_order' => $var_data['sort_order'] ?? 0,
                    ]);

                    // Save re-attached existing variation gallery images
                    if (isset($var_data['existing_id']) && isset($existingVariations[$var_data['existing_id']])) {
                        $oldVariation = $existingVariations[$var_data['existing_id']];
                        $deletedIds = $request->input("delete_var_gallery.{$index}", []);
                        foreach ($oldVariation->galleryImages as $oldGalImg) {
                            if (!in_array($oldGalImg->id, $deletedIds)) {
                                $variation->galleryImages()->create([
                                    'image' => $oldGalImg->image,
                                    'sort_order' => $oldGalImg->sort_order
                                ]);
                            }
                        }
                    }

                    // Save newly uploaded variation gallery images
                    foreach ($files as $file) {
                        $path = $this->uploadImage($file, 'decorative_products');
                        $variation->galleryImages()->create(['image' => $path]);
                    }    if (isset($var_data['attributes']) && is_array($var_data['attributes'])) {
                        foreach ($var_data['attributes'] as $val_id) {
                            if (!empty($val_id)) {
                                $variation->attributeValues()->attach($val_id);
                            }
                        }
                    }

                    // Save variation spec sections
                    if (isset($var_data['spec_sections']) && is_array($var_data['spec_sections'])) {
                        foreach ($var_data['spec_sections'] as $sec_data) {
                            if (isset($sec_data['title']) && !empty($sec_data['title'])) {
                                $section = $variation->specificationSections()->create([
                                    'title'         => $sec_data['title'],
                                    'display_order' => $sec_data['display_order'] ?? 0,
                                ]);
                                if (isset($sec_data['specs']) && is_array($sec_data['specs'])) {
                                    foreach ($sec_data['specs'] as $spec_data) {
                                        if (isset($spec_data['label']) && !empty($spec_data['label'])) {
                                            $section->specifications()->create([
                                                'label'         => $spec_data['label'],
                                                'value'         => $spec_data['value'] ?? '',
                                                'display_order' => $spec_data['display_order'] ?? 0,
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // 3. Save Images
        if ($request->hasFile('primary_image')) {
            // Delete old primary
            $product->primaryImage()->delete();
            $path = $this->uploadImage($request->file('primary_image'), 'decorative_products');
            $product->images()->create(['image' => $path, 'type' => 'PRIMARY']);
        }

        if ($request->hasFile('light_on_image')) {
            // Delete old light on image
            $product->lightOnImage()->delete();
            $path = $this->uploadImage($request->file('light_on_image'), 'decorative_products');
            $product->images()->create(['image' => $path, 'type' => 'LIGHT_ON']);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $this->uploadImage($file, 'decorative_products');
                $product->images()->create(['image' => $path, 'type' => 'GALLERY']);
            }
        }
    }

    private function uploadImage($file, $path)
    {
        $name = time() . '-' . $file->getClientOriginalName();
        // Store in storage/app/public/decorative_products/
        $file->storeAs('public/' . $path, $name);
        return $name;
    }
}
