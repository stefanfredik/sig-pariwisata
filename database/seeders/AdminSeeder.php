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
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sigwisata.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'username' => 'user',
            'email' => 'user@sigwisata.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
