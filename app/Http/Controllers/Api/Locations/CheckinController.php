<?php

namespace App\Http\Controllers\Api\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreateCheckinRequest;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function store(CreateCheckinRequest $request)
    {
        $location = Location::findOrFail($request->location);
        // TODO: check policy
        if ($location->user_id != $request->user()->id) {
            return response('', 403);
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
        // TODO: policy for view checking
        return $checkin;
    }
}
