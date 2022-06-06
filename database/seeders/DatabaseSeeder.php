<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1, [
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ])->create();

        \App\Models\User::factory(1, [
            'name' => 'seller',
            'email' => 'seller@example.com',
            'role' => 'seller'
        ])->create();
    }
}
