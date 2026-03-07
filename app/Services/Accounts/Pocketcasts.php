<?php
namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\Podcasts\EpisodePlay;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Pocketcasts extends UserAccount
{
    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $plays = EpisodePlay::groupBy('episode_id')
            ->where('user_id', $this->accountUser->user_id)
            ->after($startDate)
            ->before($endDate)
            ->select(
                'episode_id',
                DB::raw('sum(seconds) as seconds'),
            )
            ->get();

        $dashboard = new DashboardResponse('pocketcasts');
        $dashboard->addItem('Episodes', count($plays), '🎙️');
        $dashboard->addItem('Minutes', floor($plays->sum('seconds') / 60), '🎙️');

        return $dashboard;
    }
}
