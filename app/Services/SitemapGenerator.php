<?php

namespace App\Services;

use App\Models\Guide;
use App\Models\Clinic;
use App\Models\Procedure;
use App\Models\Specialty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class SitemapGenerator
{
    /** @var list<string> */
    private array $locales = ['en', 'de', 'ar'];

    public function xml(): string
    {
        $entries = collect()
            ->merge($this->coreEntries())
            ->merge($this->specialtyEntries())
            ->merge($this->clinicEntries())
            ->merge($this->procedureEntries())
            ->merge($this->guideEntries());

        $xml = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">',
        ];

        foreach ($entries as $entry) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>'.$this->escapeXml($entry['url']).'</loc>';

            foreach ($entry['alternates'] as $locale => $url) {
                $xml[] = '    <xhtml:link rel="alternate" hreflang="'.$locale.'" href="'.$this->escapeXml($url).'" />';
            }
            if (isset($entry['alternates']['en'])) {
                $xml[] = '    <xhtml:link rel="alternate" hreflang="x-default" href="'.$this->escapeXml($entry['alternates']['en']).'" />';
            }

            if ($entry['lastmod']) {
                $xml[] = '    <lastmod>'.$entry['lastmod'].'</lastmod>';
            }
            $xml[] = '    <changefreq>'.$entry['changefreq'].'</changefreq>';
            $xml[] = '    <priority>'.$entry['priority'].'</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return implode("\n", $xml)."\n";
    }

    public function write(): void
    {
        File::put(public_path('sitemap.xml'), $this->xml());
    }

    /** @return Collection<int,array<string,mixed>> */
    private function coreEntries(): Collection
    {
        $routes = [
            ['home', [], 'weekly', '1.0'],
            ['treatments.index', [], 'weekly', '0.95'],
            ['how-it-works', [], 'monthly', '0.8'],
            ['patient-services', [], 'monthly', '0.75'],
            ['clinics.index', [], 'weekly', '0.75'],
            ['guides.index', [], 'weekly', '0.7'],
            ['about', [], 'monthly', '0.65'],
            ['contact', [], 'monthly', '0.6'],
        ];

        return collect($routes)->flatMap(function (array $row): array {
            [$routeName, $params, $frequency, $priority] = $row;
            $alternates = $this->alternateUrls($routeName, $params);

            return collect($this->locales)->map(
                fn (string $locale): array => $this->entry($alternates[$locale], null, $frequency, $priority, $alternates)
            )->all();
        });
    }

    /** @return Collection<int,array<string,mixed>> */
    private function specialtyEntries(): Collection
    {
        return Specialty::published()->get()->flatMap(function (Specialty $specialty): array {
            $alternates = $this->alternateUrls('treatments.show', ['specialty' => $specialty]);
            return collect($this->locales)->map(
                fn (string $locale): array => $this->entry($alternates[$locale], $specialty->updated_at, 'weekly', '0.85', $alternates)
            )->all();
        });
    }

    /** @return Collection<int,array<string,mixed>> */
    private function clinicEntries(): Collection
    {
        return Clinic::published()->get()
            ->filter(fn (Clinic $clinic): bool => ! str_contains((string) $clinic->seo_robots, 'noindex'))
            ->flatMap(function (Clinic $clinic): array {
                $alternates = $this->alternateUrls('clinics.show', ['clinic' => $clinic]);
                return collect($this->locales)->map(
                    fn (string $locale): array => $this->entry($alternates[$locale], $clinic->updated_at, 'monthly', '0.75', $alternates)
                )->all();
            });
    }

    /** @return Collection<int,array<string,mixed>> */
    private function procedureEntries(): Collection
    {
        $priority = config('seo.priority_procedures', []);

        return Procedure::published()->with('specialty')->whereIn('slug', $priority)->get()
            ->filter(fn (Procedure $procedure): bool => $procedure->specialty?->is_active === true)
            ->flatMap(function (Procedure $procedure): array {
                $params = ['specialty' => $procedure->specialty, 'procedure' => $procedure];
                $alternates = $this->alternateUrls('procedures.show', $params);
                return collect($this->locales)->map(
                    fn (string $locale): array => $this->entry($alternates[$locale], $procedure->updated_at, 'monthly', '0.8', $alternates)
                )->all();
            });
    }

    /** @return Collection<int,array<string,mixed>> */
    private function guideEntries(): Collection
    {
        // The current guide library is English-first. Do not create hreflang variants until a guide has reviewed translations.
        return Guide::published()->get()->map(function (Guide $guide): array {
            $url = $this->urlFor('guides.show', ['guide' => $guide], 'en');
            return $this->entry($url, $guide->updated_at, 'monthly', '0.65', ['en' => $url]);
        });
    }

    /** @return array<string,string> */
    private function alternateUrls(string $routeName, array $params): array
    {
        $urls = [];
        foreach ($this->locales as $locale) {
            $urls[$locale] = $this->urlFor($routeName, $params, $locale);
        }
        return $urls;
    }

    private function urlFor(string $routeName, array $params, string $locale): string
    {
        if ($locale !== 'en') {
            $params = ['locale' => $locale] + $params;
        }
        return route($routeName, $params);
    }

    /** @param array<string,string> $alternates @return array{url:string,lastmod:?string,changefreq:string,priority:string,alternates:array<string,string>} */
    private function entry(string $url, mixed $updatedAt, string $changefreq, string $priority, array $alternates): array
    {
        return [
            'url' => $url,
            'lastmod' => $updatedAt?->toDateString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
            'alternates' => $alternates,
        ];
    }

    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
