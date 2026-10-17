<?php

use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\User;

test('checkin store uses profile timezone, ignoring browser timezone', function () {
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
        'checkin_at' => '2023-02-01 15:20:00',
    ]);
});

test('checkin store requires date', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/locations/checkins', [
        'location' => $location->id,
    ]);

    $response->assertSessionHasErrors('date');
});

test('checkin update uses profile timezone, ignoring browser timezone', function () {
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
        'checkin_at' => '2023-02-01 15:20:00',
    ]);
});

test('checkin edit form round-trips without drift', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $checkin = Checkin::factory()->create([
        'user_id' => $user->id,
        'checkin_at' => '2023-02-01 15:20:00',
    ]);

    $this->actingAs($user)
        ->get(route('checkins.edit', $checkin))
        ->assertSee('value="2023-02-01T09:20"', false);

    $this->actingAs($user)->put("/locations/checkins/{$checkin->id}", [
        'date' => '2023-02-01T09:20',
    ])->assertSessionHasNoErrors();

    expect($checkin->fresh()->checkin_at->toDateTimeString())->toBe('2023-02-01 15:20:00');
});
