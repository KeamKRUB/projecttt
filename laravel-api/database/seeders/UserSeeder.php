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
        User::query()->createOrFirst([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        User::query()->createOrFirst([
            'email' => 'user01@example.com'
        ], [
            'name' => 'User One',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
    }
}
