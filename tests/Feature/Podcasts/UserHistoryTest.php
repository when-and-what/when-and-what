<?php

use App\Actions\LogPodcast;
use App\Jobs\PodcastUserHistory;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $podcast = new Podcast;
    $podcast->id = 'cb2108e0-8619-013a-d7f7-0acc26574db2';
    $podcast->title = 'Search Engine';
    $podcast->save();

    $account = new Account;
    $account = $account->forceFill([
        'name' => 'Pocket Casts',
        'slug' => 'pocketcasts',
        'service' => 'App\Services\Accounts\Pocketcasts',
        'scope' => '',
        'edit_token' => true,
    ]);
    $account->save();
});

test('first time lookup user history', function() {
    Storage::fake('local');

    $user = User::factory()->create();
    $user->accounts()->attach(1, [
        'token' => 'secrettoken',
        'refresh_token' => '',
    ]);
    $ua = AccountUser::find(1);

    Http::fake([
        'https://api.pocketcasts.com/user/history' => Http::response(file_get_contents('tests/Feature/Podcasts/history.json')),
        '*' => Http::response('', 409),
    ]);

    $history = new PodcastUserHistory($ua);
    $history->handle(new LogPodcast);

    Storage::assertExists('podcast_history/1.json');
});

test('update user history', function() {
    Storage::fake('local');
    Storage::put('podcast_history/1.json', file_get_contents('tests/Feature/Podcasts/history.json'));

    $user = User::factory()->create();
    $user->accounts()->attach(1, [
        'token' => 'secrettoken',
        'refresh_token' => '',
    ]);
    $ua = AccountUser::find(1);

    Http::fake([
        'https://api.pocketcasts.com/user/history' => Http::response(file_get_contents('tests/Feature/Podcasts/history-updated.json')),
        '*' => Http::response('', 409),
    ]);

    $history = new PodcastUserHistory($ua);
    $history->handle(new LogPodcast);

    $this->assertDatabaseHas('podcast_episodes', [
        'id' => 'episode-uuid',
        'podcast_id' => 'podcast-uuid',
        'title' => 'Are flushable wipes actually flushable?',
        'duration' => 3354,
    ]);
    $this->assertDatabaseHas('podcast_episode_plays', [
        'episode_id' => 'episode-uuid',
        'user_id' => 1,
        'play_date' => now()->subDay()->startOfDay()->toDateTimeString(),
        'seconds' => 3291,
    ]);

});
