<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    /**
     * Get latest events/news
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 4); // Default to 4 news items
            
            $events = Event::where('is_active', 'yes')
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get();
            
            // Add full image URLs and get first image
            $events = $events->map(function ($event) {
                $firstImage = EventImage::where('event_id', $event->id)
                    ->where('is_active', 'yes')
                    ->orderBy('id', 'ASC')
                    ->first();
                
                $imageUrl = null;
                if ($firstImage && $firstImage->image) {
                    // Check if the path already contains 'uploads/'
                    if (strpos($firstImage->image, 'uploads/') === 0) {
                        $imageUrl = asset('storage/' . $firstImage->image);
                    } else {
                        // Old format - just filename, assume uploads/events/
                        $imageUrl = asset('storage/uploads/events/' . $firstImage->image);
                    }
                }
                
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'source' => $event->source,
                    'source_link' => $event->source_link,
                    'slug' => $event->slug,
                    'location' => $event->location,
                    'description' => $event->description,
                    'image_url' => $imageUrl,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $events,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single event
     */
    public function show($id)
    {
        try {
            $event = Event::where('is_active', 'yes')->findOrFail($id);
            
            $images = EventImage::where('event_id', $event->id)
                ->where('is_active', 'yes')
                ->orderBy('id', 'ASC')
                ->get()
                ->map(function ($image) {
                    $imageUrl = null;
                    if ($image->image) {
                        if (strpos($image->image, 'uploads/') === 0) {
                            $imageUrl = asset('storage/' . $image->image);
                        } else {
                            $imageUrl = asset('storage/uploads/events/' . $image->image);
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
                    'id' => $event->id,
                    'name' => $event->name,
                    'source' => $event->source,
                    'source_link' => $event->source_link,
                    'slug' => $event->slug,
                    'location' => $event->location,
                    'description' => $event->description,
                    'images' => $images,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
