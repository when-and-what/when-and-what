<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\DashboardResponse;
use App\Models\Account;
use App\Models\Locations\Checkin;
use App\Models\Locations\PendingCheckin;
use App\Models\Note;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function day(Request $request, Account $account, string $date): DashboardResponse
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

    public function range(Request $request, Account $account, string $start, string $end): DashboardResponse
    {
        $user = $request->user();
        try {
            $startTime = $request->query('start_time', '00:00:00');
            $endTime = $request->query('end_time', '23:59:59');
            $startDate = new Carbon($start.' '.$startTime, new DateTimeZone($user->timezone));
            $endDate = new Carbon($end.' '.$endTime, new DateTimeZone($user->timezone));
        } catch (Exception) {
            return new DashboardResponse($account->slug);
        }

        $service = new $account->service($account, $user);

        return $service->dashboard($startDate, $endDate);
    }

    public function checkins(Request $request, $date): DashboardResponse
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $end = $start->copy()->addDay();
        $checkins = Checkin::whereBelongsTo($request->user())
            ->after($start)
            ->before($end)
            ->with('location.category')
            ->get();

        return $this->populateCheckins($checkins);
    }

    public function checkinsRange(Request $request, string $start, string $end): DashboardResponse
    {
        $startDate = new Carbon($start.' '.$request->query('start_time', '00:00:00'), $request->user()->timezone);
        $endDate = new Carbon($end.' '.$request->query('end_time', '23:59:59'), $request->user()->timezone);
        $checkins = Checkin::whereBelongsTo($request->user())
            ->after($startDate)
            ->before($endDate)
            ->with('location.category')
            ->get();

        return $this->populateCheckins($checkins);
    }

    /** @param Collection<int, Checkin> $checkins */
    private function populateCheckins(Collection $checkins): DashboardResponse
    {
        $dashboard = new DashboardResponse('checkin');
        foreach ($checkins as $checkin) {
            $icon = '';
            foreach ($checkin->location->categories as $category) {
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
        if ($checkins->isNotEmpty()) {
            $dashboard->addItem('Checkins', $checkins->count(), '📍');
        }

        return $dashboard;
    }

    public function pendingCheckins(Request $request, $date): DashboardResponse
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $end = $start->copy()->addDay();
        $checkins = PendingCheckin::whereBelongsTo($request->user())
            ->where('checkin_at', '>=', $start)
            ->where('checkin_at', '<', $end)
            ->get();

        return $this->populatePendingCheckins($checkins);
    }

    public function pendingCheckinsRange(Request $request, string $start, string $end): DashboardResponse
    {
        $startDate = new Carbon($start.' '.$request->query('start_time', '00:00:00'), $request->user()->timezone);
        $endDate = new Carbon($end.' '.$request->query('end_time', '23:59:59'), $request->user()->timezone);
        $checkins = PendingCheckin::whereBelongsTo($request->user())
            ->where('checkin_at', '>=', $startDate)
            ->where('checkin_at', '<=', $endDate)
            ->get();

        return $this->populatePendingCheckins($checkins);
    }

    /** @param Collection<int, PendingCheckin> $checkins */
    private function populatePendingCheckins(Collection $checkins): DashboardResponse
    {
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

    public function notes(Request $request, $date): DashboardResponse
    {
        $start = new Carbon($date.' 00:00:00', $request->user()->timezone);
        $start->setTimezone('UTC');
        $end = $start->copy()->addDay();
        $notes = Note::whereBelongsTo($request->user())
            ->dashboard(true)
            ->where('published_at', '>=', $start)
            ->where('published_at', '<', $end)
            ->get();

        return $this->populateNotes($notes);
    }

    public function notesRange(Request $request, string $start, string $end): DashboardResponse
    {
        $startDate = new Carbon($start.' '.$request->query('start_time', '00:00:00'), $request->user()->timezone);
        $startDate->setTimezone('UTC');
        $endDate = new Carbon($end.' '.$request->query('end_time', '23:59:59'), $request->user()->timezone);
        $endDate->setTimezone('UTC');
        $notes = Note::whereBelongsTo($request->user())
            ->dashboard(true)
            ->where('published_at', '>=', $startDate)
            ->where('published_at', '<=', $endDate)
            ->get();

        return $this->populateNotes($notes);
    }

    public function allDayNotesRange(Request $request, string $start, string $end): JsonResponse
    {
        $user = $request->user();
        $startDate = new Carbon($start.' 00:00:00', $user->timezone);
        $endDate = new Carbon($end.' 23:59:59', $user->timezone);

        $notes = Note::whereBelongsTo($user)
            ->allDay()
            ->where('published_at', '>=', $startDate->copy()->tz('UTC'))
            ->where('published_at', '<=', $endDate->copy()->tz('UTC'))
            ->get();

        $map = $notes->keyBy(fn ($note) => $note->published_at->tz($user->timezone)->toDateString())
            ->map(fn ($note) => [
                'id' => $note->id,
                'url' => route('notes.show', $note),
            ]);

        return response()->json($map);
    }

    /** @param Collection<int, Note> $notes */
    private function populateNotes(Collection $notes): DashboardResponse
    {
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
}
