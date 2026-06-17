<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@karaokeZone.com'],
            [
                'name'     => 'Admin',
                'phone'    => '+63 912 000 0001',
                'password' => Hash::make('admin1234'),
                'role'     => 'admin',
            ]
        );

        // Sample regular users
        $users = [
            ['name' => 'Maria Santos',  'email' => 'maria@example.com',  'phone' => '+63 912 111 1111'],
            ['name' => 'James Reyes',   'email' => 'james@example.com',  'phone' => '+63 912 222 2222'],
            ['name' => 'Ana Cruz',      'email' => 'ana@example.com',    'phone' => '+63 912 333 3333'],
            ['name' => 'Carlo Mendoza', 'email' => 'carlo@example.com',  'phone' => '+63 912 444 4444'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                array_merge($user, ['password' => Hash::make('password123'), 'role' => 'user'])
            );
        }

        $this->command->info('Users seeded. Admin: admin@karaokeZone.com / admin1234');
    }
}
