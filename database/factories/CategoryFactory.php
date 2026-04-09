<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => Str::random(5),
            "image" => "category/9udoW3t6YN3cXDvSPk9yVLNCHlP50AlMCDWWgm8P.jpg",
            "description" => fake()->sentence(2)
        ];
    }
}
