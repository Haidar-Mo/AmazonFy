<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'target',
        'amount',
        'image',
        'status',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transaction()
    {
        return $this->morphOne(TransactionHistory::class, 'transactionable');
    }
}
