<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreateCheckinRequest;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Checkin::class, 'checkin');
    }

    public function store(CreateCheckinRequest $request)
    {
        $location = Location::findOrFail($request->location);
        if ($request->user()->cannot('update', $location)) {
            abort(403);
        }

        $checkin = new Checkin();
        $checkin->location_id = $request->location;
        $checkin->user_id = $request->user()->id;
        $checkin->note = $request->note;
        if ($request->date) {
            $checkin->checkin_at = new Carbon($request->date, 'UTC');
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();

        return response($checkin, 201);
    }

    public function show(Checkin $checkin)
    {
        return $checkin;
    }

    public function destroy(Checkin $checkin)
    {
        $checkin->delete();
        return $checkin;
    }
}
