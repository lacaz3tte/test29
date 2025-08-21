<?php

use App\Models\Brand;
use App\Models\CarModel;

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

it('can get list of car models with brands', function () {
    $brand = Brand::factory()->create();
    CarModel::factory()->count(3)->create(['brand_id' => $brand->id]);

    $response = $this->getJson('/api/car-models');

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'brand' => ['id', 'name']]
        ]);
});

it('can get car models for specific brand', function () {
    $brand1 = Brand::factory()->create();
    $brand2 = Brand::factory()->create();

    CarModel::factory()->count(2)->create(['brand_id' => $brand1->id]);
    CarModel::factory()->count(3)->create(['brand_id' => $brand2->id]);

    $response = $this->getJson("/api/brands/{$brand1->id}/car-models");

    $response->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonStructure([
            '*' => ['id', 'name']
        ]);

    $models = $response->json();
    foreach ($models as $model) {
        $carModel = CarModel::find($model['id']);
        expect($carModel->brand_id)->toBe($brand1->id);
    }
});

it('returns empty array for brand with no models', function () {
    $brand = Brand::factory()->create();

    $response = $this->getJson("/api/brands/{$brand->id}/car-models");

    $response->assertStatus(200)
        ->assertJsonCount(0);
});

it('returns 404 for non-existent brand', function () {
    $response = $this->getJson('/api/brands/999/car-models');

    $response->assertStatus(404);
});
