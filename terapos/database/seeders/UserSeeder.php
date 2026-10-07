<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'Owner',
                'role' => 'owner',
                'password' => Hash::make('owner123')
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Co-Owner',
                'role' => 'co_owner',
                'password' => Hash::make('admin123')
            ]
        );
    }
}