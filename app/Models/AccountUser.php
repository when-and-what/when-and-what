<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountUser extends Pivot
{
    public $incrementing = true;

    protected $table = 'account_user';

    protected $casts = [
        'token' => 'encrypted',
        'refresh_token' => 'encrypted',
    ];

    /** @return BelongsTo <Account, $this> */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
