<?php

use App\Models\Locations\Category;
use App\Models\Locations\Location;
use App\Models\User;

test('it creates a new location', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user)
        ->post(route('api.locations.store', [
            'name' => fake()->words(2, true),
            'category' => [$category->id],
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ]))
        ->assertStatus(201);
});

test('other user locations are not allowed', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $this->actingAs($user)
        ->postJson(route('api.locations.store', [
            'name' => fake()->words(2, true),
            'category' => [$category->id],
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ]))
        ->assertStatus(422)
        ->assertJsonValidationErrorFor('category');
});

test('display location', function () {
    $location = Location::factory()->create();
    $this->actingAs($location->user)
        ->get(route('api.locations.show', $location))
        ->assertOk()
        ->assertJsonFragment(['id' => $location->id]);

    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('api.locations.show', $location))
        ->assertStatus(403);
    $this->actingAs($user)
        ->get(route('api.locations.show', 12345))
        ->assertStatus(404);
});
