<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Memory>
 */
class MemoryFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 years', '-1 week');
        $end = $this->faker->dateTimeBetween($start, '-1 day');

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'start_date' => $start->format('Y-m-d'),
            'start_time' => null,
            'end_date' => $end->format('Y-m-d'),
            'end_time' => null,
        ];
    }

    public function withTimes(): static
    {
        return $this->state([
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
        ]);
    }
}
