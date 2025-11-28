<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\DestinationAdminController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SavedDestinationController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

RateLimiter::for("auth-api", function (Request $request) {
    return Limit::perMinute(60)->by(
        $request->user()?->id ?? $request->ip(),
    );
});

// Tambahkan endpoint sederhana untuk /api (root API) agar mudah melihat API berjalan
Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running. Use /api/<endpoint>. Example endpoints listed in "routes".',
        'routes' => [
            'GET  /api/destinations',
            'GET  /api/destinations/slider',
            'GET  /api/destinations/{id}',
            'POST /api/register',
            'POST /api/login',
        ],
    ]);
});

// Public routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// Destinations (public)
Route::get("/destinations", [DestinationController::class, "index"]);
Route::get("/destinations/slider", [DestinationController::class, "slider"]);
Route::get("/destinations/{id}", [DestinationController::class, "show"]);

// Protected routes
Route::middleware(["auth:sanctum", "throttle:auth-api"])->group(function () {
    // Auth
    Route::post("/logout", [AuthController::class, "logout"]);

    // Profile
    Route::get("/profile", [ProfileController::class, "show"]);
    Route::post("/profile/update", [ProfileController::class, "update"]);
    Route::post("/profile/password", [
        ProfileController::class,
        "updatePassword",
    ]);

    // Reviews
    Route::post("/reviews", [ReviewController::class, "store"]);
    Route::post("/review", [ReviewController::class, "store"]);
    Route::get("/reviews/my", [ReviewController::class, "myReviews"]);
    Route::delete("/reviews/{id}", [ReviewController::class, "destroy"]);

    // Saved Destinations
    Route::get("/saved", [SavedDestinationController::class, "index"]);
    Route::post("/saved", [SavedDestinationController::class, "store"]);
    Route::delete("/saved/{id}", [
        SavedDestinationController::class,
        "destroy",
    ]);

    // Add destination (requires auth) — backend handler in DestinationAdminController
    Route::post("/destinations", [DestinationAdminController::class, "store"]);
});
