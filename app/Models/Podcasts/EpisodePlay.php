<?php

namespace App\Models\Podcasts;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $episode_id
 * @property int $user_id
 * @property Carbon $played_at
 * @property int $seconds
 * @property ?Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EpisodePlay extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'podcast_episode_plays';

    protected $fillable = ['seconds'];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function user(): BelongsTo
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
        $query->where('played_at', '>=', $date);
    }

    /**
     * Scope a query to only return checkins before a given date.
     */
    public function scopeBefore(Builder $query, Carbon $date): void
    {
        if ($date->timezone->getName() != config('app.timezone')) {
            $date->setTimezone(config('app.timezone'));
        }
        $query->where('played_at', '<=', $date);
    }
}
