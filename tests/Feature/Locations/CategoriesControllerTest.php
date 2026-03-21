<?php

use App\Http\Controllers\Locations\CategoriesController;
use App\Models\Locations\Category;
use App\Models\User;

covers(CategoriesController::class);

it('lists all categories for a user', function(){
    $user = User::factory()->create();
    Category::factory(10)->create(['user_id' => $user->id]);
    Category::factory(20)->create();

    $this->actingAs($user)
        ->get(route('categories.index'))
        ->assertStatus(200)
        ->assertViewIs('locations.categories.list')
        ->assertViewHas('categories', function($categories) {
            return count($categories) === 10;
        });
});

it('displays the create category form', function() {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('categories.create'))
        ->assertStatus(200)
        ->assertViewHas('category', null);
});

it('saves a new category to the db', function() {
    $user = User::factory()->create();
    $name = fake()->words(random_int(1, 3), true);
    $emoji = fake()->emoji;

    $this->actingAs($user)
        ->post(route('categories.store'), [
            'name' => $name,
            'emoji' => $emoji,
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('categories.edit', 1));

    $this->assertDatabaseHas('location_categories', [
        'name' => $name,
        'emoji' => $emoji,
    ]);
});

test('check for duplicate category name only for the same user', function() {
    $user = User::factory()->create();
    Category::factory()->create(['user_id' => $user->id, 'name' => 'Library']);
    $this->actingAs($user)
        ->post(route('categories.store'), [
            'name' => 'Library'
        ])
        ->assertSessionHasErrorsIn('name');

    $this->actingAs(User::factory()->create())
        ->post(route('categories.store'), [
            'name' => 'Library'
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('categories.edit', 2));
});

test('edit category', function() {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('categories.edit', $category))
        ->assertOk();

    $this->actingAs($user)
        ->put(route('categories.update', $category), [
            'name' => 'new',
            'emoji' => '⭐',
        ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('categories.edit', $category));

    $this->assertDatabaseHas('location_categories', [
        'name' => 'new',
        'emoji' => '⭐',
    ]);
});

test('delete category', function() {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('categories.destroy',$category))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('categories.index'));

    $this->assertModelMissing($category);
});

test("can not modify other user's categories", function() {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    $this->actingAs(User::factory()->create())
        ->get(route('categories.edit', $category))
        ->assertStatus(403);
    $this->actingAs(User::factory()->create())
        ->put(route('categories.update', $category))
        ->assertStatus(403);
    $this->actingAs(User::factory()->create())
        ->delete(route('categories.destroy', $category))
        ->assertStatus(403);
});
