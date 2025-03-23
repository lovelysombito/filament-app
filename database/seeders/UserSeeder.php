<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => strtoupper('Admin'),
            'last_name' => strtoupper('User'),
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Always hash passwords
            'address' => '123 Admin Street',
            'city' => 'Admin City',
            'country' => 'Adminland',
            'postcode' => '12345',
        ]);

        // Create multiple test users using a loop
        User::factory(10)->create(); // Ensure you have a UserFactory (Step 3)
    }
}
