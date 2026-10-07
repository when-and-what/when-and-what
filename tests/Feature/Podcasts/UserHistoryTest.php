<?php

use App\Actions\LogPodcast;
use App\Jobs\PodcastUserHistory;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\Podcasts\Podcast;
use App\Models\User;
use App\Notifications\PocketCastsAuthenticationFailed;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Sleep;

beforeEach(function () {
    $podcast = new Podcast;
    $podcast->id = 'cb2108e0-8619-013a-d7f7-0acc26574db2';
    $podcast->title = 'Search Engine';
    $podcast->save();

    Storage::fake('local');

    $user = User::factory()->create(['timezone' => 'UTC']);
    $user->accounts()->attach(Account::where('slug', 'pocketcasts')->first()->id, [
        'token' => 'secrettoken',
        'refresh_token' => '',
    ]);
});

function historyEpisode(string $uuid, int $playedUpTo, int $playingStatus = 2): array
{
    return [
        'uuid' => $uuid,
        'title' => 'Episode '.$uuid,
        'duration' => 3600,
        'playingStatus' => $playingStatus,
        'playedUpTo' => $playedUpTo,
        'podcastUuid' => 'podcast-uuid',
        'podcastTitle' => 'Incognito Mode',
    ];
}

function runHistory(array $episodes): void
{
    Http::swap(new Factory);
    Http::fake([
        'https://api.pocketcasts.com/user/history' => Http::response(['total' => count($episodes), 'episodes' => $episodes]),
        '*' => Http::response('', 409),
    ]);

    (new PodcastUserHistory(AccountUser::find(1)))->handle(new LogPodcast);
}

function snapshotHistory(array $episodes): void
{
    Storage::put('podcast_history/1.json', json_encode(['total' => count($episodes), 'episodes' => $episodes]));
}

function pendingPosition(string $uuid): ?int
{
    $json = json_decode(Storage::get('podcast_history/1.json'), true);

    return $json['pending'][$uuid] ?? null;
}

test('first time lookup user history', function () {
    Http::fake([
        'https://api.pocketcasts.com/user/history' => Http::response(file_get_contents('tests/Feature/Podcasts/history.json')),
        '*' => Http::response('', 409),
    ]);

    $history = new PodcastUserHistory(AccountUser::find(1));
    $history->handle(new LogPodcast);

    Storage::assertExists('podcast_history/1.json');
    $this->assertDatabaseCount('podcast_episode_plays', 0);
});

test('episode being listened to is not logged yet', function () {
    snapshotHistory([historyEpisode('a', 1000)]);

    runHistory([historyEpisode('a', 1500)]);

    $this->assertDatabaseCount('podcast_episode_plays', 0);
    expect(pendingPosition('a'))->toBe(1000);
});

test('new episode being listened to is not logged yet', function () {
    snapshotHistory([historyEpisode('b', 500)]);

    runHistory([historyEpisode('a', 300), historyEpisode('b', 500)]);

    $this->assertDatabaseCount('podcast_episode_plays', 0);
    expect(pendingPosition('a'))->toBe(0);
});

test('episode is logged once it stops moving', function () {
    snapshotHistory([historyEpisode('a', 1000)]);

    runHistory([historyEpisode('a', 1500)]);
    runHistory([historyEpisode('a', 2000)]);
    $this->assertDatabaseCount('podcast_episode_plays', 0);

    runHistory([historyEpisode('a', 2000)]);

    $this->assertDatabaseCount('podcast_episode_plays', 1);
    $this->assertDatabaseHas('podcast_episode_plays', [
        'episode_id' => 'a',
        'user_id' => 1,
        'play_date' => now()->startOfDay()->toDateTimeString(),
        'seconds' => 1000,
    ]);
    $this->assertDatabaseHas('podcast_episodes', [
        'id' => 'a',
        'podcast_id' => 'podcast-uuid',
        'title' => 'Episode a',
        'duration' => 3600,
    ]);
    expect(pendingPosition('a'))->toBeNull();
});

test('completed episode is logged immediately', function () {
    snapshotHistory([historyEpisode('a', 1000)]);

    runHistory([historyEpisode('a', 3600, PodcastUserHistory::COMPLETED)]);

    $this->assertDatabaseHas('podcast_episode_plays', ['episode_id' => 'a', 'seconds' => 2600]);
});

test('episodes no longer being listened to are logged', function () {
    snapshotHistory([historyEpisode('b', 100)]);

    runHistory([historyEpisode('a', 600), historyEpisode('b', 900)]);

    $this->assertDatabaseCount('podcast_episode_plays', 1);
    $this->assertDatabaseHas('podcast_episode_plays', ['episode_id' => 'b', 'seconds' => 800]);
});

test('sequential listens log the time listened since last logged', function () {
    snapshotHistory([historyEpisode('a', 0)]);

    foreach ([1000, 3000, 4000] as $position) {
        runHistory([historyEpisode('a', $position)]);
        runHistory([historyEpisode('a', $position)]);
    }

    expect(DB::table('podcast_episode_plays')->orderBy('id')->pluck('seconds')->all())
        ->toBe([1000, 2000, 1000]);
});

test('restarted episode logs position from the beginning', function () {
    snapshotHistory([historyEpisode('b', 3000), historyEpisode('a', 100)]);

    runHistory([historyEpisode('a', 200), historyEpisode('b', 600)]);

    $this->assertDatabaseHas('podcast_episode_plays', ['episode_id' => 'b', 'seconds' => 600]);
});

test('command dispatches history for every pocketcasts user', function () {
    Queue::fake();

    $this->artisan('podcasts:history')->assertSuccessful()->run();

    Queue::assertPushed(PodcastUserHistory::class, 1);
});

test('fetch is retried after a failure', function () {
    Sleep::fake();
    Http::fake([
        'https://api.pocketcasts.com/user/history' => Http::sequence()
            ->push('', 500)
            ->push(['total' => 0, 'episodes' => []]),
    ]);

    (new PodcastUserHistory(AccountUser::find(1)))->handle(new LogPodcast);

    Http::assertSentCount(2);
    Storage::assertExists('podcast_history/1.json');
});

test('authentication failure emails the user once retries are exhausted', function () {
    Sleep::fake();
    Notification::fake();
    Http::fake(['https://api.pocketcasts.com/user/history' => Http::response('', 401)]);

    (new PodcastUserHistory(AccountUser::find(1)))->handle(new LogPodcast);

    Http::assertSentCount(3);
    expect(AccountUser::find(1)->auth_failed_at)->not->toBeNull();
    Notification::assertSentTo(User::find(1), PocketCastsAuthenticationFailed::class);
    Storage::assertMissing('podcast_history/1.json');
});

test('other failures are thrown without emailing the user', function () {
    Sleep::fake();
    Notification::fake();
    Http::fake(['https://api.pocketcasts.com/user/history' => Http::response('', 500)]);

    expect(fn () => (new PodcastUserHistory(AccountUser::find(1)))->handle(new LogPodcast))
        ->toThrow(RequestException::class);

    Http::assertSentCount(3);
    expect(AccountUser::find(1)->auth_failed_at)->toBeNull();
    Notification::assertNothingSent();
});

test('command skips users whose authentication failed', function () {
    Queue::fake();
    AccountUser::find(1)->update(['auth_failed_at' => now()]);

    $this->artisan('podcasts:history')->assertSuccessful()->run();

    Queue::assertNothingPushed();
});
