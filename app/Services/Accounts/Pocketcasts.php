<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\Podcasts\EpisodePlay;
use App\Services\UserAccount;
use Carbon\Carbon;

class Pocketcasts extends UserAccount
{
    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $plays = EpisodePlay::where('user_id', $this->accountUser->user_id)
            ->after($startDate)
            ->before($endDate)
            ->with('episode.podcast')
            ->get();

        $dashboard = new DashboardResponse('pocketcasts');
        foreach ($plays as $play) {
            $dashboard->addEvent(
                id: $play->id,
                date: $play->played_at,
                title: $play->episode->title,
                details: [
                    'dateLink' => route('podcasts.plays.edit', $play),
                    'icon' => '🎙️',
                    'subTitle' => $play->episode->podcast->title,
                ]
            );
        }
        $dashboard->addItem('Episodes', $plays->unique('episode_id')->count(), '🎙️');
        if ($plays->isNotEmpty()) {
            $dashboard->addItem('Minutes', floor($plays->sum('seconds') / 60), '🎙️');
        }

        return $dashboard;
    }
}
