<?php

namespace Database\Factories\Podcasts;

use App\Models\Podcasts\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'title' => $this->faker->words(4, true),
            'description' => $this->faker->sentence(),
            'url' => $this->faker->url(),
            'author' => $this->faker->company(),
            'image' => 'podcasts/images.webp',
            'is_private' => false,
        ];
    }
}
