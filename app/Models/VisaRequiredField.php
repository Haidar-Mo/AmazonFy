<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaRequiredField extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'visa_id',
        'key',
        'type',
        'is_file',
        'is_required',
    ];

    public $translatedAttributes = ['label'];

    protected $casts = [
        'is_required' => 'boolean'
    ];


    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }
}
