<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountUser extends Pivot
{
    public $incrementing = true;
    protected $table = 'account_user';
    protected $casts = [
        'token' => 'encrypted',
        'refresh_token' => 'encrypted',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
