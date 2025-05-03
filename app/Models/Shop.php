<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'type',
        'address',
        'status',       //- pending - rejected - active - inactive
    ];


    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function product()
    {
        return   $this->hasMany(ShopProduct::class);
    }
    public function order()
    {
        return $this->hasMany(ShopOrder::class);
    }
}
