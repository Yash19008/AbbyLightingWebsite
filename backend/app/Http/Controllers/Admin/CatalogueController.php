<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use App\Helpers\Common_function;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $title = "Catalogues";
        $main_module = 'Catalogues';
        $tbl = Common_function::encrypt('catalogues');

        $query = Catalogue::with('category')->withCount('downloads')->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        if ($request->filled('category_id')) {
            $query->where('catalogue_category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $catalogues = $query->get();
        $categories = CatalogueCategory::active()->ordered()->get();

        return view('admin.catalogues.index', compact('title', 'main_module', 'catalogues', 'categories', 'tbl'));
    }

    public function add()
    {
        $title = "Add Catalogue";
        $main_module = 'Catalogues';
        $categories = CatalogueCategory::active()->ordered()->get();
        return view('admin.catalogues.add', compact('title', 'main_module', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'catalogue_category_id' => 'required|exists:catalogue_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:catalogues,slug',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'pdf_file' => 'required|mimes:pdf|max:102400', // max 100MB PDF
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $count = 1;
        while (Catalogue::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Upload Cover Image
        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $uploadDir = public_path('uploads/catalogues/images');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }
            $imgName = time() . '-' . uniqid() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->move($uploadDir, $imgName);
            $coverImagePath = $imgName;
        }

        // Upload PDF File
        $pdfFilePath = null;
        $fileSize = null;
        if ($request->hasFile('pdf_file')) {
            $uploadDir = public_path('uploads/catalogues/pdfs');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }
            $file = $request->file('pdf_file');
            $sizeInBytes = $file->getSize();
            $fileSize = $this->formatBytes($sizeInBytes);

            $pdfName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $pdfName);
            $pdfFilePath = $pdfName;
        }

        Catalogue::create([
            'catalogue_category_id' => $request->catalogue_category_id,
            'title' => $request->title,
            'slug' => $slug,
            'cover_image' => $coverImagePath,
            'pdf_file' => $pdfFilePath,
            'file_size' => $fileSize,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue uploaded successfully.');
    }

    public function edit($id)
    {
        $title = "Edit Catalogue";
        $main_module = 'Catalogues';
        $catalogue = Catalogue::findOrFail($id);
        $categories = CatalogueCategory::active()->ordered()->get();
        return view('admin.catalogues.edit', compact('title', 'main_module', 'catalogue', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $catalogue = Catalogue::findOrFail($id);

        $request->validate([
            'catalogue_category_id' => 'required|exists:catalogue_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:catalogues,slug,' . $id,
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'pdf_file' => 'nullable|mimes:pdf|max:102400',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->title);

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Catalogue::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Handle Cover Image
        $coverImagePath = $catalogue->cover_image;
        if ($request->hasFile('cover_image')) {
            $uploadDir = public_path('uploads/catalogues/images');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }

            if ($catalogue->cover_image && File::exists($uploadDir . '/' . $catalogue->cover_image)) {
                File::delete($uploadDir . '/' . $catalogue->cover_image);
            }

            $imgName = time() . '-' . uniqid() . '.' . $request->file('cover_image')->getClientOriginalExtension();
            $request->file('cover_image')->move($uploadDir, $imgName);
            $coverImagePath = $imgName;
        }

        // Handle PDF File
        $pdfFilePath = $catalogue->pdf_file;
        $fileSize = $catalogue->file_size;
        if ($request->hasFile('pdf_file')) {
            $uploadDir = public_path('uploads/catalogues/pdfs');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0777, true, true);
            }

            if ($catalogue->pdf_file && File::exists($uploadDir . '/' . $catalogue->pdf_file)) {
                File::delete($uploadDir . '/' . $catalogue->pdf_file);
            }

            $file = $request->file('pdf_file');
            $sizeInBytes = $file->getSize();
            $fileSize = $this->formatBytes($sizeInBytes);

            $pdfName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $pdfName);
            $pdfFilePath = $pdfName;
        }

        $catalogue->update([
            'catalogue_category_id' => $request->catalogue_category_id,
            'title' => $request->title,
            'slug' => $slug,
            'cover_image' => $coverImagePath,
            'pdf_file' => $pdfFilePath,
            'file_size' => $fileSize,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue updated successfully.');
    }

    public function duplicate($id)
    {
        $catalogue = Catalogue::findOrFail($id);

        $newCatalogue = $catalogue->replicate();
        
        $newCatalogue->title = $catalogue->title . ' (Copy)';
        
        $slug = Str::slug($newCatalogue->title);
        $originalSlug = $slug;
        $count = 1;
        while (Catalogue::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $newCatalogue->slug = $slug;

        // Clear images and PDFs per user request
        $newCatalogue->cover_image = null;
        $newCatalogue->pdf_file = null;
        $newCatalogue->file_size = null;

        $newCatalogue->status = 'inactive';
        $newCatalogue->save();

        return redirect()->route('admin.catalogues.edit', $newCatalogue->id)->with('success', 'Catalogue duplicated successfully as Inactive.');
    }

    public function destroy($id)
    {
        $catalogue = Catalogue::findOrFail($id);

        if ($catalogue->cover_image) {
            $imgPath = public_path('uploads/catalogues/images/' . $catalogue->cover_image);
            if (File::exists($imgPath)) {
                File::delete($imgPath);
            }
        }

        if ($catalogue->pdf_file) {
            $pdfPath = public_path('uploads/catalogues/pdfs/' . $catalogue->pdf_file);
            if (File::exists($pdfPath)) {
                File::delete($pdfPath);
            }
        }

        $catalogue->delete();

        return redirect()->route('admin.catalogues.index')->with('success', 'Catalogue deleted successfully.');
    }

    /**
     * Helper to format file bytes to human-readable string
     */
    private function formatBytes($bytes, $precision = 1)
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
