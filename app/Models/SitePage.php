<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SitePage extends Model
{
    use HasLocalizedAttributes;
    protected $fillable = [
        'slug',
        'title',
        'description',
        'template',
        'content',
        'translations',
        'is_active',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_robots',
    ];

    protected $casts = [
        'content' => 'array',
        'translations' => 'array',
        'is_active' => 'bool',
    ];

    public function getTitleAttribute($value): mixed { return $this->localizedValue('title', $value); }
    public function getDescriptionAttribute($value): mixed { return $this->localizedValue('description', $value); }
    public function getContentAttribute($value): mixed
    {
        $content = is_string($value) ? json_decode($value, true) : $value;

        $baseContent = is_array($content) ? $content : [];
        $locale = app()->getLocale();
        $translatedContent = data_get($this->translations ?? [], $locale.'.content');

        return $locale !== 'en' && is_array($translatedContent)
            ? array_replace_recursive($baseContent, $translatedContent)
            : $baseContent;
    }

    public static function resolve(string $slug, array $defaults = []): self
    {
        $baseAttributes = [
            'title' => self::titleFromSlug($slug),
            'description' => '',
            'template' => $slug,
            'content' => [],
            'is_active' => true,
        ];

        try {
            $page = static::query()->firstOrCreate(
                ['slug' => $slug],
                array_merge($baseAttributes, Arr::only($defaults, array_keys($baseAttributes)))
            );

            $storedContent = $page->getRawOriginal('content');
            $storedContent = is_string($storedContent) ? json_decode($storedContent, true) : $storedContent;
            $storedContent = is_array($storedContent) ? $storedContent : [];
            $mergedContent = array_replace_recursive($defaults['content'] ?? [], $storedContent);

            if ($storedContent !== $mergedContent) {
                $page->forceFill(['content' => $mergedContent]);
                $page->save();
            }

            $page->content = $mergedContent;

            return $page;
        } catch (\Throwable) {
            return new static(array_merge(
                ['slug' => $slug],
                $baseAttributes,
                Arr::only($defaults, array_keys($baseAttributes))
            ));
        }
    }

    public static function seedDefaults(): void
    {
        foreach (config('site-pages', []) as $slug => $defaults) {
            static::resolve($slug, is_array($defaults) ? $defaults : []);
        }
    }

    public static function titleFromSlug(string $slug): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $slug));
    }
}
