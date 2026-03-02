<?php

use App\Models\Locations\Category;
use App\Models\Locations\Location;
use App\Models\User;



test('list of locations', function () {
    $this->markTestIncomplete();
});

test('create location', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('locations.create'))
        ->assertStatus(200);

    $this->actingAs($user)
        ->post(route('locations.store'))
        ->assertStatus(302)
        ->assertSessionHasErrors(['name', 'latitude', 'longitude']);

    $location = Location::factory()->make();
    $this->actingAs($user)->post(
        route('locations.store'),
        $location->only(['name', 'latitude', 'longitude'])
    );
    $this->assertDatabaseHas('locations', [
        'user_id' => $user->id,
        'name' => $location->name,
        'latitude' => $location->latitude,
        'longitude' => $location->longitude,
    ]);
});

test('edit location', function () {
    $user = User::factory()->create();
    $location = Location::factory()->create(['user_id' => $user->id]);
    $new = Location::factory()->make();

    $this->actingAs($user)
        ->put(
            route('locations.update', $location),
            $new->only(['name', 'latitude', 'longitude'])
        )
        ->assertSessionHasNoErrors();
    $this->assertDatabaseHas('locations', [
        'user_id' => $user->id,
        'name' => $new->name,
        'latitude' => $new->latitude,
        'longitude' => $new->longitude,
    ]);
});

test('edit location with categories', function () {
    $user = User::factory()->create();
    $location = Location::factory()->create(['user_id' => $user->id]);
    $categories = Category::factory(3)->create(['user_id' => $user->id]);
    $this->actingAs($user)
        ->put(route('locations.update', $location), [
            'name' => $location->name,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'category' => [1, 2],
        ])
        ->assertSessionHasNoErrors();

    $new = Location::where('id', $location->id)
        ->with('category')
        ->first();
    expect($new->category)->toHaveCount(2);
});

test('edit location with invalid categories', function () {
    $user = User::factory()->create();
    $location = Location::factory()->create(['user_id' => $user->id]);
    $category = Category::factory()->create();

    $this->actingAs($user)
        ->put(route('locations.update', $location), [
            'name' => $location->name,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'category' => [$category->id],
        ])
        ->assertSessionHasErrors('category');

    $new = Location::where('id', $location->id)
        ->with('category')
        ->first();
    expect($new->category)->toHaveCount(0);
});

test('edit location permissions', function () {
    $user = User::factory()->create();
    $location = Location::factory()->create(['user_id' => $user->id]);
    $user2 = User::factory()->create();

    $this->actingAs($user)
        ->get(route('locations.edit', $location))
        ->assertStatus(200);
    $this->actingAs($user2)
        ->get(route('locations.edit', $location))
        ->assertStatus(403);

    $this->actingAs($user)
        ->put(
            route('locations.update', $location),
            $location->only(['name', 'latitude', 'longitude'])
        )
        ->assertStatus(302);
    $this->actingAs($user2)
        ->put(route('locations.update', $location))
        ->assertStatus(403);
});

test('view location', function () {
    $this->markTestIncomplete();
});

test('delete location', function () {
    $this->markTestIncomplete();
});
