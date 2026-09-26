<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProductColor;
use Illuminate\Http\Request;

class DecorativeColorController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'color_master_id' => 'required|exists:color_masters,id',
        ]);

        $maxOrder = DecProductColor::where('product_id', $productId)->max('order') ?? 0;

        $color = DecProductColor::create([
            'product_id'      => $productId,
            'color_master_id' => $request->color_master_id,
            'order'           => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Color added successfully',
            'color' => $color->load('colorMaster')
        ]);
    }

    public function show($id)
    {
        $color = DecProductColor::with('colorMaster')->findOrFail($id);
        return response()->json([
            'success' => true,
            'color' => $color
        ]);
    }

    public function update(Request $request, $id)
    {
        $color = DecProductColor::findOrFail($id);

        $request->validate([
            'color_master_id' => 'required|exists:color_masters,id',
        ]);

        $color->update([
            'color_master_id' => $request->color_master_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Color updated successfully',
            'color' => $color->load('colorMaster')
        ]);
    }

    public function updateImages(Request $request, $id)
    {
        $color = DecProductColor::findOrFail($id);

        $request->validate([
            'main_image' => 'nullable|image|max:5120',
            'lighton_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = (string) \Illuminate\Support\Str::uuid() . '_main.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $filename);

            // Delete old
            if ($color->main_image && \Storage::exists('public/uploads/decorative/' . $color->main_image)) {
                \Storage::delete('public/uploads/decorative/' . $color->main_image);
            }
            $color->main_image = $filename;
        }

        if ($request->hasFile('lighton_image')) {
            $file = $request->file('lighton_image');
            $filename = (string) \Illuminate\Support\Str::uuid() . '_lighton.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads/decorative', $filename);

            // Delete old
            if ($color->lighton_image && \Storage::exists('public/uploads/decorative/' . $color->lighton_image)) {
                \Storage::delete('public/uploads/decorative/' . $color->lighton_image);
            }
            $color->lighton_image = $filename;
        }

        $color->save();

        return response()->json([
            'success' => true,
            'message' => 'Color images updated successfully',
            'color' => $color->load('colorMaster')
        ]);
    }

    public function destroy($id)
    {
        $color = DecProductColor::findOrFail($id);
        $color->delete();

        return response()->json([
            'success' => true,
            'message' => 'Color deleted successfully'
        ]);
    }

    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // Array of [id => sort_order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductColor::where('id', $id)->where('product_id', $productId)->update(['order' => $order]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Colors reordered successfully'
        ]);
    }
}
