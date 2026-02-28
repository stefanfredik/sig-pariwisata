<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@sigwisata.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Create test user
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'Test User',
                'email' => 'user@sigwisata.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
