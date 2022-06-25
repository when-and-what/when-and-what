<?php

namespace App\Models\Locations;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkin extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = ['checkin_at' => 'datetime'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only return checkins after a given date
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAfter($query, Carbon $date)
    {
        return $query->where('checkin_at', '>=', $date);
    }

    /**
     * Scope a query to only return checkins before a given date
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBefore($query, Carbon $date)
    {
        return $query->where('checkin_at', '<=', $date);
    }
}
