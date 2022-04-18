<?php

namespace Tests\Feature\Locations;

use App\Models\Locations\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckinApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_checkin_with_specific_location_and_time()
    {
        $user = User::factory()->create();
        $data = [
            'location' => Location::factory()->create(['user_id' => $user->id])->id,
            'date' => '2022-03-01 17:00:00',
        ];
        $response = $this->actingAs($user)->postJson('/api/locations/checkin', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('checkins', [
            'user_id' => $user->id,
            'location_id' => $data['location'],
            'checkin_at' => $data['date'],
        ]);
    }

    public function test_checkin_with_specific_location()
    {
        $user = User::factory()->create();
        $data = [
            'location' => Location::factory()->create(['user_id' => $user->id])->id,
        ];
        $this->freezeTime();
        $response = $this->actingAs($user)->postJson('/api/locations/checkin', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('checkins', [
            'user_id' => $user->id,
            'location_id' => $data['location'],
            'checkin_at' => now(),
        ]);
    }
}
