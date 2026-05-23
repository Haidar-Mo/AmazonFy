<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirLineRequiredFieldTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\AirLineRequiredFieldTranslationFactory> */
    use HasFactory;

    protected $fillable = [
        'air_line_required_field_id',
        'locale',
        'label'
    ];

    public $timestamps = false;
}
