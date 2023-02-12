<?php

namespace Tests\Feature\Locations;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PendingCheckinTest extends TestCase
{
    use WithFaker;

    public function test_pending_checkin_with_specific_time()
    {
        $user = User::factory()->create(['timezone' => 'America/Chicago']);
        $data = [
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'date' => '2023-02-01T09:20',
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
    }

    public function test_pending_checkin()
    {
        $user = User::factory()->create(['timezone' => 'America/Denver']);
        $data = [
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
        $response = $this->actingAs($user)->post('/locations/checkins/pending', $data);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
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
        $user = User::factory()->create(['timezone' => 'America/Denver']);
        $data = [
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'date' => '2023-02-01T12:30',
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
    }

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
}
