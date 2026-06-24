<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Models\RoomType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Zones (Landing Page & Bookings Only)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $roomTypes = class_exists(RoomType::class) ? RoomType::all() : collect([]);
    return view('welcome', compact('roomTypes'));
})->name('home');

Route::get('/booking/reserve', [BookingController::class, 'showReservationForm'])->name('booking.create');
Route::post('/booking/reserve', [BookingController::class, 'store'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| Dedicated Staff Login Route
|--------------------------------------------------------------------------
*/
Route::get('/staff/login', function () {
    return view('auth.staff-login');
})->middleware('guest')->name('staff.login');

Route::post('/staff/login', [AuthenticatedSessionController::class, 'storeStaff'])
    ->middleware('guest')
    ->name('staff.login.store');

/*
|--------------------------------------------------------------------------
| Protected Customer Workspace
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/membership/dashboard', function () {
        return view('dashboard.member');
    })->middleware('role:customer')->name('member.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Restricted Staff Management Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff,admin'])->group(function () {
    
    Route::get('/management/portal', function () {
        return view('dashboard.staff');
    })->name('staff.portal');
    
});

require __DIR__.'/auth.php';