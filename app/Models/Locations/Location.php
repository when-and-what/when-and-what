<?php

namespace App\Models\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['latitude', 'longitude', 'name'];

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Restrict query to locations inside of a map boundery
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $north latitude
     * @param float $south latitude
     * @param float $east  longitude
     * @param float $west  longitude
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMap($query, float $north, float $south, float $east, float $west)
    {
        return $query
            ->where('latitude', '<', $north)
            ->where('latitude', '>', $south)
            ->where('longitude', '<', $east)
            ->where('longitude', '>', $west);
    }
}
