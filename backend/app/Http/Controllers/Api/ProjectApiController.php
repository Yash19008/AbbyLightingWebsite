<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    /**
     * Get latest projects
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 6); // Default to 6 projects
            
            $projects = Project::where('is_active', 'yes')
                ->orderBy('sequence', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get();
            
            // Add full image URLs and get first image
            $projects = $projects->map(function ($project) {
                $firstImage = ProjectImage::where('project_id', $project->id)
                    ->where('is_active', 'yes')
                    ->orderBy('id', 'ASC')
                    ->first();
                
                $imageUrl = null;
                if ($firstImage && $firstImage->image) {
                    // Check if the path already contains 'uploads/'
                    if (strpos($firstImage->image, 'uploads/') === 0) {
                        $imageUrl = asset('storage/' . $firstImage->image);
                    } else {
                        // Old format - just filename, assume uploads/projects/
                        $imageUrl = asset('storage/uploads/projects/' . $firstImage->image);
                    }
                }
                
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'type' => $project->type,
                    'location' => $project->location,
                    'description' => $project->description,
                    'slug' => $project->slug,
                    'sequence' => $project->sequence,
                    'image_url' => $imageUrl,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $projects,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch projects',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single project
     */
    public function show($id)
    {
        try {
            $project = Project::where('is_active', 'yes')->findOrFail($id);
            
            $images = ProjectImage::where('project_id', $project->id)
                ->where('is_active', 'yes')
                ->orderBy('id', 'ASC')
                ->get()
                ->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image_url' => $image->image ? asset('storage/' . $image->image) : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'type' => $project->type,
                    'location' => $project->location,
                    'description' => $project->description,
                    'slug' => $project->slug,
                    'sequence' => $project->sequence,
                    'images' => $images,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
                'error' => $e->getMessage(),
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
                ->firstOrFail();
            
            $images = ProjectImage::where('project_id', $project->id)
                ->where('is_active', 'yes')
                ->orderBy('id', 'ASC')
                ->get()
                ->map(function ($image) {
                    $imageUrl = null;
                    if ($image->image) {
                        if (strpos($image->image, 'uploads/') === 0) {
                            $imageUrl = asset('storage/' . $image->image);
                        } else {
                            $imageUrl = asset('storage/uploads/projects/' . $image->image);
                        }
                    }
                    return [
                        'id' => $image->id,
                        'image_url' => $imageUrl,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'type' => $project->type,
                    'location' => $project->location,
                    'description' => $project->description,
                    'slug' => $project->slug,
                    'sequence' => $project->sequence,
                    'images' => $images,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
