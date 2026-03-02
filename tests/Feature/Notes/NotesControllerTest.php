<?php

use App\Models\Note;
use App\Models\User;

test('view user notes', function () {
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
});
