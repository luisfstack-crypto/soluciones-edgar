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
            ['email' => 'solucionesedgar@gmail.com'],
            [
                'name' => 'Edgar Solutions',
                'password' => Hash::make('6O4J@M6$FNg4r5£l%:nO)U_Mv'),
                'is_admin' => true, 
                'email_verified_at' => now(), 
            ]
        );
    }
}
