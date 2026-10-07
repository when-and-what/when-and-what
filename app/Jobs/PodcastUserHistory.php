<?php

namespace App\Jobs;

use App\Actions\LogPodcast;
use App\Models\AccountUser;
use App\Notifications\PocketCastsAuthenticationFailed;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class PodcastUserHistory implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Pocket Casts playingStatus for a finished episode.
     */
    const COMPLETED = 3;

    protected string $filename;

    /**
     * Create a new job instance.
     */
    public function __construct(public AccountUser $userAccount)
    {
        $this->filename = 'podcast_history/'.$this->userAccount->user_id.'.json';
    }

    public function uniqueId(): string
    {
        return (string) $this->userAccount->user_id;
    }

    /**
     * Execute the job.
     */
    public function handle(LogPodcast $log): void
    {
        $previous = null;
        $pending = [];
        if (Storage::disk('local')->fileExists($this->filename)) {
            $json = json_decode(Storage::get($this->filename), true);
            $previous = collect($json['episodes'])->keyBy('uuid');
            $pending = $json['pending'] ?? [];
        }
        try {
            $history = Http::withToken($this->userAccount->token)
                ->retry(3, 1000)
                ->post('https://api.pocketcasts.com/user/history')
                ->json();
        } catch (RequestException $e) {
            if (! in_array($e->response->status(), [401, 403])) {
                throw $e;
            }

            // Stop syncing until the user reconnects, so they are only emailed once.
            $this->userAccount->update(['auth_failed_at' => now()]);
            $this->userAccount->user->notify(new PocketCastsAuthenticationFailed);

            return;
        }

        // Position each deferred episode was at when it was last logged.
        $history['pending'] = [];

        if ($previous) {
            $playDay = now($this->userAccount->user->timezone)->startOfDay();
            foreach ($history['episodes'] as $index => $episode) {
                $seen = $previous[$episode['uuid']]['playedUpTo'] ?? 0;
                $logged = $pending[$episode['uuid']] ?? $seen;

                // Still being listened to, wait until it stops moving to log the whole session.
                if ($index == 0 && $episode['playingStatus'] != self::COMPLETED && $episode['playedUpTo'] != $seen) {
                    $history['pending'][$episode['uuid']] = $logged;

                    continue;
                }

                if ($episode['playedUpTo'] == $logged) {
                    continue;
                }

                // A lower position means the episode was restarted.
                $seconds = $episode['playedUpTo'] > $logged ? $episode['playedUpTo'] - $logged : $episode['playedUpTo'];
                $log->fromHistory($episode, $this->userAccount->user_id, $playDay, $seconds);
            }
        }

        Storage::disk('local')->put($this->filename, json_encode($history), Visibility::PRIVATE);
    }
}
