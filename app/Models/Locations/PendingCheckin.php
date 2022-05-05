<?php

namespace App\Models\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingCheckin extends Model
{
    use HasFactory;

    protected $casts = ['checkin_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
