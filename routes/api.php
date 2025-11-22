<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DestinationController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\SavedDestinationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// Destinations (public)
Route::get("/destinations", [DestinationController::class, "index"]);
Route::get("/destinations/slider", [DestinationController::class, "slider"]);
Route::get("/destinations/{id}", [DestinationController::class, "show"]);

// Protected routes
Route::middleware("auth:sanctum")->group(function () {
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
