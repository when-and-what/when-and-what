<?php

namespace Database\Factories\Podcasts;

use App\Models\Podcasts\Episode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcasts\EpisodePlay>
 */
class EpisodePlayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'episode_id' => Episode::factory(),
            'user_id' => User::factory(),
            'play_date' => $this->faker->date,
            'seconds' => $this->faker->numberBetween(4, 1999),
        ];
    }
}
