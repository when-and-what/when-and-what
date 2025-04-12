<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\DashboardResponse;
use App\Models\Account;
use App\Models\Locations\Checkin;
use App\Models\Locations\PendingCheckin;
use App\Models\Note;
use App\Models\Podcasts\EpisodePlay;
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
            $startDate = new Carbon($date.' 00:00:00', new DateTimeZone($user->timezone));
            $endDate = $startDate->copy()->endOfDay();
        } catch (Exception) {
            return new DashboardResponse($account->slug);
        }

        $service = new $account->service($account, $user);

        return $service->dashboard($startDate, $endDate);
    }

    public function checkins(Request $request, $date)
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $end = $start->copy()->addDay();
        $checkins = Checkin::whereBelongsTo($request->user())
            ->after($start)
            ->before($end)
            ->with('location.category')
            ->get();

        $dashboard = new DashboardResponse('checkin');
        foreach ($checkins as $checkin) {
            $icon = '';
            foreach ($checkin->location->category as $category) {
                $icon .= $category->emoji;
            }
            if ($icon == '') {
                $icon = '📍';
            }
            $dashboard->addEvent(
                id: $checkin->id,
                date: $checkin->checkin_at,
                title: $checkin->location->name,
                details: [
                    'icon' => $icon,
                    'titleLink' => route('locations.show', $checkin->location),
                    'dateLink' => route('checkins.edit', $checkin),
                    'subTitle' => $checkin->note,
                ]
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

    public function pendingCheckins(Request $request, $date)
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $end = $start->copy()->addDay(1);
        $checkins = PendingCheckin::whereBelongsTo($request->user())
            ->where('checkin_at', '>=', $start)
            ->where('checkin_at', '<', $end)
            ->get();

        $dashboard = new DashboardResponse('pending');
        foreach ($checkins as $checkin) {
            $dashboard->addEvent(
                id: $checkin->id,
                date: $checkin->checkin_at,
                title: $checkin->name ?: 'Pending Checkin',
                details: [
                    'icon' => '📍',
                    'dateLink' => route('pending.edit', $checkin),
                    'subTitle' => $checkin->note,
                ]
            );
            $dashboard->addPin(
                id: $checkin->id,
                latitude: $checkin->latitude,
                longitude: $checkin->longitude,
                title: $checkin->name ?: 'Pending Checkin'
            );
        }

        return $dashboard;
    }

    public function notes(Request $request, $date)
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $start->setTimezone('UTC');
        $end = $start->copy()->addDay(1);
        $notes = Note::whereBelongsTo($request->user())
            ->dashboard(true)
            ->where('published_at', '>=', $start)
            ->where('published_at', '<', $end)
            ->get();
        $response = new DashboardResponse('notes');
        foreach ($notes as $note) {
            $response->addEvent(
                id: $note->id,
                date: $note->published_at,
                title: $note->title,
                details: [
                    'icon' => $note->icon,
                    'subTitle' => $note->sub_title,
                    'dateLink' => route('notes.edit', $note),
                ]
            );
        }

        return $response;
    }

    public function podcasts(Request $request, $date)
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $start->setTimezone('UTC');
        $end = $start->copy()->addDay(1);

        $plays = EpisodePlay::with('episode')
            ->whereBelongsTo($request->user())
            ->where('played_at', '>=', $start)
            ->where('played_at', '<', $end)
            ->get();
        $response = new DashboardResponse('podcasts');
        foreach ($plays as $play) {
            $response->addEvent(
                id: $play->id,
                date: $play->played_at,
                title: $play->episode->name,
                details: [
                    'icon' => '🎙',
                    'subTitle' => $play->episode->podcast->name,
                    'titleLink' => route('episodes.show', $play->episode),
                    'subTitleLink' => route('podcasts.show', $play->episode->podcast),
                    'dateLink' => route('plays.edit', $play),
                ]
            );
        }

        return $response;
    }
}
