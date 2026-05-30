<?php

namespace App\Http\Controllers\Trackers;

use App\Enums\TrackerUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trackers\StoreTrackerRequest;
use App\Http\Requests\Trackers\UpdateTrackerRequest;
use App\Models\Trackers\Tracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('trackers.trackers', [
            'trackers' => Tracker::whereBelongsTo(Auth::user())->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('trackers.edit', [
            'tracker' => null,
            'units' => TrackerUnit::groupedByType(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTrackerRequest $request): RedirectResponse
    {
        $tracker = new Tracker;
        $tracker->user_id = $request->user()->id;
        $tracker->fill($request->validated());
        $tracker->save();

        return redirect(route('trackers.show', $tracker))->with('message', 'Successfully created tracker');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tracker $tracker): View
    {
        Gate::authorize('view', $tracker);

        return view('trackers.show', [
            'tracker' => $tracker,
            'events' => $tracker->events()->orderByDesc('event_time')->paginate(25),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tracker $tracker): View
    {
        Gate::authorize('view', $tracker);
        return view('trackers.edit', [
            'tracker' => $tracker,
            'units' => TrackerUnit::groupedByType(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTrackerRequest $request, Tracker $tracker): RedirectResponse
    {
        Gate::authorize('view', $tracker);
        $tracker->fill($request->validated());
        $tracker->save();

        return redirect(route('trackers.show', $tracker))->with('message', 'Successfully updated tracker');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tracker $tracker): RedirectResponse
    {
        Gate::authorize('view', $tracker);
        $tracker->delete();

        return redirect(route('trackers.index'));
    }
}
