<?php

use App\Models\Account;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\User;

test('it returns podcast plays for the user local day', function () {
    $user = User::factory()->create(['timezone' => 'America/Denver']);
    $user->accounts()->attach(Account::where('slug', 'pocketcasts')->first()->id, [
        'token' => 'secrettoken',
        'refresh_token' => '',
    ]);
    $episode = Episode::factory()->create();
    // 2023-02-01 19:00 and 19:30 in Denver, same episode
    $play = EpisodePlay::factory()->create([
        'user_id' => $user->id,
        'episode_id' => $episode->id,
        'played_at' => '2023-02-02 02:00:00',
        'seconds' => 600,
    ]);
    EpisodePlay::factory()->create([
        'user_id' => $user->id,
        'episode_id' => $episode->id,
        'played_at' => '2023-02-02 02:30:00',
        'seconds' => 300,
    ]);
    // 2023-01-31 20:00 in Denver
    EpisodePlay::factory()->create([
        'user_id' => $user->id,
        'played_at' => '2023-02-01 03:00:00',
    ]);
    EpisodePlay::factory()->create(['played_at' => '2023-02-01 20:00:00']);

    $response = $this->actingAs($user)->getJson('api/dashboard/pocketcasts/2023-02-01');
    $response
        ->assertStatus(200)
        ->assertJsonCount(2, 'events')
        ->assertJsonFragment([
            'id' => 'pocketcasts-'.$play->id,
            'title' => $episode->title,
            'subTitle' => $episode->podcast->title,
            'icon' => '🎙️',
        ])
        ->assertJsonFragment(['name' => 'Episodes', 'value' => 1])
        ->assertJsonFragment(['name' => 'Minutes', 'value' => 15]);
});
