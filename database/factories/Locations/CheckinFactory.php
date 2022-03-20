<?php

namespace Database\Factories\Locations;

use App\Models\Locations\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locations\Checkin>
 */
class CheckinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory();
        return [
            'location_id' => Location::factory()->create(['user_id' => $user]),
            'user_id' => $user,
            'checkin_at' => $this->faker()->dateTimeBetween(),
            'note' => $this->faker()->sentence(),
            'foursquare_checkin' => null
        ];
    }
}
