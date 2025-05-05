<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'user_id',
        'available_balance',
        'marginal_balance',
        'total_balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chargeHistories()
    {
        return $this->hasMany(ChargeHistory::class);
    }

    public function withdrawHistories()
    {
        return $this->hasMany(WithdrawHistory::class);
    }

    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }
}
