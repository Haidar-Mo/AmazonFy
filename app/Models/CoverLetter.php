<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverLetter extends Model
{
    
    protected $fillable = [
        'visa_id',
        'locale',
        'content',
    ];

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }
}
