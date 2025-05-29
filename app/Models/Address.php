<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $fillable = [
        'network_name',
        'target',
        'image'
    ];

}
