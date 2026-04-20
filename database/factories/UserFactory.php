<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nickname' => fake()->userName(),
            'email' => fake()->email(),
            'password' => Hash::make("password"),
            'first_name' => fake()->name(),
            "last_name" => fake()->lastName(),
            "birthday" => fake()->date('Y-m-d', '2008-01-01'),
            "superuser" => false,
            'points' => fake()->numberBetween(10, 500),
            'avatar' => ""
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
