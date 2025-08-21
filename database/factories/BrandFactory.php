<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $brands = [
            'Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes-Benz',
            'Audi', 'Volkswagen', 'Nissan', 'Hyundai', 'Kia',
            'Chevrolet', 'Volvo', 'Lexus', 'Subaru', 'Mazda',
            'Jeep', 'Renault', 'Peugeot', 'Skoda', 'Seat'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($brands),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
