<?php

use App\Models\Locations\Location;
use App\Models\User;

test('example', function () {
    $user = User::factory()->create();
    $locations = Location::factory(5)->create(['user_id' => $user->id]);
    $this->actingAs($user)->post(route('locations.search'), [
        'search' => $locations[0]['name'],
    ])
    ->assertOk()
    ->assertSeeText($locations[0]['name'])
    ->assertViewHas('locations', function ($locations) {
        return count($locations) === 1;
    });
});
