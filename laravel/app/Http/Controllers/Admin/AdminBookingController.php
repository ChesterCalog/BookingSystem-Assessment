<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusUpdated;
use App\Models\Booking;
use App\Models\GuestBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminBookingController extends Controller
{
    /**
     * List all bookings (users + guests) with search and filter.
     */
    public function index(Request $request)
    {
        // User bookings
        $userQuery = Booking::with(['user', 'room']);
        // Guest bookings
        $guestQuery = GuestBooking::with('room');

        // Apply status filter
        if ($request->filled('status')) {
            $userQuery->where('status', $request->status);
            $guestQuery->where('status', $request->status);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $userQuery->whereDate('booking_date', $request->date);
            $guestQuery->whereDate('booking_date', $request->date);
        }

        // Apply search filter (name/email/ref)
        if ($request->filled('search')) {
            $search = $request->search;
            $userQuery->where(function ($q) use ($search) {
                $q->where('reference_number', 'ilike', "%$search%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'ilike', "%$search%")
                                                       ->orWhere('email', 'ilike', "%$search%"));
            });
            $guestQuery->where(function ($q) use ($search) {
                $q->where('reference_number', 'ilike', "%$search%")
                  ->orWhere('full_name', 'ilike', "%$search%")
                  ->orWhere('email', 'ilike', "%$search%");
            });
        }

        $userBookings  = $userQuery->latest()->get()->map(fn($b) => array_merge($b->toArray(), ['booking_category' => 'user']));
        $guestBookings = $guestQuery->latest()->get()->map(fn($b) => array_merge($b->toArray(), ['booking_category' => 'guest']));

        $bookings = $userBookings->merge($guestBookings)->sortByDesc('created_at')->values();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show a single booking detail.
     */
    public function show(string $type, int $id)
    {
        if ($type === 'user') {
            $booking = Booking::with(['user', 'room'])->findOrFail($id);
        } else {
            $booking = GuestBooking::with('room')->findOrFail($id);
        }

        return view('admin.bookings.show', compact('booking', 'type'));
    }

    /**
     * Approve a booking.
     */
    public function approve(string $type, int $id)
    {
        $booking = $this->findBooking($type, $id);
        $booking->update(['status' => 'approved']);

        Mail::to($this->getEmail($booking, $type))->send(new BookingStatusUpdated($booking, 'approved'));

        return back()->with('success', 'Booking approved.');
    }

    /**
     * Reject a booking.
     */
    public function reject(string $type, int $id)
    {
        $booking = $this->findBooking($type, $id);
        $booking->update(['status' => 'rejected']);

        Mail::to($this->getEmail($booking, $type))->send(new BookingStatusUpdated($booking, 'rejected'));

        return back()->with('success', 'Booking rejected.');
    }

    /**
     * Mark booking as completed.
     */
    public function complete(string $type, int $id)
    {
        $booking = $this->findBooking($type, $id);
        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Booking marked as completed.');
    }

    /**
     * Show the booking calendar.
     */
    public function calendar()
    {
        $userBookings = Booking::with('room')
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->map(fn($b) => [
                'id'    => 'user-' . $b->id,
                'title' => $b->room->name . ' (' . $b->reference_number . ')',
                'start' => $b->booking_date->format('Y-m-d') . 'T' . $b->start_time,
                'end'   => $b->booking_date->format('Y-m-d') . 'T' . $b->end_time,
                'color' => $b->status === 'approved' ? '#10b981' : '#f59e0b',
            ]);

        $guestBookings = GuestBooking::with('room')
            ->whereIn('status', ['approved', 'pending'])
            ->get()
            ->map(fn($b) => [
                'id'    => 'guest-' . $b->id,
                'title' => $b->room->name . ' (' . $b->reference_number . ')',
                'start' => $b->booking_date->format('Y-m-d') . 'T' . $b->start_time,
                'end'   => $b->booking_date->format('Y-m-d') . 'T' . $b->end_time,
                'color' => $b->status === 'approved' ? '#6366f1' : '#f97316',
            ]);

        $events = $userBookings->merge($guestBookings)->values()->toJson();

        return view('admin.bookings.calendar', compact('events'));
    }

    // ---------------------------------------------------------------
    //  Helpers
    // ---------------------------------------------------------------

    private function findBooking(string $type, int $id)
    {
        return $type === 'user'
            ? Booking::findOrFail($id)
            : GuestBooking::findOrFail($id);
    }

    private function getEmail($booking, string $type): string
    {
        return $type === 'user' ? $booking->user->email : $booking->email;
    }
}
