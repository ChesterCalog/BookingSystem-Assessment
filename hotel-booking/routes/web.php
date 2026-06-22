<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. HOTEL SYSTEM DASHBOARD & BOOKING ROUTES
// ==========================================
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/process-booking', [BookingController::class, 'store'])->name('booking.store');

// ==========================================
// 2. LARAVEL BREEZE AUTHENTICATION ROUTES
// ==========================================

// Standard logged-in user home screen (Created by Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User profile management routes (Created by Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pulls in the hidden login/register backend rules
require __DIR__.'/auth.php';