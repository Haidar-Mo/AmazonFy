<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{

    protected $fillable = [
        'locale',
        'content',
    ];

}
