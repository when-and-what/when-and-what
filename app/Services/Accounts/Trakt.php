<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\User;
use App\Services\DashboardElement;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Trakt extends UserAccount
{
    public function socialite(): \Laravel\Socialite\Contracts\Provider
    {
        return Socialite::driver('trakt');
    }

    /**
     * Get history to populate dashboard
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return DashboardResponse
     */
    public function dashboard(Carbon $startDate, Carbon $endDate)
    {
        $dashboard = new DashboardResponse('trakt');
        $movies = $this->getHistory($startDate, $endDate, 'movies');
        foreach ($movies as $movie) {
            $dashboard->addEvent(
                id: $movie['id'],
                date: new Carbon($movie['watched_at']),
                title: $movie['movie']['title'],
                details: ['icon' => '🎥']
            );
        }
        $episodes = $this->getHistory($startDate, $endDate, 'episodes');
        foreach ($episodes as $episode) {
            $dashboard->addEvent(
                id: $episode['id'],
                date: new Carbon($episode['watched_at']),
                title: $episode['show']['title'] . ' : ' . $episode['episode']['title'],
                details: ['icon' => '📺']
            );
        }
        return $dashboard;
    }

    /**
     * Get user's watch history
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param string $type  movies | shows | seasons | episodes
     * @return mixed json
     */
    public function getHistory(Carbon $startDate, Carbon $endDate, string $type)
    {
        $url = 'https://api.trakt.tv/users/' . $this->username() . '/history/' . $type;
        $url .= '?start_at=' . $startDate->toIso8601ZuluString();
        $url .= '&end_at=' . $endDate->toIso8601ZuluString();
        return $this->_get($url);
    }

    /**
     * Looks up the authenticated user's username if it's not saved in the model
     *
     * @return string
     */
    public function username(): string
    {
        if (!$this->accountUser->username) {
            $this->updateProfile();
        }
        return $this->accountUser->username;
    }

    public function updateProfile()
    {
        $settings = $this->_get('https://api.trakt.tv/users/settings');

        $this->accountUser->username = $settings['user']['username'];
        $this->accountUser->save();
    }

    /**
     * Perform get request to track API
     *
     * @param string $url
     * @return mixed
     */
    private function _get(string $url)
    {
        return Http::acceptJson()
            ->asJson()
            ->withToken($this->accountUser->token)
            ->withHeaders([
                'trakt-api-key' => config('services.trakt.client_id'),
                'trakt-api-version' => '2',
            ])
            ->get($url)
            ->json();
    }
}
