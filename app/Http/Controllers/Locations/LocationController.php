<?php

namespace App\Http\Controllers\Locations;

use App\Actions\CreateNewLocation;
use App\Http\Controllers\Controller;
use App\Models\Locations\Category;
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
    public function create(Request $request)
    {
        return view('locations.location', [
            'categories' => Category::whereBelongsTo($request->user())
                ->orderBy('name', 'ASC')
                ->get(),
            'location' => null,
            'locationCategories' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateNewLocation $createNewLocation)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
            'category' => 'nullable',
        ]);

        $location = $createNewLocation(
            ...[
                'categories' => $validated['category'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'name' => $validated['name'],
                'user' => $request->user(),
            ]
        );

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
    public function edit(Request $request, Location $location)
    {
        return view('locations.location', [
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
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'required',
            'category' => 'nullable',
        ]);

        $location->fill($validated);
        $location->save();

        $location->category()->sync($validated['category']);

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
