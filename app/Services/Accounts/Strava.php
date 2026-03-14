<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Strava extends UserAccount
{
    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $dashboard = new DashboardResponse('strava', '#FC4C02');
        $activities = $this->activities($startDate, $endDate);
        foreach ($activities as $activity) {
            $dashboard->addEvent(id: $activity['id'], date: new Carbon($activity['start_date']), title: $activity['type'], details: [
                'icon' => '🚶',
                'subTitle' => $activity['device_name'],
            ]);
            $details = $this->activity($activity['id']);
            if (isset($details['map']['polyline'])) {
                $dashboard->addLine(id: $activity['id'], cords: $this->decodePolyline($details['map']['polyline']));
            }
        }

        return $dashboard;
    }

    public function activities(Carbon $startDate, Carbon $endDate)
    {
        $url = 'https://www.strava.com/api/v3/athlete/activities';

        return Http::withToken($this->getToken())
            ->acceptJson()
            ->throw()
            ->get($url, [
                'before' => $endDate->getTimestamp(),
                'after' => $startDate->getTimestamp(),
            ])->json();
    }

    public function activity(int $id)
    {
        $url = 'https://www.strava.com/api/v3/activities/'.$id;

        return Http::withToken($this->getToken())
            ->acceptJson()
            ->throw()
            ->get($url)
            ->json();
    }

    private function getToken(): string
    {
        if ($this->accountUser->updated_at <= now()->subHours(5)) {
            return $this->refreshToken();
        }

        return $this->accountUser->token;
    }

    private function refreshToken(): string
    {
        $respone = Http::acceptJson()
            ->post('https://www.strava.com/api/v3/oauth/token', [
                'refresh_token' => $this->accountUser->refresh_token,
                'client_id' => config('services.strava.client_id'),
                'client_secret' => config('services.strava.client_secret'),
                'grant_type' => 'refresh_token',
            ])
            ->throw()
            ->json();

        $this->accountUser->token = $respone['access_token'];
        $this->accountUser->refresh_token = $respone['refresh_token'];
        $this->accountUser->save();

        return $respone['access_token'];
    }

    private function decodePolyline(string $encoded): array
    {
        $index = 0;
        $lat = 0;
        $lng = 0;
        $coords = [];

        while ($index < strlen($encoded)) {
            $shift = 0;
            $result = 0;
            do {
                $byte = ord($encoded[$index++]) - 63;
                $result |= ($byte & 0x1F) << $shift;
                $shift += 5;
            } while ($byte >= 0x20);
            $lat += ($result & 1) ? ~($result >> 1) : ($result >> 1);

            $shift = 0;
            $result = 0;
            do {
                $byte = ord($encoded[$index++]) - 63;
                $result |= ($byte & 0x1F) << $shift;
                $shift += 5;
            } while ($byte >= 0x20);
            $lng += ($result & 1) ? ~($result >> 1) : ($result >> 1);

            $coords[] = [$lat / 1e5, $lng / 1e5];
        }

        return $coords;
    }
}
