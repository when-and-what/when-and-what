<?php

namespace Tests\Feature\Dashboard;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateNoteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setup();
        $this->user = User::factory()->create(['timezone' => 'America/Chicago']);
    }

    public function test_create_new_note_without_providing_date()
    {
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
    }

    public function test_create_new_note_with_date()
    {
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
    }

    public function test_create_note_with_icon_and_subtitle()
    {
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
    }
}
