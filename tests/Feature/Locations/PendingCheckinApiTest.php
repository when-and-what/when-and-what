<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;


uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('pending checkin with specific time', function () {
    Sanctum::actingAs($user = User::factory()->create());
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'date' => '2022-03-01 17:00:00',
    ];
    $response = $this->postJson('/api/locations/checkins/pending', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => $data['date'],
    ]);
});

test('pending checkin', function () {
    Sanctum::actingAs($user = User::factory()->create());
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
    ];
    $response = $this->postJson('/api/locations/checkins/pending', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => now(),
        'name' => null,
        'note' => null,
    ]);
});

test('pending checkin with name and notes', function () {
    $user = User::factory()->create();
    $data = [
        'latitude' => $this->faker->latitude(),
        'longitude' => $this->faker->longitude(),
        'name' => $this->faker()->words(3, true),
        'note' => $this->faker()->sentence(),
    ];
    $response = $this->actingAs($user)->postJson('/api/locations/checkins/pending', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('pending_checkins', [
        'user_id' => $user->id,
        'latitude' => $data['latitude'],
        'longitude' => $data['longitude'],
        'checkin_at' => now(),
        'name' => $data['name'],
        'note' => $data['note'],
    ]);
});
