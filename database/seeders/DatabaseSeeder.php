<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Execute seeders in order
        // IMPORTANT: RoleSeeder must run BEFORE UserSeeder
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
