<?php

namespace App\Actions;

use App\Models\Locations\Location;
use App\Models\User;

class CreateNewLocation
{
    public function __invoke(
        User $user,
        string $name,
        float $latitude,
        float $longitude,
        ?array $categories
    ): Location {
        $location = new Location();
        $location->user_id = $user->id;
        $location->name = $name;
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->save();

        if (is_array($categories) && count($categories) > 0) {
            $location->category()->sync($categories);
        }

        return $location;
    }
}
