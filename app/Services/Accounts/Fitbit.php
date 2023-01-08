<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;
use App\Services\UserAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Fitbit extends UserAccount
{
    public function saveAccount(User $user)
    {
    }

    public function socialite(): \Laravel\Socialite\Contracts\Provider
    {
        return Socialite::driver('fitbit');
    }

    public function refreshToken()
    {
        $response = Http::withHeaders([
            'Authorization' =>
                'Basic ' .
                base64_encode(
                    config('services.fitbit.client_id') .
                        ':' .
                        config('services.fitbit.client_secret')
                ),
        ])
            ->withBody(
                'grant_type=refresh_token&refresh_token=' . $this->accountUser->refresh_token,
                'application/x-www-form-urlencoded'
            )
            ->post('https://api.fitbit.com/oauth2/token');
        if ($response->ok()) {
            $details = $response->json();
            $this->accountUser->token = $details['access_token'];
            $this->accountUser->refresh_token = $details['refresh_token'];
            $this->accountUser->save();
        }
        return $response->json();
    }

    public function summary(Carbon $date)
    {
        $url = 'https://api.fitbit.com/1/user/-/activities/date/' . $date->toDateString() . '.json';

        $response = Http::acceptJson()
            ->withToken($this->accountUser->token)
            ->get($url);
        if ($response->status() == 401) {
            $this->refreshToken();

            $response = Http::acceptJson()
                ->withToken($this->accountUser->token)
                ->get($url);
        }
        return $response->json();

        // steps, sleep, heart rate,
    }

    public function activities(Carbon $startDate, int $limit)
    {
        $url =
            'https://api.fitbit.com/1/user/-/activities/list.json?afterDate=' .
            $startDate->toDateString() .
            '&limit=' .
            $limit .
            '&sort=desc&offset=0';

        $response = Http::acceptJson()
            ->withToken($this->accountUser->token)
            ->get($url);
        return $response->json();
    }

    public function getTcx(array $activity): ?array
    {
        if (isset($activity['tcxLink']) && $activity['tcxLink']) {
            $tcx = simplexml_load_string(
                Http::withToken($this->accountUser->token)
                    ->get($activity['tcxLink'])
                    ->body()
            );
            if (isset($tcx->Activities->Activity->Lap->Track->Trackpoint)) {
                $cords = [];
                foreach ($tcx->Activities->Activity->Lap->Track->Trackpoint as $trackpoint) {
                    $cords[] = [
                        (float) $trackpoint->Position->LatitudeDegrees,
                        (float) $trackpoint->Position->LongitudeDegrees,
                    ];
                }
                return $cords;
            }
        }
        return null;
    }

    public function getSleep(Carbon $startDate, Carbon $endDate)
    {
        $url =
            'https://api.fitbit.com/1.2/user/-/sleep/date/' .
            $startDate->toDateString() .
            '/' .
            $endDate->toDateString() .
            '.json';

        $response = Http::acceptJson()
            ->withToken($this->accountUser->token)
            ->get($url);
        return $response->json();
    }

    public function dashboard(Carbon $startDate, Carbon $endDate)
    {
        $response = new DashboardResponse('fitbit', '#00b0b9');
        $summary = $this->summary($startDate);
        $sleep = $this->getSleep($startDate, $startDate->copy()->addDay());
        if (isset($sleep['sleep']) && count($sleep['sleep']) > 0) {
            foreach ($sleep['sleep'] as $log) {
                $duration = floor($log['minutesAsleep'] / 60) . ':' . $log['minutesAsleep'] % 60;
                // TODO: Replace timezone with fitibit profile's timezone
                $startSleep = new Carbon($log['startTime'], 'America/Chicago');
                if ($startDate->isSameDay($startSleep)) {
                    $response->addEvent(
                        id: $log['logId'],
                        date: $log['startTime'],
                        title: 'Sleep',
                        details: ['icon' => $log['isMainSleep'] ? '🛏' : '😴']
                    );
                }
                // TODO: Replace timezone with fitibit profile's timezone
                $endSleep = new Carbon($log['endTime'], 'America/Chicago');
                if ($startDate->isSameDay($endSleep)) {
                    $response->addEvent(
                        id: $log['logId'],
                        date: $log['endTime'],
                        title: 'Wake Up',
                        details: [
                            'subTitle' => $log['isMainSleep'] ? $duration : 'Nap :' . $duration,
                            'icon' => $log['isMainSleep'] ? '⏰' : '😴',
                        ]
                    );
                }
                if ($log['isMainSleep'] && $startDate->isSameDay($endSleep)) {
                    $response->addItem(name: 'Sleep', value: $log['efficiency'], icon: '🛌');
                }
            }
        }

        if (isset($summary['activities']) && count($summary['activities']) > 0) {
            $activites = $this->activities($startDate, count($summary['activities']));
            foreach ($activites['activities'] as $activity) {
                $tcx = $this->getTcx($activity);
                if ($tcx) {
                    $response->addLine(id: $activity['logId'], cords: $tcx);
                }
                $response->addEvent(
                    id: $activity['logId'],
                    date: $activity['startTime'],
                    title: $activity['activityName'],
                    details: [
                        'subtitle' => isset($activity['distance'])
                            ? $activity['distance'] . ' ' . $activity['distanceUnit']
                            : '',
                    ]
                );
            }
        }
        $response->addItem(name: 'Steps', icon: '👟', value: $summary['summary']['steps']);
        $response->addItem(name: 'Floors', value: $summary['summary']['floors'], icon: '🛗');
        $response->addItem(
            name: 'Heartrate',
            icon: '💓',
            value: $summary['summary']['restingHeartRate']
        );

        return $response;
    }
}
