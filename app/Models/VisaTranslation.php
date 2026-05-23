<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class VisaTranslation extends Model
{
    protected $fillable = [
        'locale',
        'name',
        'description',
        'destination',
    ];

    public $timestamps = false;

}
