<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\RoomType;
use App\Models\RoomInventory;
use App\Models\RoomUnit;
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

        // 2. RUN EXTERNAL ROOM TYPE SEEDER
        // Instead of hardcoding one room here, we run your custom file with the 3 room types!
        $this->call([
            RoomTypeSeeder::class,
        ]);

        // 3. POPULATE CALENDAR INVENTORY FOR ALL ROOMS FOR THE NEXT 30 DAYS
        // We fetch ALL the room types your custom seeder just made, and generate inventory calendars for them!
        $roomTypes = RoomType::all();
        $startDate = Carbon::today();

        foreach ($roomTypes as $roomType) {
            for ($i = 0; $i < 30; $i++) {
                $dateString = $startDate->copy()->addDays($i)->toDateString();

                RoomInventory::firstOrCreate(
                    [
                        'room_type_id' => $roomType->id,
                        'inventory_date' => $dateString
                    ],
                    [
                        'available_count' => $roomType->total_inventory
                    ]
                );
            }
        }
    }
}