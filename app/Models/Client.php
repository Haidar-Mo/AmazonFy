<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'region_id',
        'address'
    ];


    public function orders()
    {
        return $this->hasMany(ShopOrder::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
