<?php

namespace App\Policies\Podcasts;

use App\Models\Podcasts\EpisodePlay;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePlayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model
     */
    public function view(User $user, EpisodePlay $play)
    {
        return $user->id === $play->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EpisodePlay $play)
    {
        return $user->id === $play->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EpisodePlay $play)
    {
        return $user->id === $play->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EpisodePlay $play)
    {
        return $user->id === $play->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EpisodePlay $play)
    {
        return $user->id === $play->user_id;
    }
}
