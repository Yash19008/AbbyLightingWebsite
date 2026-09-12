<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use Illuminate\Http\Request;

class CatalogueApiController extends Controller
{
    public function categories()
    {
        try {
            $categories = CatalogueCategory::active()
                ->ordered()
                ->whereHas('catalogues', function ($q) {
                    $q->active();
                })
                ->withCount(['catalogues' => function ($q) {
                    $q->active();
                }])
                ->get()
                ->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'description' => $cat->description,
                        'image_url' => $cat->image ? asset('uploads/catalogue_categories/' . $cat->image) : null,
                        'catalogues_count' => $cat->catalogues_count,
                        'sort_order' => $cat->sort_order,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Catalogue::with('category')->active();

            // Filter by category slug or ID
            if ($request->filled('category') && $request->category !== 'all') {
                $category = $request->category;
                $query->whereHas('category', function ($q) use ($category) {
                    if (is_numeric($category)) {
                        $q->where('id', $category);
                    } else {
                        $q->where('slug', $category);
                    }
                });
            }

            // Filter by featured
            if ($request->has('featured') && ($request->featured === '1' || $request->featured === 'true')) {
                $query->featured();
            }

            // Search query
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Order by sort_order asc, then id desc
            $catalogues = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

            $formatted = $catalogues->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'description' => $item->description,
                    'category_id' => $item->catalogue_category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'name' => $item->category->name,
                        'slug' => $item->category->slug,
                    ] : null,
                    'cover_image' => $item->cover_image ? asset('uploads/catalogues/images/' . $item->cover_image) : null,
                    'pdf_url' => $item->pdf_file ? asset('uploads/catalogues/pdfs/' . $item->pdf_file) : asset('1product-catalog.pdf'),
                    'download_url' => url('/api/catalogues/' . $item->id . '/download-pdf'),
                    'pdf_file_name' => $item->pdf_file ?: '1product-catalog.pdf',
                    'file_size' => $item->file_size ?? 'PDF',
                    'is_featured' => (bool)$item->is_featured,
                    'sort_order' => $item->sort_order,
                    'created_at' => $item->created_at ? $item->created_at->toISOString() : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($slug)
    {
        try {
            $catalogue = Catalogue::with('category')
                ->where('slug', $slug)
                ->active()
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $catalogue->id,
                    'title' => $catalogue->title,
                    'slug' => $catalogue->slug,
                    'description' => $catalogue->description,
                    'category' => $catalogue->category ? [
                        'id' => $catalogue->category->id,
                        'name' => $catalogue->category->name,
                        'slug' => $catalogue->category->slug,
                    ] : null,
                    'cover_image' => $catalogue->cover_image ? asset('uploads/catalogues/images/' . $catalogue->cover_image) : null,
                    'pdf_url' => $catalogue->pdf_file ? asset('uploads/catalogues/pdfs/' . $catalogue->pdf_file) : asset('1product-catalog.pdf'),
                    'download_url' => url('/api/catalogues/' . $catalogue->id . '/download-pdf'),
                    'pdf_file_name' => $catalogue->pdf_file ?: '1product-catalog.pdf',
                    'file_size' => $catalogue->file_size,
                    'is_featured' => (bool)$catalogue->is_featured,
                    'sort_order' => $catalogue->sort_order,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catalogue not found',
            ], 404);
        }
    }

    public function storeDownloadLead(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'catalogue_name' => 'nullable|string|max:255',
            'catalogue_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed: ' . $validator->errors()->first(),
            ], 422);
        }

        try {
            $mobile = $request->input('phone') ?? $request->input('mobile');
            $lead = \App\Models\CatalogDownload::create([
                'catalogue_name' => $request->catalogue_name,
                'catalogue_id' => $request->catalogue_id,
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $mobile,
                'city' => $request->city,
                'company' => $request->company,
                'role' => $request->role,
                'message' => $request->message,
                'is_active' => 'yes',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted successfully.',
                'data' => $lead,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save download record: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadPdf($id)
    {
        $catalogue = Catalogue::find($id);
        if ($catalogue && $catalogue->pdf_file) {
            $path = public_path('uploads/catalogues/pdfs/' . $catalogue->pdf_file);
            if (file_exists($path)) {
                $cleanTitle = \Illuminate\Support\Str::slug($catalogue->title) ?: 'catalogue';
                return response()->download($path, $cleanTitle . '.pdf', [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $cleanTitle . '.pdf"',
                ]);
            }
        }

        $fallbackPath = public_path('1product-catalog.pdf');
        if (file_exists($fallbackPath)) {
            $filename = ($catalogue ? \Illuminate\Support\Str::slug($catalogue->title) : 'abby-lighting') . '-catalogue.pdf';
            return response()->download($fallbackPath, $filename, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        }

        return response()->json(['message' => 'PDF file not found'], 404);
    }
}


