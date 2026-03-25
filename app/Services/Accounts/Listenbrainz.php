<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Listenbrainz extends UserAccount
{
    /**
     * currently limited to 100 listens. this could be increased to 1000: https://listenbrainz.readthedocs.io/en/latest/users/api/core.html#listenbrainz.webserver.views.api_tools.MAX_ITEMS_PER_GET
     * although ideally this would be something like 50 and then lookup more if needed.
     */
    public function getRange(?Carbon $startDate, Carbon $endDate): Collection
    {
        $listens = Http::get('https://api.listenbrainz.org/1/user/'.$this->accountUser->username.'/listens', [
            'max_ts' => $endDate->timestamp,
            'count' => 100,
        ])->json(['payload']);

        $day = collect([]);
        foreach ($listens['listens'] as $listen) {
            $at = Carbon::createFromTimestamp($listen['listened_at']);
            if ($at->greaterThan($startDate)) {
                $day->push($listen);
            }
        }

        return $day;
    }

    public function dashboard(?Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $songs = $this->getRange($startDate, $endDate);
        $dashboard = new DashboardResponse('listenbrainz');
        $dashboard->collapsible(groupLabel: 'songs', groupIcon: '🎵');
        $dashboard->addItem('Music', count($songs), '🎵');
        foreach($songs as $song) {
            $artist = '';
            if(isset($song['track_metadata']['additional_info']['artist_names'][0])) {
                $artist = $song['track_metadata']['additional_info']['artist_names'][0];
            }elseif(isset($song['track_metadata']['artist_name'])) {
                $artist = $song['track_metadata']['artist_name'];
            }
            $dashboard->addEvent($song['inserted_at'], Carbon::createFromTimestamp($song['listened_at']), $song['track_metadata']['track_name'], [
                'subTitle' => $artist,
            ]);
        }

        return $dashboard;
    }
}
