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

        // 2. CALL YOUR CUSTOM ROOM TYPE SEEDER
        // This executes your RoomTypeSeeder.php file!
        $this->call([
            RoomTypeSeeder::class,
        ]);

        // Fetch ALL the room types we just seeded to use in our loops below
        $roomTypes = RoomType::all();

        // Dynamically generate Room Units for each Room Type
        $roomNumberCounter = 101;
        foreach ($roomTypes as $type) {
            // Creates units up to the total_inventory limit of each room type
            for ($i = 0; $i < $type->total_inventory; $i++) {
                RoomUnit::firstOrCreate(
                    ['room_number' => (string) $roomNumberCounter++],
                    [
                        'room_type_id' => $type->id,
                        'status' => 'available',
                    ]
                );
            }
        }

        // 3. POPULATE CALENDAR INVENTORY FOR THE NEXT 30 DAYS
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