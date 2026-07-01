<?php

use App\Http\Controllers\RangeController;
use App\Models\User;

mutates(RangeController::class);

test('must be logged in', function () {
    $this->get(route('range', [
        'start' => now()->toDateString(),
        'end' => now()->toDateString(),
    ]))->assertRedirect('login');
});

test('invalid URLs', function ($param) {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(url('/range', $param))
        ->assertStatus(404);
})->with([
    'no start' => [['end' => now()->toDateString()]],
    'no end' => [['start' => now()->toDateString()]],
    'invalid start' => [['start' => '2026-01', 'end' => now()->toDateString()]],
    'invalid end' => [['end' => '2026-01', 'start' => now()->toDateString()]],
]);

test('loads OK', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('range', [
            'start' => now()->toDateString(),
            'end' => now()->toDateString(),
        ]))
        ->assertViewIs('range')
        ->assertOk();
});
