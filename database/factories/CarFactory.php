<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        $colors = ['Красный', 'Синий', 'Зеленый', 'Черный', 'Белый', 'Серый', 'Серебристый', 'Желтый'];

        return [
            'car_model_id' => CarModel::inRandomOrder()->first() ?? CarModel::factory()->create(),
            'user_id' => User::inRandomOrder()->first() ?? User::factory()->create(),
            'year' => $this->faker->numberBetween(2000, 2024),
            'mileage' => $this->faker->numberBetween(1000, 300000),
            'color' => $this->faker->randomElement($colors),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
