<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DestinationAdminController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos.*' => 'nullable|file|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        // If Destination model exists, use it; otherwise return informative response
        if (class_exists(\App\Models\Destination::class)) {
            $data = $request->only([
                'name','description','category_id','province_id','city_id',
                'budget_min','budget_max','facilities','latitude','longitude'
            ]);

            $destination = \App\Models\Destination::create($data);

            // handle file uploads (optional)
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $file) {
                    $path = $file->store('destination-photos','public');
                    $url = Storage::url($path);

                    // if a DestinationPhoto model exists, create it, otherwise try to save relation if available
                    if (class_exists(\App\Models\DestinationPhoto::class)) {
                        \App\Models\DestinationPhoto::create([
                            'destination_id' => $destination->id,
                            'photo_url' => $url,
                            'order' => 0,
                        ]);
                    } elseif (method_exists($destination, 'photos')) {
                        // try to attach via relation if defined
                        try {
                            $destination->photos()->create(['photo_url' => $url, 'order' => 0]);
                        } catch (\Throwable $e) {
                            // ignore if relation different
                        }
                    }
                }
            }

            $destination->loadMissing(['photos','category','city','province']);
            return response()->json(['message' => 'Destination created', 'destination' => $destination], 201);
        }

        return response()->json([
            'message' => 'Destination model / migration not found. Create App\\Models\\Destination and migration first (see database/migrations).'
        ], 501);
    }
}
