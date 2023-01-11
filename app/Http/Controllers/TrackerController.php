<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trackers\CreateTrackerRequest;
use App\Http\Requests\Trackers\EditTrackerRequest;
use App\Models\Tracker;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tracker::class, 'tracker');
    }

    public function index(Request $request)
    {
        return view('trackers.index', [
            // must filter by user id, access not managed by policy class
            'trackers' => Tracker::whereBelongsTo($request->user())->get(),
        ]);
    }

    public function create()
    {
        return view('trackers.edit', ['tracker' => null]);
    }

    public function store(CreateTrackerRequest $request)
    {
        $tracker = new Tracker();
        $tracker->name = $request->name;
        $tracker->user_id = $request->user()->id;
        $tracker->fill($request->safe()->only('display_name', 'icon'));
        $tracker->save();

        return redirect(route('trackers.show', $tracker));
    }

    public function show(Tracker $tracker)
    {
        return view('trackers.tracker', ['tracker' => $tracker]);
    }

    public function edit(Tracker $tracker)
    {
        return view('trackers.edit', ['tracker' => $tracker]);
    }

    public function update(EditTrackerRequest $request, Tracker $tracker)
    {
        $tracker->fill($request->safe()->all());
        $tracker->save();

        return redirect(route('trackers.show', $tracker));
    }

    public function destroy(Tracker $tracker)
    {
        $tracker->delete();

        return redirect(route('trackers.index'));
    }
}
