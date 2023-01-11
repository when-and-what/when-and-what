<?php

namespace Tests\Feature\Trackers;

use App\Models\Tracker;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageTrackersTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->user2 = User::factory()->create();
    }

    public function test_view_all_trackers()
    {
        $trackers = Tracker::factory(7)->create(['user_id' => $this->user->id]);
        $trackers2 = Tracker::factory(5)->create(['user_id' => $this->user2->id]);
        Tracker::factory(10)->create();

        $response = $this->get(route('trackers.index'));
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('trackers.index'));
        $response->assertStatus(200);
        $response->assertViewHas('trackers', $trackers);
        foreach ($trackers as $tracker) {
            $response->assertSeeText($tracker->name);
        }

        $response = $this->actingAs($this->user2)->get(route('trackers.index'));
        $response->assertStatus(200);
        $response->assertViewHas('trackers', $trackers2);
        foreach ($trackers2 as $tracker) {
            $response->assertSeeText($tracker->name);
        }
    }

    public function test_create_tracker()
    {
        $form = [
            'name' => 'hockey',
            'display_name' => 'Hockey',
            'icon' => '🏒',
        ];

        $response = $this->actingAs($this->user)->get(route('trackers.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('trackers', array_merge($form, ['user_id' => $this->user->id]));

        $response = $this->actingAs($this->user2)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('trackers', array_merge($form, ['user_id' => $this->user2->id]));

        $response = $this->actingAs($this->user)->post(route('trackers.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_view_tracker()
    {
        $tracker = Tracker::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('trackers.show', $tracker));
        $response->assertStatus(302);
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('trackers.show', $tracker));
        $response->assertStatus(200);
        $response->assertSeeText($tracker->display_name);

        $response = $this->actingAs($this->user2)->get(route('trackers.show', $tracker));
        $response->assertStatus(403);
    }

    public function test_edit_tracker()
    {
        $form = [
            'name' => 'hockey',
            'display_name' => 'Hockey',
            'icon' => '🏒',
        ];
        $tracker = Tracker::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('trackers.edit', $tracker));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->put(route('trackers.update', $tracker), $form);
        $response->assertRedirect(route('trackers.show', $tracker));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('trackers', [
            'user_id' => $this->user->id,
            'name' => $tracker->name,
            'display_name' => $form['display_name'],
            'icon' => $form['icon'],
        ]);

        $response = $this->actingAs($this->user2)->put(route('trackers.update', $tracker), $form);
        $response->assertStatus(403);

        $response = $this->put(route('trackers.update', $tracker), $form);
        $response->assertStatus(403);
    }

    public function test_delete_tracker()
    {
        $tracker = Tracker::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user2)->delete(route('trackers.destroy', $tracker));
        $response->assertStatus(403);
        $this->assertNotSoftDeleted($tracker);

        $response = $this->delete(route('trackers.destroy', $tracker));
        $response->assertStatus(403);
        $this->assertNotSoftDeleted($tracker);

        $response = $this->actingAs($this->user)->delete(route('trackers.destroy', $tracker));
        $response->assertRedirect(route('trackers.index'));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($tracker);
    }
}
