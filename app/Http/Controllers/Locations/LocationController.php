<?php

namespace App\Http\Controllers\Locations;

use App\Actions\CreateNewLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\LocationRequest;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
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
     *!this is not restricted  by the Location policy.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('locations.list', [
            'locations' => Location::whereBelongsTo($request->user())
                ->with('category')
                ->orderBy('updated_at', 'DESC')
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (is_numeric($request->latitude) && is_numeric($request->longitude)) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        } else {
            $latitude = $longitude = null;
        }

        return view('locations.create', [
            'categories' => Category::whereBelongsTo($request->user())
                ->orderBy('name', 'ASC')
                ->get(),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request, CreateNewLocation $createNewLocation)
    {
        $validated = $request->validated();
        $location = $createNewLocation(
            ...[
                'categories' => isset($validated['category']) ? $validated['category'] : null,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'name' => $validated['name'],
                'user' => $request->user(),
            ]
        );

        return redirect(route('locations.show', $location));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Location $location)
    {
        return view('locations/location', [
            'checkins' => Checkin::whereBelongsTo($request->user())
                ->whereBelongsTo($location)
                ->orderBy('checkin_at', 'desc')
                ->get(),
            'location' => $location,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Location $location)
    {
        return view('locations.edit', [
            'categories' => Category::whereBelongsTo($request->user())
                ->orderBy('name', 'ASC')
                ->get(),
            'location' => $location,
            'locationCategories' => $location->category->modelKeys(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, Location $location)
    {
        $validated = $request->validated();
        $location->fill($validated);
        $location->save();

        if (isset($validated['category']) && count($validated['category']) > 0) {
            $location->category()->sync($validated['category']);
        } else {
            $location->category()->sync([]);
        }

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
