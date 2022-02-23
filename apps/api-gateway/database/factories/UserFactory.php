<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'nickname' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->email(),
            'password' => '12345678',
            'is_active' => $this->faker->boolean(),
            'role' => 1,
        ];
    }
}
