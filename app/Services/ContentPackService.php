<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Condition;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\LegalDocument;
use App\Models\PatientService;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class ContentPackService
{
    /** @var list<string> */
    public const DATASETS = [
        'settings',
        'site_pages',
        'specialties',
        'conditions',
        'procedures',
        'clinics',
        'doctors',
        'guides',
        'patient_services',
        'patient_stories',
        'legal_documents',
    ];

    /** @return array{path:string, manifest:array<string,mixed>, datasets:array<string,array<int,array<string,mixed>>>, errors:list<string>, warnings:list<string>, counts:array<string,int>} */
    public function validate(string $contentPath): array
    {
        $path = $this->resolvePath($contentPath);
        $errors = [];
        $warnings = [];

        if (! File::isDirectory($path)) {
            return [
                'path' => $path,
                'manifest' => [],
                'datasets' => [],
                'errors' => ["Content pack folder not found: {$path}"],
                'warnings' => [],
                'counts' => [],
            ];
        }

        $manifest = $this->readObject($path.DIRECTORY_SEPARATOR.'manifest.json', $errors);
        $datasets = [];

        foreach (self::DATASETS as $dataset) {
            $datasets[$dataset] = $this->readList($path.DIRECTORY_SEPARATOR.$dataset.'.json', $errors);
        }

        if (! in_array((int) ($manifest['schema_version'] ?? 0), [1, 2], true)) {
            $errors[] = 'manifest.json must contain schema_version = 1 or 2.';
        }

        if (($manifest['locale'] ?? null) !== 'en') {
            $warnings[] = 'The canonical content pack should keep English as the base locale while translations live inside each record.';
        }

        if ((int) ($manifest['schema_version'] ?? 0) >= 2) {
            $locales = (array) ($manifest['locales'] ?? []);
            foreach (['en', 'de', 'ar'] as $requiredLocale) {
                if (! in_array($requiredLocale, $locales, true)) {
                    $errors[] = "manifest.json schema v2 must declare locale '{$requiredLocale}'.";
                }
            }
        }

        $this->validateUnique($datasets['settings'], 'key', 'settings', $errors);
        foreach (array_diff(self::DATASETS, ['settings']) as $dataset) {
            $key = $dataset === 'site_pages' ? 'slug' : 'slug';
            $this->validateUnique($datasets[$dataset], $key, $dataset, $errors);
        }

        $specialtySlugs = $this->keySet($datasets['specialties'], 'slug');
        $conditionSlugs = $this->keySet($datasets['conditions'], 'slug');
        $procedureSlugs = $this->keySet($datasets['procedures'], 'slug');
        $clinicSlugs = $this->keySet($datasets['clinics'], 'slug');

        $this->requireFields($datasets['site_pages'], ['slug', 'title', 'template'], 'site_pages', $errors);
        $this->requireFields($datasets['specialties'], ['slug', 'name'], 'specialties', $errors);
        $this->requireFields($datasets['procedures'], ['slug', 'name', 'specialty_slug'], 'procedures', $errors);
        $this->requireFields($datasets['clinics'], ['slug', 'name'], 'clinics', $errors);
        $this->requireFields($datasets['doctors'], ['slug', 'name'], 'doctors', $errors);
        $this->requireFields($datasets['guides'], ['slug', 'name'], 'guides', $errors);
        $this->requireFields($datasets['patient_services'], ['slug', 'name'], 'patient_services', $errors);
        $this->requireFields($datasets['patient_stories'], ['slug', 'name'], 'patient_stories', $errors);
        $this->requireFields($datasets['legal_documents'], ['slug', 'title'], 'legal_documents', $errors);

        foreach ($datasets['conditions'] as $row) {
            $slug = (string) ($row['slug'] ?? '(unknown)');
            $specialty = (string) ($row['specialty_slug'] ?? '');
            if ($specialty === '' || ! isset($specialtySlugs[$specialty])) {
                $errors[] = "conditions: {$slug} references missing specialty_slug '{$specialty}'.";
            }
        }

        foreach ($datasets['procedures'] as $row) {
            $slug = (string) ($row['slug'] ?? '(unknown)');
            $specialty = (string) ($row['specialty_slug'] ?? '');
            if ($specialty === '' || ! isset($specialtySlugs[$specialty])) {
                $errors[] = "procedures: {$slug} references missing specialty_slug '{$specialty}'.";
            }
            $condition = $row['condition_slug'] ?? null;
            if (filled($condition) && ! isset($conditionSlugs[(string) $condition])) {
                $errors[] = "procedures: {$slug} references missing condition_slug '{$condition}'.";
            }
        }

        foreach ($datasets['doctors'] as $row) {
            $slug = (string) ($row['slug'] ?? '(unknown)');
            $clinic = $row['clinic_slug'] ?? null;
            if (filled($clinic) && ! isset($clinicSlugs[(string) $clinic])) {
                $errors[] = "doctors: {$slug} references missing clinic_slug '{$clinic}'.";
            }
            foreach ((array) ($row['specialty_slugs'] ?? []) as $specialty) {
                if (! isset($specialtySlugs[(string) $specialty])) {
                    $errors[] = "doctors: {$slug} references missing specialty '{$specialty}'.";
                }
            }
            foreach ((array) ($row['procedure_slugs'] ?? []) as $procedure) {
                if (! isset($procedureSlugs[(string) $procedure])) {
                    $errors[] = "doctors: {$slug} references missing procedure '{$procedure}'.";
                }
            }
        }

        foreach ($datasets as $dataset => $rows) {
            foreach ($rows as $index => $row) {
                foreach (['image_path', 'breadcrumb_image_path'] as $field) {
                    $value = $row[$field] ?? null;
                    if (! filled($value)) {
                        continue;
                    }
                    $value = (string) $value;
                    if (Str::startsWith($value, ['http://localhost', 'https://localhost', 'http://127.0.0.1', 'https://127.0.0.1'])) {
                        $errors[] = "{$dataset}[{$index}].{$field} contains a localhost URL: {$value}";
                        continue;
                    }
                    if (! Str::startsWith($value, ['http://', 'https://', '//', 'data:']) && ! $this->mediaExists($value)) {
                        $warnings[] = "{$dataset}[{$index}].{$field} points to a missing packaged file: {$value}";
                    }
                }

                $this->scanForAccidentalContent($dataset, $index, $row, $warnings);
            }
        }

        foreach ($datasets['settings'] as $row) {
            $key = strtolower((string) ($row['key'] ?? ''));
            if (Str::contains($key, ['password', 'secret', 'private_key'])) {
                $errors[] = "settings.json must not contain secret setting '{$key}'. Keep secrets in .env.";
            }
        }

        // Keep canonical SEO fields inside the database schema limits used by the
        // public-content tables. Catch editorial overflow before a transaction
        // reaches MySQL and rolls the whole factory import back.
        $seoLimitedDatasets = [
            'site_pages', 'specialties', 'procedures', 'clinics',
            'doctors', 'guides', 'patient_services', 'patient_stories',
        ];
        foreach ($seoLimitedDatasets as $dataset) {
            foreach ($datasets[$dataset] as $index => $row) {
                foreach ([
                    'seo_title' => 60,
                    'seo_description' => 160,
                    'seo_keywords' => 500,
                    'seo_robots' => 100,
                ] as $field => $limit) {
                    $value = $row[$field] ?? null;
                    if (is_string($value) && mb_strlen($value) > $limit) {
                        $errors[] = "{$dataset}[{$index}].{$field} is ".mb_strlen($value)." characters; database limit is {$limit}.";
                    }
                }
            }
        }

        $counts = [];
        foreach ($datasets as $dataset => $rows) {
            $counts[$dataset] = count($rows);
        }

        return compact('path', 'manifest', 'datasets', 'errors', 'warnings', 'counts');
    }

    /** @return array{counts:array<string,int>, warnings:list<string>} */
    public function import(string $contentPath, bool $prune = false): array
    {
        $result = $this->validate($contentPath);
        if ($result['errors'] !== []) {
            throw new RuntimeException("Content pack validation failed:\n- ".implode("\n- ", $result['errors']));
        }

        /** @var array<string,array<int,array<string,mixed>>> $datasets */
        $datasets = $result['datasets'];
        $counts = [];

        DB::transaction(function () use ($datasets, $prune, &$counts): void {
            app()->setLocale('en');

            foreach ($datasets['settings'] as $row) {
                SiteSetting::query()->updateOrCreate(
                    ['key' => $row['key']],
                    ['value' => $row['value'] ?? null]
                );
            }
            $counts['settings'] = count($datasets['settings']);

            $counts['site_pages'] = $this->upsertSimple(SitePage::class, $datasets['site_pages'], 'slug');
            $counts['specialties'] = $this->upsertSimple(Specialty::class, $datasets['specialties'], 'slug');
            $counts['clinics'] = $this->upsertSimple(Clinic::class, $datasets['clinics'], 'slug');

            $conditionIds = [];
            foreach ($datasets['conditions'] as $row) {
                $specialty = Specialty::query()->where('slug', $row['specialty_slug'])->firstOrFail();
                $attrs = $this->without($row, ['specialty_slug']);
                $attrs['specialty_id'] = $specialty->id;
                $record = Condition::query()->updateOrCreate(['slug' => $row['slug']], $this->without($attrs, ['slug']));
                $conditionIds[$record->slug] = $record->id;
            }
            $counts['conditions'] = count($datasets['conditions']);

            foreach ($datasets['procedures'] as $row) {
                $specialty = Specialty::query()->where('slug', $row['specialty_slug'])->firstOrFail();
                $attrs = $this->without($row, ['specialty_slug', 'condition_slug']);
                $attrs['specialty_id'] = $specialty->id;
                $attrs['condition_id'] = filled($row['condition_slug'] ?? null)
                    ? ($conditionIds[(string) $row['condition_slug']] ?? Condition::query()->where('slug', $row['condition_slug'])->value('id'))
                    : null;
                Procedure::query()->updateOrCreate(['slug' => $row['slug']], $this->without($attrs, ['slug']));
            }
            $counts['procedures'] = count($datasets['procedures']);

            foreach ($datasets['doctors'] as $row) {
                $clinicId = null;
                if (filled($row['clinic_slug'] ?? null)) {
                    $clinicId = Clinic::query()->where('slug', $row['clinic_slug'])->value('id');
                }
                $attrs = $this->without($row, ['clinic_slug', 'specialty_slugs', 'procedure_slugs']);
                $attrs['clinic_id'] = $clinicId;
                $doctor = Doctor::query()->updateOrCreate(['slug' => $row['slug']], $this->without($attrs, ['slug']));

                $specialtyIds = Specialty::query()->whereIn('slug', (array) ($row['specialty_slugs'] ?? []))->pluck('id')->all();
                $procedureIds = Procedure::query()->whereIn('slug', (array) ($row['procedure_slugs'] ?? []))->pluck('id')->all();
                $doctor->treatments()->sync($specialtyIds);
                $doctor->procedures()->sync($procedureIds);
            }
            $counts['doctors'] = count($datasets['doctors']);

            $counts['guides'] = $this->upsertSimple(Guide::class, $datasets['guides'], 'slug');
            $counts['patient_services'] = $this->upsertSimple(PatientService::class, $datasets['patient_services'], 'slug');
            $counts['patient_stories'] = $this->upsertSimple(PatientStory::class, $datasets['patient_stories'], 'slug');
            $counts['legal_documents'] = $this->upsertSimple(LegalDocument::class, $datasets['legal_documents'], 'slug');

            if ($prune) {
                // Prune only managed content records. Runtime settings, secrets and operational data stay untouched.
                $this->prune(Doctor::class, $datasets['doctors']);
                $this->prune(Procedure::class, $datasets['procedures']);
                $this->prune(Condition::class, $datasets['conditions']);
                $this->prune(Specialty::class, $datasets['specialties']);
                $this->prune(Clinic::class, $datasets['clinics']);
                $this->prune(Guide::class, $datasets['guides']);
                $this->prune(PatientService::class, $datasets['patient_services']);
                $this->prune(PatientStory::class, $datasets['patient_stories']);
                $this->prune(LegalDocument::class, $datasets['legal_documents']);
                $this->prune(SitePage::class, $datasets['site_pages']);
            }
        });

        return ['counts' => $counts, 'warnings' => $result['warnings']];
    }

    /** @param class-string<Model> $model */
    private function upsertSimple(string $model, array $rows, string $key, array $forced = []): int
    {
        foreach ($rows as $row) {
            $attrs = array_merge($this->without($row, [$key]), $forced);
            $model::query()->updateOrCreate([$key => $row[$key]], $attrs);
        }

        return count($rows);
    }

    /** @param class-string<Model> $model */
    private function prune(string $model, array $rows): void
    {
        $slugs = array_values(array_filter(array_map(fn (array $row): mixed => $row['slug'] ?? null, $rows)));
        $query = $model::query();
        $slugs === [] ? $query->delete() : $query->whereNotIn('slug', $slugs)->delete();
    }

    /** @param list<array<string,mixed>> $rows */
    private function validateUnique(array $rows, string $field, string $dataset, array &$errors): void
    {
        $seen = [];
        foreach ($rows as $index => $row) {
            $value = $row[$field] ?? null;
            if (! filled($value)) {
                continue;
            }
            $value = (string) $value;
            if (isset($seen[$value])) {
                $errors[] = "{$dataset}: duplicate {$field} '{$value}' at rows {$seen[$value]} and {$index}.";
            }
            $seen[$value] = $index;
        }
    }

    /** @param list<array<string,mixed>> $rows @param list<string> $fields */
    private function requireFields(array $rows, array $fields, string $dataset, array &$errors): void
    {
        foreach ($rows as $index => $row) {
            foreach ($fields as $field) {
                if (! filled($row[$field] ?? null)) {
                    $errors[] = "{$dataset}[{$index}] is missing required field '{$field}'.";
                }
            }
        }
    }

    /** @param list<array<string,mixed>> $rows @return array<string,true> */
    private function keySet(array $rows, string $field): array
    {
        $set = [];
        foreach ($rows as $row) {
            if (filled($row[$field] ?? null)) {
                $set[(string) $row[$field]] = true;
            }
        }
        return $set;
    }

    /** @return array<string,mixed> */
    private function readObject(string $path, array &$errors): array
    {
        $value = $this->readJson($path, $errors);
        if (! is_array($value) || array_is_list($value)) {
            $errors[] = basename($path).' must contain one JSON object.';
            return [];
        }
        return $value;
    }

    /** @return list<array<string,mixed>> */
    private function readList(string $path, array &$errors): array
    {
        $value = $this->readJson($path, $errors);
        if (! is_array($value) || ! array_is_list($value)) {
            $errors[] = basename($path).' must contain a JSON array.';
            return [];
        }
        return array_values(array_filter($value, 'is_array'));
    }

    private function readJson(string $path, array &$errors): mixed
    {
        if (! File::exists($path)) {
            $errors[] = 'Missing content file: '.$path;
            return null;
        }
        try {
            return json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            $errors[] = basename($path).' is invalid JSON: '.$e->getMessage();
            return null;
        }
    }

    private function resolvePath(string $path): string
    {
        if ($path === '') {
            $path = 'content/en';
        }
        if (Str::startsWith($path, ['/', '\\']) || preg_match('/^[A-Za-z]:[\\\\\/]/', $path) === 1) {
            return rtrim($path, "\\/");
        }
        return base_path(trim($path, "\\/"));
    }

    private function mediaExists(string $path): bool
    {
        $path = ltrim(parse_url($path, PHP_URL_PATH) ?: $path, '/');
        if (File::exists(public_path($path))) {
            return true;
        }
        if (Str::startsWith($path, 'storage/')) {
            return File::exists(storage_path('app/public/'.Str::after($path, 'storage/')));
        }
        return false;
    }

    /** @param array<string,mixed> $row */
    private function scanForAccidentalContent(string $dataset, int $index, array $row, array &$warnings): void
    {
        $text = json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
        foreach (['Babar bhai', 'manpreet.juneja@a1genesis.com', 'Voluptatem consequatur', 'Sapiente vitae non'] as $needle) {
            if (Str::contains($text, $needle)) {
                $warnings[] = "{$dataset}[{$index}] contains likely accidental/test content: '{$needle}'.";
            }
        }
    }

    /** @param array<string,mixed> $row @param list<string> $keys @return array<string,mixed> */
    private function without(array $row, array $keys): array
    {
        foreach ($keys as $key) {
            unset($row[$key]);
        }
        return $row;
    }
}
