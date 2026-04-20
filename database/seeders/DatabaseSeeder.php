<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(['email' => "admin@mail.ru"], 
        [
            'nickname' => 'Admin',
            'password' => Hash::make("game2017"),
            'first_name' => "Admin",
            "last_name" => "Adminovich",
            "birthday" => "2008-07-18",
            "superuser" => true,
            'points' => 100,
            'avatar' => ""
        ]);
        GameSession::factory(2)->create();
        // Category::factory()->count(10)->has(Game::factory()->count(1)->hasWords(10))->create();
    }
}
