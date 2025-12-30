<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        Log::info("ProfileController@show called", [
            "user_id" => $request->user()->id,
        ]);

        $user = $request
            ->user()
            ->load(["reviews.destination", "savedDestinations.destination"]);

        $latestReview = $user
            ->reviews()
            ->with("destination")
            ->latest()
            ->first();

        $response = $user->toArray();
        $response["latest_review"] = $latestReview;
        $response["reviews_count"] = $user->reviews()->count();
        $response["saved_count"] = $user->savedDestinations()->count();

        return response()->json($response);
    }

    public function update(Request $request)
    {
        Log::info("ProfileController@update called", [
            "user_id" => $request->user()->id,
            "has_name" => $request->filled("name"),
            "has_photo" => $request->hasFile("photo"),
        ]);

        $user = $request->user();

        $request->validate([
            "name" => "sometimes|string|max:255",
            // Allow larger and modern formats to avoid false validation failures on common uploads
            "photo" => "sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:5120",
        ]);

        if ($request->has("name")) {
            $user->name = $request->name;
        }

        if ($request->hasFile("photo")) {
            // Delete old photo
            if ($user->photo_url) {
                Storage::disk("public")->delete($user->photo_url);
            }

            $path = $request->file("photo")->store("profile-photos", "public");
            $user->photo_url = $path;
        }

        $user->save();

        return response()->json([
            "message" => "Profile updated successfully",
            "user" => $user,
        ]);
    }

    public function updatePassword(Request $request)
    {
        Log::info("ProfileController@updatePassword called", [
            "user_id" => $request->user()->id,
        ]);

        $request->validate([
            "current_password" => "required|string",
            "password" => "required|string|min:8|confirmed",
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(
                [
                    "message" => "Current password is incorrect",
                ],
                422,
            );
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            "message" => "Password updated successfully",
        ]);
    }
}
