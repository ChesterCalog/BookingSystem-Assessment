<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\GuestBooking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = Room::all();
        $users = User::where('role', 'user')->get();

        if ($rooms->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Skipping BookingSeeder: run UserSeeder and RoomSeeder first.');
            return;
        }

        // ── Member bookings ──────────────────────────────────────────
        $memberBookings = [
            [
                'user_id'      => $users->first()->id,
                'room_id'      => $rooms->firstWhere('type', 'vip')?->id ?? $rooms->first()->id,
                'booking_date' => now()->addDays(3)->format('Y-m-d'),
                'start_time'   => '18:00',
                'end_time'     => '21:00',
                'num_guests'   => 10,
                'status'       => 'approved',
            ],
            [
                'user_id'      => $users->skip(1)->first()->id,
                'room_id'      => $rooms->firstWhere('type', 'deluxe')?->id ?? $rooms->first()->id,
                'booking_date' => now()->addDays(5)->format('Y-m-d'),
                'start_time'   => '14:00',
                'end_time'     => '17:00',
                'num_guests'   => 8,
                'status'       => 'pending',
            ],
            [
                'user_id'      => $users->first()->id,
                'room_id'      => $rooms->firstWhere('type', 'standard')?->id ?? $rooms->first()->id,
                'booking_date' => now()->subDays(7)->format('Y-m-d'),
                'start_time'   => '20:00',
                'end_time'     => '23:00',
                'num_guests'   => 4,
                'status'       => 'completed',
            ],
            [
                'user_id'      => $users->skip(2)->first()->id ?? $users->first()->id,
                'room_id'      => $rooms->firstWhere('type', 'party')?->id ?? $rooms->first()->id,
                'booking_date' => now()->addDays(10)->format('Y-m-d'),
                'start_time'   => '17:00',
                'end_time'     => '23:00',
                'num_guests'   => 25,
                'status'       => 'pending',
            ],
        ];

        foreach ($memberBookings as $data) {
            $room      = Room::find($data['room_id']);
            $totalCost = Booking::calculateCost($room, $data['start_time'], $data['end_time']);

            Booking::create(array_merge($data, [
                'total_cost'       => $totalCost,
                'special_requests' => null,
            ]));
        }

        // ── Guest bookings ───────────────────────────────────────────
        $guestBookings = [
            [
                'full_name'    => 'Patricia Lim',
                'email'        => 'patricia@example.com',
                'phone'        => '+63 917 555 1001',
                'room_id'      => $rooms->firstWhere('type', 'standard')?->id ?? $rooms->first()->id,
                'booking_date' => now()->addDays(2)->format('Y-m-d'),
                'start_time'   => '15:00',
                'end_time'     => '18:00',
                'num_guests'   => 5,
                'status'       => 'approved',
            ],
            [
                'full_name'    => 'Roberto Garcia',
                'email'        => 'roberto@example.com',
                'phone'        => '+63 917 555 2002',
                'room_id'      => $rooms->firstWhere('type', 'deluxe')?->id ?? $rooms->first()->id,
                'booking_date' => now()->addDays(6)->format('Y-m-d'),
                'start_time'   => '19:00',
                'end_time'     => '22:00',
                'num_guests'   => 12,
                'status'       => 'pending',
            ],
            [
                'full_name'    => 'Sophia Tan',
                'email'        => 'sophia@example.com',
                'phone'        => '+63 917 555 3003',
                'room_id'      => $rooms->firstWhere('type', 'vip')?->id ?? $rooms->first()->id,
                'booking_date' => now()->subDays(3)->format('Y-m-d'),
                'start_time'   => '20:00',
                'end_time'     => '23:00',
                'num_guests'   => 15,
                'status'       => 'completed',
            ],
        ];

        foreach ($guestBookings as $data) {
            $room      = Room::find($data['room_id']);
            $totalCost = Booking::calculateCost($room, $data['start_time'], $data['end_time']);

            GuestBooking::create(array_merge($data, [
                'total_cost'       => $totalCost,
                'special_requests' => null,
            ]));
        }

        $this->command->info('Sample bookings seeded (4 member + 3 guest).');
    }
}
