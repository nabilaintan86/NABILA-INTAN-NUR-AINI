<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@radnet.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Staff NOC',
            'email' => 'staff@radnet.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $this->command->info('✅ User berhasil dibuat!');
        $this->command->info('   Super Admin → superadmin@radnet.com / password123');
        $this->command->info('   Staff       → staff@radnet.com / password123');
    }
}