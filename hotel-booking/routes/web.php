<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Commercial Routes
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::get('/reserve', [BookingController::class, 'showReservationForm'])->name('booking.create');
Route::post('/process-booking', [BookingController::class, 'store'])->name('booking.store');

// Auth Infrastructure (Breeze)
Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';