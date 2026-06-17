<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\GuestBooking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    // ---------------------------------------------------------------
    //  Show booking form
    // ---------------------------------------------------------------

    public function create(Request $request)
    {
        $rooms      = Room::available()->get();
        $selectedRoom = $request->filled('room_id') ? Room::find($request->room_id) : null;

        return view('bookings.create', compact('rooms', 'selectedRoom'));
    }

    // ---------------------------------------------------------------
    //  Store a booking (authenticated user)
    // ---------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'room_id'          => ['required', 'exists:rooms,id'],
            'booking_date'     => ['required', 'date', 'after_or_equal:today'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'num_guests'       => ['required', 'integer', 'min:1'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        $room = Room::findOrFail($request->room_id);

        // Enforce capacity limit
        if ($request->num_guests > $room->capacity) {
            return back()->withErrors(['num_guests' => "This room holds a maximum of {$room->capacity} guests."]);
        }

        // Check for double-booking
        if ($room->isBookedFor($request->booking_date, $request->start_time, $request->end_time)) {
            return back()->withErrors(['booking_date' => 'This room is already booked for the selected time slot. Please choose a different time.']);
        }

        $totalCost = Booking::calculateCost($room, $request->start_time, $request->end_time);

        $booking = Booking::create([
            'user_id'          => Auth::id(),
            'room_id'          => $room->id,
            'booking_date'     => $request->booking_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'num_guests'       => $request->num_guests,
            'total_cost'       => $totalCost,
            'status'           => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        // Send confirmation email
        Mail::to($booking->user->email)->send(new BookingConfirmation($booking, 'user'));

        return redirect()->route('bookings.confirmation', $booking)
                         ->with('success', 'Booking submitted! Your reference is ' . $booking->reference_number);
    }

    // ---------------------------------------------------------------
    //  Guest booking
    // ---------------------------------------------------------------

    public function guestCreate(Request $request)
    {
        $rooms        = Room::available()->get();
        $selectedRoom = $request->filled('room_id') ? Room::find($request->room_id) : null;

        return view('bookings.guest', compact('rooms', 'selectedRoom'));
    }

    public function guestStore(Request $request)
    {
        $request->validate([
            'full_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email'],
            'phone'            => ['required', 'string', 'max:20'],
            'room_id'          => ['required', 'exists:rooms,id'],
            'booking_date'     => ['required', 'date', 'after_or_equal:today'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'num_guests'       => ['required', 'integer', 'min:1'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($request->num_guests > $room->capacity) {
            return back()->withErrors(['num_guests' => "This room holds a maximum of {$room->capacity} guests."]);
        }

        if ($room->isBookedFor($request->booking_date, $request->start_time, $request->end_time)) {
            return back()->withErrors(['booking_date' => 'This room is already booked for the selected time slot.']);
        }

        $totalCost = Booking::calculateCost($room, $request->start_time, $request->end_time);

        $booking = GuestBooking::create([
            'full_name'        => $request->full_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'room_id'          => $room->id,
            'booking_date'     => $request->booking_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'num_guests'       => $request->num_guests,
            'total_cost'       => $totalCost,
            'status'           => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        Mail::to($booking->email)->send(new BookingConfirmation($booking, 'guest'));

        return redirect()->route('bookings.guestConfirmation', $booking)
                         ->with('success', 'Booking submitted! Your reference is ' . $booking->reference_number);
    }

    // ---------------------------------------------------------------
    //  Confirmation pages
    // ---------------------------------------------------------------

    public function confirmation(Booking $booking)
    {
        $this->authorize('view', $booking); // only the owner can see it
        return view('bookings.confirmation', ['booking' => $booking, 'type' => 'user']);
    }

    public function guestConfirmation(GuestBooking $guestBooking)
    {
        return view('bookings.confirmation', ['booking' => $guestBooking, 'type' => 'guest']);
    }

    // ---------------------------------------------------------------
    //  Cancel booking (authenticated user)
    // ---------------------------------------------------------------

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (in_array($booking->status, ['pending', 'approved'])) {
            $booking->update(['status' => 'cancelled']);
            return back()->with('success', 'Booking cancelled successfully.');
        }

        return back()->withErrors(['error' => 'This booking cannot be cancelled.']);
    }

    // ---------------------------------------------------------------
    //  AJAX: calculate cost
    // ---------------------------------------------------------------

    public function calculateCost(Request $request)
    {
        $request->validate([
            'room_id'    => ['required', 'exists:rooms,id'],
            'start_time' => ['required'],
            'end_time'   => ['required'],
        ]);

        $room = Room::findOrFail($request->room_id);
        $cost = Booking::calculateCost($room, $request->start_time, $request->end_time);

        return response()->json(['total_cost' => $cost]);
    }
}
