<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientGuide extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'parentid',
        'type',
        'detail_content',
    ];

    protected $casts = [
        'detail_content' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(PatientGuide::class, 'parentid');
    }

    public function children()
    {
        return $this->hasMany(PatientGuide::class, 'parentid');
    }
}
