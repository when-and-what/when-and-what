<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'icon' => $this->faker->emoji(),
            'title' => $this->faker->sentence(),
            'sub_title' => $this->faker->sentence(),
            'note' => '',
            'published_at' => $this->faker->dateTime(),
            'dashboard_visible' => $this->faker->boolean(),
        ];
    }

    /**
     * visible on the dashboard
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dashboard_visible()
    {
        return $this->state(function (array $attributes) {
            return [
                'dashboard_visible' => true,
            ];
        });
    }

    /**
     * hidden from the dashboard
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dashboard_hidden()
    {
        return $this->state(function (array $attributes) {
            return [
                'dashboard_visible' => false,
            ];
        });
    }
}
