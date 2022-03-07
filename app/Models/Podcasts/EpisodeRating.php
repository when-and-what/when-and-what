<?php

namespace App\Models\Podcasts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $episode_id
 * @property int $user_id
 * @property int $rating
 * @property string $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class EpisodeRating extends Model
{
    use HasFactory;

    protected $table = 'podcast_episode_ratings';

    protected $fillable = ['rating', 'notes'];

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
