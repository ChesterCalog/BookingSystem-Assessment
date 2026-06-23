<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Models\RoomType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Zones (Landing Page & Bookings Only)
|--------------------------------------------------------------------------
*/

// The clean, pure entry point for public visitors
Route::get('/', function () {
    $roomTypes = class_exists(RoomType::class) ? RoomType::all() : collect([]);
    return view('welcome', compact('roomTypes'));
})->name('home');

Route::get('/booking/reserve', function () {
    $rooms = [
        [
            'id' => 1,
            'name' => 'Horizon Deluxe Suite',
            'price' => 350.00,
            'size' => '850 sq. ft.',
            'image' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80',
            'description' => 'Our signature suite featuring panoramic ocean views, a private wrap-around balcony, and soundproof structural architecture.',
            'amenities' => ['High-Speed Wi-Fi', 'King Bed', 'Ocean View', 'Mini Bar', 'Smart TV']
        ],
        [
            'id' => 2,
            'name' => 'Oceanfront Standard',
            'price' => 200.00,
            'size' => '450 sq. ft.',
            'image' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80',
            'description' => 'An elegant, minimalist layout prioritizing absolute comfort with direct beach access from the ground floor.',
            'amenities' => ['High-Speed Wi-Fi', 'Queen Bed', 'Beach Access', 'Work Desk']
        ],
        [
            'id' => 3,
            'name' => 'Executive Penthouse',
            'price' => 850.00,
            'size' => '1,500 sq. ft.',
            'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
            'description' => 'The ultimate luxury experience. Features a private plunge pool, dedicated butler service, and a full kitchen.',
            'amenities' => ['Gigabit Wi-Fi', 'Private Pool', 'Butler Service', 'Full Kitchen', 'Private Elevator']
        ]
    ];

    return view('booking.create', ['rooms' => $rooms]);
})->name('booking.create');

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

    // Profile Settings
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
