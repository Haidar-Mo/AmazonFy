<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_order_id',
        'product_id',
        'count'
    ];


    public function order()
    {
        return $this->belongsTo(ShopOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
