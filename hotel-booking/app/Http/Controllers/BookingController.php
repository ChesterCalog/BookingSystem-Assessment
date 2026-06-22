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
    public function index()
    {
        $roomTypes = RoomType::all();
        // Grabs active bookings and pulls user info alongside it
        $bookings = Booking::with(['roomType', 'user'])->orderBy('created_at', 'desc')->get();

        return view('welcome', compact('roomTypes', 'bookings'));
    }

    public function store(Request $request)
    {
        // 1. Gather Guest Details & Booking Dates
        $guestName = $request->input('guest_name');
        $guestEmail = $request->input('guest_email');
        $guestPhone = $request->input('guest_phone');
        
        $roomTypeId = $request->input('room_type_id');
        $checkIn = Carbon::parse($request->input('check_in'));
        $checkOut = Carbon::parse($request->input('check_out'));

        return DB::transaction(function () use ($guestName, $guestEmail, $guestPhone, $roomTypeId, $checkIn, $checkOut) {
            
            // 2. Room Inventory Protection Logic
            $days = DB::table('room_inventories')
                ->where('room_type_id', $roomTypeId)
                ->where('inventory_date', '>=', $checkIn->toDateString())
                ->where('inventory_date', '<', $checkOut->toDateString())
                ->lockForUpdate()
                ->get();

            foreach ($days as $day) {
                if ($day->available_count <= 0) {
                    return redirect()->back()->with('error', "No rooms available for " . $day->inventory_date);
                }
            }

            // 3. Deduct room inventory count
            DB::table('room_inventories')
                ->where('room_type_id', $roomTypeId)
                ->where('inventory_date', '>=', $checkIn->toDateString())
                ->where('inventory_date', '<', $checkOut->toDateString())
                ->decrement('available_count');

            // 4. Calculate total price
            $roomType = RoomType::find($roomTypeId);
            $totalPrice = $days->sum(function ($day) use ($roomType) {
                return $day->price_override ?? $roomType->base_price;
            });

            // 5. Instantly locate or generate a background profile for this guest
            $user = User::firstOrCreate(
                ['email' => $guestEmail],
                [
                    'name' => $guestName,
                    'phone' => $guestPhone,
                    'password' => bcrypt(str_random(16)), // Gives them a secure random password if they ever want to activate their account later
                    'role' => 'customer'
                ]
            );

            // 6. Record the Reservation
            Booking::create([
                'user_id' => $user->id,
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'total_price' => $totalPrice,
                'status' => 'confirmed'
            ]);

            return redirect()->route('home')->with('success', 'Reservation booked successfully!');
        });
    }
}