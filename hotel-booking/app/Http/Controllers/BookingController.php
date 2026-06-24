<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    // 1. PUBLIC LANDING PAGE (Displays room products only)
    public function index()
    {
        $roomTypes = RoomType::orderBy('base_price', 'desc')->get();
        return view('welcome', compact('roomTypes'));
    }

    // 2. DEDICATED RESERVATION PAGE (Sorted Descending)
    public function showReservationForm()
    {
        $rooms = RoomType::orderBy('base_price', 'desc')->get();
        return view('booking.create', compact('rooms'));
    }

    // 3. BACKGROUND PROCESSING ENGINE
    public function store(Request $request)
    {
        $guestName  = $request->input('guest_name');
        $guestEmail = $request->input('guest_email');
        $guestPhone = $request->input('guest_phone');

        $roomTypeId = $request->input('room_type_id');
        $checkIn    = Carbon::parse($request->input('check_in'));
        $checkOut   = Carbon::parse($request->input('check_out'));

        return DB::transaction(function () use ($guestName, $guestEmail, $guestPhone, $roomTypeId, $checkIn, $checkOut) {

            $days = DB::table('room_inventories')
                ->where('room_type_id', $roomTypeId)
                ->where('inventory_date', '>=', $checkIn->toDateString())
                ->where('inventory_date', '<', $checkOut->toDateString())
                ->lockForUpdate()
                ->get();

            foreach ($days as $day) {
                if ($day->available_count <= 0) {
                    return redirect()->back()->with('error', "No availability remains for " . $day->inventory_date);
                }
            }

            DB::table('room_inventories')
                ->where('room_type_id', $roomTypeId)
                ->where('inventory_date', '>=', $checkIn->toDateString())
                ->where('inventory_date', '<', $checkOut->toDateString())
                ->decrement('available_count');

            $roomType   = RoomType::find($roomTypeId);
            $totalPrice = $days->sum(function ($day) use ($roomType) {
                return $day->price_override ?? $roomType->base_price;
            });

            $user = User::firstOrCreate(
                ['email' => $guestEmail],
                [
                    'name'     => $guestName,
                    'phone'    => $guestPhone,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                    'role'     => 'customer'
                ]
            );

            Booking::create([
                'user_id'      => $user->id,
                'room_type_id' => $roomTypeId,
                'check_in'     => $checkIn->toDateString(),
                'check_out'    => $checkOut->toDateString(),
                'total_price'  => $totalPrice,
                'status'       => 'pending', // Awaits admin approval
            ]);

            return redirect()->route('home')->with('success', 'Your reservation request has been submitted and is awaiting confirmation!');
        });
    }

    // 4. ADMIN: APPROVE A BOOKING
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', "Booking #{$booking->id} has been approved.");
    }

    // 5. ADMIN: REJECT A BOOKING (also restores inventory)
    public function reject(Booking $booking)
    {
        DB::table('room_inventories')
            ->where('room_type_id', $booking->room_type_id)
            ->whereBetween('inventory_date', [
                $booking->check_in,
                Carbon::parse($booking->check_out)->subDay()->toDateString()
            ])
            ->increment('available_count');

        $booking->update(['status' => 'rejected']);

        return redirect()->back()->with('success', "Booking #{$booking->id} has been rejected and inventory restored.");
    }
}