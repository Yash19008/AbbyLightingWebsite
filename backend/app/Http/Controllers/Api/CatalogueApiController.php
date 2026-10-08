<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CatalogueCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            Log::error('CatalogueApiController::categories — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch catalogue categories',
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

            // Order by sort parameter
            $sort = $request->get('sort', 'popular');
            if ($sort === 'az') {
                $query->orderBy('title', 'asc');
            } elseif ($sort === 'za') {
                $query->orderBy('title', 'desc');
            } elseif ($sort === 'new') {
                $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            } else {
                $query->orderBy('is_featured', 'desc')->orderBy('sort_order', 'asc')->orderBy('id', 'desc');
            }

            if ($request->filled('page') || $request->filled('per_page')) {
                $perPage = min((int) $request->get('per_page', 6), 50);
                $paginated = $query->paginate($perPage);
                $catalogues = collect($paginated->items());

                $formatted = $catalogues->map(function ($item) {
                    return $this->formatCatalogue($item);
                });

                return response()->json([
                    'success' => true,
                    'data' => $formatted,
                    'pagination' => [
                        'current_page' => $paginated->currentPage(),
                        'last_page' => $paginated->lastPage(),
                        'per_page' => $paginated->perPage(),
                        'total' => $paginated->total(),
                        'has_more' => $paginated->hasMorePages(),
                    ],
                ]);
            }

            $catalogues = $query->get();

            $formatted = $catalogues->map(function ($item) {
                return $this->formatCatalogue($item);
            });

            return response()->json([
                'success' => true,
                'data' => $formatted,
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $catalogues->count(),
                    'total' => $catalogues->count(),
                    'has_more' => false,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('CatalogueApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch catalogues',
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
                'data' => $this->formatCatalogue($catalogue),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catalogue not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('CatalogueApiController::show — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error',
            ], 500);
        }
    }

    public function storeDownloadLead(Request $request)
    {
        try {
            $validated = $request->validate([
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

            $mobile = $validated['phone'] ?? $validated['mobile'] ?? null;
            
            $lead = \App\Models\CatalogDownload::create([
                'catalogue_name' => $validated['catalogue_name'] ?? null,
                'catalogue_id' => $validated['catalogue_id'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $mobile,
                'city' => $validated['city'] ?? null,
                'company' => $validated['company'] ?? null,
                'role' => $validated['role'] ?? null,
                'message' => $validated['message'] ?? null,
                'is_active' => 'yes',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted successfully.',
                'data' => [
                    'id' => $lead->id
                ],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed: ' . collect($e->errors())->first()[0],
            ], 422);
        } catch (\Exception $e) {
            Log::error('CatalogueApiController::storeDownloadLead — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save download record.',
            ], 500);
        }
    }

    public function downloadPdf($id)
    {
        try {
            $catalogue = Catalogue::findOrFail($id);
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
                $filename = \Illuminate\Support\Str::slug($catalogue->title) . '-catalogue.pdf';
                return response()->download($fallbackPath, $filename, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]);
            }

            return response()->json(['success' => false, 'message' => 'PDF file not found'], 404);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Catalogue not found'], 404);
        } catch (\Exception $e) {
            Log::error('CatalogueApiController::downloadPdf — ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    private function formatCatalogue(Catalogue $item): array
    {
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
    }
}


