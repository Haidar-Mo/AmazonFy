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
        'representative_code',
        'rate',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function shopProduct()
    {
        return $this->hasMany(ShopProduct::class);
    }
    public function shopOrders()
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

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }


    //! Accessories
    //: logo_full_path, identity_front_face_full_path,  identity_back_face_full_path,   type_name,  is_blocked, is_blocked_text

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

    public function getIsBlockedAttribute()
    {
        return $this->user()->first()->is_blocked;
    }

    public function getIsBlockedTextAttribute()
    {
        return $this->user()->first()->is_blocked ? __('texts.user.status.blocked') : __('texts.user.status.unblocked');
    }
}
