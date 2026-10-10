<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Account extends Model
{
    use HasFactory;

    protected $casts = [
        'admin_only' => 'boolean',
    ];

    public function scopeUserAccount(Builder $query, User $user): void
    {
        $query->whereRelation('users', 'user_id', $user->id);
    }

    public function users(): BelongsToMany
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
