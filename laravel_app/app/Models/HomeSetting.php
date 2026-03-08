<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_button_text_secondary',
        'hero_button_link_secondary',
        'hero_image',
        'hero_video',
        'hero_video_url',
        'hero_items',
        'about_data',
        'about_page_data',
        'contact_data',
        'services_data',
        'whatwedo_data',
        'causes_data',
        'whychoose_data',
        'howitwork_data',
        'testimonials_data',
        'gallery_data',
        'lasthope_data',
        'mail_data',
        'header_footer_data',
    ];

    protected $casts = [
        'hero_items' => 'array',
        'about_data' => 'array',
        'about_page_data' => 'array',
        'contact_data' => 'array',
        'services_data' => 'array',
        'whatwedo_data' => 'array',
        'causes_data' => 'array',
        'whychoose_data' => 'array',
        'howitwork_data' => 'array',
        'testimonials_data' => 'array',
        'gallery_data' => 'array',
        'lasthope_data' => 'array',
        'mail_data' => 'array',
        'header_footer_data' => 'array',
    ];
}
