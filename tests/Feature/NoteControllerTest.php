<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public User $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['timezone' => 'America/New_York']);
        $this->user2 = User::factory()->create();
    }

    public function test_view_notes()
    {
        $notes = Note::factory(6)->dashboard_hidden()->create(['user_id' => $this->user->id]);
        Note::factory(6)->dashboard_visible()->create(['user_id' => $this->user->id]);
        $notes2 = Note::factory(8)->dashboard_hidden()->create(['user_id' => $this->user2->id]);
        Note::factory(8)->dashboard_visible()->create(['user_id' => $this->user2->id]);
        Note::factory(15)->create();

        $response = $this->get(route('notes.index'));
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('notes.index'));
        $response->assertViewIs('notes.index');
        $response->assertStatus(200);
        $response->assertViewHas(
            'notes',
            Note::whereBelongsTo($this->user)
                ->orderBy('published_at', 'desc')
                ->dashboard(false)
                ->get()
        );
        foreach ($notes as $note) {
            $response->assertSeeText($note->title);
        }

        $response = $this->actingAs($this->user2)->get(route('notes.index'));
        $response->assertViewIs('notes.index');
        $response->assertStatus(200);
        $response->assertViewHas(
            'notes',
            Note::whereBelongsTo($this->user2)
                ->orderBy('published_at', 'desc')
                ->dashboard(false)
                ->get()
        );
        foreach ($notes2 as $note) {
            $response->assertSeeText($note->title);
        }
    }

    public function test_create_note()
    {
        $response = $this->get(route('notes.create'));
        $response->assertRedirect('login');

        $response = $this->actingAs($this->user)->get(route('notes.create'));
        $response->assertStatus(200);
        $response->assertSeeText('New Note');

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'my title',
        ]);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('notes.index'));
        $this->assertDatabaseHas('notes', [
            'user_id' => $this->user->id,
            'title' => 'my title',
        ]);

        $response = $this->actingAs($this->user)->post(route('notes.store'), [
            'title' => 'new note',
            'published_at' => '2023-02-01T08:20',
        ]);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('notes.index'));
        $this->assertDatabaseHas('notes', [
            'user_id' => $this->user->id,
            'title' => 'new note',
            'published_at' => '2023-02-01 13:20:00',
        ]);
    }

    public function test_edit_note()
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user2)->get(route('notes.edit', $note));
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->get(route('notes.edit', $note));
        $response->assertStatus(200);
        $response->assertViewHas('note', $note);

        $response = $this->actingAs($this->user2)->put(route('notes.update', $note), []);
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->put(route('notes.update', $note), [
            'title' => 'new title',
        ]);
        $response->assertSessionHasErrors('published_at');

        $response = $this->actingAs($this->user)->put(route('notes.update', $note), [
            'title' => 'new title',
            'sub_title' => $note->sub_title,
            'icon' => $note->icon,
            'published_at' => '2023-01-01T12:00',
        ]);
        $response->assertRedirect(route('notes.index'));
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'user_id' => $this->user->id,
            'title' => 'new title',
            'sub_title' => $note->sub_title,
            'published_at' => '2023-01-01 17:00:00',
        ]);
    }

    public function test_delete_note()
    {
        $note = Note::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user2)->delete(route('notes.destroy', $note), []);
        $response->assertStatus(403);
        $this->assertNotSoftDeleted($note);

        $response = $this->actingAs($this->user)->delete(route('notes.destroy', $note), []);
        $response->assertRedirect(route('notes.index'));
        $this->assertSoftDeleted($note);
    }
}
