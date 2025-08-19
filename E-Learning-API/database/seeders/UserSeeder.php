<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ananasacademy.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Instructor
        User::create([
            'name' => 'John Instructor',
            'email' => 'instructor@ananasacademy.com',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
        ]);

        // Create some Students
        User::factory()->count(10)->create([
            'role' => 'student',
        ]);
    }
}
