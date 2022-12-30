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

    public function dashboard(Carbon $startDate, Carbon $endDate)
    {
        $dashboard = new DashboardResponse('trakt');
        $movies = $this->movies($startDate, $endDate);
        foreach ($movies as $movie) {
            $dashboard->addEvent(
                id: $movie['id'],
                date: $movie['watched_at'],
                title: $movie['movie']['title']
            );
        }
        $episodes = $this->episodes($startDate, $endDate);
        foreach ($episodes as $episode) {
            $dashboard->addEvent(
                id: $episode['id'],
                date: $episode['watched_at'],
                title: $episode['show']['title'] . ' : ' . $episode['episode']['title']
            );
        }
        return $dashboard;
    }

    public function movies(Carbon $startDate, Carbon $endDate)
    {
        $url = 'https://api.trakt.tv/users/' . $this->username() . '/history/movies';
        $url .= '?start_at=' . $startDate->toIso8601ZuluString();
        $url .= '&end_at=' . $endDate->toIso8601ZuluString();
        return $this->_get($url);
    }

    public function episodes(Carbon $startDate, Carbon $endDate)
    {
        $url = 'https://api.trakt.tv/users/' . $this->username() . '/history/episodes';
        $url .= '?start_at=' . $startDate->toIso8601ZuluString();
        $url .= '&end_at=' . $endDate->toIso8601ZuluString();
        return $this->_get($url);
    }

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
