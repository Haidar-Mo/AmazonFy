<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'available_balance',
        'marginal_balance',
        'total_balance',
        'wallet_password',
    ];

    // protected $hidden = [
    //     'wallet_password',
    // ];

    protected function casts(): array
    {
        return [
            'wallet_password' => 'hashed',
        ];
    }

    protected $appends = [
        'shop_id',
        'shop_name'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class);
    }

    public function walletAddress(): HasMany
    {
        return $this->hasMany(WalletAddress::class);
    }

    //! Accessors
    public function getShopIdAttribute()
    {
        return $this->user()->first()
            ->shop?->id;
    }
    public function getShopNameAttribute()
    {
        return $this->user()->first()
            ->shop?->name;
    }
}
