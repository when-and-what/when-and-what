<?php

namespace App\Models\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['latitude', 'longitude', 'name'];

    // ! Deprecated
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        Log::warning('Deprecated category method');
        return $this->belongsToMany(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Restrict query to locations inside of a map boundery.
     *
     * @param  float  $north  latitude
     * @param  float  $south  latitude
     * @param  float  $east  longitude
     * @param  float  $west  longitude
     */
    public function scopeMap(Builder $query, float $north, float $south, float $east, float $west): void
    {
        $query
            ->where('latitude', '<', $north)
            ->where('latitude', '>', $south)
            ->where('longitude', '<', $east)
            ->where('longitude', '>', $west);
    }
}
