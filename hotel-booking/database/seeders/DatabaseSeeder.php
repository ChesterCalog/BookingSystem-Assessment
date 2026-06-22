<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RoomType;
use App\Models\RoomInventory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. GENERATE SYSTEM USERS WITH DISTINCT ROLES
        User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            ['name' => 'System Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'staff@hotel.com'],
            ['name' => 'Desk Clerk Sam', 'password' => bcrypt('password'), 'role' => 'staff']
        );

        User::firstOrCreate(
            ['email' => 'guest@example.com'],
            ['name' => 'John Doe', 'password' => bcrypt('password'), 'role' => 'customer']
        );

        // 2. GENERATE DEFAULT ROOM TYPE
        $roomType = RoomType::firstOrCreate(
            ['name' => 'Deluxe Suite'],
            ['base_price' => 150.00, 'total_inventory' => 5]
        );

        // 3. POPULATE CALENDAR INVENTORY FOR THE NEXT 30 DAYS
        $startDate = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $dateString = $startDate->copy()->addDays($i)->toDateString();

            RoomInventory::firstOrCreate(
                [
                    'room_type_id' => $roomType->id,
                    'inventory_date' => $dateString
                ],
                [
                    'available_count' => $roomType->total_inventory,
                    'price_override' => null
                ]
            );
        }
    }
}