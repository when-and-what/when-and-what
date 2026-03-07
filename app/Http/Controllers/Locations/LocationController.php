<?php

namespace App\Http\Controllers\Locations;

use App\Actions\CreateNewLocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\LocationRequest;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Location::class, 'location');
    }

    /**
     * Display a listing of the resource.
     *!this is not restricted  by the Location policy.
     */
    public function index(Request $request): View
    {
        return view('locations.list', [
            'locations' => Location::whereBelongsTo($request->user())
                ->with('category')
                ->orderBy('updated_at', 'DESC')
                ->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new location.
     */
    public function create(Request $request): View
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
     * Store a newly created location in storage.
     */
    public function store(LocationRequest $request, CreateNewLocation $createNewLocation): RedirectResponse
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
     * Display the specified location.
     */
    public function show(Request $request, Location $location): View
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
     * Show the form for editing the specified location.
     */
    public function edit(Request $request, Location $location): View
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
     */
    public function update(LocationRequest $request, Location $location): RedirectResponse
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
     * Remove the specified location from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        $location->delete();

        return redirect(route('locations.index'));
    }
}
