<?php

namespace Tests\Feature\Locations;

use App\Models\Locations\Category;
use App\Models\Locations\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_of_locations()
    {
        $this->markTestIncomplete();
    }

    public function test_create_location()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('locations.create'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->post(route('locations.store'))
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'latitude', 'longitude']);

        $location = Location::factory()->make();
        $this->actingAs($user)->post(
            route('locations.store'),
            $location->only(['name', 'latitude', 'longitude'])
        );
        $this->assertDatabaseHas('locations', [
            'user_id' => $user->id,
            'name' => $location->name,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
    }

    public function test_edit_location()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create(['user_id' => $user->id]);
        $new = Location::factory()->make();

        $this->actingAs($user)
            ->put(
                route('locations.update', $location),
                $new->only(['name', 'latitude', 'longitude'])
            )
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('locations', [
            'user_id' => $user->id,
            'name' => $new->name,
            'latitude' => $new->latitude,
            'longitude' => $new->longitude,
        ]);
    }

    public function test_edit_location_with_categories()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create(['user_id' => $user->id]);
        $categories = Category::factory(3)->create(['user_id' => $user->id]);
        $this->actingAs($user)
            ->put(route('locations.update', $location), [
                'name' => $location->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'category' => [1, 2],
            ])
            ->assertSessionHasNoErrors();

        $new = Location::where('id', $location->id)
            ->with('category')
            ->first();
        $this->assertCount(2, $new->category);
    }

    public function test_edit_location_with_invalid_categories()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();

        $this->actingAs($user)
            ->put(route('locations.update', $location), [
                'name' => $location->name,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'category' => [$category->id],
            ])
            ->assertSessionHasErrors('category');

        $new = Location::where('id', $location->id)
            ->with('category')
            ->first();
        $this->assertCount(0, $new->category);
    }

    public function test_edit_location_permissions()
    {
        $user = User::factory()->create();
        $location = Location::factory()->create(['user_id' => $user->id]);
        $user2 = User::factory()->create();

        $this->actingAs($user)
            ->get(route('locations.edit', $location))
            ->assertStatus(200);
        $this->actingAs($user2)
            ->get(route('locations.edit', $location))
            ->assertStatus(403);

        $this->actingAs($user)
            ->put(
                route('locations.update', $location),
                $location->only(['name', 'latitude', 'longitude'])
            )
            ->assertStatus(302);
        $this->actingAs($user2)
            ->put(route('locations.update', $location))
            ->assertStatus(403);
    }

    public function test_view_location()
    {
        $this->markTestIncomplete();
    }

    public function test_delete_location()
    {
        $this->markTestIncomplete();
    }
}
