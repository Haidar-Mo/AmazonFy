<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTypeTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTypeTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'locale',
        'name'
    ];

    public $timestamps = false;

}
