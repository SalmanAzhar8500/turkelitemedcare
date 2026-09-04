<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\LegalDocumentController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LegacyPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitePageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TreatmentContentController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/__build', function () {
    abort_unless(app()->environment(['local', 'testing', 'staging']), 404);
    return response()->json([
        'build' => config('build.version'),
        'label' => config('build.label'),
        'port' => config('build.port'),
        'database' => config('database.connections.'.config('database.default').'.database'),
        'specialties' => \App\Models\Specialty::query()->count(),
        'procedures' => \App\Models\Procedure::query()->count(),
        'priority_procedures' => \App\Models\Procedure::query()->whereIn('slug', config('seo.priority_procedures', []))->count(),
        'clinics' => \App\Models\Clinic::query()->count(),
        'doctors' => \App\Models\Doctor::query()->count(),
        'guides' => \App\Models\Guide::query()->count(),
        'patient_services' => \App\Models\PatientService::query()->count(),
        'patient_stories' => \App\Models\PatientStory::query()->count(),
        'environment' => app()->environment(),
        'content_source' => 'content/en',
    ]);
})->name('build.info');

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, config('locales.supported', ['en']), true), 404);
    request()->session()->put('public_locale', $locale);

    return redirect($locale === 'en' ? url('/') : url('/'.$locale));
})->name('language.switch');

// FINAL explicit English matcher routes.
// Laravel does not backtrack an optional constrained prefix like {locale?} when the
// first real path segment is "treatments", "about", etc. The named optional
// routes below remain the canonical URL-generation routes for EN/DE/AR, while these
// unprefixed routes make every English public URL actually match.
Route::middleware('public.locale')->group(function (): void {
    Route::get('/about', [SitePageController::class, 'about']);
    Route::get('/contact', [SitePageController::class, 'contact']);
    Route::get('/for-clinics', [SitePageController::class, 'forClinics']);
    Route::get('/how-it-works', [SitePageController::class, 'howItWorks']);
    Route::get('/patient-services', [SitePageController::class, 'patientServices']);
    Route::get('/guides', [SitePageController::class, 'guides']);
    Route::get('/guides/{guide}', [TreatmentContentController::class, 'guide']);
    Route::get('/legal', [SitePageController::class, 'legal']);
    Route::get('/legal/{document}', [SitePageController::class, 'legalDocument']);
    Route::get('/treatment-plan', [SitePageController::class, 'treatmentPlan']);
    Route::post('/treatment-plan', [InquiryController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/treatments', [SitePageController::class, 'treatmentsIndex']);
    Route::get('/treatments/{specialty}/procedures/{procedure}', [TreatmentContentController::class, 'procedure']);
    Route::get('/treatments/{specialty}', [SitePageController::class, 'treatmentShow'])->where('specialty', '[A-Za-z0-9\-]+');
    Route::get('/clinics', [TreatmentContentController::class, 'clinics']);
    Route::get('/clinics/{clinic}', [TreatmentContentController::class, 'clinic']);
    Route::get('/doctors', [TreatmentContentController::class, 'doctors']);
    Route::get('/doctors/{doctor}', [TreatmentContentController::class, 'doctor']);
    Route::get('/patient-stories', [TreatmentContentController::class, 'stories']);
    Route::get('/patient-stories/{story}', [TreatmentContentController::class, 'story']);
    Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:6,1');
});

Route::prefix('{locale?}')
    ->where(['locale' => 'en|de|ar'])
    ->middleware('public.locale')
    ->group(function (): void {
        Route::get('/', [SitePageController::class, 'home'])->name('home');
        Route::get('/showcase', function () {
            abort_unless(app()->environment(['local', 'testing']), 404);
            return redirect()->route('home', app()->getLocale() === 'en' ? [] : ['locale' => app()->getLocale()]);
        })->name('showcase');
        Route::get('/about', [SitePageController::class, 'about'])->name('about');
        Route::get('/contact', [SitePageController::class, 'contact'])->name('contact');
        Route::get('/for-clinics', [SitePageController::class, 'forClinics'])->name('for-clinics');
        Route::get('/how-it-works', [SitePageController::class, 'howItWorks'])->name('how-it-works');
        Route::get('/patient-services', [SitePageController::class, 'patientServices'])->name('patient-services');
        Route::get('/guides', [SitePageController::class, 'guides'])->name('guides.index');
        Route::get('/guides/{guide}', [TreatmentContentController::class, 'guide'])->name('guides.show');
        Route::get('/legal', [SitePageController::class, 'legal'])->name('legal');
        Route::get('/legal/{document}', [SitePageController::class, 'legalDocument'])->name('legal.documents.show');
        Route::get('/treatment-plan', [SitePageController::class, 'treatmentPlan'])->name('treatment-plan');
        Route::post('/treatment-plan', [InquiryController::class, 'store'])->middleware('throttle:6,1')->name('treatment-plan.store');
        Route::get('/treatments', [SitePageController::class, 'treatmentsIndex'])->name('treatments.index');
        Route::get('/treatments/{specialty}/procedures/{procedure}', [TreatmentContentController::class, 'procedure'])->name('procedures.show');
        Route::get('/treatments/{specialty}', [SitePageController::class, 'treatmentShow'])->where('specialty', '[A-Za-z0-9\-]+')->name('treatments.show');
        Route::get('/clinics', [TreatmentContentController::class, 'clinics'])->name('clinics.index');
        Route::get('/clinics/{clinic}', [TreatmentContentController::class, 'clinic'])->name('clinics.show');
        Route::get('/doctors', [TreatmentContentController::class, 'doctors'])->name('doctors.index');
        Route::get('/doctors/{doctor}', [TreatmentContentController::class, 'doctor'])->name('doctors.show');
        Route::get('/patient-stories', [TreatmentContentController::class, 'stories'])->name('stories.index');
        Route::get('/patient-stories/{story}', [TreatmentContentController::class, 'story'])->name('stories.show');
        Route::post('/contact', [InquiryController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');
    });

Route::middleware(['auth', 'auth.no-cache'])->group(function (): void {
    Route::redirect('/dashboard', '/admin/dashboard');

    Route::prefix('admin')->as('admin.')->group(function (): void {
        Route::get('/dashboard', [ContentController::class, 'index'])->name('dashboard');

        Route::get('/pages', [ContentController::class, 'pagesIndex'])->name('pages.index');
        Route::get('/pages/data', [ContentController::class, 'pagesData'])->name('pages.data');
        Route::get('/pages/{page}/edit', [ContentController::class, 'pagesEdit'])->name('pages.edit');
        Route::put('/pages/{page}', [ContentController::class, 'pagesUpdate'])->name('pages.update');
        Route::delete('/pages/{page}', [ContentController::class, 'pagesDestroy'])->name('pages.destroy');

        Route::get('/legal-documents', [LegalDocumentController::class, 'index'])->name('legal.index');
        Route::get('/legal-documents/create', [LegalDocumentController::class, 'create'])->name('legal.create');
        Route::post('/legal-documents', [LegalDocumentController::class, 'store'])->name('legal.store');
        Route::get('/legal-documents/{document}/edit', [LegalDocumentController::class, 'edit'])->name('legal.edit');
        Route::put('/legal-documents/{document}', [LegalDocumentController::class, 'update'])->name('legal.update');
        Route::delete('/legal-documents/{document}', [LegalDocumentController::class, 'destroy'])->name('legal.destroy');

        Route::get('/inquiries', [ContentController::class, 'inquiriesIndex'])->name('inquiries.index');
        Route::get('/inquiries/data', [ContentController::class, 'inquiriesData'])->name('inquiries.data');
        Route::get('/inquiries/{inquiry}', [ContentController::class, 'inquiriesShow'])->name('inquiries.show');
        Route::patch('/inquiries/{inquiry}', [ContentController::class, 'inquiriesUpdate'])->name('inquiries.update');
        Route::delete('/inquiries/{inquiry}', [ContentController::class, 'inquiriesDestroy'])->name('inquiries.destroy');

        Route::get('/treatment-requests', [ContentController::class, 'treatmentRequestsIndex'])->name('treatment-requests.index');
        Route::get('/treatment-requests/data', [ContentController::class, 'treatmentRequestsData'])->name('treatment-requests.data');
        Route::get('/treatment-requests/{inquiry}', [ContentController::class, 'treatmentRequestsShow'])->name('treatment-requests.show');
        Route::patch('/treatment-requests/{inquiry}', [ContentController::class, 'treatmentRequestsUpdate'])->name('treatment-requests.update');
        Route::delete('/treatment-requests/{inquiry}', [ContentController::class, 'treatmentRequestsDestroy'])->name('treatment-requests.destroy');

        Route::get('/catalog/{type}', [CatalogController::class, 'index'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.index');
        Route::get('/catalog/{type}/data', [CatalogController::class, 'data'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.data');
        Route::get('/catalog/{type}/create', [CatalogController::class, 'create'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.create');
        Route::post('/catalog/{type}', [CatalogController::class, 'store'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.store');
        Route::get('/catalog/{type}/{record}/edit', [CatalogController::class, 'edit'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.edit');
        Route::put('/catalog/{type}/{record}', [CatalogController::class, 'update'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.update');
        Route::delete('/catalog/{type}/{record}', [CatalogController::class, 'destroy'])->whereIn('type', ['treatments', 'clinics', 'doctors', 'guides', 'procedures', 'patient-services', 'patient-stories'])->name('catalog.destroy');

        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/labels', [SettingsController::class, 'labelsEdit'])->name('labels.edit');
        Route::put('/labels', [SettingsController::class, 'labelsUpdate'])->name('labels.update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback([LegacyPageController::class, 'show']);

require __DIR__.'/auth.php';

