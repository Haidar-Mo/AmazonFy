<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'identity_number',
        'logo',
        'identity_front_face',
        'identity_back_face',
        'shop_type_id',
        'address',
        'status',       //- pending - rejected - active - inactive
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shopProduct()
    {
        return $this->hasMany(ShopProduct::class);
    }
    public function order()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ShopType::class, 'shop_type_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'shop_products');
    }
}
