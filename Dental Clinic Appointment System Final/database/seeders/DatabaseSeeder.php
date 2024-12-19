<?php

namespace Database\Seeders;

use App\Models\User;
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

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'is_admin' => 1,
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);

        User::factory()->create([
            'is_admin' => 0,
            'email' => 'jaypabua@example.com',
            'password' => bcrypt('password123'),
        ]);
    }
}