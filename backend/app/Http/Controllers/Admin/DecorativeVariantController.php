<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProductVariant;
use Illuminate\Http\Request;

class DecorativeVariantController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'color_master_id' => 'nullable|exists:color_masters,id',
            'status' => 'required|in:active,inactive',
        ]);

        $maxOrder = DecProductVariant::where('product_id', $productId)->max('order') ?? 0;

        $variant = DecProductVariant::create([
            'product_id'      => $productId,
            'name'            => $request->name,
            'sku'             => $request->sku,
            'size'            => $request->size,
            'color_master_id' => $request->color_master_id,
            'status'          => $request->status,
            'order'           => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Variant added successfully',
            'variant' => $variant->load('colorMaster')
        ]);
    }

    public function show($id)
    {
        $variant = DecProductVariant::with('colorMaster')->findOrFail($id);
        return response()->json([
            'success' => true,
            'variant' => $variant
        ]);
    }

    public function update(Request $request, $id)
    {
        $variant = DecProductVariant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:100',
            'color_master_id' => 'nullable|exists:color_masters,id',
            'status' => 'required|in:active,inactive',
        ]);

        $variant->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'size' => $request->size,
            'color_master_id' => $request->color_master_id,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully',
            'variant' => $variant->load('colorMaster')
        ]);
    }

    public function updateImages(Request $request, $id)
    {
        $variant = DecProductVariant::findOrFail($id);

        $request->validate([
            'main_image' => 'nullable|image|max:5120',
            'lighton_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = (string) \Illuminate\Support\Str::uuid() . '_main.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $filename);

            // Delete old
            if ($variant->main_image && \Storage::exists('public/uploads/decorative/' . $variant->main_image)) {
                \Storage::delete('public/uploads/decorative/' . $variant->main_image);
            }
            $variant->main_image = $filename;
        }

        if ($request->hasFile('lighton_image')) {
            $file = $request->file('lighton_image');
            $filename = (string) \Illuminate\Support\Str::uuid() . '_lighton.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $filename);

            // Delete old
            if ($variant->lighton_image && \Storage::exists('public/uploads/decorative/' . $variant->lighton_image)) {
                \Storage::delete('public/uploads/decorative/' . $variant->lighton_image);
            }
            $variant->lighton_image = $filename;
        }

        $variant->save();

        return response()->json([
            'success' => true,
            'message' => 'Variant images updated successfully',
            'variant' => $variant->load('colorMaster')
        ]);
    }

    public function destroy($id)
    {
        $variant = DecProductVariant::findOrFail($id);
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully'
        ]);
    }

    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // Array of [id => sort_order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductVariant::where('id', $id)->where('product_id', $productId)->update(['order' => $order]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Variants reordered successfully'
        ]);
    }
}
