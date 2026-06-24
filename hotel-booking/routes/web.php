<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageAccountsController;
use App\Http\Controllers\Admin\TransactionReportController;
use App\Models\RoomType;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Zones
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $roomTypes = class_exists(RoomType::class) ? RoomType::all() : collect([]);
    return view('welcome', compact('roomTypes'));
})->name('home');

Route::get('/booking/reserve', function () {
    $rooms = [
        [
            'id'          => 1,
            'name'        => 'Horizon Deluxe Suite',
            'price'       => 350.00,
            'size'        => '850 sq. ft.',
            'image'       => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80',
            'description' => 'Our signature suite featuring panoramic ocean views, a private wrap-around balcony, and soundproof structural architecture.',
            'amenities'   => ['High-Speed Wi-Fi', 'King Bed', 'Ocean View', 'Mini Bar', 'Smart TV'],
        ],
        [
            'id'          => 2,
            'name'        => 'Oceanfront Standard',
            'price'       => 200.00,
            'size'        => '450 sq. ft.',
            'image'       => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80',
            'description' => 'An elegant, minimalist layout prioritizing absolute comfort with direct beach access from the ground floor.',
            'amenities'   => ['High-Speed Wi-Fi', 'Queen Bed', 'Beach Access', 'Work Desk'],
        ],
        [
            'id'          => 3,
            'name'        => 'Executive Penthouse',
            'price'       => 850.00,
            'size'        => '1,500 sq. ft.',
            'image'       => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80',
            'description' => 'The ultimate luxury experience. Features a private plunge pool, dedicated butler service, and a full kitchen.',
            'amenities'   => ['Gigabit Wi-Fi', 'Private Pool', 'Butler Service', 'Full Kitchen', 'Private Elevator'],
        ],
    ];

    return view('booking.create', ['rooms' => $rooms]);
})->name('booking.create');

Route::post('/process-booking', [BookingController::class, 'store'])->name('booking.store');

/*
|--------------------------------------------------------------------------
| Dedicated Staff Login
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

    // Member dashboard (booking tickets + avatar picker)
    Route::get('/membership/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:customer')
        ->name('member.dashboard');

    Route::post('/membership/dashboard/avatar', [DashboardController::class, 'updateAvatar'])
        ->middleware('role:customer')
        ->name('dashboard.avatar');

    // Profile Settings (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Restricted Staff / Admin Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->group(function () {

    Route::get('/staff/portal', function () {
        return redirect()->route('staff.dashboard');
    })->name('staff.portal');

    Route::prefix('staff')->name('staff.')->group(function () {
    });

});
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/management/portal', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin.portal');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/manage-accounts', [ManageAccountsController::class, 'index'])->name('accounts');
        Route::patch('/manage-accounts/{user}/role', [ManageAccountsController::class, 'updateRole'])->name('accounts.update-role');
        Route::patch('/manage-accounts/{user}/status', [ManageAccountsController::class, 'toggleStatus'])->name('accounts.toggle-status');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');
        Route::get('/transaction-reports', [TransactionReportController::class, 'index'])->name('transaction-reports');
    });

});

require __DIR__.'/auth.php';
