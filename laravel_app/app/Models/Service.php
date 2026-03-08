<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'image', 'detail_content', 'slug', 'parentid', 'type'];

    protected $casts = [
        'detail_content' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parentid');
    }

    public function children()
    {
        return $this->hasMany(Service::class, 'parentid');
    }
}
