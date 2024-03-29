<?php

namespace App\Models\Podcasts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $episode_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $played_at
 * @property int $seconds
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EpisodePlay extends Model
{
    use HasFactory;

    protected $table = 'podcast_episode_plays';

    protected $fillable = ['seconds'];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function episode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
