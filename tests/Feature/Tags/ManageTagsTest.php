<?php

namespace Tests\Feature\Tags;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageTagsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->user2 = User::factory()->create();
    }

    public function test_view_all_tags()
    {
        $tags = Tag::factory(7)->create(['user_id' => $this->user->id]);
        $tags2 = Tag::factory(5)->create(['user_id' => $this->user2->id]);
        Tag::factory(10)->create();

        $response = $this->get(route('tags.index'));
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('tags.index'));
        $response->assertStatus(200);
        $response->assertViewHas('tags', $tags);
        foreach ($tags as $tag) {
            $response->assertSeeText($tag->name);
        }

        $response = $this->actingAs($this->user2)->get(route('tags.index'));
        $response->assertStatus(200);
        $response->assertViewHas('tags', $tags2);
        foreach ($tags2 as $tag) {
            $response->assertSeeText($tag->name);
        }
    }

    public function test_create_tag()
    {
        $form = [
            'name' => 'hockey',
            'display_name' => 'Hockey',
            'icon' => '🏒',
        ];

        $response = $this->actingAs($this->user)->get(route('tags.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->post(route('tags.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tags', array_merge($form, ['user_id' => $this->user->id]));

        $response = $this->actingAs($this->user2)->post(route('tags.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tags', array_merge($form, ['user_id' => $this->user2->id]));

        $response = $this->actingAs($this->user)->post(route('tags.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
    }

    public function test_view_tag()
    {
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('tags.show', $tag));
        $response->assertStatus(302);
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('tags.show', $tag));
        $response->assertStatus(200);
        $response->assertSeeText($tag->display_name);

        $response = $this->actingAs($this->user2)->get(route('tags.show', $tag));
        $response->assertStatus(403);
    }

    public function test_edit_tag()
    {
        $form = [
            'name' => 'hockey',
            'display_name' => 'Hockey',
            'icon' => '🏒',
        ];
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('tags.edit', $tag));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->put(route('tags.update', $tag), $form);
        $response->assertRedirect(route('tags.show', $tag));
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tags', [
            'user_id' => $this->user->id,
            'name' => $tag->name,
            'display_name' => $form['display_name'],
            'icon' => $form['icon'],
        ]);

        $response = $this->actingAs($this->user2)->put(route('tags.update', $tag), $form);
        $response->assertStatus(403);

        $response = $this->put(route('tags.update', $tag), $form);
        $response->assertStatus(403);
    }

    public function test_delete_tag()
    {
        $tag = Tag::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user2)->delete(route('tags.destroy', $tag));
        $response->assertStatus(403);
        $this->assertNotSoftDeleted($tag);

        $response = $this->delete(route('tags.destroy', $tag));
        $response->assertStatus(403);
        $this->assertNotSoftDeleted($tag);

        $response = $this->actingAs($this->user)->delete(route('tags.destroy', $tag));
        $response->assertRedirect(route('tags.index'));
        $response->assertSessionHasNoErrors();
        $this->assertSoftDeleted($tag);
    }
}
