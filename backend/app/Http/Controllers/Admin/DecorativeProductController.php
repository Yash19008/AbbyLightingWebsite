<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProduct;
use App\Models\Collection;
use App\Models\Decorative\DecCategory;
use App\Models\ColorMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DecorativeProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DecProduct::query();
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('order', 'asc')->paginate(20);
        return view('admin.decorative.index', compact('products'));
    }

    public function create()
    {
        $collections = Collection::orderBy('order', 'asc')->get();
        $categories = DecCategory::orderBy('name', 'asc')->get();
        return view('admin.decorative.edit', compact('collections', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dec_products,slug',
        ]);

        $product = DecProduct::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'collection_id' => $request->collection_id,
            'category_id' => $request->category_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 'draft',
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $imageName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $imageName);
            $product->update(['featured_image' => $imageName]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'redirect' => route('decorative_product_admin.edit', $product->id)
        ]);
    }

    public function edit($id)
    {
        $product = DecProduct::with([
            'variants' => function($q) {
                $q->orderBy('order', 'asc');
            },
            'variants.colorMaster',
            'variants.specRows',  // Eager-load spec rows to avoid N+1 in blade
        ])->findOrFail($id);

        $collections  = Collection::orderBy('order', 'asc')->get();
        $categories   = DecCategory::orderBy('name', 'asc')->get();
        $color_masters = ColorMaster::orderBy('name', 'asc')->get();

        return view('admin.decorative.edit', compact('product', 'collections', 'categories', 'color_masters'));
    }

    public function update(Request $request, $id)
    {
        $product = DecProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dec_products,slug,'.$id,
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'collection_id' => $request->collection_id,
            'category_id' => $request->category_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 'draft',
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $imageName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $imageName);
            $product->update(['featured_image' => $imageName]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product basic information updated successfully'
        ]);
    }

    public function updateSeo(Request $request, $id)
    {
        $product = DecProduct::findOrFail($id);

        $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:500',
            'status'           => 'required|in:draft,published,archived',
        ]);

        $product->update([
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords'    => $request->meta_keywords,
            'status'           => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SEO Settings updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $product = DecProduct::findOrFail($id);
        $product->delete(); // Soft delete

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function duplicate($id)
    {
        $original = DecProduct::with(['variants', 'variants.specRows'])->findOrFail($id);

        $newProduct = $original->replicate();
        $newProduct->name = $original->name . ' (Copy)';
        $newProduct->slug = Str::slug($newProduct->name) . '-' . time();
        $newProduct->status = 'draft';
        $newProduct->installation_guide = null;
        $newProduct->care_instructions = null;
        $newProduct->featured_image = null;
        $newProduct->save();

        foreach ($original->variants as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->product_id = $newProduct->id;
            $newVariant->main_image = null;
            $newVariant->lighton_image = null;
            $newVariant->save();

            foreach ($variant->specRows as $spec) {
                $newSpec = $spec->replicate();
                $newSpec->variant_id = $newVariant->id;
                $newSpec->save();
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Product duplicated successfully.',
            'redirect' => route('decorative_product_admin.edit', $newProduct->id)
        ]);
    }

    public function uploadDownload(Request $request, $productId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'type' => 'required|in:installation_guide,care_instructions'
        ]);

        $product = DecProduct::findOrFail($productId);
        $type = $request->type;
        $file = $request->file('file');

        // Safe UUID-based filename — no original client name used
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/uploads/decorative/downloads', $filename);

        // Delete old file if exists
        if ($product->$type) {
            \Illuminate\Support\Facades\Storage::delete('public/uploads/decorative/downloads/' . $product->$type);
        }

        $product->update([$type => $filename]);

        return response()->json([
            'success'  => true,
            'filename' => $filename
        ]);
    }

    public function deleteDownload(Request $request, $productId)
    {
        $request->validate(['type' => 'required|in:installation_guide,care_instructions']);

        $product = DecProduct::findOrFail($productId);
        $type = $request->type;

        if ($product->$type) {
            \Illuminate\Support\Facades\Storage::delete('public/uploads/decorative/downloads/' . $product->$type);
            $product->update([$type => null]);
        }

        return response()->json(['success' => true]);
    }
}
