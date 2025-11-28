<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\DestinationController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SavedController;

// Ubah root agar membuka halaman login (frontend) bukan API JSON
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'login'])->name('frontend.login');
Route::get('/register', [AuthController::class, 'register'])->name('frontend.register');
Route::get('/logout', [AuthController::class, 'logoutView'])->name('frontend.logout');

// Destination routes (frontend, non-API)
Route::get('/frontend/destinations', [DestinationController::class, 'index'])->name('frontend.destinations');
Route::get('/frontend/destinations/{id}', [DestinationController::class, 'show'])->name('frontend.destination.show');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('frontend.dashboard');

// Profile (frontend view) — pindah ke /frontend/profile agar tidak bentrok dengan API
Route::get('/frontend/profile', [ProfileController::class, 'index'])->name('frontend.profile');

// Reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('frontend.reviews');

// Saved (frontend view) — pindah ke /frontend/saved agar tidak bentrok dengan API
Route::get('/frontend/saved', [SavedController::class, 'index'])->name('frontend.saved');
