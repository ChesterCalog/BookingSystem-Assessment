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
                'total_inventory' => 5,
                'image' => 'deluxe-horizon-suite.jpg',
                'description' => 'Spacious suite with panoramic city views.',
                'size' => '200 sq ft',
                'amenities' => json_encode(['WiFi', 'Mini Bar', 'Balcony'])
            ]
        );

        // 2. Mid-Tier Smaller Room
        RoomType::updateOrCreate(
            ['name' => 'Superior Standard Room'],
            [
                'base_price' => 100.00,
                'total_inventory' => 5,
                'image' => 'superior-standard-room.jpg',
                'description' => 'Comfortable room with essential amenities.',
                'size' => '150 sq ft',
                'amenities' => json_encode(['WiFi', 'TV'])
            ]
        );

        // 3. Budget/Micro Smaller Room
        RoomType::updateOrCreate(
            ['name' => 'Compact Smart Studio'],
            [
                'base_price' => 50.00,
                'total_inventory' => 5,
                'image' => 'compact-smart-studio.jpg',
                'description' => 'Efficient and modern studio room.',
                'size' => '100 sq ft',
                'amenities' => json_encode(['WiFi'])
            ]
        );
        
        // Re-guard after seeding is complete (Best security practice)
        RoomType::reguard();
    }
}