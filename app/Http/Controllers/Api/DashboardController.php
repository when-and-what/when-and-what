<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\DashboardResponse;
use App\Models\Account;
use App\Models\Locations\Checkin;
use App\Models\Note;
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
                title: $checkin->location->name,
                details: ['icon' => '📍']
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

    public function notes(Request $request, $date)
    {
        $start = new Carbon($date . ' 00:00:00', $request->user()->timezone);
        $start->setTimezone('UTC');
        $end = $start->copy()->addDay(1);
        $notes = Note::whereBelongsTo($request->user())
            ->where('published_at', '>=', $start)
            ->where('published_at', '<', $end)
            ->where('dashboard_visible', true)
            ->get();
        $response = new DashboardResponse('notes');
        foreach ($notes as $note) {
            $response->addEvent(
                id: $note->id,
                date: $note->published_at,
                title: $note->title,
                details: ['icon' => $note->icon, 'subTitle' => $note->sub_title]
            );
        }
        return $response;
    }
}
