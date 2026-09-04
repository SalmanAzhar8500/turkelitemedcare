<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condition extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['specialty_id', 'name', 'slug', 'summary', 'content', 'translations', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'translations' => 'array'];
    public function getNameAttribute($value): mixed { return $this->localizedValue('name', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getContentAttribute($value): mixed { return $this->localizedValue('content', $value); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'); }
    public function specialty(): BelongsTo { return $this->belongsTo(Specialty::class); }
    public function procedures(): HasMany { return $this->hasMany(Procedure::class); }
}
