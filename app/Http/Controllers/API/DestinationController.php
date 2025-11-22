<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = Destination::with([
            "category",
            "province",
            "city",
            "photos",
            "reviews",
        ]);

        // Search
        if ($request->has("search")) {
            $query->where("name", "like", "%" . $request->search . "%");
        }

        // Filter by category
        if ($request->has("category_id")) {
            $query->where("category_id", $request->category_id);
        }

        // Filter by province
        if ($request->has("province_id")) {
            $query->where("province_id", $request->province_id);
        }

        // Filter by city
        if ($request->has("city_id")) {
            $query->where("city_id", $request->city_id);
        }

        // Sort
        if ($request->has("sort")) {
            switch ($request->sort) {
                case "a-z":
                    $query->orderBy("name", "asc");
                    break;
                case "z-a":
                    $query->orderBy("name", "desc");
                    break;
                case "nearby":
                    if (
                        $request->has("latitude") &&
                        $request->has("longitude")
                    ) {
                        $lat = $request->latitude;
                        $lng = $request->longitude;
                        $query
                            ->selectRaw(
                                "*,
                            (6371 * acos(cos(radians(?))
                            * cos(radians(latitude))
                            * cos(radians(longitude) - radians(?))
                            + sin(radians(?))
                            * sin(radians(latitude)))) AS distance",
                                [$lat, $lng, $lat],
                            )
                            ->orderBy("distance");
                    }
                    break;
            }
        }

        $destinations = $query->paginate(10);

        return response()->json($destinations);
    }

    public function show($id)
    {
        $destination = Destination::with([
            "category",
            "province",
            "city",
            "photos",
            "reviews.user",
        ])->findOrFail($id);

        $averageRating = $destination->reviews()->avg("rating");
        $destination->average_rating = round($averageRating, 1);

        return response()->json($destination);
    }

    public function slider()
    {
        $destinations = Destination::with("photos")->latest()->limit(5)->get();

        return response()->json($destinations);
    }
}
