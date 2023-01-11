<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trackers\CreateTrackerRequest;
use App\Models\Tracker;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function index(Request $request)
    {
        return view('trackers.index', [
            'trackers' => Tracker::whereBelongsTo($request->user())->get(),
        ]);
    }

    public function create()
    {
        return view('trackers.tracker', ['tracker' => null]);
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
}
