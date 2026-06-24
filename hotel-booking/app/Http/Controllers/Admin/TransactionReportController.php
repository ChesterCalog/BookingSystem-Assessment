<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $days = 10;
        $startDate = Carbon::today()->subDays($days - 1);
        $endDate = Carbon::today();

        // Pull bookings created in the last 10 days, excluding cancelled/rejected (not real "payments")
        $bookings = Booking::with(['user', 'roomType'])
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->orderByDesc('created_at')
            ->get();

        $totalRevenue = $bookings->sum('total_price');
        $totalTransactions = $bookings->count();
        $avgDailyRevenue = $days > 0 ? round($totalRevenue / $days, 0) : 0;

        // Build daily series for the chart (transactions count + revenue per day)
        $dailySeries = collect();
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayBookings = $bookings->filter(fn ($b) => $b->created_at->isSameDay($date));

            $dailySeries->push([
                'date' => $date->format('M j'),
                'transactions' => $dayBookings->count(),
                'revenue' => $dayBookings->sum('total_price'),
            ]);
        }

        $transactionDetails = $bookings->take(50)->map(function ($booking) {
            return [
                'tx_id' => 'TX-' . (8800 + $booking->id),
                'date' => $booking->created_at->format('Y-m-d'),
                'booking_id' => 'BK-' . $booking->id,
                'guest' => $booking->user->name ?? '—',
                'room' => $booking->roomType->name ?? '—',
                'amount' => $booking->total_price,
                'status' => $booking->status === 'confirmed' ? 'Completed' : ucfirst($booking->status),
            ];
        });

        return view('admin.transaction-reports', [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'avgDailyRevenue' => $avgDailyRevenue,
            'dailySeries' => $dailySeries,
            'transactionDetails' => $transactionDetails,
        ]);
    }
}
