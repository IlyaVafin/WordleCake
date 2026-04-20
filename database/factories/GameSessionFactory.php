<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => Game::factory()->hasWords(10),
            'status' => "open",
            "win_word" => 'placeholder',
            "start" => now(),
            
            'attempts_left' => 6
        ];
    }
    public function configure()
    {
        return $this->afterMaking(function (GameSession $session) {
            $game = $session->game;

            if ($game && $game->words->isNotEmpty()) {
                $session->win_word = $game->words->random()->text;
            } else {
                $session->win_word = fake()->word(); 
            }
        });
    }
}
