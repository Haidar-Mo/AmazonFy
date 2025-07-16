<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTypeTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\ShopTypeTranslationFactory> */
    use HasFactory;

        protected $fillable = [
        'locale',
        'name'
    ];

    public $timestamps = false;
}
