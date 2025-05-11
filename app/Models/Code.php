<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'user_id',
        'verification_code',
        'expired_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
