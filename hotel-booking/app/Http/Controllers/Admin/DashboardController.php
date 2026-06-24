<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RoomInventory;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfWeek = $today->copy()->startOfWeek();

        // Total bookings + bookings created this week
        $totalBookings = Booking::count();
        $bookingsThisWeek = Booking::where('created_at', '>=', $startOfWeek)->count();

        // Pending approvals
        $pendingApprovals = Booking::where('status', 'pending')->count();

        // Available rooms today (sum of available_count across room_inventories for today)
        $availableRoomsToday = RoomInventory::where('inventory_date', $today->toDateString())
            ->sum('available_count');

        // Total inventory across all room types (capacity reference)
        $totalInventory = RoomType::sum('total_inventory');

        // Rooms currently checked-in (occupied) today: bookings spanning today with status confirmed/checked_in
        $occupiedToday = Booking::where('check_in', '<=', $today->toDateString())
            ->where('check_out', '>', $today->toDateString())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        // Monthly revenue: sum of total_price for bookings created this month, excluding cancelled/rejected
        $monthlyRevenue = Booking::where('created_at', '>=', $startOfMonth)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->sum('total_price');

        // Last month revenue for % comparison
        $startOfLastMonth = $startOfMonth->copy()->subMonth();
        $endOfLastMonth = $startOfMonth->copy()->subDay();
        $lastMonthRevenue = Booking::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->sum('total_price');

        $revenueChangePercent = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : null;

        // Recent pending approvals (with guest + room type info)
        $recentPendingApprovals = Booking::with(['user', 'roomType'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalBookings' => $totalBookings,
            'bookingsThisWeek' => $bookingsThisWeek,
            'pendingApprovals' => $pendingApprovals,
            'availableRoomsToday' => $availableRoomsToday,
            'totalInventory' => $totalInventory,
            'occupiedToday' => $occupiedToday,
            'monthlyRevenue' => $monthlyRevenue,
            'revenueChangePercent' => $revenueChangePercent,
            'recentPendingApprovals' => $recentPendingApprovals,
        ]);
    }
}
