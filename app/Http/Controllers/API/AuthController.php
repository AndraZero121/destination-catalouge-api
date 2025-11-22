<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info("AuthController@register called", [
            "ip" => $request->ip(),
            "has_email" => $request->filled("email"),
            "has_phone" => $request->filled("phone"),
        ]);

        $request->validate([
            "name" => "required|string|max:255",
            "email" => "nullable|email|unique:users,email",
            "phone" => "nullable|string|unique:users,phone",
            "password" => "required|string|min:8|confirmed",
        ]);

        if (!$request->email && !$request->phone) {
            return response()->json(
                [
                    "message" => "Either email or phone is required",
                ],
                422,
            );
        }

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json(
            [
                "message" => "Registration successful",
                "user" => $user,
                "access_token" => $token,
                "token_type" => "Bearer",
            ],
            201,
        );
    }

    public function login(Request $request)
    {
        Log::info("AuthController@login called", [
            "ip" => $request->ip(),
            "login" => $request->input("login"),
        ]);

        $request->validate([
            "login" => "required|string",
            "password" => "required|string",
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? "email"
            : "phone";

        $user = User::where($loginType, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                "login" => ["The provided credentials are incorrect."],
            ]);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "message" => "Login successful",
            "user" => $user,
            "access_token" => $token,
            "token_type" => "Bearer",
        ]);
    }

    public function logout(Request $request)
    {
        Log::info("AuthController@logout called", [
            "user_id" => $request->user()->id,
            "ip" => $request->ip(),
        ]);

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logged out successfully",
        ]);
    }
}
