<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRoomController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------
//  Public routes
// ---------------------------------------------------------------

Route::get('/', [HomeController::class, 'index'])->name('home');

// Room listing
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Guest booking
Route::get('/book/guest', [BookingController::class, 'guestCreate'])->name('bookings.guestCreate');
Route::post('/book/guest', [BookingController::class, 'guestStore'])->name('bookings.guestStore');
Route::get('/book/guest/confirmation/{guestBooking}', [BookingController::class, 'guestConfirmation'])->name('bookings.guestConfirmation');

// Cost calculator (AJAX)
Route::post('/bookings/calculate-cost', [BookingController::class, 'calculateCost'])->name('bookings.calculateCost');

// ---------------------------------------------------------------
//  Authentication routes
// ---------------------------------------------------------------

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

// ---------------------------------------------------------------
//  Authenticated user routes
// ---------------------------------------------------------------

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.profile.update');

    // User bookings
    Route::get('/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/book/confirmation/{booking}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ---------------------------------------------------------------
//  Admin routes
// ---------------------------------------------------------------

Route::prefix('admin')
     ->middleware(['auth', 'admin'])
     ->name('admin.')
     ->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Room management (CRUD)
    Route::resource('rooms', AdminRoomController::class);

    // Booking management
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/calendar', [AdminBookingController::class, 'calendar'])->name('bookings.calendar');
    Route::get('bookings/{type}/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{type}/{id}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::patch('bookings/{type}/{id}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('bookings/{type}/{id}/complete', [AdminBookingController::class, 'complete'])->name('bookings.complete');
});
