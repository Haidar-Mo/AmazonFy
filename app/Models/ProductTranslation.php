<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'locale',
        'title',
        'details'
    ];

    public $timestamps = false;

}
