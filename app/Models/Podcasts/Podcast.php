<?php

namespace App\Models\Podcasts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property string $image
 * @property string $author
 * @property bool $is_private
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Podcast extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name'];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function scopeName(Builder $query, string $name): void
    {
        $query->where('name', $name);
    }
}
