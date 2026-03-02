<?php

use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\User;
use Laravel\Sanctum\Sanctum;


uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('checkin with specific date', function () {
    Sanctum::actingAs($user = User::factory()->create());
    $data = [
        'location' => Location::factory()->create(['user_id' => $user->id])->id,
        'date' => '2022-03-01 17:00:00',
        'note' => $this->faker->sentence(),
    ];
    $response = $this->postJson('/api/locations/checkins', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('checkins', [
        'user_id' => $user->id,
        'location_id' => $data['location'],
        'checkin_at' => $data['date'],
        'note' => $data['note'],
    ]);
});

test('checkin without date', function () {
    Sanctum::actingAs($user = User::factory()->create());
    $data = [
        'location' => Location::factory()->create(['user_id' => $user->id])->id,
        'note' => $this->faker->sentence(),
    ];
    $this->freezeTime();
    $response = $this->postJson('/api/locations/checkins', $data);
    $response->assertStatus(201);
    $this->assertDatabaseHas('checkins', [
        'user_id' => $user->id,
        'location_id' => $data['location'],
        'checkin_at' => now(),
        'note' => $data['note'],
    ]);
});

test('get checkin', function () {
    Sanctum::actingAs($user = User::factory()->create());

    $checkin = Checkin::factory()->create(['user_id' => $user->id]);
    $response = $this->getJson('/api/locations/checkins/'.$checkin->id);
    $response->assertStatus(200);

    $checkin = Checkin::factory()->create();
    $response = $this->getJson('/api/locations/checkins/'.$checkin->id);
    $response->assertStatus(403);
});

test('delete checkin', function () {
    Sanctum::actingAs($user = User::factory()->create());

    $checkin = Checkin::factory()->create(['user_id' => $user->id]);
    $response = $this->deleteJson('/api/locations/checkins/'.$checkin->id);
    $response->assertStatus(200);
    expect($response->json('deleted_at'))->not->toBeNull();
    $this->assertSoftDeleted('checkins', ['id' => $checkin->id]);

    $checkin = Checkin::factory()->create();
    $response = $this->deleteJson('/api/locations/checkins/'.$checkin->id);
    $response->assertStatus(403);
    expect($response->json('deleted_at'))->toBeNull();
    $this->assertNotSoftDeleted('checkins', ['id' => $checkin->id]);
});
