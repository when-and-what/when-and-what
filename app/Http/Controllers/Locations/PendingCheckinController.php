<?php

namespace App\Http\Controllers\Locations;

use App\Actions\CreateNewLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreatePendingCheckinRequest;
use App\Http\Requests\PendingCheckinUpdateRequest;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendingCheckinController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PendingCheckin::class, 'pending');
    }

    public function index(Request $request)
    {
        return view('locations.pending.list', [
            'checkins' => PendingCheckin::where('user_id', $request->user()->id)
                ->orderBy('checkin_at', 'ASC')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('locations.pending.create');
    }

    public function store(CreatePendingCheckinRequest $request): RedirectResponse
    {
        $checkin = new PendingCheckin();
        $checkin->latitude = $request->latitude;
        $checkin->longitude = $request->longitude;
        $checkin->name = $request->name;
        $checkin->user_id = $request->user()->id;
        $checkin->note = $request->note;
        if ($request->date) {
            $checkin->checkin_at = Carbon::parse($request->date, $request->user()->timezone)->tz(
                'gmt'
            );
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();

        return redirect(route('pending.edit', $checkin));
    }

    public function edit(Request $request, PendingCheckin $pending): View
    {
        return view('locations.pending.edit', [
            'categories' => Category::whereBelongsTo($request->user())
                ->orderBy('name', 'ASC')
                ->get(),
            'pending' => $pending,
        ]);
    }

    public function update(
        PendingCheckinUpdateRequest $request,
        PendingCheckin $pending,
        CreateNewLocation $createNewLocation
    ): RedirectResponse {

        $valid = $request->validated();
        $checkin = new Checkin();

        if (isset($valid['newlocation']) && $valid['newlocation']) {
            $location = $createNewLocation(
                ...[
                    'categories' => isset($valid['category']) ? $valid['category'] : null,
                    'latitude' => $valid['latitude'],
                    'longitude' => $valid['longitude'],
                    'name' => $valid['name'],
                    'user' => $request->user(),
                ]
            );
            $checkin->location_id = $location->id;
        } else {
            $checkin->location_id = $valid['location'];
        }

        $checkin->user_id = $request->user()->id;
        $checkin->checkin_at = Carbon::parse($valid['date'], $request->user()->timezone)->tz('GMT');
        $checkin->note = $valid['note'];
        $checkin->save();

        $pending->delete();

        if (isset($location)) {
            return redirect(route('locations.edit', $location));
        }

        return redirect(route('checkins.edit', $checkin));
    }

    public function destroy(PendingCheckin $pending): RedirectResponse
    {
        $pending->delete();

        return redirect(route('checkins.index'));
    }
}
