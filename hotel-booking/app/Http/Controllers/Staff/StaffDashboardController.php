<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RoomUnit;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function approvals()
    {
        $pendingBookings = Booking::with(['user', 'roomType'])
            ->where('status', 'pending')
            ->orderBy('check_in')
            ->orderBy('created_at')
            ->get();

        return view('staff.approvals', [
            'pendingBookings' => $pendingBookings,
        ]);
    }

    public function maintenance()
    {
        $roomUnits = RoomUnit::with('roomType')
            ->orderBy('room_number')
            ->get();

        return view('staff.maintenance', [
            'roomUnits' => $roomUnits,
            'availableRooms' => $roomUnits->where('status', 'available')->count(),
            'occupiedRooms' => $roomUnits->where('status', 'occupied')->count(),
        ]);
    }

    public function approveBooking(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }

        DB::transaction(function () use ($booking) {
            $roomUnit = RoomUnit::where('room_type_id', $booking->room_type_id)
                ->where('status', 'available')
                ->orderBy('room_number')
                ->lockForUpdate()
                ->first();

            if (!$roomUnit) {
                abort(422, 'No available room units remain for this room type.');
            }

            $booking->update(['status' => 'confirmed']);
            $roomUnit->update(['status' => 'occupied']);
        });

        return back()->with('success', 'Booking approved and the assigned room was marked occupied.');
    }

    public function rejectBooking(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be rejected.');
        }

        $booking->update(['status' => 'rejected']);

        return back()->with('success', 'Booking rejected.');
    }

}