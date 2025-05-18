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
        'shop_type_id',
        'name',
        'phone_number',
        'identity_number',
        'logo',
        'identity_front_face',
        'identity_back_face',
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
    public function orders()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ShopType::class, 'shop_type_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_products');
    }

    //! Accessories

    public function getLogoFullPathAttribute()
    {
        return asset($this->logo);
    }
    public function getIdentityFrontFaceFullPathAttribute()
    {
        return asset($this->identity_front_face);
    }
    public function getIdentityBackFaceFullPathAttribute()
    {
        return asset($this->identity_back_face);
    }

    public function getTypeNameAttribute()
    {
        return $this->type()->first()->name;
    }
}
