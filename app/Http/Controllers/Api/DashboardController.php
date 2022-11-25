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
    public function day(Request $request, Account $account, $date)
    {
        $user = $request->user();
        try {
            $startDate = new Carbon($date . ' 00:00:00', new DateTimeZone($user->timezone));
            $endDate = $startDate->copy()->endOfDay();
        } catch (Exception) {
            return ['events' => [], 'items' => []];
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

        $dashboard = new DashboardResponse();
        foreach ($checkins as $checkin) {
            $dashboard->addEvent([
                'id' => 'checkin-' . $checkin->id,
                'startTime' => $checkin->checkin_at,
                'title' => $checkin->location->name,
            ]);
            $dashboard->addPin([
                'id' => 'checkin-' . $checkin->id,
                'latitude' => $checkin->location->latitude,
                'longitude' => $checkin->location->longitude,
            ]);
        }
        return $dashboard;
    }
}
