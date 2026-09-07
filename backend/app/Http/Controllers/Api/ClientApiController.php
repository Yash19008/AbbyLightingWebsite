<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    /**
     * Get all clients
     */
    public function index()
    {
        try {
            $clients = Client::orderBy('id', 'DESC')->get();
            
            // Add full image URLs
            $clients = $clients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'path' => $client->path,
                    'image_url' => $client->path ? asset('storage/uploads/clients/' . $client->path) : null,
                    'created_at' => $client->created_at,
                    'updated_at' => $client->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $clients,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch clients',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get single client
     */
    public function show($id)
    {
        try {
            $client = Client::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $client->id,
                    'path' => $client->path,
                    'image_url' => $client->path ? asset('storage/uploads/clients/' . $client->path) : null,
                    'created_at' => $client->created_at,
                    'updated_at' => $client->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
