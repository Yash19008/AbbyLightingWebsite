<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;

class NewsItemApiController extends Controller
{
    public function index()
    {
        $newsItems = NewsItem::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $newsItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'link' => $item->link,
                ];
            })
        ]);
    }
}
