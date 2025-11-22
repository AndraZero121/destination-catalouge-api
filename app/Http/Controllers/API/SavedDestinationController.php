<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SavedDestination;
use Illuminate\Http\Request;

class SavedDestinationController extends Controller
{
    public function index(Request $request)
    {
        $saved = SavedDestination::with("destination.photos")
            ->where("user_id", $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($saved);
    }

    public function store(Request $request)
    {
        $request->validate([
            "destination_id" => "required|exists:destinations,id",
        ]);

        $saved = SavedDestination::firstOrCreate([
            "user_id" => $request->user()->id,
            "destination_id" => $request->destination_id,
        ]);

        return response()->json(
            [
                "message" => "Destination saved successfully",
                "saved" => $saved,
            ],
            201,
        );
    }

    public function destroy(Request $request, $id)
    {
        $saved = SavedDestination::where("user_id", $request->user()->id)
            ->where("destination_id", $id)
            ->firstOrFail();

        $saved->delete();

        return response()->json([
            "message" => "Destination removed from saved list",
        ]);
    }
}
