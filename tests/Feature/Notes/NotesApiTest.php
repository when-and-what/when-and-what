<?php

use App\Http\Controllers\Api\NoteController;
use App\Models\Note;

covers(NoteController::class);

test('list all notes', function() {
    $user = createUser();
    Note::factory(50)->create(['user_id' => $user->id]);
    login($user)->get(route('api.notes.index'))
        ->assertOk();
})->todo();
test('create note')->todo();

