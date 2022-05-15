<?php

namespace App\Http\Controllers\Locations;

use App\Actions\CreateNewLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreatePendingCheckinRequest;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function create()
    {
        return view('locations.pending.create');
    }

    public function store(CreatePendingCheckinRequest $request)
    {
        $checkin = new PendingCheckin();
        $checkin->latitude = $request->latitude;
        $checkin->longitude = $request->longitude;
        $checkin->name = $request->name;
        $checkin->user_id = $request->user()->id;
        $checkin->note = $request->note;
        if ($request->date) {
            $checkin->checkin_at = Carbon::parse($valid['date'], $request->user()->timezone)->tz(
                'GMT'
            );
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();
        return redirect(route('pending.edit', $checkin));
    }

    public function edit(Request $request, PendingCheckin $pending)
    {
        return view('locations.pending.edit', [
            'categories' => Category::whereBelongsTo($request->user())
                ->orderBy('name', 'ASC')
                ->get(),
            'pending' => $pending,
        ]);
    }

    public function update(
        Request $request,
        PendingCheckin $pending,
        CreateNewLocation $createNewLocation
    ) {
        $valid = $request->validate([
            'location' => 'nullable|required_without:newlocation|exists:locations,id',
            'date' => 'required',
            'note' => 'nullable',
            'newlocation' => 'boolean',
            'name' => 'required_with:newlocation',
            'category' => 'nullable',
            'latitude' => 'required_with:newlocation|numeric',
            'longitude' => 'required_with:newlocation|numeric',
        ]);

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

    public function destroy(PendingCheckin $pending)
    {
        $pending->delete();
        return redirect(route('checkins.index'));
    }
}
