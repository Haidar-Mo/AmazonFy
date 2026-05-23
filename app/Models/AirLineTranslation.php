<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirLineTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\AirLineTranslationFactory> */
    use HasFactory;


    protected $fillable = [
        'air_line_id',
        'locale',
        'name',
    ];

    public $timestamps = false;


    public function airLine()
    {
        return $this->belongsTo(AirLine::class);
    }

}
