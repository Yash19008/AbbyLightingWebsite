<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectApiController extends Controller
{
    /**
     * Get latest projects (eager-loads first image to avoid N+1)
     */
    public function index(Request $request)
    {
        try {
            $limit = min((int) $request->query('limit', 6), 100);

            $query = Project::where('is_active', 'yes')
                ->with(['projectImages' => function ($q) {
                    $q->where('is_active', 'yes')->orderBy('id', 'ASC');
                }]);

            if ($request->has('featured') && in_array($request->query('featured'), ['1', 'true', 1, true], true)) {
                $query->where('is_featured', 1);
            }

            $projects = $query->orderBy('sequence', 'ASC')
                ->orderBy('id', 'DESC')
                ->limit($limit)
                ->get();

            $projects = $projects->map(function ($project) {
                $firstImage = $project->projectImages->first();

                $imageUrl = null;
                if ($firstImage && $firstImage->image) {
                    if (strpos($firstImage->image, 'uploads/') === 0) {
                        $imageUrl = asset('storage/' . $firstImage->image);
                    } else {
                        $imageUrl = asset('storage/uploads/projects/' . $firstImage->image);
                    }
                }

                return [
                    'id'          => $project->id,
                    'name'        => $project->name,
                    'type'        => $project->type,
                    'location'    => $project->location,
                    'description' => $project->description,
                    'slug'        => $project->slug,
                    'sequence'    => $project->sequence,
                    'is_featured' => (bool) $project->is_featured,
                    'image_url'   => $imageUrl,
                    'created_at'  => $project->created_at,
                    'updated_at'  => $project->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $projects,
            ], 200);
        } catch (\Exception $e) {
            Log::error('ProjectApiController::index — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch projects',
            ], 500);
        }
    }

    /**
     * Get single project by ID
     */
    public function show($id)
    {
        try {
            $project = Project::where('is_active', 'yes')
                ->with(['projectImages' => function ($q) {
                    $q->where('is_active', 'yes')->orderBy('id', 'ASC');
                }])
                ->findOrFail($id);

            $images = $project->projectImages->map(function ($image) {
                return [
                    'id'        => $image->id,
                    'image_url' => $image->image ? asset('storage/' . $image->image) : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'          => $project->id,
                    'name'        => $project->name,
                    'type'        => $project->type,
                    'location'    => $project->location,
                    'description' => $project->description,
                    'slug'        => $project->slug,
                    'sequence'    => $project->sequence,
                    'images'      => $images,
                    'created_at'  => $project->created_at,
                    'updated_at'  => $project->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('ProjectApiController::show — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }
    }

    /**
     * Get single project by slug
     */
    public function showBySlug($slug)
    {
        try {
            $project = Project::where('is_active', 'yes')
                ->where('slug', $slug)
                ->with(['projectImages' => function ($q) {
                    $q->where('is_active', 'yes')->orderBy('id', 'ASC');
                }])
                ->firstOrFail();

            $images = $project->projectImages->map(function ($image) {
                $imageUrl = null;
                if ($image->image) {
                    if (strpos($image->image, 'uploads/') === 0) {
                        $imageUrl = asset('storage/' . $image->image);
                    } else {
                        $imageUrl = asset('storage/uploads/projects/' . $image->image);
                    }
                }
                return [
                    'id'        => $image->id,
                    'image_url' => $imageUrl,
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'          => $project->id,
                    'name'        => $project->name,
                    'type'        => $project->type,
                    'location'    => $project->location,
                    'description' => $project->description,
                    'slug'        => $project->slug,
                    'sequence'    => $project->sequence,
                    'images'      => $images,
                    'created_at'  => $project->created_at,
                    'updated_at'  => $project->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('ProjectApiController::showBySlug — ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
            ], 404);
        }
    }
}
