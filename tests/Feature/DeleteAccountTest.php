<?php

use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Models\Note;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Tag;
use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;

test('user accounts can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'password')
        ->call('deleteUser');

    expect($user->fresh())->toBeNull();
});

test('correct password must be provided before account can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    Livewire::test(DeleteUserForm::class)
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

test('models are removed when an account is deleted', function() {
    $user = createUser();

    $locations = Location::factory(15)->create();
    foreach($locations as $location) {
        Checkin::factory()->create(['user_id' => $user->id, 'location_id' => $location->id]);
        PendingCheckin::factory()->create(['user_id' => $user->id]);
    }
    Tag::factory()->create(['user_id' => $user->id]);
    Note::factory(5)->create(['user_id' => $user->id]);
    EpisodePlay::factory(5)->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $component = Livewire::test(DeleteUserForm::class)
        ->set('password', 'password')
        ->call('deleteUser');

    $this->assertEquals(0, Location::where('user_id', $user->id)->count());
    $this->assertDatabaseEmpty('checkins');
    $this->assertDatabaseEmpty('pending_checkins');
    $this->assertDatabaseEmpty('notes');
    $this->assertDatabaseEmpty('tags');
    $this->assertDatabaseEmpty('notes');


    expect($user->fresh())->toBeNull();
});
