<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\PatientService;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\SitePage;
use App\Models\Specialty;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportStaticHtmlContent extends Command
{
    protected $signature = 'site:import-static-content
        {--source=Turkelitemedcare V10 Draft : Static HTML folder relative to the Laravel project}
        {--dry-run : Analyze pages without writing records}';

    protected $description = 'Import content from the static Turkelitemedcare HTML site into Laravel content tables.';

    /** @var array<string, int> */
    private array $counts = [
        'specialties' => 0,
        'procedures' => 0,
        'clinics' => 0,
        'patient services' => 0,
        'patient stories' => 0,
        'site pages' => 0,
        'German translations' => 0,
    ];

    /** @var array<string, Specialty> */
    private array $specialties = [];

    public function handle(): int
    {
        if (! class_exists(DOMDocument::class)) {
            $this->error('The PHP DOM extension is required to import HTML content. Enable extension=dom in php.ini.');

            return self::FAILURE;
        }

        $source = base_path(trim((string) $this->option('source'), "\\/"));

        if (! File::isDirectory($source)) {
            $this->error("Static HTML source folder was not found: {$source}");

            return self::FAILURE;
        }

        $pages = collect(File::allFiles($source))
            ->filter(fn ($file): bool => strtolower($file->getExtension()) === 'html')
            ->sortBy(fn ($file): string => str_replace('\\', '/', $file->getRelativePathname()))
            ->values();

        $this->info("Reading {$pages->count()} HTML pages from {$source}");

        foreach ($pages as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());
            $page = $this->readPage($file->getPathname());

            if ($page === null) {
                $this->warn("Skipped unreadable page: {$relative}");
                continue;
            }

            $this->importPage($relative, $page, $source);
        }

        $this->newLine();
        $this->table(['Imported content', 'Records'], collect($this->counts)
            ->map(fn (int $count, string $type): array => [$type, $count])
            ->values()
            ->all());

        $this->info($this->option('dry-run') ? 'Dry run complete. No records were written.' : 'Static HTML content import complete.');

        return self::SUCCESS;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importPage(string $relative, array $page, string $source): void
    {
        if ($relative === 'de/index.html') {
            $this->importGermanHome($relative, $page);

            return;
        }

        if (preg_match('#^treatments/([^/]+)/index\.html$#', $relative, $matches) === 1) {
            $this->importSpecialty($matches[1], $page, $relative);

            return;
        }

        if (preg_match('#^treatments/[^/]+/conditions/[^/]+\.html$#', $relative) === 1) {
            return;
        }

        if (preg_match('#^treatments/([^/]+)/procedures/([^/]+)\.html$#', $relative, $matches) === 1) {
            $this->importProcedure($matches[1], $matches[2], $page, $relative, $source);

            return;
        }

        if (preg_match('#^clinics/([^/]+)\.html$#', $relative, $matches) === 1 && $matches[1] !== 'index') {
            $this->importClinic($matches[1], $page, $relative);

            return;
        }

        if (preg_match('#^patient-stories/([^/]+)\.html$#', $relative, $matches) === 1 && $matches[1] !== 'index') {
            $this->importPatientStory($matches[1], $page, $relative);

            return;
        }

        if ($relative === 'patient-services.html') {
            $this->importPatientServices($page, $relative);
        }

        $this->importSitePage($relative, $page);
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importSpecialty(string $slug, array $page, string $relative): Specialty
    {
        if (isset($this->specialties[$slug])) {
            return $this->specialties[$slug];
        }

        $attributes = [
            'name' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'summary' => Str::limit($page['summary'] ?: $page['description'], 255, ''),
            'description' => $page['content'],
            'image_path' => "assets/img/synthetic/tile-{$slug}.svg",
            'sort_order' => Specialty::query()->where('slug', '!=', $slug)->count(),
            'is_active' => true,
        ];

        $specialty = Specialty::query()->firstOrNew(['slug' => $slug]);
        $specialty->fill($attributes);
        $this->save($specialty);
        $this->specialties[$slug] = $specialty;
        $this->counts['specialties']++;

        return $specialty;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importProcedure(string $specialtySlug, string $sourceSlug, array $page, string $relative, string $source): void
    {
        $specialty = $this->specialty($specialtySlug, $source);
        $slug = $this->uniqueCatalogSlug(Procedure::class, $sourceSlug, $specialty->id, $specialtySlug);
        $record = Procedure::query()->firstOrNew(['slug' => $slug]);
        $record->fill([
            'specialty_id' => $specialty->id,
            'name' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'summary' => Str::limit($page['summary'] ?: $page['description'], 255, ''),
            'content' => $this->withSource($page['content'], $relative),
            'sort_order' => Procedure::query()->where('specialty_id', $specialty->id)->whereKeyNot($record->getKey())->count(),
            'is_active' => true,
        ]);
        $this->save($record);
        $this->counts['procedures']++;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importClinic(string $sourceSlug, array $page, string $relative): void
    {
        $record = Clinic::query()->firstOrNew(['slug' => $sourceSlug]);
        $record->fill([
            'name' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'summary' => Str::limit($page['summary'] ?: $page['description'], 255, ''),
            'description' => $this->withSource($page['content'], $relative),
            'sort_order' => Clinic::query()->whereKeyNot($record->getKey())->count(),
            'is_active' => true,
        ]);
        $this->save($record);
        $this->counts['clinics']++;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importPatientStory(string $sourceSlug, array $page, string $relative): void
    {
        $record = PatientStory::query()->firstOrNew(['slug' => $sourceSlug]);
        $record->fill([
            'name' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'summary' => Str::limit($page['summary'] ?: $page['description'], 255, ''),
            'content' => $this->withSource($page['content'], $relative),
            'sort_order' => PatientStory::query()->whereKeyNot($record->getKey())->count(),
            'is_active' => true,
        ]);
        $this->save($record);
        $this->counts['patient stories']++;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importPatientServices(array $page, string $relative): void
    {
        $nodes = $page['xpath']->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' service-detail-copy ')]");

        foreach ($nodes ?: [] as $position => $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $name = $this->firstText($page['xpath'], './/h2', $node);

            if ($name === '') {
                continue;
            }

            $summary = $this->firstText($page['xpath'], './/h3 | .//p', $node);
            $record = PatientService::query()->firstOrNew(['slug' => Str::slug($name)]);
            $record->fill([
                'name' => $name,
                'summary' => Str::limit($summary, 255, ''),
                'content' => $this->withSource($this->elementText($page['xpath'], $node), $relative),
                'sort_order' => $position,
                'is_active' => true,
            ]);
            $this->save($record);
            $this->counts['patient services']++;
        }
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importSitePage(string $relative, array $page): void
    {
        $slug = $this->sitePageSlug($relative);
        $record = SitePage::query()->firstOrNew(['slug' => $slug]);
        $content = is_array($record->content) ? $record->content : [];
        $content['imported_static_content'] = [
            'source_path' => $relative,
            'text' => $page['content'],
        ];
        $record->fill([
            'title' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'description' => $page['description'] ?: $page['summary'],
            'template' => $record->template ?: $slug,
            'content' => $content,
            'is_active' => true,
        ]);
        $this->save($record);
        $this->counts['site pages']++;
    }

    /** @param array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string} $page */
    private function importGermanHome(string $relative, array $page): void
    {
        $record = SitePage::query()->firstOrNew(['slug' => 'home']);

        if (! $record->exists) {
            $record->fill([
                'title' => 'Home',
                'description' => '',
                'template' => 'home',
                'content' => [],
            ]);
        }

        $translations = is_array($record->translations) ? $record->translations : [];
        $germanContent = is_array(data_get($translations, 'de.content')) ? data_get($translations, 'de.content') : [];
        $germanContent['imported_static_content'] = [
            'source_path' => $relative,
            'text' => $page['content'],
        ];
        $translations['de'] = array_filter([
            'title' => $page['heading'] ?: $this->titleWithoutBrand($page['title']),
            'description' => $page['description'] ?: $page['summary'],
            'content' => $germanContent,
        ]);
        $record->fill(['translations' => $translations, 'is_active' => true]);
        $this->save($record);
        $this->counts['German translations']++;
    }

    private function specialty(string $slug, string $source): Specialty
    {
        if (isset($this->specialties[$slug])) {
            return $this->specialties[$slug];
        }

        $page = $this->readPage($source.DIRECTORY_SEPARATOR.'treatments'.DIRECTORY_SEPARATOR.$slug.DIRECTORY_SEPARATOR.'index.html');

        if ($page === null) {
            throw new \RuntimeException("Treatment specialty source is missing: {$slug}");
        }

        return $this->importSpecialty($slug, $page, "treatments/{$slug}/index.html");
    }

    private function uniqueCatalogSlug(string $model, string $sourceSlug, int $specialtyId, string $specialtySlug): string
    {
        $existing = $model::query()->where('slug', $sourceSlug)->first();

        if ($existing === null || (int) $existing->specialty_id === $specialtyId) {
            return $sourceSlug;
        }

        return "{$specialtySlug}-{$sourceSlug}";
    }

    /** @return array{dom: DOMDocument, xpath: DOMXPath, title: string, description: string, heading: string, summary: string, content: string}|null */
    private function readPage(string $path): ?array
    {
        $html = File::get($path);

        if ($html === '') {
            return null;
        }

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
        libxml_clear_errors();

        if (! $loaded) {
            return null;
        }

        $xpath = new DOMXPath($dom);
        $main = $xpath->query('//*[@id="main-content"]')->item(0) ?: $xpath->query('//main')->item(0);

        return [
            'dom' => $dom,
            'xpath' => $xpath,
            'title' => $this->firstText($xpath, '//title'),
            'description' => $this->metaDescription($xpath),
            'heading' => $this->firstText($xpath, '//main//h1 | //*[@id="main-content"]//h1'),
            'summary' => $this->firstText($xpath, './/p', $main),
            'content' => $main instanceof DOMElement ? $this->elementText($xpath, $main) : '',
        ];
    }

    private function metaDescription(DOMXPath $xpath): string
    {
        $node = $xpath->query('//meta[translate(@name, "DESCRIPTION", "description") = "description"]/@content')->item(0);

        return $node === null ? '' : $this->cleanText($node->nodeValue ?? '');
    }

    private function firstText(DOMXPath $xpath, string $query, ?DOMElement $context = null): string
    {
        $node = $xpath->query($query, $context)->item(0);

        return $node === null ? '' : $this->cleanText($node->textContent ?? '');
    }

    private function elementText(DOMXPath $xpath, DOMElement $element): string
    {
        $nodes = $xpath->query('.//*[self::h2 or self::h3 or self::h4 or self::p or self::li]', $element);
        $lines = [];

        foreach ($nodes ?: [] as $node) {
            $text = $this->cleanText($node->textContent ?? '');

            if ($text === '') {
                continue;
            }

            $tag = strtolower($node->nodeName);
            $lines[] = $tag === 'li' ? '- '.$text : $text;
        }

        return trim(implode("\n\n", $lines));
    }

    private function cleanText(string $text): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[\t\r\n ]+/', ' ', $text) ?? $text;

        return trim($text);
    }

    private function titleWithoutBrand(string $title): string
    {
        return trim((string) preg_replace('/\s*[|\-]\s*Turkelitemedcare.*$/i', '', $title));
    }

    private function withSource(string $content, string $relative): string
    {
        return "Imported from static HTML: {$relative}\n\n".trim($content);
    }

    private function sitePageSlug(string $relative): string
    {
        $map = [
            'index.html' => 'home',
            'about.html' => 'about',
            'contact.html' => 'contact',
            'for-clinics.html' => 'for-clinics',
            'how-it-works.html' => 'how-it-works',
            'patient-services.html' => 'patient-services',
            'treatment-plan.html' => 'treatment-plan',
            'legal.html' => 'legal',
            'treatments/index.html' => 'treatments.index',
        ];

        if (isset($map[$relative])) {
            return $map[$relative];
        }

        $path = preg_replace('/\.html$/', '', $relative) ?? $relative;
        $path = preg_replace('#/index$#', '', $path) ?? $path;

        return 'static/'.trim($path, '/');
    }

    private function save($model): void
    {
        if (! $this->option('dry-run')) {
            $model->save();
        }
    }
}
