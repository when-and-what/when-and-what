<?php

namespace Database\Factories\Locations;

use App\Models\Locations\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
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
            'name' => $this->faker->unique()->word(),
            'emoji' => $this->faker->emoji(),
        ];
    }
}
