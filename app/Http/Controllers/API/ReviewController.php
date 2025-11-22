<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "destination_id" => "required|exists:destinations,id",
            "rating" => "required|integer|min:1|max:5",
            "description" => "nullable|string",
        ]);

        $review = Review::create([
            "user_id" => $request->user()->id,
            "destination_id" => $request->destination_id,
            "rating" => $request->rating,
            "description" => $request->description,
        ]);

        $review->load("user");

        return response()->json(
            [
                "message" => "Review submitted successfully",
                "review" => $review,
            ],
            201,
        );
    }

    public function myReviews(Request $request)
    {
        $reviews = Review::with("destination")
            ->where("user_id", $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    public function destroy(Request $request, $id)
    {
        $review = Review::where("user_id", $request->user()->id)->findOrFail(
            $id,
        );

        $review->delete();

        return response()->json([
            "message" => "Review deleted successfully",
        ]);
    }
}
