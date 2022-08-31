<?php

namespace Tests\Feature\Podcasts;

use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManagePodcastsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_create_podcast()
    {
        $response = $this->get(route('podcasts.create'));
        $response->assertStatus(302);

        $response = $this->actingAs($this->user)->get(route('podcasts.create'));
        $response->assertOk();
        $response->assertSeeText('Name');
        $response->assertSeeText('Website');
        $response->assertSeeText('Feed');

        $podcast = [
            'name' => $this->faker->words(3, true),
            'website' => $this->faker->url(),
        ];
        $response = $this->actingAs($this->user)->post(route('podcasts.store'), $podcast);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('podcasts', $podcast);
    }

    public function test_edit_podcast()
    {
        $podcast = Podcast::factory()->create(['created_by' => $this->user->id]);

        $response = $this->get(route('podcasts.edit', $podcast));
        $response->assertStatus(302);

        $response = $this->actingAs($this->user)->get(route('podcasts.edit', $podcast));
        $response->assertOk();
        $response->assertSeeText('Name');
        $response->assertSeeText('Website');
        $response->assertSeeText('Feed');

        $pod = [
            'name' => $podcast->name,
            'nickname' => $this->faker->words(2, true),
            'website' => $this->faker->url(),
        ];
        $response = $this->actingAs($this->user)->put(route('podcasts.update', $podcast), $pod);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('podcasts', [
            'id' => $podcast->id,
            'name' => $pod['name'],
            'nickname' => $pod['nickname'],
            'website' => $pod['website'],
        ]);
    }
}
