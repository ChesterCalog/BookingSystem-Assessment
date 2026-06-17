<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name'           => 'The Starlight Booth',
                'type'           => 'standard',
                'size'           => 'small',
                'capacity'       => 4,
                'price_per_hour' => 200.00,
                'description'    => 'A cozy intimate booth perfect for couples or close friends. Features a curated song list and mood lighting.',
                'amenities'      => ['2 Microphones', 'HD TV Screen', 'Air Conditioning', 'Song Book'],
                'is_available'   => true,
            ],
            [
                'name'           => 'The Neon Stage',
                'type'           => 'standard',
                'size'           => 'medium',
                'capacity'       => 8,
                'price_per_hour' => 350.00,
                'description'    => 'A vibrant medium-sized room with neon aesthetics. Great for small groups and barkada nights.',
                'amenities'      => ['4 Microphones', 'HD TV Screen', 'Surround Sound', 'Air Conditioning', 'Sofa Seating', 'Song Book'],
                'is_available'   => true,
            ],
            [
                'name'           => 'The Deluxe Den',
                'type'           => 'deluxe',
                'size'           => 'medium',
                'capacity'       => 10,
                'price_per_hour' => 500.00,
                'description'    => 'Upgraded experience with premium sound system, tambourine set, and a mini refreshment bar.',
                'amenities'      => ['4 Microphones', '4K TV Screen', 'Surround Sound', 'Air Conditioning', 'Sofa Seating', 'Mini Bar', 'Tambourine', 'WiFi'],
                'is_available'   => true,
            ],
            [
                'name'           => 'The Golden Mic Room',
                'type'           => 'deluxe',
                'size'           => 'large',
                'capacity'       => 15,
                'price_per_hour' => 750.00,
                'description'    => 'Our flagship deluxe room. Perfect for medium-sized celebrations with premium amenities.',
                'amenities'      => ['6 Microphones', '4K TV Screen', 'Surround Sound', 'Air Conditioning', 'Sofa Seating', 'Mini Bar', 'Tambourine', 'Party Lights', 'WiFi', 'Song Book'],
                'is_available'   => true,
            ],
            [
                'name'           => 'The VIP Lounge',
                'type'           => 'vip',
                'size'           => 'large',
                'capacity'       => 18,
                'price_per_hour' => 1200.00,
                'description'    => 'The ultimate VIP experience. Luxury furnishings, private bathroom, dedicated attendant, and studio-grade audio.',
                'amenities'      => ['8 Microphones', '75" 4K Screen', 'Studio Sound', 'Air Conditioning', 'Private Bathroom', 'Mini Bar', 'Tambourine', 'Party Lights', 'Disco Ball', 'WiFi', 'Song Book'],
                'is_available'   => true,
            ],
            [
                'name'           => 'The Grand Arena',
                'type'           => 'party',
                'size'           => 'xlarge',
                'capacity'       => 30,
                'price_per_hour' => 2000.00,
                'description'    => 'Our largest room — the ultimate party hall! Holds up to 30 guests with a concert-grade setup and dance floor.',
                'amenities'      => ['10 Microphones', '85" 4K Screen', 'Concert Sound', 'Air Conditioning', 'Private Bathroom', 'Full Bar', 'Tambourine', 'Party Lights', 'Disco Ball', 'WiFi', 'Stage Area', 'Dance Floor'],
                'is_available'   => true,
            ],
        ];

        foreach ($rooms as $roomData) {
            Room::firstOrCreate(['name' => $roomData['name']], $roomData);
        }

        $this->command->info('6 sample rooms seeded.');
    }
}
