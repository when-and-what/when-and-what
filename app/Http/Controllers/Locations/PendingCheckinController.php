<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkins\CreatePendingCheckinRequest;
use App\Models\Locations\Checkin;
use App\Models\Locations\PendingCheckin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PendingCheckinController extends Controller
{
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
            $checkin->checkin_at = new Carbon($request->date, $request->user()->timezone);
        } else {
            $checkin->checkin_at = new Carbon();
        }
        $checkin->save();
        return redirect(route('pending.edit', $checkin));
    }

    public function edit(PendingCheckin $pending)
    {
        return view('locations.pending.edit', [
            'pending' => $pending,
        ]);
    }

    public function update(Request $request, PendingCheckin $pending)
    {
        $valid = $request->validate([
            'location' => 'required|exists:locations,id',
            'date' => 'required',
            'note' => 'nullable',
        ]);

        $checkin = new Checkin();
        $checkin->location_id = $valid['location'];
        $checkin->user_id = $request->user()->id;
        $checkin->checkin_at = new Carbon($valid['date'], $request->user()->timezone);
        $checkin->note = $valid['note'];
        $checkin->save();

        $pending->delete();

        return redirect(route('checkins.edit', $checkin));
    }
}
