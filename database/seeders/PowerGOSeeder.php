<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Device;

class PowerGOSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user default
        $user = User::firstOrCreate(
            ['email' => 'admin@powergo.test'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Buat device default yang terkait user ini
        Device::firstOrCreate(
            ['firebase_path' => 'powergo/realtime'],
            [
                'user_id' => $user->id,
                'name' => 'Main Device',
            ]
        );
    }
}
