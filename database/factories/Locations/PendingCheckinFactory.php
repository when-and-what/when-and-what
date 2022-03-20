<?php

namespace Database\Factories\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locations\PendingCheckin>
 */
class PendingCheckinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'latitude' => $this->faker()->latitude(),
            'longitude' => $this->faker()->longitude(),
            'checkin_at' => $this->faker()->dateTimeBetween(),
            'name' => $this->faker()->words(2, true),
            'note' => $this->faker()->sentence(),
        ];
    }
}
