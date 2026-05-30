<?php

namespace App\Policies\Trackers;

use App\Models\Trackers\Tracker;
use App\Models\User;

class TrackerPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tracker $tracker): bool
    {
        return $user->id === $tracker->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tracker $tracker): bool
    {
        return $user->id === $tracker->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tracker $tracker): bool
    {
        return $user->id === $tracker->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tracker $tracker): bool
    {
        return $user->id === $tracker->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tracker $tracker): bool
    {
        return false;
    }
}
