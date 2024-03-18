<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'phone' => '1234567890',
            'role' => UserRole::ADMIN,
            'password' => Hash::make('12345')
        ]);
    }
}
