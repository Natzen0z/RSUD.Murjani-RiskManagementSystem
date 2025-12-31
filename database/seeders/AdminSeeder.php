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
        User::updateOrCreate(
            ['email' => 'kusnadijaya@rsudmurjani.id'],
            [
                'name' => 'Kusnadi Jaya',
                'password' => Hash::make('kusnadijaya1'),
                'role' => 'admin',
            ]
        );

        // Create a sample regular user for testing
        User::updateOrCreate(
            ['email' => 'user@rsudmurjani.id'],
            [
                'name' => 'User Test',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}
