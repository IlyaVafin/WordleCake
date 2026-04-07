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
            'nickname' => 'Admin',
            'email' => 'admin@mail.ru',
            'password' => Hash::make("game2017"),
            'first_name' => "Admin",
            "last_name" => "Adminovich",
            "birthday" => "2008-07-18",
            "superuser" => true,
            'points' => 0,
            'avatar' => ""
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
