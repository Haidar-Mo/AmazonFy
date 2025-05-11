<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'details',
        'type',
        'image',
        'wholesale_price',
        'selling_price',
        'is_available',
    ];

    public function shopProduct()
    {
        return $this->hasMany(ShopProduct::class);
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class);
    }
}
