<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decorative\DecProduct;
use App\Models\Decorative\DecProductRelated;

class DecorativeRelatedController extends Controller
{
    // Toggle the family section visibility
    public function toggleFamily(Request $request, $productId)
    {
        $product = DecProduct::findOrFail($productId);
        $product->update(['show_family_section' => $request->show_family_section ? 1 : 0]);
        return response()->json(['success' => true]);
    }

    // Get lists for a product
    public function index($productId)
    {
        $product = DecProduct::findOrFail($productId);
        
        $related = DecProductRelated::with('relatedProduct')
            ->where('product_id', $productId)
            ->orderBy('order')
            ->get();

        $manual = $related->where('type', 'manual')->values();

        return response()->json([
            'success' => true,
            'show_family_section' => $product->show_family_section,
            'manual' => $manual,
        ]);
    }

    // Search for products to attach
    public function search(Request $request)
    {
        $term = $request->q;
        $excludeId = $request->exclude_id;

        $products = DecProduct::with(['variants' => function($q) {
                // Only fetch first variant for SKU display — avoid loading all variant data
                $q->select(['id', 'product_id', 'sku', 'order'])->orderBy('order')->limit(1);
            }])
            ->where('id', '!=', $excludeId)
            ->where(function($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhereHas('variants', function($q2) use ($term) {
                      $q2->where('sku', 'LIKE', "%{$term}%");
                  });
            })
            ->select(['id', 'name', 'featured_image'])
            ->limit(10)
            ->get();

        $results = $products->map(function($p) {
            $mainImage = $p->featured_image ? asset('storage/uploads/decorative/'.$p->featured_image) : null;
            $sku = $p->variants->first()?->sku ?? '';
            return [
                'id'    => $p->id,
                'name'  => $p->name,
                'sku'   => $sku,
                'image' => $mainImage
            ];
        });

        return response()->json(['results' => $results]);
    }

    // Attach a product
    public function attach(Request $request, $productId)
    {
        $request->validate([
            'related_product_id' => 'required|exists:dec_products,id',
            'type'               => 'required|in:manual'
        ]);

        $exists = DecProductRelated::where('product_id', $productId)
            ->where('related_product_id', $request->related_product_id)
            ->where('type', $request->type)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Product already added to this list.']);
        }

        $maxOrder = DecProductRelated::where('product_id', $productId)
            ->where('type', $request->type)
            ->max('order');

        $rel = DecProductRelated::create([
            'product_id' => $productId,
            'related_product_id' => $request->related_product_id,
            'type' => $request->type,
            'order' => $maxOrder + 1
        ]);

        $rel->load('relatedProduct');

        return response()->json([
            'success' => true, 
            'item' => $rel,
            'message' => 'Product added successfully.'
        ]);
    }

    // Remove a relation
    public function detach($id)
    {
        $rel = DecProductRelated::findOrFail($id);
        $rel->delete();
        return response()->json(['success' => true]);
    }

    // Reorder
    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // [id => order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductRelated::where('id', $id)
                    ->where('product_id', $productId)
                    ->update(['order' => $order]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}
