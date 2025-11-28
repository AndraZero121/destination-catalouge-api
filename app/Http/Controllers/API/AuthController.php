<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info("AuthController@register called", [
            "ip" => $request->ip(),
            "has_email" => $request->filled("email"),
            "has_phone" => $request->filled("phone"),
        ]);

        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users",
                "phone" => "required|string|max:20",
                "password" => "required|string|min:8|confirmed",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Validation failed",
                    "errors" => $validator->errors(),
                ], 422);
            }

            // Buat user baru
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "password" => Hash::make($request->password),
            ]);

            // Buat token
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
        } catch (\Exception $e) {
            return response()->json(
                [
                    "message" => "Registration failed",
                    "error" => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function login(Request $request)
    {
        Log::info("AuthController@login called", [
            "ip" => $request->ip(),
            "login" => $request->input("login"),
        ]);

        try {
            $validator = Validator::make($request->all(), [
                "login" => "required|string",
                "password" => "required|string",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Validation failed",
                    "errors" => $validator->errors(),
                ], 422);
            }

            // Cari user by email
            $user = User::where("email", $request->login)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(
                    [
                        "message" => "Invalid credentials",
                    ],
                    401,
                );
            }

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json(
                [
                    "message" => "Login successful",
                    "user" => $user,
                    "access_token" => $token,
                    "token_type" => "Bearer",
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "message" => "Login failed",
                    "error" => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function logout(Request $request)
    {
        Log::info("AuthController@logout called", [
            "user_id" => $request->user()->id,
            "ip" => $request->ip(),
        ]);

        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(
                [
                    "message" => "Logged out successfully",
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "message" => "Logout failed",
                    "error" => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
