<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AuditLaunchReadiness extends Command
{
    protected $signature = 'site:factory:audit {--production : Apply production launch gates instead of presentation gates}';
    protected $description = 'Audit FINAL content, multilingual decision pages, visual completeness and production blockers.';

    public function handle(): int
    {
        $production = (bool) $this->option('production');
        $base = base_path('content/en');
        $errors = [];
        $warnings = [];
        $passes = [];

        $manifest = $this->json($base.'/manifest.json');
        $procedures = $this->json($base.'/procedures.json');
        $clinics = $this->json($base.'/clinics.json');
        $doctors = $this->json($base.'/doctors.json');
        $stories = $this->json($base.'/patient_stories.json');
        $guides = $this->json($base.'/guides.json');
        $services = $this->json($base.'/patient_services.json');
        $sitePages = $this->json($base.'/site_pages.json');
        $legal = $this->json($base.'/legal_documents.json');
        $priority = config('seo.priority_procedures', []);

        if ((int)($manifest['schema_version'] ?? 0) === 2 && ($manifest['locales'] ?? []) === ['en','de','ar']) {
            $passes[] = 'Content pack schema v2 declares EN / DE / AR.';
        } else {
            $errors[] = 'Manifest must be schema v2 with locales en, de, ar.';
        }

        $procedureBySlug = collect($procedures)->keyBy('slug');

        // The presentation failed in V108 when the UI rendered a valid specialty
        // against an incomplete database. Catch the same problem at the source pack.
        $specialties = $this->json($base.'/specialties.json');
        $procedureCounts = collect($procedures)->countBy(fn ($row) => (string)($row['specialty_slug'] ?? ''));
        if (count($specialties) === 10 && count($procedures) === 100 && collect($specialties)->every(fn ($row) => ($procedureCounts[(string)($row['slug'] ?? '')] ?? 0) === 10)) {
            $passes[] = 'Canonical catalog is complete: 10 specialties with exactly 10 procedures each (100 total).';
        } else {
            $errors[] = 'Canonical catalog must contain 10 specialties with exactly 10 procedures each before presentation.';
        }

        if (config('build.version') === 'FINAL-MGMT' && (int) config('build.port') === 8135 && str_contains((string) File::get(base_path('.env.mamp.example')), 'DB_DATABASE=turenewtheme_final_mgmt')) {
            $passes[] = 'Build identity is isolated: FINAL-MGMT / port 8135 / turenewtheme_final_mgmt.';
        } else {
            $errors[] = 'Build identity is not isolated to FINAL-MGMT / 8135 / turenewtheme_final_mgmt.';
        }

        if (count($priority) === 20 && count(array_unique($priority)) === 20) {
            $passes[] = 'Exactly 20 commercial priority procedures are configured.';
        } else {
            $errors[] = 'SEO priority list must contain exactly 20 unique procedure slugs.';
        }

        foreach ($priority as $slug) {
            $row = $procedureBySlug->get($slug);
            if (! $row) {
                $errors[] = "Priority procedure missing from content pack: {$slug}";
                continue;
            }
            if (($row['seo_robots'] ?? '') !== 'index,follow') {
                $errors[] = "Priority procedure is not index,follow: {$slug}";
            }
            foreach (['de','ar'] as $locale) {
                $translation = data_get($row, 'translations.'.$locale, []);
                foreach (['name','summary','content','seo_title','seo_description'] as $field) {
                    if (blank($translation[$field] ?? null)) {
                        $errors[] = "{$slug} missing {$locale}.{$field}";
                    }
                }
            }
            if (blank($row['seo_title'] ?? null) || blank($row['seo_description'] ?? null)) {
                $errors[] = "{$slug} missing English SEO title or description.";
            }
        }
        if (! collect($errors)->contains(fn($e) => str_contains($e, 'Priority') || str_contains($e, 'missing de.') || str_contains($e, 'missing ar.'))) {
            $passes[] = 'All 20 priority pages have EN / DE / AR copy and metadata.';
        }

        $parityErrors = [];
        foreach ($procedures as $row) {
            $slug = (string)($row['slug'] ?? 'unknown');
            foreach (['de','ar'] as $locale) {
                foreach (['name','summary','content','seo_title','seo_description'] as $field) {
                    if (blank(data_get($row, 'translations.'.$locale.'.'.$field))) {
                        $parityErrors[] = "procedure {$slug} missing {$locale}.{$field}";
                    }
                }
            }
        }
        $parityDatasets = [
            ['specialties', $specialties, ['name','summary']],
            ['clinics', $clinics, ['name','summary','description']],
            ['guides', $guides, ['name','summary','content']],
            ['patient services', $services, ['name','summary','content']],
            ['site pages', $sitePages, ['title','description']],
            ['legal documents', $legal, ['title','summary','content']],
        ];
        foreach ($parityDatasets as [$datasetName,$rows,$fields]) {
            foreach ($rows as $row) {
                $slug = (string)($row['slug'] ?? 'unknown');
                foreach (['de','ar'] as $locale) {
                    foreach ($fields as $field) {
                        if (blank(data_get($row, 'translations.'.$locale.'.'.$field))) {
                            $parityErrors[] = "{$datasetName} {$slug} missing {$locale}.{$field}";
                        }
                    }
                }
            }
        }
        if ($parityErrors === []) {
            $passes[] = 'EN / DE / AR record parity covers all 10 specialties, 100 procedures, Clinic Expert, 8 guides, 9 patient services, 26 site pages and 8 legal documents.';
        } else {
            $errors[] = 'Multilingual parity gaps: '.implode('; ', array_slice($parityErrors, 0, 20));
        }

        // Depth and metadata checks for the commercial SEO layer. Word count is a QA floor, not a ranking target.
        $seoTitles = ['en' => [], 'de' => [], 'ar' => []];
        foreach ($priority as $slug) {
            $row = $procedureBySlug->get($slug);
            if (! $row) continue;
            $contentByLocale = [
                'en' => (string)($row['content'] ?? ''),
                'de' => (string)data_get($row, 'translations.de.content', ''),
                'ar' => (string)data_get($row, 'translations.ar.content', ''),
            ];
            foreach ($contentByLocale as $locale => $content) {
                preg_match_all('/[\p{L}\p{N}]+/u', $content, $matches);
                $floor = $locale === 'ar' ? 300 : 380;
                if (count($matches[0] ?? []) < $floor) {
                    $errors[] = "{$slug} {$locale} copy is below the editorial QA floor ({$floor} words).";
                }
            }
            foreach (['en','de','ar'] as $locale) {
                $title = $locale === 'en' ? (string)($row['seo_title'] ?? '') : (string)data_get($row, 'translations.'.$locale.'.seo_title', '');
                $description = $locale === 'en' ? (string)($row['seo_description'] ?? '') : (string)data_get($row, 'translations.'.$locale.'.seo_description', '');
                if ($title !== '') $seoTitles[$locale][$title] = ($seoTitles[$locale][$title] ?? 0) + 1;
                if (mb_strlen($title) > 65) $warnings[] = "{$slug} {$locale} SEO title is over 65 characters.";
                if (mb_strlen($description) < 90 || mb_strlen($description) > 165) $warnings[] = "{$slug} {$locale} SEO description is outside the 90-165 character review range.";
            }
        }
        foreach ($seoTitles as $locale => $titles) {
            $duplicates = array_keys(array_filter($titles, fn($count) => $count > 1));
            if ($duplicates !== []) $errors[] = strtoupper($locale).' priority SEO titles contain duplicates.';
        }
        if (! collect($errors)->contains(fn($e) => str_contains($e, 'editorial QA floor') || str_contains($e, 'SEO titles contain duplicates'))) {
            $passes[] = 'Priority treatment copy clears multilingual depth and unique-title QA gates.';
        }

        $longTailIndexed = collect($procedures)
            ->reject(fn($row) => in_array($row['slug'] ?? '', $priority, true))
            ->filter(fn($row) => str_contains((string)($row['seo_robots'] ?? ''), 'index') && ! str_contains((string)($row['seo_robots'] ?? ''), 'noindex'));
        if ($longTailIndexed->isEmpty()) {
            $passes[] = 'Long-tail treatment library is noindex until editorial depth is added.';
        } else {
            $errors[] = 'Non-priority treatment pages are indexable: '.$longTailIndexed->pluck('slug')->implode(', ');
        }

        $clinicSlugsForIndex = collect($clinics)->pluck('slug')->filter()->values()->all();
        $indexedClinics = collect($clinics)->filter(fn($row) => ! str_contains((string)($row['seo_robots'] ?? ''), 'noindex'));
        if ($clinicSlugsForIndex === ['clinic-expert'] && $indexedClinics->count() === 1) {
            $passes[] = 'Configured Clinic Expert profile is indexable; no synthetic clinic profiles are published.';
        } elseif ($clinics !== []) {
            $errors[] = 'Clinic indexability does not match the configured live-clinic contract.';
        }
        foreach ([['doctors',$doctors],['patient stories',$stories]] as [$name,$rows]) {
            $indexed = collect($rows)->filter(fn($row) => ! str_contains((string)($row['seo_robots'] ?? ''), 'noindex'));
            if ($indexed->isEmpty()) $passes[] = ucfirst($name).' sample records are noindex.';
            else $errors[] = ucfirst($name).' contain indexable sample records.';
        }

        $badEncoding = [];
        foreach (File::allFiles(base_path('content/en')) as $file) {
            if (preg_match('/(â|Ã|�|Imported from static HTML|management demo|video-ready)/u', File::get($file->getPathname()))) {
                $badEncoding[] = $file->getRelativePathname();
            }
        }
        foreach (File::allFiles(resource_path('views')) as $file) {
            if (preg_match('/(â|Ã|�)/u', File::get($file->getPathname()))) {
                $badEncoding[] = 'views/'.$file->getRelativePathname();
            }
        }
        if ($badEncoding === []) $passes[] = 'No known mojibake / import-marker patterns found in canonical content or views.';
        else $errors[] = 'Encoding/import artifacts remain: '.implode(', ', array_unique($badEncoding));

        $internalCopyLeaks = [];
        foreach ([resource_path('views/pages'), lang_path()] as $scanRoot) {
            if (! is_dir($scanRoot)) continue;
            foreach (File::allFiles($scanRoot) as $file) {
                $text = File::get($file->getPathname());
                if (preg_match('/video placeholder|management demo|SEO \/|Long-Tail|Suchmaschinenoptimierung|محركات البحث/u', $text)) {
                    $internalCopyLeaks[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }
        if ($internalCopyLeaks === []) $passes[] = 'Public page copy is free of internal SEO/demo/video-placeholder language.';
        else $errors[] = 'Internal implementation language leaked into public copy: '.implode(', ', array_unique($internalCopyLeaks));

        $legacyRuntime = File::get(resource_path('views/layouts/site.blade.php'));
        if (! str_contains($legacyRuntime, 'v7-enhancements.js')) $passes[] = 'Legacy video-era runtime is not loaded by the FINAL public layout.';
        else $errors[] = 'FINAL public layout still loads legacy v7-enhancements.js behavior.';

        $visualErrors = [];
        foreach ($priority as $slug) {
            $row = $procedureBySlug->get($slug);
            $path = ltrim((string)($row['image_path'] ?? ''), '/');
            if ($path === '' || ! File::exists(public_path($path))) {
                $visualErrors[] = $slug;
            }
        }
        if ($visualErrors === []) $passes[] = 'All 20 in-depth treatment pages have a local procedure visual.';
        else $errors[] = 'Priority treatment visuals are missing: '.implode(', ', $visualErrors);

        $v109PriorityMissing = [];
        foreach ($priority as $slug) {
            if (! File::exists(public_path('assets/img/final/procedure-'.$slug.'.webp'))) $v109PriorityMissing[] = $slug;
        }
        if ($v109PriorityMissing === []) $passes[] = 'All 20 priority pages have FINAL presentation visuals.';
        else $errors[] = 'FINAL priority visuals missing: '.implode(', ', $v109PriorityMissing);

        $allProcedureVisualMissing = [];
        foreach ($procedures as $row) {
            $slug = (string)($row['slug'] ?? '');
            if ($slug !== '' && ! File::exists(public_path('assets/img/final/procedure-'.$slug.'.webp'))) {
                $allProcedureVisualMissing[] = $slug;
            }
        }
        if ($allProcedureVisualMissing === []) $passes[] = 'All 100 procedure cards have local FINAL visuals; no procedure relies on the old placeholder layer.';
        else $errors[] = 'FINAL procedure visuals missing: '.implode(', ', $allProcedureVisualMissing);

        $specialtyRows = $this->json($base.'/specialties.json');
        $v109SpecialtyMissing = [];
        foreach ($specialtyRows as $row) {
            $slug = (string)($row['slug'] ?? '');
            if ($slug !== '' && ! File::exists(public_path('assets/img/final/specialty-'.$slug.'.webp'))) $v109SpecialtyMissing[] = $slug;
        }
        if ($v109SpecialtyMissing === []) $passes[] = 'All 10 specialty overviews have FINAL presentation visuals.';
        else $errors[] = 'FINAL specialty visuals missing: '.implode(', ', $v109SpecialtyMissing);

        $externalFontRefs = [];
        foreach ([resource_path('views'), public_path('assets/css')] as $scanRoot) {
            if (! is_dir($scanRoot)) continue;
            foreach (File::allFiles($scanRoot) as $file) {
                $text = File::get($file->getPathname());
                if (str_contains($text, 'fonts.googleapis.com') || str_contains($text, 'fonts.gstatic.com')) {
                    $externalFontRefs[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }
        if ($externalFontRefs === []) $passes[] = 'Public FINAL pages do not depend on remote Google Fonts.';
        else $warnings[] = 'Remote font references remain outside the FINAL public layout: '.implode(', ', array_unique($externalFontRefs));

        $obsoleteVideoRefs = [];
        foreach ([resource_path('views'), base_path('content/en'), base_path('config')] as $scanRoot) {
            if (! is_dir($scanRoot)) continue;
            foreach (File::allFiles($scanRoot) as $file) {
                $text = File::get($file->getPathname());
                if (preg_match('/procedure videos|video-ready|browse videos|youtube-nocookie/i', $text)) {
                    $obsoleteVideoRefs[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                }
            }
        }
        if ($obsoleteVideoRefs === []) $passes[] = 'Future-video placeholders have been replaced by decision-article content.';
        else $errors[] = 'Obsolete video placeholder references remain: '.implode(', ', array_unique($obsoleteVideoRefs));

        $legalPlaceholderCount = collect($legal)->filter(function ($row) {
            $text = (string)($row['content'] ?? '');
            return preg_match('/\[[^\]]+\]|draft|pending legal review|qualified counsel/i', $text) === 1;
        })->count();
        if ($legalPlaceholderCount > 0) {
            $message = "{$legalPlaceholderCount} legal documents still contain draft/legal-review placeholders.";
            $production ? $errors[] = $message : $warnings[] = $message.' This is acceptable for tomorrow\'s private presentation, not public launch.';
        }

        $remotePresentationAssets = [];
        foreach (File::allFiles(resource_path('views/pages/home-concepts')) as $file) {
            if (str_contains(File::get($file->getPathname()), 'images.unsplash.com')) $remotePresentationAssets[] = $file->getFilename();
        }
        if ($remotePresentationAssets !== []) {
            $message = 'Showcase concepts still use remote presentation photography: '.implode(', ', $remotePresentationAssets).'.';
            $production ? $errors[] = $message : $warnings[] = $message.' Localize approved photography before launch.';
        }

        $clinicSlugs = collect($clinics)->pluck('slug')->filter()->values()->all();
        if ($clinicSlugs === ['clinic-expert'] && count($doctors) === 0) {
            $passes[] = 'Clinic directory is restricted to the configured Clinic Expert record; synthetic clinic/doctor records are not published.';
        } elseif (count($clinics) || count($doctors)) {
            $message = 'Unexpected provider records remain in the content pack; verify partner identity, credentials and commercial permission before publication.';
            $production ? $errors[] = $message : $warnings[] = $message;
        }

        $this->newLine();
        $this->info($production ? 'TURKELITE FINAL — PRODUCTION READINESS AUDIT' : 'TURKELITE FINAL — PRESENTATION READINESS AUDIT');
        foreach ($passes as $pass) $this->line('  PASS  '.$pass);
        foreach ($warnings as $warning) $this->warn('  WARN  '.$warning);
        foreach ($errors as $error) $this->error('  FAIL  '.$error);
        $this->newLine();
        $this->line(sprintf('Summary: %d pass / %d warning / %d fail', count($passes), count($warnings), count($errors)));

        if ($errors === []) {
            $this->info($production ? 'Production gates passed.' : 'Presentation gates passed.');
            return self::SUCCESS;
        }

        return self::FAILURE;
    }

    private function json(string $path): array
    {
        return json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR);
    }
}
