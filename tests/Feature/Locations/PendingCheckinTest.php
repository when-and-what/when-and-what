<?php

use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('pending checkin with specific time', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'America/Chicago',
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => '2023-02-01 15:20:00',
    ]);
});

test('pending checkin uses browser timezone, not profile timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'America/Denver',
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => '2023-02-01 16:20:00',
    ]);
});

test('pending checkin requires browser timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2023-02-01T09:20',
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertSessionHasErrors('browser_timezone');
});

test('pending checkin rejects invalid browser timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'Not/AZone',
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertSessionHasErrors('browser_timezone');
});

test('pending checkin requires date', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'browser_timezone' => 'America/Denver',
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertSessionHasErrors('date');
});

test('pending checkin with name and notes', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2023-02-01T12:30',
        'browser_timezone' => 'America/Denver',
        'name' => $this->faker()->words(3, true),
        'note' => $this->faker()->sentence(),
    ];
    $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => '2023-02-01 19:30:00',
        'name' => $data['name'],
        'note' => $data['note'],
    ]);
});

test('finalize pending checkin uses browser timezone, not profile timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);
    $pending = PendingCheckin::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('pending.update', $pending), [
        'location' => $location->id,
        'date' => '2023-02-01T09:20',
        'browser_timezone' => 'America/Denver',
        'note' => null,
    ]);

    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('checkins', [
        'user_id' => $user->id,
        'location_id' => $location->id,
        'checkin_at' => '2023-02-01 16:20:00',
    ]);
    $this->assertDatabaseMissing('pending_checkins', ['id' => $pending->id]);
});

test('finalize pending checkin requires browser timezone', function () {
    $user = User::factory()->create(['timezone' => 'America/Chicago']);
    $location = Location::factory()->create(['user_id' => $user->id]);
    $pending = PendingCheckin::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('pending.update', $pending), [
        'location' => $location->id,
        'date' => '2023-02-01T09:20',
        'note' => null,
    ]);

    $response->assertSessionHasErrors('browser_timezone');
});

test('view pending checking', function () {
    $pending = PendingCheckin::factory()->create();

    $this->actingAs($pending->user)
        ->get(route('pending.edit', $pending))
        ->assertOK();

    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('pending.edit', $pending))
        ->assertStatus(403);

    $this->get(route('pending.edit', $pending))->assertStatus(403);
});

// public function test_pending_checkin()
// {
//     Sanctum::actingAs($user = User::factory()->create());
//     $data = [
//         'latitude' => $this->faker->latitude(),
//         'longitude' => $this->faker->longitude(),
//     ];
//     $response = $this->postJson('/api/locations/checkins/pending', $data);
//     $response->assertStatus(201);
//     $this->assertDatabaseHas('pending_checkins', [
//         'user_id' => $user->id,
//         'latitude' => $data['latitude'],
//         'longitude' => $data['longitude'],
//         'checkin_at' => now(),
//         'name' => null,
//         'note' => null,
//     ]);
// }
// public function test_pending_checkin_with_name_and_notes()
// {
//     $user = User::factory()->create();
//     $data = [
//         'latitude' => $this->faker->latitude(),
//         'longitude' => $this->faker->longitude(),
//         'name' => $this->faker()->words(3, true),
//         'note' => $this->faker()->sentence(),
//     ];
//     $response = $this->actingAs($user)->postJson('/api/locations/checkins/pending', $data);
//     $response->assertStatus(201);
//     $this->assertDatabaseHas('pending_checkins', [
//         'user_id' => $user->id,
//         'latitude' => $data['latitude'],
//         'longitude' => $data['longitude'],
//         'checkin_at' => now(),
//         'name' => $data['name'],
//         'note' => $data['note'],
//     ]);
// }
