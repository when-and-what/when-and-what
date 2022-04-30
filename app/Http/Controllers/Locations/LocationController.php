<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Models\Locations\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Location::class, 'location');
    }
    /**
     * Display a listing of the resource.
     *!this is not restricted  by the Location policy
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('locations.list', [
            'locations' => Location::where('user_id', $request->user()->id)
                ->orderBy('updated_at', 'DESC')
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.location', [
            'location' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
        ]);
        $location = new Location();
        $location->user_id = $request->user()->id;
        $location->fill($validated);
        $location->save();
        return redirect(route('locations.edit', $location));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return $location;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.location', [
            'location' => $location,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
        ]);
        $location->fill($validated);
        $location->save();
        return redirect(route('locations.edit', $location));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }
}
