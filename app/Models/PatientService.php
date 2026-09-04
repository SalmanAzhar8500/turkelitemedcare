<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PatientService extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['name', 'slug', 'summary', 'content', 'bullet_points', 'service_points', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'];
    protected $casts = ['is_active' => 'boolean', 'translations' => 'array', 'bullet_points' => 'array', 'service_points' => 'array'];

    public function getNameAttribute($value): mixed { return $this->localizedValue('name', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getContentAttribute($value): mixed { return $this->localizedValue('content', $value); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'); }

    public static function seedDefaults(): void
    {
        $defaultBulletPoints = [
            'Planning around the confirmed treatment timetable',
            'One coordination contact from arrival to return home',
            'Support tailored to the patient journey',
        ];

        foreach (data_get(config('site-pages.patient-services'), 'content.services.items', []) as $order => $item) {
            $name = is_array($item) ? ($item['title'] ?? '') : $item;
            if ($name !== '') {
                $service = static::query()->firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'summary' => is_array($item) ? ($item['text'] ?? null) : null, 'sort_order' => $order, 'is_active' => true, 'bullet_points' => $defaultBulletPoints]);

            }
        }
    }
}
