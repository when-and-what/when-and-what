<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\Checkins\CreateCheckin;
use App\Http\Requests\Locations\Checkins\EditCheckin;
use App\Models\Locations\Checkin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Checkin::class, 'checkin');
    }
    /**
     * Display a listing of the resource.
     *!this is not restricted  by the Checkin::class policy
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('locations.checkins.list', [
            'checkins' => Checkin::where('user_id', $request->user()->id)->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.checkins.edit', [
            'checkin' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCheckin $request)
    {
        $validated = $request->safe();

        $checkin = new Checkin();
        $checkin->user_id = $request->user()->id;
        $checkin->location_id = $validated['location'];
        if (isset($validated['date'])) {
            $checkin->checkin_at = Carbon::parse(
                $request['checkin_at'],
                $request->user()->timezone
            );
        } else {
            $checkin->checkin_at = now();
        }
        $checkin->note = $validated['note'];
        $checkin->save();
        return redirect(route('checkins.edit', $checkin));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Http\Response
     */
    public function show(Checkin $checkin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkin $checkin)
    {
        // dd($checkin->created_at->toDateTimeLocalString());
        return view('locations.checkins.edit', [
            'checkin' => $checkin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Http\Response
     */
    public function update(EditCheckin $request, Checkin $checkin)
    {
        $validated = $request->safe();

        $checkin->checkin_at = Carbon::parse($request['checkin_at'], $request->user()->timezone);
        $checkin->note = $validated['note'];
        $checkin->save();
        return redirect(route('checkins.edit', $checkin));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkin $checkin)
    {
        //
    }
}
