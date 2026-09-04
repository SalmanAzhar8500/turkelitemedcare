<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['clinic_id', 'name', 'slug', 'designation', 'specialty', 'summary', 'content', 'profile_highlight', 'experience_years', 'languages', 'consultation_method', 'response_time', 'verification_notes', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'];
    protected $casts = ['is_active' => 'boolean', 'translations' => 'array'];

    public function getNameAttribute($value): mixed { return $this->localizedValue('name', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getContentAttribute($value): mixed { return $this->localizedValue('content', $value); }
    public function getSpecialtyAttribute($value): mixed
    {
        if ($this->relationLoaded('treatments') && $this->treatments->isNotEmpty()) {
            return $this->treatments->pluck('name')->implode(', ');
        }

        return $value;
    }
    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function treatments(): BelongsToMany { return $this->belongsToMany(Specialty::class, 'doctor_specialty')->withTimestamps(); }
    public function procedures(): BelongsToMany { return $this->belongsToMany(Procedure::class, 'doctor_procedure')->withTimestamps(); }
}
