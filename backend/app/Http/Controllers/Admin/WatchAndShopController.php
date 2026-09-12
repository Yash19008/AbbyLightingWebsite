<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WatchAndShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WatchAndShopController extends Controller
{
    protected $main_module;

    public function __construct()
    {
        $this->main_module = 'Watch & Shop';
    }

    public function index(Request $request)
    {
        $title = "Watch & Shop (Reels & Videos)";
        $main_module = $this->main_module;
        
        $query = WatchAndShop::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('product_name', 'LIKE', "%{$search}%");
        }
        
        $items = $query->orderBy('display_order', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.watch_and_shops.index', compact('items', 'title', 'main_module'));
    }

    public function add()
    {
        $title = "Add Watch & Shop Video";
        $main_module = $this->main_module;
        $item = new WatchAndShop();

        return view('admin.watch_and_shops.edit', [
            'title' => $title,
            'main_module' => $main_module,
            'item' => $item,
            'method' => 'Add',
            'action' => route('admin.watch_and_shops.store'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'video_type' => 'required|in:upload,url,instagram,youtube',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg,mkv|max:102400',
            'video_url' => 'nullable|string|max:1000',
            'product_name' => 'nullable|string|max:255',
            'product_link' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer',
        ]);

        $videoUrl = $request->video_url;
        if ($request->video_type === 'upload') {
            if (!$request->hasFile('video_file')) {
                return back()->withInput()->with('error', 'Please upload a video file when Video Type is set to Direct Upload.');
            }
            $videoUrl = $request->file('video_file')->store('uploads/watch_and_shop/videos', 'public');
        } elseif (empty($videoUrl)) {
            return back()->withInput()->with('error', 'Please provide a Video / Reel URL.');
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('uploads/watch_and_shop/thumbnails', 'public');
        }

        WatchAndShop::create([
            'title' => $request->title,
            'thumbnail' => $thumbnailPath,
            'video_type' => $request->video_type,
            'video_url' => $videoUrl,
            'product_name' => $request->product_name,
            'product_link' => $request->product_link,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.watch_and_shops.index')->with('success', 'Watch & Shop item created successfully.');
    }

    public function edit($id)
    {
        $item = WatchAndShop::findOrFail($id);
        $title = "Edit Watch & Shop Video";
        $main_module = $this->main_module;

        return view('admin.watch_and_shops.edit', [
            'title' => $title,
            'main_module' => $main_module,
            'item' => $item,
            'method' => 'Edit',
            'action' => route('admin.watch_and_shops.update', $id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = WatchAndShop::findOrFail($id);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'video_type' => 'required|in:upload,url,instagram,youtube',
            'video_file' => 'nullable|file|mimes:mp4,webm,mov,ogg,mkv|max:102400',
            'video_url' => 'nullable|string|max:1000',
            'product_name' => 'nullable|string|max:255',
            'product_link' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer',
        ]);

        $videoUrl = $item->video_url;

        if ($request->video_type === 'upload') {
            if ($request->hasFile('video_file')) {
                // Delete old uploaded video if it was stored on public disk
                if ($item->video_type === 'upload' && $item->video_url && Storage::disk('public')->exists($item->video_url)) {
                    Storage::disk('public')->delete($item->video_url);
                }
                $videoUrl = $request->file('video_file')->store('uploads/watch_and_shop/videos', 'public');
            }
        } else {
            $videoUrl = $request->video_url;
        }

        $thumbnailPath = $item->thumbnail;
        if ($request->input('remove_thumbnail') === '1') {
            if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                Storage::disk('public')->delete($item->thumbnail);
            }
            $thumbnailPath = null;
        } elseif ($request->hasFile('thumbnail')) {
            if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                Storage::disk('public')->delete($item->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('uploads/watch_and_shop/thumbnails', 'public');
        }

        $item->update([
            'title' => $request->title,
            'thumbnail' => $thumbnailPath,
            'video_type' => $request->video_type,
            'video_url' => $videoUrl,
            'product_name' => $request->product_name,
            'product_link' => $request->product_link,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.watch_and_shops.index')->with('success', 'Watch & Shop item updated successfully.');
    }

    public function delete($id)
    {
        $item = WatchAndShop::findOrFail($id);

        if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
            Storage::disk('public')->delete($item->thumbnail);
        }

        if ($item->video_type === 'upload' && $item->video_url && Storage::disk('public')->exists($item->video_url)) {
            Storage::disk('public')->delete($item->video_url);
        }

        $item->delete();

        return redirect()->route('admin.watch_and_shops.index')->with('success', 'Watch & Shop item deleted successfully.');
    }
}
