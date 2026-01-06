<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Head of Department',
            'email' => 'hod@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true,
        ]);

        // Manager
        User::create([
            'name' => 'Manager One',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => true,
        ]);

        // Staff
        User::create([
            'name' => 'Staff One',
            'email' => 'staff1@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => true,
        ]);

        User::create([
            'name' => 'Staff Two',
            'email' => 'staff2@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => true,
        ]);
    }
}
