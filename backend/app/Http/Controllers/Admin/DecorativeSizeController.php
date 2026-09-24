<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Decorative\DecProductSize;
use Illuminate\Http\Request;

class DecorativeSizeController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $maxOrder = DecProductSize::where('product_id', $productId)->max('order') ?? 0;

        $size = DecProductSize::create([
            'product_id' => $productId,
            'label'      => $request->label,
            'order'      => $maxOrder + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Size added successfully',
            'size'    => $size
        ]);
    }

    public function show($id)
    {
        $size = DecProductSize::findOrFail($id);
        return response()->json([
            'success' => true,
            'size' => $size
        ]);
    }

    public function update(Request $request, $id)
    {
        $size = DecProductSize::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255',
        ]);

        $size->update([
            'label' => $request->label,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Size updated successfully',
            'size' => $size
        ]);
    }

    public function destroy($id)
    {
        $size = DecProductSize::findOrFail($id);
        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'Size deleted successfully'
        ]);
    }

    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // Array of [id => sort_order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductSize::where('id', $id)->where('product_id', $productId)->update(['order' => $order]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sizes reordered successfully'
        ]);
    }
}
