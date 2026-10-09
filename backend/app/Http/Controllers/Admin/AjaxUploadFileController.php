<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxUploadFileController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'uploadedImages' => 'nullable|array',
            'uploadedImages.*' => 'file|mimes:jpeg,png,jpg,webp,gif,svg,pdf|max:10240',
        ]);

        $fileNames = [];
        if ($request->has('uploadedImages') && $request->uploadedImages !== null && $request->uploadedImages !== 'null') {
            $uploadedImages = $request->uploadedImages;
            foreach ($uploadedImages as $uploadedImage) {
                $fileNamePhoto = time() . '_' . str_replace(' ', '_', trim($uploadedImage->getClientOriginalName()));
                $uploadedImage->storeAs($request->path, $fileNamePhoto, 'public');
                $fileNames[] = $fileNamePhoto;
            }
        }
        return response()->json(['fileNames' => $fileNames]);
    }
}
