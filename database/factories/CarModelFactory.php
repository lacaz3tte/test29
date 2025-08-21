<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarModelFactory extends Factory
{
    public function definition(): array
    {
        $models = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Highlander', 'Land Cruiser'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'HR-V'],
            'Ford' => ['Focus', 'Fiesta', 'Mondeo', 'Kuga', 'Explorer'],
            'BMW' => ['3 Series', '5 Series', '7 Series', 'X5', 'X3'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE'],
            'Audi' => ['A4', 'A6', 'A8', 'Q5', 'Q7'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'Touareg'],
            'Nissan' => ['Qashqai', 'X-Trail', 'Juke', 'Leaf', 'Patrol'],
            'Hyundai' => ['Tucson', 'Santa Fe', 'Elantra', 'Sonata', 'Creta'],
            'Kia' => ['Sportage', 'Sorento', 'Rio', 'Optima', 'Stinger'],
        ];

        $brand = Brand::inRandomOrder()->first() ?? Brand::factory()->create();
        $brandName = $brand->name;

        $availableModels = $models[$brandName] ?? ['Unknown Model'];

        return [
            'name' => $this->faker->randomElement($availableModels),
            'brand_id' => $brand->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
