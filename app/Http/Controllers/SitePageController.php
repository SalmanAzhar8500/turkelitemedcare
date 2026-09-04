<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Clinic;
use App\Models\Condition;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\LegalDocument;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\Specialty;
use App\Models\PatientService;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SitePageController extends Controller
{
    public function home(): View
    {
        Specialty::seedDefaults();
        PatientService::seedDefaults();

        return view('pages.home', [
            'page' => site_page('home'),
            'specialties' => Specialty::published()->withCount('procedures')->get(),
            'clinics' => Clinic::published()->limit(4)->get(),
            'services' => PatientService::published()->limit(3)->get(),
            'stories' => PatientStory::published()->limit(3)->get(),
            'doctors' => Doctor::published()->with(['clinic', 'treatments'])->get(),
            'guides' => Guide::published()->limit(4)->get(),
            'priorityProcedures' => Procedure::published()->with('specialty')
                ->whereIn('slug', config('seo.priority_procedures', []))
                ->get()
                ->sortBy(fn (Procedure $procedure): int => array_search($procedure->slug, config('seo.priority_procedures', []), true) ?: 0)
                ->values(),
            'procedureCount' => Procedure::published()->count(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'page' => site_page('about'),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'page' => site_page('contact'),
        ]);
    }

    public function forClinics(): View
    {
        return view('pages.for-clinics', [
            'page' => site_page('for-clinics'),
        ]);
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works', [
            'page' => site_page('how-it-works'),
        ]);
    }

    public function patientServices(): View
    {
        PatientService::seedDefaults();

        return view('pages.patient-services', [
            'page' => site_page('patient-services'),
            'services' => PatientService::published()->get(),
        ]);
    }

    public function guides(): View
    {
        return view('pages.guides.index', [
            'guides' => Guide::published()->get(),
            'page' => site_page('guides.index'),
            'priorityProcedures' => Procedure::published()->with('specialty')
                ->whereIn('slug', config('seo.priority_procedures', []))
                ->get()
                ->sortBy(fn (Procedure $procedure): int => array_search($procedure->slug, config('seo.priority_procedures', []), true) ?: 0)
                ->values(),
        ]);
    }

    public function legal(): View
    {
        LegalDocument::seedDefaults();

        return view('pages.legal', [
            'page' => site_page('legal'),
            'documents' => LegalDocument::published()->get(),
        ]);
    }

    public function legalDocument(string $document): View
    {
        $document = LegalDocument::query()
            ->where('slug', Str::beforeLast($document, '.html'))
            ->firstOrFail();
        abort_unless($document->is_active, 404);

        return view('pages.legal-document', compact('document'));
    }

    public function treatmentPlan(Request $request): View
    {
        Specialty::seedDefaults();

        return view('pages.treatment-plan', [
            'page' => site_page('treatment-plan'),
            'specialties' => Specialty::published()->orderBy('sort_order')->orderBy('name')->get(),
            'selectedSpecialty' => $request->string('specialty')->toString(),
        ]);
    }

    public function treatmentsIndex(): View
    {
        Specialty::seedDefaults();

        return view('pages.treatments.index', [
            'page' => site_page('treatments.index'),
            'specialties' => Specialty::published()->withCount('procedures')->get(),
            'featuredConditions' => Condition::published()->with('specialty')->limit(6)->get(),
            'featuredProcedures' => Procedure::published()->with('specialty')
                ->whereIn('slug', config('seo.priority_procedures', []))
                ->get()
                ->sortBy(fn (Procedure $procedure): int => array_search($procedure->slug, config('seo.priority_procedures', []), true) ?: 0)
                ->values(),
        ]);
    }

    public function treatmentShow(string $specialty): View
    {
        Specialty::seedDefaults();
        $specialtyData = Specialty::published()->where('slug', $specialty)->firstOrFail();

        $allProcedures = Procedure::published()
            ->where('specialty_id', $specialtyData->id)
            ->get();

        $visibleProcedures = $allProcedures;

        return view('pages.treatments.show', [
            'page' => site_page('treatments.show', [
                'content' => [
                    'hero' => [
                        'headline' => $specialtyData['name'],
                        'lead' => $specialtyData['description'],
                    ],
                ],
            ]),
            'specialty' => $specialtyData,
            'procedures' => $visibleProcedures,
            'totalProcedureCount' => $allProcedures->count(),
        ]);
    }

    /**
     * @return array<int, array{title: string, url: string, kind: string}>
     */
    private function discoverLegacyPages(string $directory): array
    {
        $paths = [];
        $root = base_path($directory);

        foreach (['procedures'] as $kind) {
            $folder = $root.DIRECTORY_SEPARATOR.$kind;

            if (! is_dir($folder)) {
                continue;
            }

            foreach (File::files($folder) as $file) {
                if ($file->getExtension() !== 'html') {
                    continue;
                }

                $relative = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
                $title = $this->extractTitle($file->getPathname()) ?? $this->titleFromFilename($file->getFilenameWithoutExtension());

                $paths[] = [
                    'title' => $title,
                    'url' => '/'.$relative,
                    'kind' => 'Procedure',
                ];
            }
        }

        usort($paths, static fn (array $left, array $right): int => [$left['kind'], $left['title']] <=> [$right['kind'], $right['title']]);

        return $paths;
    }

    private function extractTitle(string $path): ?string
    {
        $html = @file_get_contents($path);

        if ($html === false) {
            return null;
        }

        if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches) !== 1) {
            return null;
        }

        return trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private function titleFromFilename(string $filename): string
    {
        $title = str_replace(['-', '_'], ' ', $filename);
        $title = preg_replace('/\s+/', ' ', $title) ?? $title;

        return ucwords($title);
    }

    private function legacyHtmlResponse(string $relativePath): BinaryFileResponse
    {
        $fullPath = base_path($relativePath);

        abort_unless(File::exists($fullPath) && File::isFile($fullPath), 404);

        return response()->file($fullPath, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }
}
