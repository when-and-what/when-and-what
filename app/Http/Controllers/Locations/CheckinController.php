<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\Checkins\CreateCheckin;
use App\Http\Requests\Locations\Checkins\EditCheckin;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Checkin::class, 'checkin');
    }

    /**
     * Display a listing of the resource.
     *!this is not restricted  by the Checkin::class policy.
     */
    public function index(Request $request): View
    {
        $checkins = Checkin::whereBelongsTo($request->user())
            ->with(['location', 'location.category'])
            ->orderBy('checkin_at', 'DESC')
            ->paginate(30);

        return view('locations.checkins.list', [
            'checkins' => $checkins->groupBy(function ($checkin) use ($request) {
                return $checkin->checkin_at
                    ->tz($request->user()->timezone)
                    ->toFormattedDateString();
            }),
            'checkinLinks' => $checkins->links(),
            'pending' => PendingCheckin::whereBelongsTo($request->user())
                ->orderBy('checkin_at', 'DESC')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(?Location $location = null): View
    {
        return view('locations.checkins.create', [
            'location' => $location,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCheckin $request): RedirectResponse
    {
        $validated = $request->safe();

        $checkin = new Checkin;
        $checkin->user_id = $request->user()->id;
        $checkin->location_id = $validated['location'];
        if (isset($validated['date'])) {
            $checkin->checkin_at = Carbon::parse($request['date'], $request->user()->timezone)->tz(
                'GMT'
            );
        } else {
            $checkin->checkin_at = now();
        }
        $checkin->note = $validated['note'];
        $checkin->save();

        return redirect(route('locations.show', $checkin->location));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkin $checkin): View
    {
        // dd($checkin->created_at->toDateTimeLocalString());
        return view('locations.checkins.edit', [
            'checkin' => $checkin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditCheckin $request, Checkin $checkin): RedirectResponse
    {
        $validated = $request->safe();

        $checkin->checkin_at = Carbon::parse($validated['date'], $request->user()->timezone)->tz(
            'GMT'
        );
        $checkin->note = $validated['note'];
        $checkin->save();

        return redirect(route('checkins.edit', $checkin));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkin $checkin): RedirectResponse
    {
        $checkin->delete();

        return redirect(route('checkins.index'));
    }
}
