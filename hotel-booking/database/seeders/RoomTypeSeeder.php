<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run()
    {
        // FORCE LARAVEL TO BEYOND MASS ASSIGNMENT RESTRICTIONS
        RoomType::unguard();

        // 1. Deluxe Option
        RoomType::updateOrCreate(
            ['name' => 'Deluxe Horizon Suite'],
            [
                'base_price' => 250.00,
                'total_inventory' => 10
            ]
        );

        // 2. Mid-Tier Smaller Room
        RoomType::updateOrCreate(
            ['name' => 'Superior Standard Room'],
            [
                'base_price' => 100.00,
                'total_inventory' => 25
            ]
        );

        // 3. Budget/Micro Smaller Room
        RoomType::updateOrCreate(
            ['name' => 'Compact Smart Studio'],
            [
                'base_price' => 50.00,
                'total_inventory' => 40
            ]
        );
        
        // Re-guard after seeding is complete (Best security practice)
        RoomType::reguard();
    }
}