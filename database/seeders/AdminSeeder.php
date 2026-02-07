<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'manuel37@gmail.com'],
            [
                'name' => 'Manuel Admin',
                'password' => Hash::make('ManuelAdmin2026!'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
