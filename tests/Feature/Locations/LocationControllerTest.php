<?php

use App\Models\Locations\Location;
use App\Models\User;

test('search filters locations and only matches current user', function () {
    $user = User::factory()->create();
    $locations = Location::factory(5)->create(['user_id' => $user->id]);
    // other user
    Location::factory()->create([
        'name' => $locations[0]['name'],
    ]);
    Location::factory(10)->create();

    $this->actingAs($user)->post(route('locations.search'), [
        'search' => $locations[0]['name'],
    ])
        ->assertOk()
        ->assertSeeText($locations[0]['name'])
        ->assertViewHas('locations', function ($locations) {
            return count($locations) === 1;
        });
});

it('renders the map page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('locations.map'))
        ->assertOk();
});
