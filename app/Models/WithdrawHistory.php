<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'wallet_id',
        'type',
        'amount',
        'target',
        'coin_type',
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
