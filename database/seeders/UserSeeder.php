<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@radnet.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@radnet.com'],
            [
                'name' => 'Staff NOC',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );

        if ($this->command) {
            $this->command->info('✅ User berhasil dibuat!');
            $this->command->info('   Super Admin → superadmin@radnet.com / password123');
            $this->command->info('   Staff       → staff@radnet.com / password123');
        }
    }
}
