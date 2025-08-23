<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\User;
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
     * Get history to populate dashboard.
     */
    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
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
                title: $episode['show']['title'],
                details: ['icon' => '📺', 'subTitle' => $episode['episode']['title']]
            );
        }

        return $dashboard;
    }

    /**
     * Get user's watch history.
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @param  string  $type  movies | shows | seasons | episodes
     * @return mixed json
     */
    public function getHistory(Carbon $startDate, Carbon $endDate, string $type)
    {
        $url = 'https://api.trakt.tv/users/'.$this->username().'/history/'.$type;
        $url .= '?start_at='.$startDate->toIso8601ZuluString();
        $url .= '&end_at='.$endDate->toIso8601ZuluString();

        return $this->_get($url);
    }

    /**
     * Looks up the authenticated user's username if it's not saved in the model.
     */
    public function username(): string
    {
        if (! $this->accountUser->username) {
            $this->updateProfile();
        }

        return $this->accountUser->username;
    }

    public function updateProfile(): void
    {
        $settings = $this->_get('https://api.trakt.tv/users/settings');

        $this->accountUser->username = $settings['user']['username'];
        $this->accountUser->save();
    }

    /**
     * Perform get request to track API.
     *
     * @param  string  $url
     * @return mixed json
     */
    private function _get(string $url): mixed
    {
        return Http::acceptJson()
            ->asJson()
            ->withToken($this->getToken())
            ->withHeaders([
                'trakt-api-key' => config('services.trakt.client_id'),
                'trakt-api-version' => '2',
            ])
            ->get($url)
            ->json();
    }

    private function getToken(): string
    {
        if ($this->accountUser->updated_at <= now()->subDay()) {
            return $this->refreshToken();
        }

        return $this->accountUser->token;
    }

    public function refreshToken(): string
    {
        $respone = Http::acceptJson()
            ->post('https://api.trakt.tv/oauth/token', [
                'refresh_token' => $this->accountUser->refresh_token,
                'client_id' => config('services.trakt.client_id'),
                'client_secret' => config('services.trakt.client_secret'),
                'redirect_uri' => 'https://whenandwhat.app/accounts/trakt/edit',
                'grant_type' => 'refresh_token',
            ])
            ->throw()
            ->json();

        $this->accountUser->token = $respone['access_token'];
        $this->accountUser->refresh_token = $respone['refresh_token'];
        $this->accountUser->save();

        return $respone['access_token'];
    }
}
