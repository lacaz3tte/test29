<?php

use App\Models\Car;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

it('allows user to view their own car', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user->id]);

    expect($user->can('view', $car))->toBeTrue();
});

it('denies user to view other user car', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user2->id]);

    expect($user1->can('view', $car))->toBeFalse();
});

it('allows user to update their own car', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user->id]);

    expect($user->can('update', $car))->toBeTrue();
});

it('denies user to update other user car', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user2->id]);

    expect($user1->can('update', $car))->toBeFalse();
});

it('allows user to delete their own car', function () {
    $user = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user->id]);

    expect($user->can('delete', $car))->toBeTrue();
});

it('denies user to delete other user car', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $car = Car::factory()->create(['user_id' => $user2->id]);

    expect($user1->can('delete', $car))->toBeFalse();
});
