<?php

use App\Http\Controllers\MemoryController;
use App\Models\Memory;
use App\Models\Tag;
use App\Models\User;

mutates(MemoryController::class);

test('guests are redirected to login from all routes', function () {
    $this->get(route('memories.index'))->assertRedirect('login');
    $this->get(route('memories.create'))->assertRedirect('login');
    $this->post(route('memories.store'))->assertRedirect('login');

    $memory = Memory::factory()->create();
    $this->get(route('memories.show', $memory))->assertRedirect('login');
    $this->get(route('memories.edit', $memory))->assertRedirect('login');
    $this->put(route('memories.update', $memory))->assertRedirect('login');
    $this->delete(route('memories.destroy', $memory))->assertRedirect('login');
});

// ── 403 for another user's memory ────────────────────────────────────────────

test('cannot view another users memory', function () {
    $memory = Memory::factory()->create();
    $this->actingAs(createUser())->get(route('memories.show', $memory))->assertForbidden();
});

test('cannot edit another users memory', function () {
    $memory = Memory::factory()->create();
    $this->actingAs(createUser())->get(route('memories.edit', $memory))->assertForbidden();
    $this->actingAs(createUser())->put(route('memories.update', $memory), [
        'name' => 'Hijacked',
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-05',
    ])->assertForbidden();
});

test('cannot delete another users memory', function () {
    $memory = Memory::factory()->create();
    $this->actingAs(createUser())->delete(route('memories.destroy', $memory))->assertForbidden();
});

// ── Index ────────────────────────────────────────────────────────────────────

test('index shows only the authenticated users memories', function () {
    $user = createUser();
    Memory::factory(3)->create(['user_id' => $user->id]);
    Memory::factory(2)->create();

    $this->actingAs($user)
        ->get(route('memories.index'))
        ->assertOk()
        ->assertViewIs('memories.index')
        ->assertViewHas('memories', fn ($memories) => $memories->count() === 3);
});

test('index is ordered by start date descending', function () {
    $user = createUser();
    $older = Memory::factory()->create(['user_id' => $user->id, 'start_date' => '2025-01-01', 'end_date' => '2025-01-05']);
    $newer = Memory::factory()->create(['user_id' => $user->id, 'start_date' => '2026-01-01', 'end_date' => '2026-01-05']);

    $this->actingAs($user)
        ->get(route('memories.index'))
        ->assertViewHas('memories', fn ($memories) => $memories->first()->id === $newer->id);
});

// ── Create ───────────────────────────────────────────────────────────────────

test('create renders the create view with users tags', function () {
    $user = createUser();
    $tag = Tag::factory()->create(['user_id' => $user->id]);
    Tag::factory()->create();

    $this->actingAs($user)
        ->get(route('memories.create'))
        ->assertOk()
        ->assertViewIs('memories.create')
        ->assertViewHas('tags', fn ($tags) => $tags->count() === 1 && $tags->first()->id === $tag->id);
});

// ── Store ────────────────────────────────────────────────────────────────────

test('store creates a memory and redirects to show', function () {
    $user = createUser();

    $response = $this->actingAs($user)->post(route('memories.store'), [
        'name' => 'Rome Trip',
        'start_date' => '2026-04-10',
        'end_date' => '2026-04-17',
    ]);

    $memory = Memory::whereBelongsTo($user)->first();
    expect($memory)->not->toBeNull()
        ->and($memory->name)->toBe('Rome Trip')
        ->and($memory->start_date->toDateString())->toBe('2026-04-10')
        ->and($memory->end_date->toDateString())->toBe('2026-04-17');

    $response->assertRedirect(route('memories.show', $memory));
});

test('store saves optional times', function () {
    $user = createUser();

    $this->actingAs($user)->post(route('memories.store'), [
        'name' => 'Flight Window',
        'start_date' => '2026-04-10',
        'start_time' => '08:30',
        'end_date' => '2026-04-10',
        'end_time' => '14:00',
    ]);

    $memory = Memory::whereBelongsTo($user)->first();
    expect($memory->start_time)->toBe('08:30')
        ->and($memory->end_time)->toBe('14:00');
});

test('store syncs tags', function () {
    $user = createUser();
    $tag = Tag::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->post(route('memories.store'), [
        'name' => 'Tagged Memory',
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-03',
        'tags' => [$tag->id],
    ]);

    $memory = Memory::whereBelongsTo($user)->first();
    expect($memory->tags)->toHaveCount(1)
        ->and($memory->tags->first()->id)->toBe($tag->id);
});

test('store validates required fields', function () {
    $this->actingAs(createUser())
        ->post(route('memories.store'), [])
        ->assertSessionHasErrors(['name', 'start_date', 'end_date']);
});

test('store validates end date is after or equal to start date', function () {
    $this->actingAs(createUser())
        ->post(route('memories.store'), [
            'name' => 'Bad Range',
            'start_date' => '2026-04-17',
            'end_date' => '2026-04-10',
        ])
        ->assertSessionHasErrors(['end_date']);
});

// ── Show ─────────────────────────────────────────────────────────────────────

test('show renders the range view for the memory owner', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('memories.show', $memory))
        ->assertOk()
        ->assertViewIs('range')
        ->assertViewHas('memory', $memory)
        ->assertViewHas('startTime', $memory->start_time)
        ->assertViewHas('endTime', $memory->end_time);
});

// ── Edit ─────────────────────────────────────────────────────────────────────

test('edit renders the edit view for the memory owner', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('memories.edit', $memory))
        ->assertOk()
        ->assertViewIs('memories.edit')
        ->assertViewHas('memory', fn ($m) => $m->id === $memory->id);
});

test('edit only shows the users own tags', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);
    $tag = Tag::factory()->create(['user_id' => $user->id]);
    Tag::factory()->create();

    $this->actingAs($user)
        ->get(route('memories.edit', $memory))
        ->assertViewHas('tags', fn ($tags) => $tags->count() === 1 && $tags->first()->id === $tag->id);
});

// ── Update ───────────────────────────────────────────────────────────────────

test('update saves changes and redirects to show', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->put(route('memories.update', $memory), [
        'name' => 'Updated Name',
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-07',
    ])->assertRedirect(route('memories.show', $memory));

    $memory->refresh();
    expect($memory->name)->toBe('Updated Name')
        ->and($memory->start_date->toDateString())->toBe('2026-06-01')
        ->and($memory->end_date->toDateString())->toBe('2026-06-07');
});

test('update syncs tags', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);
    $tagA = Tag::factory()->create(['user_id' => $user->id]);
    $tagB = Tag::factory()->create(['user_id' => $user->id]);
    $memory->tags()->sync([$tagA->id]);

    $this->actingAs($user)->put(route('memories.update', $memory), [
        'name' => $memory->name,
        'start_date' => $memory->start_date->toDateString(),
        'end_date' => $memory->end_date->toDateString(),
        'tags' => [$tagB->id],
    ]);

    expect($memory->fresh()->tags->pluck('id')->toArray())->toBe([$tagB->id]);
});

test('update clears tags when none submitted', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);
    $tag = Tag::factory()->create(['user_id' => $user->id]);
    $memory->tags()->sync([$tag->id]);

    $this->actingAs($user)->put(route('memories.update', $memory), [
        'name' => $memory->name,
        'start_date' => $memory->start_date->toDateString(),
        'end_date' => $memory->end_date->toDateString(),
    ]);

    expect($memory->fresh()->tags)->toBeEmpty();
});

test('update validates required fields', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->put(route('memories.update', $memory), [])
        ->assertSessionHasErrors(['name', 'start_date', 'end_date']);
});

// ── Destroy ──────────────────────────────────────────────────────────────────

test('destroy soft deletes the memory and redirects to index', function () {
    $user = createUser();
    $memory = Memory::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('memories.destroy', $memory))
        ->assertRedirect(route('memories.index'));

    expect(Memory::find($memory->id))->toBeNull();
    expect(Memory::withTrashed()->find($memory->id))->not->toBeNull();
});
