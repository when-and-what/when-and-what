<?php

namespace Tests\Feature\Locations;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PendingCheckinApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_pending_checkin_with_specific_time()
    {
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
    }

    public function test_pending_checkin()
    {
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
    }

    public function test_pending_checkin_with_name_and_notes()
    {
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
    }
}
