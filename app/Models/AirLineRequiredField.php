<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirLineRequiredField extends Model
{
    /** @use HasFactory<\Database\Factories\AirLineRequiredFieldFactory> */
    use HasFactory, Translatable;

    protected $fillable = [
        'air_line_id',
        'key',
        'type',
        'is_file',
        'is_required',
    ];

    public $translatedAttributes = ['label'];


    protected $casts = [
        'is_required' => 'boolean'
    ];


    public function airLine()
    {
        return $this->belongsTo(AirLine::class);
    }

}
