<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaArrangement extends Model
{
    protected $fillable = [
        'user_id',
        'visa_id',
        'status',
        'cover_letter',
        'pdf_path'
    ];

    protected $appends = [
        'visa_name',
        'user_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function fields()
    {
        return $this->hasMany(VisaArrangementField::class);
    }

    //!Accessors

    public function getVisaNameAttribute()
    {
        return $this->visa?->name;
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name;
    }

    public function getCoverLetterContentAttribute()
    {
        return $this->coverLetter?->content;
    }

    public function getShopNameAttribute()
    {
        return $this->user->shop?->name;
    }


}
