<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\Specialty;
use App\Services\SitemapGenerator;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function sitemap(SitemapGenerator $sitemap): Response
    {
        return response($sitemap->xml(), 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /dashboard\n\nSitemap: ".url('/sitemap.xml')."\n", 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /** @return Collection<int, array{url: string, lastmod: ?string, changefreq: string, priority: string}> */
    private function specialtyEntries(): Collection
    {
        return Specialty::published()->get()->map(fn (Specialty $specialty): array => $this->entry(
            route('treatments.show', $specialty),
            $specialty->updated_at,
            'weekly',
            '0.8'
        ));
    }

    /** @return Collection<int, array{url: string, lastmod: ?string, changefreq: string, priority: string}> */
    private function procedureEntries(): Collection
    {
        return Procedure::published()->with('specialty')->get()
            ->filter(fn (Procedure $procedure): bool => $procedure->specialty?->is_active === true)
            ->map(fn (Procedure $procedure): array => $this->entry(
                route('procedures.show', [$procedure->specialty, $procedure]),
                $procedure->updated_at,
                'monthly',
                '0.7'
            ));
    }

    /** @return Collection<int, array{url: string, lastmod: ?string, changefreq: string, priority: string}> */
    private function clinicEntries(): Collection
    {
        return Clinic::published()->get()->map(fn (Clinic $clinic): array => $this->entry(
            route('clinics.show', $clinic),
            $clinic->updated_at,
            'monthly',
            '0.6'
        ));
    }

    /** @return Collection<int, array{url: string, lastmod: ?string, changefreq: string, priority: string}> */
    private function storyEntries(): Collection
    {
        return PatientStory::published()->get()->map(fn (PatientStory $story): array => $this->entry(
            route('stories.show', $story),
            $story->updated_at,
            'monthly',
            '0.6'
        ));
    }

    /** @return array{url: string, lastmod: ?string, changefreq: string, priority: string} */
    private function entry(string $url, mixed $updatedAt, string $changefreq, string $priority): array
    {
        return [
            'url' => $url,
            'lastmod' => $updatedAt?->toDateString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    /** @param Collection<int, array{url: string, lastmod: ?string, changefreq: string, priority: string}> $entries */
    private function sitemapXml(Collection $entries): string
    {
        $xml = ['<?xml version="1.0" encoding="UTF-8"?>', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];

        foreach ($entries as $entry) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>'.$this->escapeXml($entry['url']).'</loc>';

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

    private function escapeXml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
