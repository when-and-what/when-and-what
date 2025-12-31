<?php

namespace App\Models\Trackers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\Trackers\EventFactory> */
    use HasFactory;

    protected $table = 'tracker_events';

    protected $casts = [
        'event_time' => 'datetime',
    ];

    public function tracker(): BelongsTo
    {
        return $this->belongsTo(Tracker::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Tracker::class);
    }
}
