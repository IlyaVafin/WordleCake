<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nickname' => 'Admin',
            'email' => 'admin@mail.ru',
            'password' => "game2017",
            'first_name' => "Admin",
            "last_name" => "Adminovich",
            "birthday" => "2008-07-18",
            "superuser" => true,
            'points' => 0,
            'avatar' => ""
        ]);

        Category::factory(10)->create();

    }
}
