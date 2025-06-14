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
        'type_id',
        'local',
        'title',
        'details',
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

    public function type()
    {
        return $this->belongsTo(ProductType::class);
    }

    //! Accessories
    //- type_name,  full_path_image

    public function getTypeNameAttribute()
    {
        return $this->type()->first()->name ?? __('texts.product.type.undefined');
    }
    public function getFullPathImageAttribute()
    {
        return asset($this->image);
    }

}
