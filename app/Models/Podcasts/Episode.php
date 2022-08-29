<?php

namespace App\Models\Podcasts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $podcast_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $published_at
 * @property string $description
 * @property int $duration
 * @property int $season
 * @property int $episode
 * @property boolean $imported
 * @property \Illuminate\Support\Carbon $deleted_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read Podcast $podcast
 */
class Episode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'podcast_episodes';

    protected $fillable = ['podcast_id', 'name'];

    protected $casts = [
        'imported' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $with = ['podcast'];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function plays()
    {
        return $this->hasMany(EpisodePlay::class);
    }

    public function rating()
    {
        return $this->hasOne(EpisodeRating::class);
    }

    public function scopeName($query, string $name)
    {
        $query->where('name', $name);
    }
}
