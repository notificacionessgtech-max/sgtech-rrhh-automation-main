<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed initial users for the system
     * These are internal credentials for admin and RRHH staff
     */
    public function run(): void
    {
        // Create RRHH user
        User::create([
            'name' => 'RRHH',
            'email' => 'rrhh@gmail.com',
            'password' => Hash::make('RRHH$ecur3P@ssw0rd2026!'),
        ])->assignRole('rrhh');

        // Create Admin user
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Adm1n$ecur3P@ssw0rd2026!'),
        ])->assignRole('admin');
    }
}
