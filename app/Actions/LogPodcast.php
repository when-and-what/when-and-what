<?php

namespace App\Actions;

use App\Models\Podcasts\Episode;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Support\Carbon;

class LogPodcast
{
    public function fromHistory(array $history, int|User $user, Carbon $playDay): EpisodePlay
    {
        $userId = $user instanceof User ? $user->id : $user;

        $podcast = Podcast::find($history['podcastUuid']);
        if (! $podcast) {
            $podcast = $this->createPodcast($history['podcastUuid'], $history['podcastTitle']);
        }
        $episode = Episode::find($history['uuid']);
        if (! $episode) {
            $episode = $this->createEpisode($history['uuid'], $history['podcastUuid'], $history['title'], $history['duration']);
        }

        $lastPlay = EpisodePlay::where('episode_id', $history['uuid'])
            ->where('user_id', $userId)
            ->orderBy('play_day', 'desc')
            ->limit(1)
            ->first();
        $duration = $lastPlay ? $lastPlay->seconds : 0;

        $play = new EpisodePlay;
        $play->episode_id = $history['uuid'];
        $play->user_id = $userId;
        $play->play_date = $playDay;
        $play->seconds = $history['playedUpTo'] - $duration;
        $play->save();

        return $play;
    }

    private function createPodcast(string $uid, string $title): Podcast
    {
        $podcast = new Podcast;
        $podcast->id = $uid;
        $podcast->title = $title;
        $podcast->save();

        return $podcast;
    }

    private function createEpisode(string $uid, string $podcastUid, string $title, ?int $duration): Episode
    {
        $episode = new Episode;
        $episode->id = $uid;
        $episode->podcast_id = $podcastUid;
        $episode->title = $title;
        $episode->duration = $duration;
        $episode->save();

        return $episode;
    }
}
