<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "level" => "medium",
            "increment_points" => fake()->randomNumber(2),
            "decrement_points" => fake()->randomNumber(1),
            "preview" => "category/2wwVLFLCu2Bus29ZnKmZAbAXLcs2Uh3DgqdSjYmR.jpg",
            "status" => "active",
            "category_id" => 1,
            "age_limit" => 16,
            "attempts" => 8,
        ];
    }
}
