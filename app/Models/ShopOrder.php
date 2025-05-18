<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    use HasFactory;


    protected $fillable = [
        'shop_id',
        'client_id',
        'product_id',
        'selling_price',
        'wholesale_price',
        'count',
        'total_price',
        'customer_note',
        'status'        //- checking, reviewing, delivering, canceled
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    //! Accessories

    public function getShopNameAttribute()
    {
        return $this->shop()->first()->name;
    }

    public function getMerchantNameAttribute()
    {
        return $this->shop()->first()->user()->first()->name;
    }

    public function getClientNameAttribute()
    {
        return $this->client()->first()->name;
    }
    public function getClientEmailAttribute()
    {
        return $this->client()->first()->email;

    }
    public function getClientAddressAttribute()
    {
        return $this->client()->first()->address;
    }
    public function getClientPhoneNumberAttribute()
    {
        return $this->client()->first()->phone_number;

    }
    public function getClientRegionAttribute()
    {
        return $this->client()->first()->region()->first()->name;
    }


    public function getCreatedFromAttribute()
    {
        Carbon::setLocale('ar');
        $diff = $this->created_at->locale('ar')->diffForHumans();
        return preg_replace('/(d+)/', '<strong>$1</strong>', $diff);
    }
}