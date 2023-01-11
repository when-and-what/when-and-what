<?php

namespace Tests\Feature\Trackers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageTrackersTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_tracker()
    {
        $form = [
            'name' => 'hockey',
            'display_name' => 'Hockey',
            'icon' => '🏒',
        ];

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $response = $this->actingAs($user2)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $response = $this->actingAs($user)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }
}
