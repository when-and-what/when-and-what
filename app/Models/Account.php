<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function scopeUserAccount(Builder $query, User $user): void
    {
        $query->whereRelation('users', 'user_id', $user->id);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(AccountUser::class);
    }

    /**
     * Get the route key for accounts.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
