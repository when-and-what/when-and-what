<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\DashboardResponse;
use App\Models\Account;
use App\Models\Locations\Checkin;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function day(Request $request, Account $account, string $date)
    {
        $user = $request->user();
        try {
            $startDate = new Carbon($date . ' 00:00:00', new DateTimeZone($user->timezone));
            $endDate = $startDate->copy()->endOfDay();
        } catch (Exception) {
            return new DashboardResponse($account->slug);
        }

        $service = new $account->service($account, $user);
        return $service->dashboard($startDate, $endDate);
    }

    public function checkins(Request $request, $date)
    {
        $start = new Carbon($date . ' 00:00:00', $request->user()->timezone);
        $end = $start->copy()->addDay(1);
        $checkins = Checkin::whereBelongsTo($request->user())
            ->where('checkin_at', '>=', $start)
            ->where('checkin_at', '<', $end)
            ->with('location')
            ->get();

        $dashboard = new DashboardResponse('checkin');
        foreach ($checkins as $checkin) {
            $dashboard->addEvent(
                id: $checkin->id,
                date: $checkin->checkin_at,
                title: $checkin->location->name
            );
            $dashboard->addPin(
                id: $checkin->id,
                latitude: $checkin->location->latitude,
                longitude: $checkin->location->longitude,
                title: $checkin->location->name
            );
        }
        return $dashboard;
    }
}
