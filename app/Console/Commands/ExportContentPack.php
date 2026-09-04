<?php

namespace App\Console\Commands;

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
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ExportContentPack extends Command
{
    protected $signature = 'site:factory:export {--content=content/en : Destination content pack folder}';

    protected $description = 'Export current multilingual CMS content back to the canonical JSON content pack.';

    public function handle(): int
    {
        app()->setLocale('en');
        $path = $this->resolvePath((string) $this->option('content'));
        File::ensureDirectoryExists($path);

        $this->write($path.'/manifest.json', [
            'schema_version' => 2,
            'site' => 'Turkelite Medcare',
            'locale' => 'en',
            'locales' => ['en', 'de', 'ar'],
            'source' => 'FINAL multilingual CMS export',
            'datasets' => ['settings', 'site_pages', 'specialties', 'conditions', 'procedures', 'clinics', 'doctors', 'guides', 'patient_services', 'patient_stories', 'legal_documents'],
        ]);

        $settings = SiteSetting::query()->orderBy('key')->get()
            ->filter(function (SiteSetting $setting): bool {
                $key = strtolower($setting->key);
                return ! Str::contains($key, ['password', 'secret', 'private_key'])
                    ;
            })
            ->map(fn (SiteSetting $setting): array => ['key' => $setting->key, 'value' => $setting->value])
            ->values()->all();
        $this->write($path.'/settings.json', $settings);

        $this->write($path.'/site_pages.json', SitePage::query()->orderBy('id')->get()->map(fn (SitePage $m): array => $this->row($m, [
            'slug', 'title', 'description', 'template', 'content', 'translations', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots',
        ], ['content', 'translations']))->all());

        $this->write($path.'/specialties.json', Specialty::query()->orderBy('sort_order')->orderBy('id')->get()->map(fn (Specialty $m): array => $this->row($m, [
            'name', 'slug', 'summary', 'description', 'image_path', 'breadcrumb_image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots',
        ]))->all());

        $this->write($path.'/conditions.json', Condition::query()->with('specialty')->orderBy('sort_order')->orderBy('id')->get()->map(function (Condition $m): array {
            $row = $this->row($m, ['name', 'slug', 'summary', 'content', 'translations', 'sort_order', 'is_active'], ['translations']);
            $row['specialty_slug'] = $m->specialty?->slug;
            return $row;
        })->all());

        $this->write($path.'/procedures.json', Procedure::query()->with(['specialty', 'condition'])->orderBy('specialty_id')->orderBy('sort_order')->orderBy('id')->get()->map(function (Procedure $m): array {
            $row = $this->row($m, ['name', 'slug', 'summary', 'content', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots']);
            $row['specialty_slug'] = $m->specialty?->slug;
            $row['condition_slug'] = $m->condition?->slug;
            return $row;
        })->all());

        $this->write($path.'/clinics.json', Clinic::query()->orderBy('sort_order')->orderBy('id')->get()->map(fn (Clinic $m): array => $this->row($m, [
            'name', 'slug', 'location', 'website', 'email', 'phone', 'summary', 'description', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots',
        ]))->all());

        $this->write($path.'/doctors.json', Doctor::query()->with(['clinic', 'treatments', 'procedures'])->orderBy('sort_order')->orderBy('id')->get()->map(function (Doctor $m): array {
            $row = $this->row($m, [
                'name', 'slug', 'designation', 'specialty', 'summary', 'content', 'profile_highlight', 'experience_years', 'languages', 'consultation_method', 'response_time', 'verification_notes', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots',
            ]);
            $row['clinic_slug'] = $m->clinic?->slug;
            $row['specialty_slugs'] = $m->treatments->pluck('slug')->values()->all();
            $row['procedure_slugs'] = $m->procedures->pluck('slug')->values()->all();
            return $row;
        })->all());

        $this->writeSimple($path, 'guides', Guide::class, ['name', 'slug', 'summary', 'content', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots']);
        $this->writeSimple($path, 'patient_services', PatientService::class, ['name', 'slug', 'summary', 'content', 'bullet_points', 'service_points', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'], ['bullet_points', 'service_points', 'translations']);
        $this->writeSimple($path, 'patient_stories', PatientStory::class, ['name', 'slug', 'summary', 'content', 'bullet_points', 'image_path', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots'], ['bullet_points', 'translations']);
        $this->writeSimple($path, 'legal_documents', LegalDocument::class, ['title', 'slug', 'summary', 'content', 'translations', 'sort_order', 'is_active', 'seo_title', 'seo_description', 'seo_keywords', 'seo_robots']);

        $this->info("Multilingual CMS content exported to {$path}");
        $this->line('Run site:factory:validate --strict before committing or deploying the exported pack.');
        return self::SUCCESS;
    }

    /** @param class-string<Model> $model */
    private function writeSimple(string $path, string $dataset, string $model, array $fields, array $jsonFields = []): void
    {
        $rows = $model::query()->orderBy('sort_order')->orderBy('id')->get()->map(fn (Model $m): array => $this->row($m, $fields, $jsonFields))->all();
        $this->write($path.'/'.$dataset.'.json', $rows);
    }

    /** @param list<string> $fields @param list<string> $jsonFields @return array<string,mixed> */
    private function row(Model $model, array $fields, array $jsonFields = []): array
    {
        $row = [];
        foreach ($fields as $field) {
            $value = $model->getRawOriginal($field);
            if (($field === 'translations' || in_array($field, $jsonFields, true)) && is_string($value)) {
                $decoded = json_decode($value, true);
                $value = json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
            }
            if ($field === 'is_active') {
                $value = (bool) $value;
            }
            $row[$field] = $value;
        }
        return $row;
    }

    private function write(string $path, mixed $value): void
    {
        File::put($path, json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL);
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
}
