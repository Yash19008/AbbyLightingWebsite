<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Decorative\DecProduct;
use App\Models\Decorative\DecProductGallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DecorativeGalleryController extends Controller
{
    public function store(Request $request, $productId)
    {
        $product = DecProduct::findOrFail($productId);

        if ($request->hasFile('new_images')) {
            $files = $request->file('new_images');
            $captions = $request->input('new_captions', []);
            $tempIds = $request->input('temp_ids', []);

            // Fetch max order once, increment in-loop (avoids N+1)
            $maxOrder = DecProductGallery::where('product_id', $productId)->max('order') ?? 0;

            $createdImages = [];
            foreach ($files as $index => $file) {
                if ($file->isValid()) {
                    $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/uploads/decorative_gallery', $filename);

                    $maxOrder++;
                    $gallery = DecProductGallery::create([
                        'product_id' => $productId,
                        'image'      => $filename,
                        'caption'    => $captions[$index] ?? null,
                        'order'      => $maxOrder,
                    ]);

                    $createdImages[] = [
                        'temp_id' => $tempIds[$index] ?? null,
                        'id'      => $gallery->id,
                        'url'     => asset('storage/uploads/decorative_gallery/' . $filename)
                    ];
                }
            }
            return response()->json(['success' => true, 'images' => $createdImages]);
        }

        return response()->json(['success' => false, 'message' => 'No files uploaded'], 400);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255'
        ]);

        $gallery = DecProductGallery::findOrFail($id);
        $gallery->update([
            'caption' => $request->caption
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Caption updated.'
        ]);
    }

    public function reorder(Request $request, $productId)
    {
        $orders = $request->orders; // associative array: [id => order]
        
        if (is_array($orders)) {
            foreach ($orders as $id => $order) {
                DecProductGallery::where('id', $id)
                    ->where('product_id', $productId)
                    ->update(['order' => $order]);
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        $gallery = DecProductGallery::findOrFail($id);
        
        $path = 'public/uploads/decorative_gallery/' . $gallery->image;
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }
}
