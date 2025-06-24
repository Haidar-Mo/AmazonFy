<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\RegionTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'locale',
        'name'
    ];

    public $timestamps = false;

}
