<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Strava extends UserAccount
{
    public int $maxCache = 604800; // 7 days

    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $dashboard = new DashboardResponse('strava', '#FC4C02');
        $activities = $this->activities($startDate, $endDate);

        foreach ($activities as $activity) {
            $dashboard->addEvent(id: $activity['id'], date: new Carbon($activity['start_date']), title: $activity['sport_type'], details: [
                'icon' => $this->activityIcon($activity['sport_type']),
                'subTitle' => $this->isGarmin($activity) ? $activity['device_name'] : $this->displayDistance($activity['distance']),
                'titleLink' => 'https://www.strava.com/activities/'.$activity['id'],
            ]);
            $details = $this->activity($activity['id']);
            if (isset($details['map']['polyline'])) {
                $dashboard->addLine(id: $activity['id'], cords: $this->decodePolyline($details['map']['polyline']));
            }
        }

        return $dashboard;
    }

    public function activities(Carbon $startDate, Carbon $endDate): array
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

    public function activity(int $id): array
    {
        $url = 'https://www.strava.com/api/v3/activities/'.$id;

        return Cache::remember($this->accountUser->user_id.'-strava-activity-'.$id, $this->maxCache, function () use ($url) {
            return Http::withToken($this->getToken())
                ->acceptJson()
                ->throw()
                ->get($url)
                ->json();
        });
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

    public function activityIcon(string $sport): ?string
    {
        return match ($sport) {
            'AlpineSki' => '<i class="fa-solid fa-person-skiing"></i>',
            'BackcountrySki' => '<i class="fa-solid fa-person-skiing-nordic"></i>',
            'Badminton' => '<i class="fa-solid fa-shuttlecock"></i>',
            'Canoeing' => '<i class="fa-solid fa-person-rowing"></i>',
            'Crossfit' => '<i class="fa-solid fa-dumbbell"></i>',
            'EBikeRide' => '<i class="fa-solid fa-bicycle"></i>',
            'Elliptical' => '<i class="fa-solid fa-person-running"></i>',
            'EMountainBikeRide' => '<i class="fa-solid fa-bicycle"></i>',
            'Golf' => '<i class="fa-solid fa-golf-ball-tee"></i>',
            'GravelRide' => '<i class="fa-solid fa-bicycle"></i>',
            'Handcycle' => '<i class="fa-solid fa-wheelchair-move"></i>',
            'HighIntensityIntervalTraining' => '<i class="fa-solid fa-fire"></i>',
            'Hike' => '<i class="fa-solid fa-person-hiking"></i>',
            'IceSkate' => '<i class="fa-solid fa-ice-skate"></i>',
            'InlineSkate' => '<i class="fa-solid fa-person-skating"></i>',
            'Kayaking' => '<i class="fa-solid fa-person-kayaking"></i>',
            'Kitesurf' => '<i class="fa-solid fa-kite"></i>',
            'MountainBikeRide' => '<i class="fa-solid fa-bicycle"></i>',
            'NordicSki' => '<i class="fa-solid fa-person-skiing-nordic"></i>',
            'Pickleball' => '<i class="fa-solid fa-table-tennis-paddle-ball"></i>',
            'Pilates' => '<i class="fa-solid fa-person-yoga"></i>',
            'Racquetball' => '<i class="fa-solid fa-table-tennis-paddle-ball"></i>',
            'Ride' => '<i class="fa-solid fa-bicycle"></i>',
            'RockClimbing' => '<i class="fa-solid fa-person-climbing"></i>',
            'RollerSki' => '<i class="fa-solid fa-person-skiing"></i>',
            'Rowing' => '<i class="fa-solid fa-person-rowing"></i>',
            'Run' => '<i class="fa-solid fa-person-running"></i>',
            'Sail' => '<i class="fa-solid fa-sailboat"></i>',
            'Skateboard' => '<i class="fa-solid fa-person-skating"></i>',
            'Snowboard' => '<i class="fa-solid fa-person-snowboarding"></i>',
            'Snowshoe' => '<i class="fa-solid fa-boot"></i>',
            'Soccer' => '<i class="fa-solid fa-futbol"></i>',
            'Squash' => '<i class="fa-solid fa-table-tennis-paddle-ball"></i>',
            'StairStepper' => '<i class="fa-solid fa-stairs"></i>',
            'StandUpPaddling' => '<i class="fa-solid fa-water"></i>',
            'Surfing' => '<i class="fa-solid fa-person-surfing"></i>',
            'Swim' => '<i class="fa-solid fa-person-swimming"></i>',
            'TableTennis' => '<i class="fa-solid fa-table-tennis-paddle-ball"></i>',
            'Tennis' => '<i class="fa-solid fa-tennis-ball"></i>',
            'TrailRun' => '<i class="fa-solid fa-person-running"></i>',
            'Velomobile' => '<i class="fa-solid fa-bicycle"></i>',
            'VirtualRide' => '<i class="fa-solid fa-bicycle"></i>',
            'VirtualRow' => '<i class="fa-solid fa-person-rowing"></i>',
            'VirtualRun' => '<i class="fa-solid fa-person-running"></i>',
            'Walk' => '<i class="fa-solid fa-person-walking"></i>',
            'WeightTraining' => '<i class="fa-solid fa-dumbbell"></i>',
            'Wheelchair' => '<i class="fa-solid fa-wheelchair"></i>',
            'Windsurf' => '<i class="fa-solid fa-wind"></i>',
            'Workout' => '<i class="fa-solid fa-dumbbell"></i>',
            'Yoga' => '<i class="fa-solid fa-person-yoga"></i>',
            default => null,
        };
    }

    public function isGarmin(array $activity): bool
    {
        return isset($activity['device_name']) && str_contains(strtolower($activity['device_name']), 'garmin');
    }

    public function displayDistance(int $meters): string
    {
        $profile = $this->getProfile();

        if (isset($profile['measurement_preference']) && $profile['measurement_preference'] == 'feet') {
            return number_format($meters / 609.344, 2).' miles';
        } else {
            return number_format($meters / 1000, 2).' km';
        }
    }

    public function getProfile()
    {
        $url = 'https://www.strava.com/api/v3/athlete';

        return Cache::remember($this->accountUser->user_id.'-strava-athlete', $this->maxCache, function () use ($url) {
            return Http::withToken($this->getToken())
            ->acceptJson()
            ->throw()
            ->get($url)
            ->json();
        });
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
