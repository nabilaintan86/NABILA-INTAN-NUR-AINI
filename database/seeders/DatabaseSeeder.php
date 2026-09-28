<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            DeviceSeeder::class,
            ActivityLogSeeder::class,
            PopMappingSeeder::class,
            DeviceConnectionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
