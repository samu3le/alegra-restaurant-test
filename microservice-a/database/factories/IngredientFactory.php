<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => '',
            'key' => '',
            'is_active' => $this->faker->boolean(),
            'stock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
