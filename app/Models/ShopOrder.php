<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;


    protected $fillable = [
        'shop_id',
        'name',
        'phone_number',
        'address',
        'total_price',
        'customer_note',
        'status'        //- checking, reviewing, delivering, canceled
    ];


    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
