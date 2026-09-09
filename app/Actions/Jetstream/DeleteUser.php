<?php

namespace App\Actions\Jetstream;

use App\Models\AccountUser;
use App\Models\Locations\Category;
use App\Models\Locations\Checkin;
use App\Models\Locations\Location;
use App\Models\Locations\PendingCheckin;
use App\Models\Memory;
use App\Models\Note;
use App\Models\Podcasts\EpisodePlay;
use App\Models\Tag;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();

        Checkin::whereBelongsTo($user)->forceDelete();
        PendingCheckin::whereBelongsTo($user)->delete();
        Location::whereBelongsTo($user)->forceDelete();
        Category::whereBelongsTo($user)->forceDelete();
        Tag::whereBelongsTo($user)->forceDelete();
        Note::whereBelongsTo($user)->forceDelete();
        Memory::whereBelongsTo($user)->forceDelete();
        EpisodePlay::whereBelongsTo($user)->delete();
        AccountUser::whereBelongsTo($user)->delete();

        if ($user->subscribed()) {
            $user->subscription()->cancel();
        }

        $user->delete();
    }
}
