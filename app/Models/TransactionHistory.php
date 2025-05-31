<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'wallet_id',
        'amount',
        'transaction_type',
        'target',
        'charge_network',
        'coin_type',
        'status',
        'image',
    ];

    public function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i'
        ];
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactionable()
    {
        $this->morphTo();
    }


    //! Accessories

    public function getUserNameAttribute()
    {
        return $this->wallet()->first()->user()->first()->name;
    }
    public function getUserPhoneNumberAttribute()
    {
        return $this->wallet()->first()->user()->first()->phone_number;
    }

    public function getImageFullPathAttribute()
    {
        if ($this->image) {
            return asset(path: $this->image);
        } else {
            return '';
        }
    }

}
