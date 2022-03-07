<?php

namespace Database\Factories\Podcasts;

use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcasts\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'podcast_id' => Podcast::factory(),
            'name' => $this->faker->words(4, true),
            'published_at' => $this->faker->dateTime(),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(200, 1999),
            'imported' => $this->faker->boolean(),
            'season' => $this->faker->randomDigit(),
            'episode' => $this->faker->randomDigit(),
        ];
    }
}
