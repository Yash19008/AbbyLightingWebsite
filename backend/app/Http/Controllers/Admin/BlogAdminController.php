<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use App\Helpers\Common_function;

class BlogAdminController extends Controller
{
    public function index()
    {
        $title = "Blog Articles";
        $main_module = 'Blogs';
        $tbl = Common_function::encrypt('blogs');
        $blogs = Blog::with('category')->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.blogs.index', compact('title', 'main_module', 'blogs', 'tbl'));
    }

    public function add()
    {
        $title = "Add Blog Article";
        $main_module = 'Blogs';
        $categories = BlogCategory::where('status', 'active')->orderBy('name', 'asc')->get();
        return view('admin.blogs.add', compact('title', 'main_module', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'category_id' => 'nullable|exists:blog_categories,id',
            'dek' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
            'read_time' => 'nullable|string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'featured_image_caption' => 'nullable|string|max:255',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'secondary_image_caption' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'pull_quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:150',
            'status' => 'nullable|in:published,draft,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $uploadDir = public_path('uploads/blogs');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }

        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $name = time() . '-featured-' . uniqid() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move($uploadDir, $name);
            $featuredImagePath = $name;
        }

        $secondaryImagePath = null;
        if ($request->hasFile('secondary_image')) {
            $name = time() . '-sec-' . uniqid() . '.' . $request->file('secondary_image')->getClientOriginalExtension();
            $request->file('secondary_image')->move($uploadDir, $name);
            $secondaryImagePath = $name;
        }

        Blog::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'dek' => $request->dek,
            'author' => $request->author ?: 'Abby Studio',
            'published_at' => $request->published_at ?: now()->toDateString(),
            'read_time' => $request->read_time,
            'featured_image' => $featuredImagePath,
            'featured_image_caption' => $request->featured_image_caption,
            'secondary_image' => $secondaryImagePath,
            'secondary_image_caption' => $request->secondary_image_caption,
            'content' => $request->content,
            'table_of_contents' => null,
            'pull_quote' => $request->pull_quote,
            'quote_author' => $request->quote_author,
            'status' => $request->status ?? 'published',
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article added successfully.');
    }

    public function edit($id)
    {
        $title = "Edit Blog Article";
        $main_module = 'Blogs';
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::where('status', 'active')->orderBy('name', 'asc')->get();
        return view('admin.blogs.edit', compact('title', 'main_module', 'blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $id,
            'category_id' => 'nullable|exists:blog_categories,id',
            'dek' => 'nullable|string',
            'author' => 'nullable|string|max:100',
            'published_at' => 'nullable|date',
            'read_time' => 'nullable|string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'featured_image_caption' => 'nullable|string|max:255',
            'secondary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'secondary_image_caption' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'pull_quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:150',
            'status' => 'nullable|in:published,draft,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $uploadDir = public_path('uploads/blogs');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }

        $featuredImagePath = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && File::exists($uploadDir . '/' . $blog->featured_image)) {
                File::delete($uploadDir . '/' . $blog->featured_image);
            }
            $name = time() . '-featured-' . uniqid() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move($uploadDir, $name);
            $featuredImagePath = $name;
        }

        $secondaryImagePath = $blog->secondary_image;
        if ($request->hasFile('secondary_image')) {
            if ($blog->secondary_image && File::exists($uploadDir . '/' . $blog->secondary_image)) {
                File::delete($uploadDir . '/' . $blog->secondary_image);
            }
            $name = time() . '-sec-' . uniqid() . '.' . $request->file('secondary_image')->getClientOriginalExtension();
            $request->file('secondary_image')->move($uploadDir, $name);
            $secondaryImagePath = $name;
        }

        $blog->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'dek' => $request->dek,
            'author' => $request->author ?: 'Abby Studio',
            'published_at' => $request->published_at ?: ($blog->published_at ?: now()->toDateString()),
            'read_time' => $request->read_time,
            'featured_image' => $featuredImagePath,
            'featured_image_caption' => $request->featured_image_caption,
            'secondary_image' => $secondaryImagePath,
            'secondary_image_caption' => $request->secondary_image_caption,
            'content' => $request->content,
            'table_of_contents' => null,
            'pull_quote' => $request->pull_quote,
            'quote_author' => $request->quote_author,
            'status' => $request->status ?? 'published',
            'is_featured' => $request->has('is_featured'),
            'sort_order' => $request->sort_order ?? 0,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article updated successfully.');
    }

    public function duplicate($id)
    {
        $blog = Blog::findOrFail($id);

        $newBlog = $blog->replicate();
        
        $newBlog->title = $blog->title . ' (Copy)';
        
        $slug = Str::slug($newBlog->title);
        $originalSlug = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $newBlog->slug = $slug;

        $newBlog->featured_image = null;
        $newBlog->secondary_image = null;

        $newBlog->status = 'draft';
        $newBlog->save();

        return redirect()->route('admin.blogs.edit', $newBlog->id)->with('success', 'Blog article duplicated successfully as Draft.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $uploadDir = public_path('uploads/blogs');

        if ($blog->featured_image && File::exists($uploadDir . '/' . $blog->featured_image)) {
            File::delete($uploadDir . '/' . $blog->featured_image);
        }
        if ($blog->secondary_image && File::exists($uploadDir . '/' . $blog->secondary_image)) {
            File::delete($uploadDir . '/' . $blog->secondary_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        $file = $request->file('upload') ?: $request->file('file');

        if ($file) {
            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            $uploadDir = public_path('uploads/blogs/content');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }

            $file->move($uploadDir, $fileName);

            // Use a relative path so the URL isn't tied to any specific APP_URL / host.
            // The frontend will prepend its own API base URL when rendering the HTML content.
            $relativePath = '/uploads/blogs/content/' . $fileName;

            if ($request->filled('CKEditorFuncNum')) {
                $absoluteUrl = asset('uploads/blogs/content/' . $fileName);
                $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$absoluteUrl', 'Image uploaded successfully');</script>";
                return response($response)->header('Content-Type', 'text/html; charset=utf-8');
            }

            return response()->json([
                'uploaded' => 1,
                'location' => $relativePath,
                'url' => $relativePath,
                'fileName' => $fileName,
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'No image file was received.'],
        ], 400);
    }
}
