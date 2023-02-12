<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function scopeUserAccount($query, User $user)
    {
        return $query->whereRelation('users', 'user_id', $user->id);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(AccountUser::class);
    }

    /**
     * Get the route key for accounts
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
