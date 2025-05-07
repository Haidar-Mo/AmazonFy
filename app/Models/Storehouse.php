<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storehouse extends Model
{

    protected $fillable = [
        'name',
        'region_id'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    //! Accessories

    public function getRegionNameAttribute()
    {
        return $this->region()->first()->name;
    }
}
