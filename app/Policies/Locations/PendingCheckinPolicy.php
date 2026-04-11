<?php

namespace App\Policies;

use App\Models\Locations\PendingCheckin;
use App\Models\User;

class PendingCheckinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PendingCheckin $pendingCheckin): bool
    {
        return $pendingCheckin->user_id === $user->id;
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
    public function update(User $user, PendingCheckin $pendingCheckin): bool
    {
        return $pendingCheckin->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PendingCheckin $pendingCheckin): bool
    {
        return $pendingCheckin->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PendingCheckin $pendingCheckin): bool
    {
        return $pendingCheckin->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PendingCheckin $pendingCheckin): bool
    {
        return $pendingCheckin->user_id === $user->id;
    }
}
