<?php

namespace Tests\Feature\Notes;

use App\Models\Note;
use App\Models\User;
use Tests\TestCase;

class NotesControllerTest extends TestCase
{
    public function test_view_user_notes()
    {
        $this->get(route('notes.index'))->assertRedirect('login');

        $user = User::factory()->create();
        Note::factory(15)->dashboard_hidden()->create(['user_id' => $user->id]);
        Note::factory(7)->dashboard_visible()->create(['user_id' => $user->id]);
        Note::factory(20)->create();
        $response = $this->actingAs($user)->get(route('notes.index'));
        $response->assertStatus(200);
        $response->assertViewIs('notes.index');
        $response->assertViewHas('notes', function ($notes) {
            return 15 === count($notes);
        });
    }
}
