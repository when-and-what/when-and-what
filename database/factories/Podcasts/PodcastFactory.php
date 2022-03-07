<?php

namespace Database\Factories\Podcasts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcasts\Podcast>
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
            'name' => $this->faker->words(4, true),
            'nickname' => $this->faker->words(2, true),
            'website' => $this->faker->url(),
            'created_by' => User::factory(),
        ];
    }
}
