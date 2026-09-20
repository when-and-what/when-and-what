<?php

use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\User;

test('checkin store requires browser timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/locations/checkins', [
        'location' => $location->id,
        'date' => '2023-02-01T09:20',
    ]);

    $response->assertSessionHasErrors('browser_timezone');
});

test('checkin store uses browser timezone, not profile timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/locations/checkins', [
        'location' => $location->id,
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'America/Denver',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('checkins', [
        'user_id' => $user->id,
        'location_id' => $location->id,
        'checkin_at' => '2023-02-01 16:20:00',
    ]);
});

test('checkin store requires date', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/locations/checkins', [
        'location' => $location->id,
        'browser_timezone' => 'America/Chicago',
    ]);

    $response->assertSessionHasErrors('date');
});

test('checkin update requires browser timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $checkin = Checkin::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put("/locations/checkins/{$checkin->id}", [
        'date' => '2023-02-01T09:20',
    ]);

    $response->assertSessionHasErrors('browser_timezone');
});

test('checkin update uses browser timezone, not profile timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $checkin = Checkin::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put("/locations/checkins/{$checkin->id}", [
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'America/Denver',
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('checkins', [
        'id' => $checkin->id,
        'checkin_at' => '2023-02-01 16:20:00',
    ]);
});
