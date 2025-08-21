<?php

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
    $this->headers = ['Authorization' => 'Bearer ' . $this->token];

    $this->brand = Brand::factory()->create();
    $this->carModel = CarModel::factory()->create(['brand_id' => $this->brand->id]);
});

it('requires authentication for car endpoints', function () {
    $response = $this->getJson('/api/cars');
    $response->assertStatus(401);
});

it('can get list of user cars', function () {
    Car::factory()->count(3)->create(['user_id' => $this->user->id]);

    Car::factory()->create(['user_id' => User::factory()->create()->id]);

    $response = $this->withHeaders($this->headers)->getJson('/api/cars');

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonStructure([
            '*' => ['id', 'year', 'mileage', 'color', 'car_model', 'user_id']
        ]);

    $cars = $response->json();
    foreach ($cars as $car) {
        expect($car['user_id'])->toBe($this->user->id);
    }
});

it('can create a new car', function () {
    $carData = [
        'car_model_id' => $this->carModel->id,
        'year' => 2020,
        'mileage' => 50000,
        'color' => 'Красный'
    ];

    $response = $this->withHeaders($this->headers)
        ->postJson('/api/cars', $carData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id', 'year', 'mileage', 'color', 'car_model', 'user_id'
        ])
        ->assertJson([
            'year' => 2020,
            'mileage' => 50000,
            'color' => 'Красный',
            'user_id' => $this->user->id
        ]);

    $this->assertDatabaseHas('cars', [
        'user_id' => $this->user->id,
        'car_model_id' => $this->carModel->id,
        'year' => 2020
    ]);
});

it('validates car creation data', function () {
    $response = $this->withHeaders($this->headers)
        ->postJson('/api/cars', [
            'car_model_id' => 999,
            'year' => 1800,
            'mileage' => -100,
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['car_model_id', 'year', 'mileage']);
});

it('can show specific car', function () {
    $car = Car::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeaders($this->headers)
        ->getJson("/api/cars/{$car->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $car->id,
            'user_id' => $this->user->id
        ]);
});

it('cannot access other user car', function () {
    $otherUser = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeaders($this->headers)
        ->getJson("/api/cars/{$car->id}");

    $response->assertStatus(403);
});

it('can update car', function () {
    $car = Car::factory()->create(['user_id' => $this->user->id]);

    $updateData = [
        'year' => 2021,
        'mileage' => 60000,
        'color' => 'Синий'
    ];

    $response = $this->withHeaders($this->headers)
        ->putJson("/api/cars/{$car->id}", $updateData);

    $response->assertStatus(200)
        ->assertJson($updateData);

    $this->assertDatabaseHas('cars', [
        'id' => $car->id,
        'year' => 2021,
        'mileage' => 60000,
        'color' => 'Синий'
    ]);
});

it('cannot update other user car', function () {
    $otherUser = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeaders($this->headers)
        ->putJson("/api/cars/{$car->id}", ['color' => 'Зеленый']);

    $response->assertStatus(403);
});

it('can delete car', function () {
    $car = Car::factory()->create(['user_id' => $this->user->id]);

    $response = $this->withHeaders($this->headers)
        ->deleteJson("/api/cars/{$car->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('cars', ['id' => $car->id]);
});

it('cannot delete other user car', function () {
    $otherUser = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $otherUser->id]);

    $response = $this->withHeaders($this->headers)
        ->deleteJson("/api/cars/{$car->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('cars', ['id' => $car->id]);
});
