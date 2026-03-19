<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@donaton.test'],
            [
                'name' => 'Admin DONATON',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@donaton.test'],
            [
                'name' => 'Usuario DONATON',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}