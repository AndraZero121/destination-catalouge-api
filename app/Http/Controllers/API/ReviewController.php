<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        Log::info("ReviewController@store called", [
            "user_id" => $request->user()->id,
            "destination_id" => $request->input("destination_id"),
        ]);

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
        Log::info("ReviewController@myReviews called", [
            "user_id" => $request->user()->id,
        ]);


        Log::info("Get Review...");
        $reviews = Review::with("destination")
            ->where("user_id", $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    public function destroy(Request $request, $id)
    {
        Log::info("ReviewController@destroy called", [
            "user_id" => $request->user()->id,
            "review_id" => $id,
        ]);

        $review = Review::where("user_id", $request->user()->id)->findOrFail(
            $id,
        );

        $review->delete();

        return response()->json([
            "message" => "Review deleted successfully",
        ]);
    }
}
