<?php

use App\Models\Note;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['timezone' => 'America/Chicago']);
});

test('it returns the all day note for a single day when no end date is given', function () {
    $note = Note::factory()->for($this->user)->create([
        'title' => 'Day summary',
        'icon' => '🎉',
        'is_all_day' => true,
        'dashboard_visible' => false,
        'published_at' => '2023-01-10 18:00:00',
    ]);
    Note::factory()->for($this->user)->create([
        'is_all_day' => true,
        'dashboard_visible' => false,
        'published_at' => '2023-01-11 18:00:00',
    ]);

    $this->actingAs($this->user)
        ->getJson('/api/range/all-day-notes/2023-01-10')
        ->assertOk()
        ->assertExactJson([
            '2023-01-10' => [
                'id' => $note->id,
                'title' => 'Day summary',
                'icon' => '🎉',
                'url' => route('notes.show', $note),
            ],
        ]);
});

test('it uses the user timezone for late night all day notes', function () {
    // 23:30 Chicago on Jan 10 is 05:30 UTC on Jan 11
    $note = Note::factory()->for($this->user)->create([
        'is_all_day' => true,
        'dashboard_visible' => false,
        'published_at' => '2023-01-11 05:30:00',
    ]);

    $this->actingAs($this->user)
        ->getJson('/api/range/all-day-notes/2023-01-10')
        ->assertOk()
        ->assertJsonPath('2023-01-10.id', $note->id);
});

test('it ignores notes that are not all day', function () {
    Note::factory()->for($this->user)->create([
        'is_all_day' => false,
        'published_at' => '2023-01-10 18:00:00',
    ]);

    $this->actingAs($this->user)
        ->getJson('/api/range/all-day-notes/2023-01-10')
        ->assertOk()
        ->assertExactJson([]);
});

test('it still accepts a start and end date', function () {
    Note::factory()->for($this->user)->count(2)->sequence(
        ['published_at' => '2023-01-10 18:00:00'],
        ['published_at' => '2023-01-12 18:00:00'],
    )->create(['is_all_day' => true, 'dashboard_visible' => false]);

    $this->actingAs($this->user)
        ->getJson('/api/range/all-day-notes/2023-01-10/2023-01-12')
        ->assertOk()
        ->assertJsonCount(2);
});
