<?php

namespace Tests\Feature\Locations;

use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CheckinApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_checkin_with_specific_date()
    {
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
    }

    public function test_checkin_without_date()
    {
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
    }

    public function test_get_checkin()
    {
        $user = User::factory()->create();
        $checkin = Checkin::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->getJson('/api/locations/checkins/' . $checkin->id);
        $response->assertStatus(200);

        $checkin = Checkin::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/locations/checkins/' . $checkin->id);
        $response->assertStatus(403);
    }

    public function test_delete_checkin()
    {
        $user = User::factory()->create();
        $checkin = Checkin::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->deleteJson('/api/locations/checkins/' . $checkin->id);
        $response->assertStatus(200);
        $this->assertNotNull($response->json('deleted_at'));
        $this->assertSoftDeleted('checkins', ['id' => $checkin->id]);

        $checkin = Checkin::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/locations/checkins/' . $checkin->id);
        $response->assertStatus(403);
    }
}
