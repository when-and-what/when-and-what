<?php

namespace App\Services\Accounts;

use App\Http\Responses\DashboardResponse;
use App\Services\UserAccount;
use Carbon\Carbon;
use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Photos\Library\V1\FiltersBuilder;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Google\Type\Date;
use Laravel\Socialite\Facades\Socialite;

class Google extends UserAccount
{
    public function socialite(): \Laravel\Socialite\Contracts\Provider
    {
        return Socialite::driver('google');
    }

    /**
     * Get history to populate dashboard
     */
    public function dashboard(Carbon $startDate, Carbon $endDate): DashboardResponse
    {
        $dashboard = new DashboardResponse('google');

        $authCredentials = new UserRefreshCredentials(
            $this->account->scope,
            [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'refresh_token' => $this->account->refresh_token,
            ]
        );
        $start = new Date();
        $start->setDay($startDate->day);
        $start->setMonth($startDate->month);
        $start->setYear($startDate->year);
        $end = new Date();
        $end->setDay($endDate->day);
        $end->setMonth($endDate->month);
        $end->setYear($endDate->year);
        $client = new PhotosLibraryClient(['credentials' => $authCredentials]);
        $filter = new FiltersBuilder();
        $filter->addDateRange($start, $end);
        $response = $client->searchMediaItems([
            'filters' => $filter->build()
        ]);

        foreach($response as $item) {
            $dashboard->addEvent(
                id: $item->getId(),
                date: new Carbon($item->getMediaMetadata()->getCreationTime()),
                title: $item->getFilename(),
                details: ['icon' => '📷']
            );
        }
        return $dashboard;
    }
}
