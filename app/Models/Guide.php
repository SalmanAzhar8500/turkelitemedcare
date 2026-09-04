<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['name', 'slug', 'summary', 'content', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'];

    protected $casts = ['is_active' => 'boolean', 'translations' => 'array'];

    public function getNameAttribute($value): mixed { return $this->localizedValue('name', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getContentAttribute($value): mixed { return $this->localizedValue('content', $value); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'); }
}
