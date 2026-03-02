<?php

use App\Models\User;
use Carbon\Carbon;



beforeEach(function () {
    parent::setup();
    $this->user = User::factory()->create(['timezone' => 'America/Chicago']);
});

test('create new note without providing date', function () {
    $testDate = Carbon::create(2023, 1, 2, 10, 20, 30, 'America/Chicago');
    Carbon::setTestNow($testDate);
    $response = $this->actingAs($this->user)->postJson(route('api.notes.dashboard'), [
        'title' => 'hello',
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('notes', [
        'user_id' => $this->user->id,
        'title' => 'hello',
        'published_at' => '2023-01-02 16:20:30',
    ]);
});

test('create new note with date', function () {
    $response = $this->actingAs($this->user)->postJson(route('api.notes.dashboard'), [
        'title' => 'hello',
        'published_at' => '2023-01-10T12:00',
    ]);
    $response->assertStatus(201);

    $this->assertDatabaseHas('notes', [
        'user_id' => $this->user->id,
        'title' => 'hello',
        'published_at' => '2023-01-10 18:00:00',
    ]);
});

test('create note with icon and subtitle', function () {
    $response = $this->actingAs($this->user)->postJson(route('api.notes.dashboard'), [
        'title' => 'hello',
        'icon' => '🏈',
        'sub_title' => 'testing',
    ]);
    $response->assertStatus(201);
    $this->assertDatabaseHas('notes', [
        'user_id' => $this->user->id,
        'title' => 'hello',
        'icon' => '🏈',
        'sub_title' => 'testing',
    ]);
});
