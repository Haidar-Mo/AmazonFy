<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndConditionsTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\TermsAndConditionsTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'locale',
        'content',
    ];

    public $timestamps = false;

}
