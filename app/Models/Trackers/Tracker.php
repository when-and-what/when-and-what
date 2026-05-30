<?php

namespace App\Models\Trackers;

use App\Enums\TrackerUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tracker extends Model
{
    /** @use HasFactory<\Database\Factories\Trackers\TrackerFactory> */
    use HasFactory;

    protected $casts = [
        'unit' => TrackerUnit::class,
    ];

    protected $fillable = [
        'name',
        'code',
        'unit',
        'color',
        'icon',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
