<?php

namespace App\Models\Podcasts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $podcast_id
 * @property string $title
 * @property Carbon $published_at
 * @property string $description
 * @property int $duration
 * @property int $season
 * @property int $episode
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Podcast $podcast
 */
class Episode extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'podcast_episodes';

    protected $fillable = ['podcast_id', 'name'];

    protected $casts = [
        'duration' => 'integer',
        'episode' => 'integer',
        'published_at' => 'datetime',
        'season' => 'integer',
    ];

    protected $with = ['podcast'];

    /** @return BelongsTo<Podcast, $this> */
    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }

    /** @return HasMany<EpisodePlay, $this> */
    public function plays(): HasMany
    {
        return $this->hasMany(EpisodePlay::class);
    }
}
