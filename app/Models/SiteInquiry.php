<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInquiry extends Model
{
    protected $fillable = [
        'type',
        'page_slug',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'preferred_language',
        'preferred_time',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
