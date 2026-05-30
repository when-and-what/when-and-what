<?php

namespace App\Http\Controllers\Trackers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trackers\CreateEventRequest;
use App\Models\Trackers\Event;
use App\Models\Trackers\Tracker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function store(CreateEventRequest $request, Tracker $tracker)
    {
        Gate::authorize('view', $tracker);

        $data = $request->validated();
        $data['event_time'] = Carbon::parse($data['event_time'], $request->user()->timezone)->tz('GMT');
        $tracker->events()->create($data);

        return back()->with('message', 'Event logged.');
    }

    public function destroy(Tracker $tracker, Event $event)
    {
        Gate::authorize('view', $tracker);

        $event->delete();

        return back()->with('message', 'Event deleted.');
    }
}
