<?php

namespace App\Models\Party;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Party extends Model {
    public $fillable = [
        'user',
        'origin',
        'role'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user');
    }
}

