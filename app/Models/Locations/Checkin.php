<?php

namespace App\Models\Locations;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkin extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['checkin_at' => 'datetime'];

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only return checkins after a given date.
     */
    public function scopeAfter(Builder $query, Carbon $date): void
    {
        if ($date->timezone->getName() != config('app.timezone')) {
            $date->setTimezone(config('app.timezone'));
        }
        $query->where('checkin_at', '>=', $date);
    }

    /**
     * Scope a query to only return checkins before a given date.
     */
    public function scopeBefore(Builder $query, Carbon $date): void
    {
        if ($date->timezone->getName() != config('app.timezone')) {
            $date->setTimezone(config('app.timezone'));
        }
        $query->where('checkin_at', '<=', $date);
    }
}
