<?php

namespace App\Http\Controllers\Api\Podcasts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Podcasts\EpisodePlayRequest;
use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\Podcast;
use Carbon\Carbon;

class EpisodePlayController extends Controller
{
    public function __invoke(EpisodePlayRequest $request)
    {
        $response = $request->validated();

        $podcast = Podcast::firstOrCreate(
            [
                'name' => $response['podcast'],
                'created_by' => $request->user()->id,
            ],
            [
                'nickname' => $response['podcast'],
            ]
        );

        $episode = Episode::firstOrCreate(
            [
                'podcast_id' => $podcast->id,
                'name' => $response['episode'],
            ],
            [
                'created_by' => $request->user()->id,
            ]
        );

        $play = new EpisodePlay();
        $play->episode_id = $episode->id;
        $play->user_id = $request->user()->id;
        if (isset($response['date'])) {
            $play->played_at = new Carbon($response['played_at'], 'UTC');
        } else {
            $play->played_at = new Carbon();
        }
        $play->seconds = $response['seconds'];
        $play->save();

        return response($play, 201);
    }
}
