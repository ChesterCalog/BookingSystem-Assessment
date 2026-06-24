<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManageAccountsController;
use App\Http\Controllers\Admin\TransactionReportController;
use App\Http\Controllers\Staff\StaffDashboardController;
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

    // Profile Settings (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Restricted Staff Management Portal
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->group(function () {

    Route::get('/staff/portal', function () {
        return redirect()->route('staff.approvals');
    })->name('staff.portal');

    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('staff.approvals');
        })->name('dashboard');
        Route::get('/approvals', [StaffDashboardController::class, 'approvals'])->name('approvals');
        Route::get('/maintenance', [StaffDashboardController::class, 'maintenance'])->name('maintenance');
        Route::post('/bookings/{booking}/approve', [StaffDashboardController::class, 'approveBooking'])->name('bookings.approve');
        Route::post('/bookings/{booking}/reject', [StaffDashboardController::class, 'rejectBooking'])->name('bookings.reject');
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
        Route::get('/approvals', [StaffDashboardController::class, 'approvals'])->name('approvals');
        Route::get('/maintenance', [StaffDashboardController::class, 'maintenance'])->name('maintenance');
        Route::post('/bookings/{booking}/approve', [StaffDashboardController::class, 'approveBooking'])->name('bookings.approve');
        Route::post('/bookings/{booking}/reject', [StaffDashboardController::class, 'rejectBooking'])->name('bookings.reject');
    });

});


require __DIR__.'/auth.php';
