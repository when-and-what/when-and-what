<?php

namespace App\Jobs;

use App\Actions\LogPodcast;
use App\Models\AccountUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class PodcastUserHistory implements ShouldQueue
{
    use Queueable;

    protected string $filename;

    /**
     * Create a new job instance.
     */
    public function __construct(public AccountUser $userAccount)
    {
        $this->filename = 'podcast_history/'.$this->userAccount->user_id.'.json';
    }

    /**
     * Execute the job.
     */
    public function handle(LogPodcast $log): void
    {
        $yesterday = null;
        if (Storage::disk('local')->fileExists($this->filename)) {
            $json = json_decode(Storage::get($this->filename), true);
            $yesterday = collect($json['episodes'])->groupBy('uuid');
        }
        $history = Http::withToken($this->userAccount->token)
            ->post('https://api.pocketcasts.com/user/history')
            ->throw()
            ->json();

        Storage::disk('local')->put($this->filename, json_encode($history), Visibility::PRIVATE);

        if ($yesterday) {
            foreach ($history['episodes'] as $episode) {
                if (! isset($yesterday[$episode['uuid']]) || $yesterday[$episode['uuid']][0]['duration'] != $episode['duration']) {
                    $log->fromHistory($episode, $this->userAccount->user_id, now()->yesterday());
                }
            }
        }
    }
}
