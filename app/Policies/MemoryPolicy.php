<?php

namespace App\Policies;

use App\Models\Memory;
use App\Models\User;

class MemoryPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Memory $memory): bool
    {
        return $user->id === $memory->user_id;
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
    public function update(User $user, Memory $memory): bool
    {
        return $user->id === $memory->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Memory $memory): bool
    {
        return $user->id === $memory->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Memory $memory): bool
    {
        return $user->id === $memory->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Memory $memory): bool
    {
        return in_array($user->email, config('auth.admin_emails'));
    }
}
