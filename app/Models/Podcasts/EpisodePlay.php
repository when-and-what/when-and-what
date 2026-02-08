<?php

namespace App\Models\Podcasts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $episode_id
 * @property int $user_id
 * @property Carbon $played_date
 * @property int $seconds
 * @property ?Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class EpisodePlay extends Model
{
    use HasFactory;

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
}
