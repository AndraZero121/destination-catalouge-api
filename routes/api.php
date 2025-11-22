<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
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
});
