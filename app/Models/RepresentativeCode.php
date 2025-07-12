<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepresentativeCode extends Model
{
    /** @use HasFactory<\Database\Factories\RepresentativeCodeFactory> */
    use HasFactory;

    protected $fillable =[
        'representor_name',
        'code',
    ];
}
