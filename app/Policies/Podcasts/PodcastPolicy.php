<?php

namespace App\Policies\Podcasts;

use App\Models\Podcasts\Podcast;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PodcastPolicy
{
    use HandlesAuthorization;

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
    public function view(User $user, Podcast $podcast): bool
    {
        if (! $podcast->is_private) {
            return true;
        }

        return $podcast->episodes()
            ->whereHas('plays', fn ($query) => $query->where('user_id', $user->id))
            ->exists();
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
    public function update(User $user, Podcast $podcast): bool
    {
        return in_array($user->email, config('auth.admin_emails'), true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Podcast $podcast): bool
    {
        return false;
    }
}
