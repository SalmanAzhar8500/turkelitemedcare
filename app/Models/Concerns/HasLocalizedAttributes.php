<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    /** @return array<string, mixed> */
    public function translation(string $locale): array
    {
        return data_get($this->translations ?? [], $locale, []);
    }

    public function getSeoTitleAttribute($value): mixed { return $this->localizedValue('seo_title', $value); }
    public function getSeoDescriptionAttribute($value): mixed { return $this->localizedValue('seo_description', $value); }
    public function getSeoKeywordsAttribute($value): mixed { return $this->localizedValue('seo_keywords', $value); }

    protected function localizedValue(string $field, mixed $fallback): mixed
    {
        $locale = app()->getLocale();
        $translated = data_get($this->translations ?? [], $locale.'.'.$field);

        return $locale !== 'en' && filled($translated) ? $translated : $fallback;
    }
}
