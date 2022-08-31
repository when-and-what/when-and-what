<?php

namespace App\Policies\Podcasts;

use App\Models\Podcasts\Episode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Episode $episode)
    {
        return $user->id == $episode->created_by;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Episode $episode)
    {
        return $user->id == $episode->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Episode $episode)
    {
        return $user->id == $episode->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Episode $episode)
    {
        return $user->id == $episode->created_by;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcasts\Episode  $episode
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Episode $episode)
    {
        return $user->id == $episode->created_by;
    }
}
