<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request
            ->user()
            ->load(["reviews.destination", "savedDestinations.destination"]);

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            "name" => "sometimes|string|max:255",
            "photo" => "sometimes|image|mimes:jpeg,png,jpg|max:2048",
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
