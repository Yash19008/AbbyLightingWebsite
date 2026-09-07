<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsItemController extends Controller
{
    public function index()
    {
        $newsItems = NewsItem::orderBy('created_at', 'desc')->get();
        $main_module = 'News';
        
        return view('admin.news-items.index', compact('newsItems', 'main_module'));
    }

    public function add()
    {
        $main_module = 'News';
        return view('admin.news-items.add', compact('main_module'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $newsItem = new NewsItem();
        $newsItem->title = $validated['title'];
        $newsItem->subtitle = $validated['subtitle'] ?? null;
        $newsItem->link = $validated['link'] ?? null;
        $newsItem->is_active = $request->has('is_active');
        $newsItem->created_by = Auth::id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/news', 'public');
            $newsItem->image = $imagePath;
        }

        $newsItem->save();

        return redirect()->route('admin.news-items.index')->with('success', 'News item created successfully');
    }

    public function edit($id)
    {
        $newsItem = NewsItem::findOrFail($id);
        $main_module = 'News';
        
        return view('admin.news-items.edit', compact('newsItem', 'main_module'));
    }

    public function update(Request $request, $id)
    {
        $newsItem = NewsItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $newsItem->title = $validated['title'];
        $newsItem->subtitle = $validated['subtitle'] ?? null;
        $newsItem->link = $validated['link'] ?? null;
        $newsItem->is_active = $request->has('is_active');
        $newsItem->updated_by = Auth::id();

        if ($request->hasFile('image')) {
            if ($newsItem->image && Storage::disk('public')->exists($newsItem->image)) {
                Storage::disk('public')->delete($newsItem->image);
            }
            $imagePath = $request->file('image')->store('uploads/news', 'public');
            $newsItem->image = $imagePath;
        }

        $newsItem->save();

        return redirect()->route('admin.news-items.index')->with('success', 'News item updated successfully');
    }

    public function delete($id)
    {
        $newsItem = NewsItem::findOrFail($id);
        
        if ($newsItem->image && Storage::disk('public')->exists($newsItem->image)) {
            Storage::disk('public')->delete($newsItem->image);
        }
        
        $newsItem->delete();

        return redirect()->route('admin.news-items.index')->with('success', 'News item deleted successfully');
    }
}
