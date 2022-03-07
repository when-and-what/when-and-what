<?php

namespace Tests\Feature\Podcasts;

use App\Models\Podcasts\Episode;
use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpisodePlayApi extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_play_episode_now()
    {
        $data = [
            'podcast' => $this->faker->words(random_int(3, 7), true),
            'episode' => $this->faker->words(random_int(3, 7), true),
            'seconds' => $this->faker()->numberBetween(1, 1900),
        ];
        $this->play_api_response($data);
    }

    public function test_play_episode_date()
    {
        $data = [
            'podcast' => $this->faker->words(random_int(3, 7), true),
            'episode' => $this->faker->words(random_int(3, 7), true),
            'seconds' => $this->faker()->numberBetween(1, 1900),
            'played_at' => $this->faker
                ->dateTimeBetween('-30 days', '-1 day')
                ->format('Y-m-d H:i:s'),
        ];
        $this->play_api_response($data);
    }

    public function test_multiple_users_playing_same_episode()
    {
        $episode = Episode::factory()->create();
        $user = User::factory()->create();
        $data = [
            'podcast' => $episode->podcast->name,
            'episode' => $episode->name,
            'seconds' => $this->faker()->numberBetween(1, 1900),
            'played_at' => $this->faker
                ->dateTimeBetween('-30 days', '-1 day')
                ->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($user)->postJson('/api/podcasts/play', $data);

        $response->assertStatus(201);

        $this->assertDatabaseCount('podcasts', 2);
        $this->assertDatabaseHas('podcasts', [
            'name' => $data['podcast'],
            'created_by' => $user->id,
        ]);
        $podcast = Podcast::whereBelongsTo($user)
            ->name($data['podcast'])
            ->first();
        $this->assertNotEquals($episode->podcast_id, $podcast->id);

        $this->assertDatabaseCount('podcast_episodes', 2);
        $this->assertDatabaseHas('podcast_episodes', [
            'id' => $response->json('episode_id'),
            'podcast_id' => $podcast->id,
            'name' => $data['episode'],
        ]);
        $this->assertNotEquals($episode->id, $response->json('episode_id'));

        $this->assertDatabaseHas('podcast_episode_plays', [
            'id' => $response->json('id'),
            'episode_id' => $response->json('episode_id'),
            'user_id' => $user->id,
            'played_at' => $data['played_at'],
            'seconds' => $data['seconds'],
        ]);
    }

    private function play_api_response(array $data)
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/podcasts/play', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('podcasts', [
            'name' => $data['podcast'],
            'created_by' => $user->id,
        ]);
        $podcast = Podcast::name($data['podcast'])->first();

        $this->assertDatabaseHas('podcast_episodes', [
            'id' => $response->json('episode_id'),
            'podcast_id' => $podcast->id,
            'name' => $data['episode'],
        ]);

        $this->assertDatabaseHas('podcast_episode_plays', [
            'id' => $response->json('id'),
            'episode_id' => $response->json('episode_id'),
            'user_id' => $user->id,
            'played_at' => isset($data['played_at']) ? $data['played_at'] : gmdate('Y-m-d H:i:s'),
            'seconds' => $data['seconds'],
        ]);
    }
}
