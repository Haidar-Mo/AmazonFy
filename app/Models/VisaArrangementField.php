<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaArrangementField extends Model
{
    protected $fillable = [
        'visa_arrangement_id',
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

    public function visaArrangement()
    {
        return $this->belongsTo(VisaArrangement::class);
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
