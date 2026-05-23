<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaRequestField extends Model
{
    /** @use HasFactory<\Database\Factories\VisaRequestFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'visa_request_id',
        'visa_required_field_id',
        'value',
    ];

    protected $appends = [
        'key',
        'is_file',

    ];

    protected $hidden = [
        'visaRequiredField',
    ];

    public function visaRequest()
    {
        return $this->belongsTo(VisaRequest::class);
    }

    public function visaRequiredField()
    {
        return $this->belongsTo(VisaRequiredField::class);
    }

    //! Accessors

    public function getKeyAttribute()
    {
        return $this->visaRequiredField?->key;
    }

    public function getIsFileAttribute()
    {
        return $this->visaRequiredField?->type == 'file';
    }
}
