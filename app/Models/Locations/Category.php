<?php

namespace App\Models\Locations;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'location_categories';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
