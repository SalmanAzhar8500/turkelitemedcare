<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialty extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['name', 'slug', 'summary', 'description', 'image_path', 'breadcrumb_image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'];

    protected $casts = ['is_active' => 'boolean', 'translations' => 'array'];

    public function getNameAttribute($value): mixed { return $this->localizedValue('name', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getDescriptionAttribute($value): mixed { return $this->localizedValue('description', $value); }

    public function getRouteKeyName(): string { return 'slug'; }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function conditions(): HasMany { return $this->hasMany(Condition::class); }
    public function procedures(): HasMany { return $this->hasMany(Procedure::class); }

    public static function seedDefaults(): void
    {
        foreach (config('site.specialties', []) as $position => $specialty) {
            static::query()->firstOrCreate(['slug' => $specialty['slug']], [
                'name' => $specialty['name'],
                'summary' => $specialty['summary'],
                'description' => $specialty['description'],
                'sort_order' => $position,
                'is_active' => true,
            ]);
        }
    }
}
