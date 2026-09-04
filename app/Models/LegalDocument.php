<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class LegalDocument extends Model
{
    use HasLocalizedAttributes;

    protected $fillable = ['title', 'slug', 'summary', 'content', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'];

    protected $casts = ['translations' => 'array', 'is_active' => 'boolean'];

    public function getTitleAttribute($value): mixed { return $this->localizedValue('title', $value); }
    public function getSummaryAttribute($value): mixed { return $this->localizedValue('summary', $value); }
    public function getContentAttribute($value): mixed { return $this->localizedValue('content', $value); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function scopePublished(Builder $query): Builder { return $query->where('is_active', true)->orderBy('sort_order')->orderBy('title'); }

    public static function seedDefaults(): void
    {
        foreach (config('legal-documents', []) as $order => $document) {
            $record = static::query()->firstOrCreate(
                ['slug' => $document['slug']],
                [
                    'title' => $document['title'],
                    'summary' => $document['summary'] ?? null,
                    'content' => '<p>This document is being prepared and is pending legal review before publication.</p>',
                    'sort_order' => $order,
                    'is_active' => true,
                    'seo_title' => $document['title'].' | '.config('site.brand.name'),
                ]
            );

            // Preserve admin edits, but replace the initial placeholder with the matching legacy sections.
            if (self::isPlaceholder($record->getRawOriginal('content'))) {
                $legacyContent = self::legacyContent($document['slug']);

                if (filled($legacyContent)) {
                    $record->forceFill(['content' => $legacyContent])->save();
                }
            }
        }
    }

    private static function isPlaceholder(?string $content): bool
    {
        return blank($content) || str_contains($content, 'This document is being prepared');
    }

    private static function legacyContent(string $slug): ?string
    {
        foreach ([
            base_path('legal/'.$slug.'.html'),
            base_path('Turkelitemedcare V10 Draft/legal/'.$slug.'.html'),
        ] as $path) {
            if (! File::exists($path)) {
                continue;
            }

            $html = File::get($path);

            if (preg_match('/<section class="template-section">(.*?)<\/section>/is', $html, $matches) === 1) {
                return trim($matches[1]);
            }
        }

        return null;
    }
}
