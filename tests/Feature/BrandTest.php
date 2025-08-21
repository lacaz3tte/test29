<?php

use App\Models\Brand;

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

it('can get list of brands', function () {
    Brand::factory()->count(5)->create();

    $response = $this->getJson('/api/brands');

    $response->assertStatus(200)
        ->assertJsonCount(5)
        ->assertJsonStructure([
            '*' => ['id', 'name']
        ]);
});

it('returns brands in alphabetical order', function () {
    Brand::factory()->create(['name' => 'Z Brand']);
    Brand::factory()->create(['name' => 'A Brand']);
    Brand::factory()->create(['name' => 'M Brand']);

    $response = $this->getJson('/api/brands');

    $response->assertStatus(200);

    $brands = $response->json();
    $this->assertEquals('A Brand', $brands[0]['name']);
    $this->assertEquals('M Brand', $brands[1]['name']);
    $this->assertEquals('Z Brand', $brands[2]['name']);
});
