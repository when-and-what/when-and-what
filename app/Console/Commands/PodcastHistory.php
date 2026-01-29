<?php

namespace App\Console\Commands;

use App\Actions\LogPodcast;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Visibility;

class PodcastHistory extends Command
{
    protected string $filename = 'podcast_history.json';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:podcast-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the pocketcast API to see what was listened to yesterday';

    /**
     * Execute the console command.
     */
    public function handle(LogPodcast $log)
    {
        $yesterday = null;
        if (Storage::fileExists($this->filename)) {
            $json = json_decode(Storage::get($this->filename), true);
            $yesterday = collect($json['episodes'])->groupBy('uuid');
        }
        $history = Http::withToken(env('POCKETCASTS_TOKEN'))
            ->post('https://api.pocketcasts.com/user/history')
            ->throw()
            ->json();
        // Storage::put($this->filename, json_encode($history), Visibility::PRIVATE);

        if ($yesterday) {
            $user = User::where('email', 'natec23@gmail.com')->first();
            foreach ($history['episodes'] as $episode) {
                if (isset($yesterday[$episode['uuid']]) && $yesterday[$episode['uuid']][0]['duration'] == $episode['duration']) {
                    return Command::SUCCESS;
                }
                $log->fromHistory($episode, $user, now()->yesterday());
            }
        }

        return Command::SUCCESS;
    }
}
