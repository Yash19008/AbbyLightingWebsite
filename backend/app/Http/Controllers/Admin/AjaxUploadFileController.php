<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxUploadFileController extends Controller
{
    public function index(Request $request)
    {
        $fileNames = [];
        if ($request->has('uploadedImages') && $request->uploadedImages !== null && $request->uploadedImages !== 'null') {
            $uploadedImages = $request->uploadedImages;
            foreach ($uploadedImages as $uploadedImage) {
                $fileNamePhoto = time() . '_' . trim($uploadedImage->getClientOriginalName());
                $uploadedImage->storeAs($request->path, $fileNamePhoto, 'public');
                $fileNames[] = $fileNamePhoto;
            }
        }
        return response()->json(['fileNames' => $fileNames]);
    }
}
