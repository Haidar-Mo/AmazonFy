<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'name',     //!for Network name
        'target'
    ];


    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
