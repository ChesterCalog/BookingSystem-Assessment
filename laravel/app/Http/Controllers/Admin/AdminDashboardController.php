<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\GuestBooking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Admin overview dashboard with stats.
     */
    public function index()
    {
        $totalBookings     = Booking::count() + GuestBooking::count();
        $pendingBookings   = Booking::where('status', 'pending')->count()
                          + GuestBooking::where('status', 'pending')->count();
        $totalUsers        = User::where('role', 'user')->count();
        $totalRooms        = Room::count();
        $totalRevenue      = Booking::where('status', 'completed')->sum('total_cost')
                          + GuestBooking::where('status', 'completed')->sum('total_cost');

        // Recent bookings (combined)
        $recentUserBookings  = Booking::with(['user', 'room'])->latest()->take(5)->get()->map(fn($b) => [
            'ref'    => $b->reference_number,
            'name'   => $b->user->name,
            'room'   => $b->room->name,
            'date'   => $b->booking_date->format('M d, Y'),
            'cost'   => $b->total_cost,
            'status' => $b->status,
            'type'   => 'user',
            'id'     => $b->id,
        ]);

        $recentGuestBookings = GuestBooking::with('room')->latest()->take(5)->get()->map(fn($b) => [
            'ref'    => $b->reference_number,
            'name'   => $b->full_name,
            'room'   => $b->room->name,
            'date'   => $b->booking_date->format('M d, Y'),
            'cost'   => $b->total_cost,
            'status' => $b->status,
            'type'   => 'guest',
            'id'     => $b->id,
        ]);

        $recentBookings = $recentUserBookings->merge($recentGuestBookings)
            ->sortByDesc('date')->take(10)->values();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $rev = Booking::where('status', 'completed')
                ->whereYear('booking_date', $month->year)
                ->whereMonth('booking_date', $month->month)
                ->sum('total_cost');
            $rev += GuestBooking::where('status', 'completed')
                ->whereYear('booking_date', $month->year)
                ->whereMonth('booking_date', $month->month)
                ->sum('total_cost');
            $monthlyRevenue[] = ['month' => $month->format('M Y'), 'revenue' => (float) $rev];
        }

        return view('admin.dashboard', compact(
            'totalBookings', 'pendingBookings', 'totalUsers',
            'totalRooms', 'totalRevenue', 'recentBookings', 'monthlyRevenue'
        ));
    }
}
