<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WatchAndShop;
use Illuminate\Http\Request;

class WatchAndShopApiController extends Controller
{
    public function index()
    {
        $items = WatchAndShop::active()
            ->orderBy('display_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function ($item) {
                $thumbnailUrl = $item->thumbnail;
                if ($thumbnailUrl && !str_starts_with($thumbnailUrl, 'http') && !str_starts_with($thumbnailUrl, '/images') && !str_starts_with($thumbnailUrl, 'images/')) {
                    $thumbnailUrl = asset('storage/' . $item->thumbnail);
                }

                $videoUrl = $item->video_url;
                if ($item->video_type === 'upload' && $videoUrl && !str_starts_with($videoUrl, 'http')) {
                    $videoUrl = asset('storage/' . $item->video_url);
                }

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'thumbnail' => $thumbnailUrl,
                    'video_type' => $item->video_type,
                    'video_url' => $videoUrl,
                    'product_name' => $item->product_name,
                    'product_link' => $item->product_link,
                    'display_order' => $item->display_order,
                ];
            })
        ]);
    }
}
