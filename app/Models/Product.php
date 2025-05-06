<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'details',
        'type',
        'wholesale_price',
        'selling_price',
        'is_available',
    ];

    public function shopProduct()
    {
        return $this->hasMany(ShopProduct::class);
    }

    public function item()
    {
        return  $this->hasMany(OrderItem::class);
    }
}
