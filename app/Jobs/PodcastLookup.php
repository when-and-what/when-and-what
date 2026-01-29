<?php

namespace App\Jobs;

use App\Models\Podcasts\Podcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class PodcastLookup implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $uuid)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $podcast = Podcast::find($this->uuid);
        $imagePath = 'podcasts/'.$podcast->id.'.webp';
        $result = Http::get('https://podcast-api.pocketcasts.com/podcast/full/'.$podcast->id)
            ->throw()
            ->json();
        $image = Http::get('https://static.pocketcasts.com/discover/images/webp/480/'.$podcast->id.'.webp')
            ->body();
        Storage::disk('public')->put($imagePath, $image);

        $podcast->description = $result['podcast']['description'];
        $podcast->author = $result['podcast']['author'];
        $podcast->is_private = $result['podcast']['is_private'];
        $podcast->url = $result['podcast']['url'];
        $podcast->image = $imagePath;
        $podcast->save();
    }
}
