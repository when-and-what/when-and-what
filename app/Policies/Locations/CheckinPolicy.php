<?php

namespace App\Policies\Locations;

use App\Models\Locations\Checkin;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CheckinPolicy
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
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Checkin $checkin)
    {
        return $user->id === $checkin->user_id;
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
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Checkin $checkin)
    {
        return $user->id === $checkin->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Checkin $checkin)
    {
        return $user->id === $checkin->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Checkin $checkin)
    {
        return $user->id === $checkin->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Locations\Checkin  $checkin
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Checkin $checkin)
    {
        return $user->id === $checkin->user_id;
    }
}
