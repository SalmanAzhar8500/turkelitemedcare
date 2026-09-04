<?php

use App\Models\SitePage;
use App\Models\SiteSetting;
use App\Models\Specialty;
use Illuminate\Support\Arr;

if (! function_exists('website_settings')) {
    /** @return array<string, mixed> */
    function website_settings(): array
    {
        static $settings;

        if ($settings !== null) {
            return $settings;
        }

        $settings = config('site');
        $overrides = SiteSetting::values();
        $map = [
            'site_name' => 'brand.name', 'legal_name' => 'brand.legal_name',
            'contact_email' => 'brand.email', 'contact_phone' => 'brand.phone',
            'whatsapp_number' => 'brand.whatsapp_number',
            'support_hours' => 'support_hours.weekday',
            'logo_path' => 'brand.logo_path',
            'favicon_path' => 'brand.favicon_path',
        ];

        foreach ($map as $key => $path) {
            if (filled($overrides[$key] ?? null)) {
                Arr::set($settings, $path, $overrides[$key]);
            }
        }

        return $settings;
    }
}

if (! function_exists('website_logo_url')) {
    function website_logo_url(): string
    {
        return public_media_url((string) website_setting('brand.logo_path', 'assets/img/turkelitemedcare-logo.svg'));
    }
}

if (! function_exists('website_favicon_url')) {
    function website_favicon_url(): string
    {
        return public_media_url((string) website_setting('brand.favicon_path', 'assets/img/favicon.svg'));
    }
}

if (! function_exists('website_favicon_type')) {
    function website_favicon_type(): string
    {
        return match (strtolower(pathinfo((string) website_setting('brand.favicon_path', 'assets/img/favicon.svg'), PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            default => 'image/svg+xml',
        };
    }
}

if (! function_exists('public_media_url')) {
    /** Convert stored media paths and legacy absolute storage URLs to a URL on the current app host. */
    function public_media_url(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, '//') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $parsedPath = parse_url($path, PHP_URL_PATH);
        $path = is_string($parsedPath) && $parsedPath !== '' ? $parsedPath : $path;

        return asset(ltrim($path, '/'));
    }
}

if (! function_exists('website_setting')) {
    function website_setting(string $key, mixed $default = null): mixed
    {
        return data_get(website_settings(), $key, $default);
    }
}

if (! function_exists('localized_route_url')) {
    /**
     * Build a language-equivalent URL deterministically from the current path.
     * English intentionally has no /en prefix; German and Arabic do.
     * This path-first approach avoids Laravel optional-prefix edge cases when
     * switching from /de/... or /ar/... back to the canonical English URL.
     */
    function localized_route_url(string $locale): string
    {
        $supported = config('locales.supported', ['en']);
        $locale = in_array($locale, $supported, true) ? $locale : 'en';

        $path = trim(request()->path(), '/');
        $segments = $path === '' ? [] : explode('/', $path);
        if ($segments !== [] && in_array($segments[0], $supported, true)) {
            array_shift($segments);
        }
        if ($locale !== 'en') {
            array_unshift($segments, $locale);
        }

        $localizedPath = implode('/', $segments);
        $url = url($localizedPath === '' ? '/' : '/'.$localizedPath);
        $query = request()->query();
        unset($query['locale']);
        return $query ? $url.'?'.http_build_query($query) : $url;
    }
}

if (! function_exists('current_design_concept')) {
    function current_design_concept(): string
    {
        $allowed = config('design.allowed', ['clinical']);
        $requested = strtolower((string) request()->query('concept', ''));
        if (in_array($requested, $allowed, true)) {
            request()->session()->put('design_concept', $requested);
        }
        $default = (string) config('design.default', 'clinical');
        $concept = (string) request()->session()->get('design_concept', $default);
        return in_array($concept, $allowed, true) ? $concept : $default;
    }
}

if (! function_exists('is_priority_procedure')) {
    function is_priority_procedure(string $slug): bool
    {
        return in_array($slug, config('seo.priority_procedures', []), true);
    }
}

if (! function_exists('site_ui')) {
    function site_ui(string $key): string
    {
        $map = [
            'home' => 'home', 'treatments' => 'treatments', 'clinics' => 'clinics',
            'how_it_works' => 'how', 'patient_services' => 'services', 'patient_stories' => 'stories',
            'about' => 'about', 'for_clinics' => 'about', 'contact' => 'contact', 'plan_journey' => 'plan',
            'browse_specialties' => 'explore', 'view_all_specialties' => 'all_treatments',
            'view_all_doctors' => 'meet_doctors', 'explore_clinics' => 'compare_clinics',
            'see_how_it_works' => 'how_it_works', 'read_guide' => 'guides', 'send_message' => 'contact',
            'request_callback' => 'contact', 'start_enquiry' => 'start_plan', 'show_all' => 'all_treatments',
            'aesthetic' => 'treatments', 'surgical' => 'treatments', 'vision' => 'treatments', 'specialist' => 'doctors',
        ];
        $translationKey = $map[$key] ?? $key;
        $translated = __('site.'.$translationKey);
        return $translated === 'site.'.$translationKey ? $key : $translated;
    }
}

if (! function_exists('site_footer')) {
    function site_footer(string $key): string
    {
        $brandName = (string) website_setting('brand.name', 'Turkelite Medcare');
        $legalName = (string) website_setting('brand.legal_name', $brandName);
        $locale = app()->getLocale();
        $copy = [
            'description' => [
                'en' => 'Medical-travel coordination built around clear provider roles, treatment planning and a connected journey from first enquiry to return-home handover.',
                'de' => 'Koordination medizinischer Reisen mit klaren Zuständigkeiten, strukturierter Behandlungsplanung und einem verbundenen Ablauf von der ersten Anfrage bis zur Nachsorgeübergabe.',
                'ar' => 'تنسيق رحلة العلاج الطبي مع وضوح أدوار مقدمي الرعاية وخطوات العلاج وربط الرحلة من أول استفسار حتى تسليم خطة المتابعة بعد العودة.',
            ],
            'trust_note' => [
                'en' => 'International patient coordination · Germany, Europe & Middle East',
                'de' => 'Internationale Patientenkoordination · Deutschland, Europa & Naher Osten',
                'ar' => 'تنسيق المرضى الدوليين · ألمانيا وأوروبا والشرق الأوسط',
            ],
            'treatments_heading' => ['en' => 'Treatments', 'de' => 'Behandlungen', 'ar' => 'العلاجات'],
            'support_heading' => ['en' => 'Patient support', 'de' => 'Patientenbetreuung', 'ar' => 'دعم المرضى'],
            'company_heading' => ['en' => 'Company', 'de' => 'Unternehmen', 'ar' => 'الشركة'],
            'about' => ['en' => 'About', 'de' => 'Über uns', 'ar' => 'من نحن'],
            'guides' => ['en' => 'Guides', 'de' => 'Ratgeber', 'ar' => 'الأدلة'],
            'contact' => ['en' => 'Contact', 'de' => 'Kontakt', 'ar' => 'تواصل معنا'],
            'about_us' => ['en' => 'About us', 'de' => 'Über uns', 'ar' => 'من نحن'],
            'for_clinics' => ['en' => 'For clinics', 'de' => 'Für Kliniken', 'ar' => 'للعيادات'],
            'legal_privacy' => ['en' => 'Legal & privacy', 'de' => 'Rechtliches & Datenschutz', 'ar' => 'القانون والخصوصية'],
            'copyright' => ['en' => '© 2026 '.$legalName.'.', 'de' => '© 2026 '.$legalName.'.', 'ar' => '© 2026 '.$legalName.'.'],
            'medical_notice' => [
                'en' => 'Coordination service · Not emergency care',
                'de' => 'Koordinationsservice · Keine Notfallversorgung',
                'ar' => 'خدمة تنسيق · ليست رعاية طارئة',
            ],
            'disclosure' => [
                'en' => 'Independent licensed providers are responsible for medical assessment, consent and treatment.',
                'de' => 'Unabhängige zugelassene Anbieter sind für medizinische Beurteilung, Einwilligung und Behandlung verantwortlich.',
                'ar' => 'مقدمو الرعاية المستقلون والمرخصون مسؤولون عن التقييم الطبي والموافقة والعلاج.',
            ],
        ];
        return $copy[$key][$locale] ?? $copy[$key]['en'] ?? $key;
    }
}
if (! function_exists('localized_label')) {
    function localized_label(string $label): string
    {
        $keys = [
            'Home' => 'home', 'Treatments' => 'treatments', 'Clinics' => 'clinics',
            'How It Works' => 'how_it_works', 'Patient Services' => 'patient_services',
            'Patient Stories' => 'patient_stories', 'About' => 'about', 'For Clinics' => 'for_clinics',
            'Contact' => 'contact',
        ];

        return isset($keys[$label]) ? site_ui($keys[$label]) : $label;
    }
}

if (! function_exists('site_page')) {
    function site_page(string $slug, array $defaults = []): SitePage
    {
        $configuredDefaults = config('site-pages', []);
        $pageDefaults = $defaults ?: (is_array($configuredDefaults[$slug] ?? null) ? $configuredDefaults[$slug] : []);

        return SitePage::resolve($slug, $pageDefaults);
    }
}

if (! function_exists('site_specialties')) {
    function site_specialties(): array
    {
        try {
            Specialty::seedDefaults();

            return Specialty::published()->get()->map(fn (Specialty $specialty): array => $specialty->only([
                'id', 'slug', 'name', 'summary', 'description', 'image_path',
            ]))->all();
        } catch (\Throwable) {
            return config('site.specialties', []);
        }
    }
}



if (! function_exists('procedure_visual_url')) {
    function procedure_visual_url($procedure): string
    {
        $slug = (string) data_get($procedure, 'slug', '');
        $specialtySlug = (string) data_get($procedure, 'specialty.slug', data_get($procedure, 'specialty_slug', ''));


        $final = 'assets/img/final/procedure-'.$slug.'.webp';
        if ($slug !== '' && file_exists(public_path($final))) {
            return asset($final);
        }

        // Dental catalogue pages deliberately prefer clean object-led illustrations.
        // SVG fallback remains crisp at any card size and avoids empty/duplicated art.
        if ($specialtySlug === 'dental') {
            $dentalIllustration = 'assets/img/v5/procedure-dental-'.$slug.'.svg';
            if ($slug !== '' && file_exists(public_path($dentalIllustration))) {
                return asset($dentalIllustration);
            }
        }

        $v109 = 'assets/img/v109/procedure-'.$slug.'.webp';
        if ($slug !== '' && file_exists(public_path($v109))) {
            return asset($v109);
        }

        $stored = (string) data_get($procedure, 'image_path', '');
        if ($stored !== '') {
            return public_media_url($stored);
        }

        $photo = 'assets/img/v108/'.$slug.'.webp';
        if ($slug !== '' && file_exists(public_path($photo))) {
            return asset($photo);
        }

        $specialtyPhotoPaths = [
            'assets/img/final/specialty-'.$specialtySlug.'.webp',
            'assets/img/v109/specialty-'.$specialtySlug.'.webp',
            'assets/img/v108/specialty-'.$specialtySlug.'.webp',
        ];
        foreach ($specialtyPhotoPaths as $path) {
            if ($specialtySlug !== '' && file_exists(public_path($path))) {
                return asset($path);
            }
        }

        $illustration = 'assets/img/v5/procedure-'.$specialtySlug.'-'.$slug.'.svg';
        if ($slug !== '' && $specialtySlug !== '' && file_exists(public_path($illustration))) {
            return asset($illustration);
        }

        return asset('assets/img/v108/istanbul.webp');
    }
}

if (! function_exists('specialty_visual_url')) {
    function specialty_visual_url($specialty): string
    {
        $slug = (string) data_get($specialty, 'slug', '');
        $final = 'assets/img/final/specialty-'.$slug.'.webp';
        if ($slug !== '' && file_exists(public_path($final))) {
            return asset($final);
        }

        $v109 = 'assets/img/v109/specialty-'.$slug.'.webp';
        if ($slug !== '' && file_exists(public_path($v109))) {
            return asset($v109);
        }

        $stored = (string) data_get($specialty, 'image_path', '');
        if ($stored !== '') {
            return public_media_url($stored);
        }
        return asset('assets/img/v109/hero-patient.webp');
    }
}

if (! function_exists('clinic_visual_url')) {
    function clinic_visual_url($clinic, int $position = 0): string
    {
        $stored = (string) data_get($clinic, 'image_path', '');
        if ($stored !== '') return public_media_url($stored);

        // FINAL presentation profiles are illustrative until real provider media is approved.
        // Use crisp, treatment-relevant care context instead of enlarging the soft sample clinic photos.
        $slug = (string) data_get($clinic, 'slug', '');
        $pools = [
            'clinic-expert' => ['clinic-clinic-expert.webp','procedure-dental-veneers.webp','procedure-dental-implants.webp','procedure-dental-crowns.webp'],
        ];
        $fallback = ['coordination.webp','specialty-dental.webp','specialty-eye-care.webp','home-hero.webp'];
        $pool = $pools[$slug] ?? $fallback;
        $file = $pool[abs($position) % count($pool)];
        return asset('assets/img/final/'.$file);
    }
}

if (! function_exists('story_visual_url')) {
    function story_visual_url($story): string
    {
        $stored = (string) data_get($story, 'image_path', '');
        if ($stored !== '') return public_media_url($stored);
        $slug = (string) data_get($story, 'slug', '');
        $map = [
            'anna-lasik-izmir' => 'procedure-lasik.webp',
            'james-knee-replacement-antalya' => 'procedure-total-knee-replacement.webp',
            'luca-rhinoplasty-istanbul' => 'procedure-rhinoplasty.webp',
            'martin-dental-implants-istanbul' => 'procedure-dental-implants.webp',
            'nadia-gastric-sleeve-antalya' => 'procedure-sleeve-gastrectomy.webp',
            'sophie-hair-restoration-istanbul' => 'procedure-fue-hair-transplant.webp',
        ];
        return asset('assets/img/v109/'.($map[$slug] ?? 'hero-patient.webp'));
    }
}

if (! function_exists('guide_visual_url')) {
    function guide_visual_url($guide): string
    {
        $stored = (string) data_get($guide, 'image_path', '');
        if ($stored !== '') {
            return public_media_url($stored);
        }

        $map = [
            'aftercare' => 'article-recovery.webp',
            'clinic-credentials' => 'article-clinic.webp',
            'what-if-something-goes-wrong' => 'article-recovery.webp',
            'informed-consent' => 'article-clinic.webp',
            'medical-records' => 'article-clinic.webp',
            'questions-before-booking' => 'article-turkey.webp',
            'travel-after-treatment' => 'article-turkey.webp',
            'treatment-decision-checklist' => 'article-dental.webp',
        ];
        $slug = (string) data_get($guide, 'slug', '');
        $file = $map[$slug] ?? 'article-turkey.webp';
        return asset('assets/img/v108/'.$file);
    }
}


if (! function_exists('clinic_live_ui')) {
    function clinic_live_ui(string $key): string
    {
        $locale = app()->getLocale();
        $copy = [
            'partner' => ['en'=>'Live partner clinic','de'=>'Aktuelle Partnerklinik','ar'=>'عيادة شريكة متاحة'],
            'index_description' => ['en'=>'Review the currently available partner clinic, treatment areas and direct contact details before planning your journey.','de'=>'Prüfen Sie die aktuell verfügbare Partnerklinik, Behandlungsbereiche und direkten Kontaktdaten, bevor Sie Ihre Reise planen.','ar'=>'راجع العيادة الشريكة المتاحة حالياً ومجالات العلاج وبيانات التواصل المباشر قبل التخطيط للرحلة.'],
            'experience' => ['en'=>'Published experience','de'=>'Veröffentlichte Erfahrung','ar'=>'الخبرة المنشورة'],
            'care_areas' => ['en'=>'Published care areas','de'=>'Veröffentlichte Behandlungsbereiche','ar'=>'مجالات العلاج المنشورة'],
            'international_support' => ['en'=>'International support','de'=>'Internationale Betreuung','ar'=>'دعم المرضى الدوليين'],
            'consultation_route' => ['en'=>'Consultation route','de'=>'Beratungsweg','ar'=>'مسار الاستشارة'],
            'verify_title' => ['en'=>'What to verify before you proceed','de'=>'Was Sie vor dem nächsten Schritt prüfen sollten','ar'=>'ما الذي يجب التحقق منه قبل المتابعة'],
            'treatment_focus' => ['en'=>'Treatment focus','de'=>'Behandlungsschwerpunkte','ar'=>'مجالات العلاج'],
            'clinic_context' => ['en'=>'Clinic context','de'=>'Klinikkontext','ar'=>'سياق العيادة'],
            'compare_title' => ['en'=>'How to compare a clinic beyond the brochure','de'=>'Wie Sie eine Klinik über die Broschüre hinaus prüfen','ar'=>'كيف تقارن العيادة بما يتجاوز المواد التسويقية'],
            'website' => ['en'=>'Website','de'=>'Website','ar'=>'الموقع الإلكتروني'],
            'focus' => ['en'=>'Focus','de'=>'Schwerpunkt','ar'=>'التركيز'],
            'contact' => ['en'=>'Contact','de'=>'Kontakt','ar'=>'التواصل'],
            'verify_note' => ['en'=>'Always confirm treatment scope, quote, aftercare and clinician assignment directly with the clinic before travel.','de'=>'Bestätigen Sie Behandlungsumfang, Angebot, Nachsorge und Behandler vor der Reise immer direkt mit der Klinik.','ar'=>'أكد نطاق العلاج والسعر والمتابعة والطبيب المعالج مباشرةً مع العيادة قبل السفر.'],
            'verify_commit' => ['en'=>'Always verify identity, credentials, treatment scope, quote and aftercare directly with the clinic before committing.','de'=>'Prüfen Sie Identität, Qualifikationen, Behandlungsumfang, Angebot und Nachsorge direkt mit der Klinik, bevor Sie sich festlegen.','ar'=>'تحقق من هوية العيادة والمؤهلات ونطاق العلاج والسعر والمتابعة مباشرةً قبل الالتزام.'],
            'plan_clear' => ['en'=>'Request your plan only when the treatment scope, quote and aftercare path are clear.','de'=>'Fordern Sie Ihren Reiseplan erst an, wenn Behandlungsumfang, Angebot und Nachsorgeweg klar sind.','ar'=>'اطلب خطة الرحلة بعد وضوح نطاق العلاج والسعر ومسار المتابعة.'],
        ];
        return $copy[$key][$locale] ?? $copy[$key]['en'] ?? $key;
    }
}

if (! function_exists('clinic_demo_meta')) {
    /** Public-facing comparison facts for live clinic records. Do not place unverified review scores here. */
    function clinic_demo_meta($clinic): array
    {
        $slug = (string) data_get($clinic, 'slug', '');
        $map = [
            'clinic-expert' => [
                'established'=>'10+ years',
                'rooms'=>'4 published service areas',
                'coordinators'=>'International patient support',
                'response'=>'Free consultation',
                'strengths'=>['Dental Aesthetics','Hair Restoration','Plastic Surgery','Obesity Surgery'],
            ],
        ];
        return $map[$slug] ?? ['established'=>'—','rooms'=>'—','coordinators'=>'—','response'=>'Contact clinic','strengths'=>[]];
    }
}

if (! function_exists('procedure_snapshot')) {
    /**
     * Compact decision metrics for procedure landing pages. These are planning
     * categories, not promises; the treating clinic confirms patient-specific
     * timings, suitability and anaesthesia after assessment.
     */
    function procedure_snapshot($procedure): array
    {
        $slug = (string) data_get($procedure, 'slug', '');
        $specialty = (string) data_get($procedure, 'specialty.slug', data_get($procedure, 'specialty_slug', ''));
        $locale = app()->getLocale();

        $base = match ($specialty) {
            'dental' => ['approach'=>'Dental / restorative','setting'=>'Outpatient clinic','planning'=>'Imaging + bite plan','followup'=>'Maintenance plan'],
            'hair-restoration' => ['approach'=>'Hair restoration','setting'=>'Outpatient clinic','planning'=>'Donor + recipient plan','followup'=>'Early healing checks'],
            'cosmetic-surgery' => ['approach'=>'Aesthetic surgery','setting'=>'Day case / short stay','planning'=>'Surgeon + anaesthesia plan','followup'=>'Wound + recovery review'],
            'bariatric-surgery' => ['approach'=>'Weight-management pathway','setting'=>'Endoscopy / hospital','planning'=>'Eligibility + nutrition','followup'=>'Long-term follow-up'],
            'eye-care' => ['approach'=>'Ophthalmic treatment','setting'=>'Specialist eye clinic','planning'=>'Eye exam + measurements','followup'=>'Vision / eye review'],
            'fertility' => ['approach'=>'Fertility pathway','setting'=>'Specialist clinic','planning'=>'Tests + cycle timing','followup'=>'Cycle / result follow-up'],
            'orthopedics' => ['approach'=>'Orthopedic treatment','setting'=>'Hospital / specialist clinic','planning'=>'Imaging + rehab plan','followup'=>'Rehabilitation'],
            'ent' => ['approach'=>'ENT treatment','setting'=>'Specialist clinic / hospital','planning'=>'Exam + imaging as needed','followup'=>'Healing / function review'],
            'general-surgery' => ['approach'=>'General surgery','setting'=>'Hospital / day surgery','planning'=>'Assessment + anaesthesia','followup'=>'Wound / recovery review'],
            'urology' => ['approach'=>'Urology treatment','setting'=>'Hospital / specialist clinic','planning'=>'Tests + imaging as needed','followup'=>'Function / recovery review'],
            default => ['approach'=>'Procedure pathway','setting'=>'Provider-confirmed','planning'=>'Individual assessment','followup'=>'Written follow-up plan'],
        };

        $specific = [
            'gastric-balloon' => ['approach'=>'Non-surgical endoscopic','setting'=>'Short outpatient pathway','planning'=>'Device + removal plan','followup'=>'Nutrition + removal follow-up'],
            'adjustable-gastric-band' => ['approach'=>'Laparoscopic restrictive','setting'=>'Hospital / short stay','planning'=>'Band + adjustment plan','followup'=>'Adjustments + nutrition'],
            'endoscopic-sleeve-gastroplasty' => ['approach'=>'Endoscopic suturing','setting'=>'Endoscopy unit','planning'=>'Eligibility + nutrition','followup'=>'Diet + weight-management'],
            'duodenal-switch' => ['approach'=>'Metabolic surgery','setting'=>'Inpatient hospital','planning'=>'Nutrition + surgical plan','followup'=>'Lifelong nutrition follow-up'],
            'one-anastomosis-gastric-bypass' => ['approach'=>'Metabolic bypass surgery','setting'=>'Inpatient hospital','planning'=>'Surgical + nutrition plan','followup'=>'Long-term nutrition review'],
            'sadi-s' => ['approach'=>'Metabolic surgery','setting'=>'Inpatient hospital','planning'=>'Surgical + nutrition plan','followup'=>'Long-term nutrition review'],
            'blepharoplasty' => ['approach'=>'Eyelid surgery','setting'=>'Day surgery / clinic','planning'=>'Eye health + eyelid plan','followup'=>'Swelling + wound review'],
            'body-lift' => ['approach'=>'Body-contouring surgery','setting'=>'Hospital / short stay','planning'=>'Skin + scar + recovery plan','followup'=>'Wound + mobility review'],
            'breast-augmentation' => ['approach'=>'Breast surgery','setting'=>'Day case / short stay','planning'=>'Implant / technique plan','followup'=>'Wound + implant review'],
            'breast-lift' => ['approach'=>'Breast reshaping surgery','setting'=>'Day case / short stay','planning'=>'Lift + scar plan','followup'=>'Wound + shape review'],
            'breast-reduction' => ['approach'=>'Breast reduction surgery','setting'=>'Day case / short stay','planning'=>'Reduction + scar plan','followup'=>'Wound + recovery review'],
            'facelift-neck-lift' => ['approach'=>'Facial / neck surgery','setting'=>'Day case / short stay','planning'=>'Anatomy + scar plan','followup'=>'Swelling + wound review'],
            'gynecomastia-surgery' => ['approach'=>'Male chest surgery','setting'=>'Day case / short stay','planning'=>'Gland / fat assessment','followup'=>'Compression + wound review'],
            'fue-hair-transplant' => ['approach'=>'Follicular-unit extraction','setting'=>'Outpatient clinic','planning'=>'Donor + hairline strategy','followup'=>'Donor / recipient checks'],
            'dhi-hair-transplant' => ['approach'=>'Implanter-assisted placement','setting'=>'Outpatient clinic','planning'=>'Donor + implantation plan','followup'=>'Early healing checks'],
            'dental-implants' => ['approach'=>'Implant dentistry','setting'=>'Outpatient dental clinic','planning'=>'3D imaging + bone plan','followup'=>'Integration + maintenance'],
            'all-on-4' => ['approach'=>'Full-arch implant pathway','setting'=>'Outpatient / surgical dental','planning'=>'Imaging + bite + bone plan','followup'=>'Healing + final restoration'],
            'all-on-6' => ['approach'=>'Full-arch implant pathway','setting'=>'Outpatient / surgical dental','planning'=>'Imaging + bite + bone plan','followup'=>'Healing + final restoration'],
        ];
        $data = $specific[$slug] ?? $base;

        if ($locale === 'de') {
            $labels = ['Behandlungsart','Versorgungsumgebung','Planungsschwerpunkt','Nachsorge'];
            $data = match ($specialty) {
                'dental' => ['approach'=>'Zahnmedizin / Rekonstruktion','setting'=>'Ambulante Zahnklinik','planning'=>'Bildgebung + Bissplanung','followup'=>'Kontrollen + Pflege'],
                'hair-restoration' => ['approach'=>'Haarwiederherstellung','setting'=>'Ambulante Klinik','planning'=>'Spender- + Empfängerplanung','followup'=>'Frühe Heilungskontrollen'],
                'cosmetic-surgery' => ['approach'=>'Ästhetische Chirurgie','setting'=>'Tagesklinik / Kurzaufenthalt','planning'=>'Operations- + Narkoseplan','followup'=>'Wund- + Heilungskontrolle'],
                'bariatric-surgery' => ['approach'=>'Gewichtsbehandlung','setting'=>'Endoskopie / Krankenhaus','planning'=>'Eignung + Ernährung','followup'=>'Langfristige Nachsorge'],
                default => ['approach'=>'Behandlungsweg','setting'=>'Vom Anbieter bestätigt','planning'=>'Individuelle Beurteilung','followup'=>'Schriftlicher Nachsorgeplan'],
            };
        } elseif ($locale === 'ar') {
            $labels = ['نوع العلاج','بيئة العلاج','محور التخطيط','المتابعة'];
            $data = match ($specialty) {
                'dental' => ['approach'=>'علاج أسنان / ترميم','setting'=>'عيادة أسنان خارجية','planning'=>'تصوير + خطة الإطباق','followup'=>'صيانة ومراجعة'],
                'hair-restoration' => ['approach'=>'استعادة الشعر','setting'=>'عيادة خارجية','planning'=>'خطة المنطقة المانحة والمستقبلة','followup'=>'متابعة الالتئام المبكر'],
                'cosmetic-surgery' => ['approach'=>'جراحة تجميلية','setting'=>'جراحة يومية / إقامة قصيرة','planning'=>'خطة الجراحة والتخدير','followup'=>'متابعة الجرح والتعافي'],
                'bariatric-surgery' => ['approach'=>'مسار إدارة الوزن','setting'=>'تنظير / مستشفى','planning'=>'الملاءمة + التغذية','followup'=>'متابعة طويلة المدى'],
                default => ['approach'=>'مسار علاجي','setting'=>'يؤكده مقدم العلاج','planning'=>'تقييم فردي','followup'=>'خطة متابعة مكتوبة'],
            };
        } else {
            $labels = ['Approach','Care setting','Planning focus','Follow-up focus'];
        }

        $timing = match ($locale) {
            'de' => match ($slug) {
                'gastric-balloon' => 'Kurzer endoskopischer Termin',
                default => match ($specialty) {
                    'dental' => 'Je nach Behandlungsplan',
                    'hair-restoration' => 'Meist ein Behandlungstag',
                    'cosmetic-surgery', 'bariatric-surgery', 'general-surgery', 'orthopedics', 'urology' => 'Vom Behandlungsteam bestätigt',
                    default => 'Vom Anbieter bestätigt',
                },
            },
            'ar' => match ($slug) {
                'gastric-balloon' => 'موعد تنظيري قصير',
                default => match ($specialty) {
                    'dental' => 'بحسب خطة العلاج',
                    'hair-restoration' => 'غالباً يوم علاجي واحد',
                    default => 'يؤكده الفريق المعالج',
                },
            },
            default => match ($slug) {
                'gastric-balloon' => 'Short endoscopic appointment',
                default => match ($specialty) {
                    'dental' => 'Plan-dependent',
                    'hair-restoration' => 'Often one treatment day',
                    default => 'Provider-confirmed',
                },
            },
        };
        $recovery = match ($locale) {
            'de' => 'Individuell; Rückreise vorher klären',
            'ar' => 'فردي؛ تأكد من توقيت العودة قبل السفر',
            default => 'Individual; confirm return-travel timing',
        };
        $extraLabels = match ($locale) {
            'de' => ['Typischer Zeitrahmen','Erholung & Rückreise'],
            'ar' => ['الإطار الزمني المعتاد','التعافي والعودة'],
            default => ['Typical timing','Recovery & return travel'],
        };
        return [
            ['label'=>$labels[0],'value'=>$data['approach']],
            ['label'=>$labels[1],'value'=>$data['setting']],
            ['label'=>$labels[2],'value'=>$data['planning']],
            ['label'=>$labels[3],'value'=>$data['followup']],
            ['label'=>$extraLabels[0],'value'=>$timing],
            ['label'=>$extraLabels[1],'value'=>$recovery],
        ];
    }
}

if (! function_exists('procedure_guidance')) {
    /**
     * User-facing planning guidance. Values are deliberately framed as common
     * planning patterns, not medical promises. Treating providers confirm the
     * patient-specific timetable after assessment.
     */
    function procedure_guidance($procedure): array
    {
        $slug = (string) data_get($procedure, 'slug', '');
        $specialty = (string) data_get($procedure, 'specialty.slug', '');
        $locale = app()->getLocale();

        $generic = match ($locale) {
            'de' => [
                ['title'=>'Behandlungsablauf','value'=>'Individuell','text'=>'Untersuchung, Behandlungsplan, Behandlung, Erholung und Nachsorge werden vom behandelnden Anbieter bestätigt.'],
                ['title'=>'Termine & Sitzungen','value'=>'Anbieterabhängig','text'=>'Fragen Sie, wie viele Kliniktermine geplant sind und was einen zusätzlichen Termin erforderlich machen könnte.'],
                ['title'=>'Erholung','value'=>'Individuell','text'=>'Klären Sie frühe Erholung, Reisefähigkeit und den Zeitpunkt der Rückkehr in Alltag und Arbeit.'],
                ['title'=>'Nachsorge','value'=>'Vor Reise klären','text'=>'Lassen Sie sich Ansprechpartner, Unterlagen und einen schriftlichen Nachsorgeplan für zuhause geben.'],
            ],
            'ar' => [
                ['title'=>'المسار العلاجي','value'=>'حسب الحالة','text'=>'يؤكد مقدم العلاج الفحص والخطة والعلاج والتعافي والمتابعة بعد تقييم الحالة.'],
                ['title'=>'المواعيد والجلسات','value'=>'يحددها مقدم العلاج','text'=>'اسأل عن عدد الزيارات المتوقعة وما الذي قد يستدعي موعداً إضافياً.'],
                ['title'=>'التعافي','value'=>'حسب الحالة','text'=>'وضّح التعافي المبكر وموعد السفر الآمن والعودة إلى النشاط والعمل.'],
                ['title'=>'المتابعة','value'=>'قبل السفر','text'=>'احصل على جهة الاتصال والسجلات وخطة متابعة مكتوبة بعد العودة إلى بلدك.'],
            ],
            default => [
                ['title'=>'Treatment timeline','value'=>'Provider-confirmed','text'=>'Assessment → written plan → treatment → recovery → follow-up. Your provider confirms the patient-specific sequence.'],
                ['title'=>'Appointments & sessions','value'=>'Case-dependent','text'=>'Ask how many clinic visits are expected, how long each stage takes and what could add another appointment.'],
                ['title'=>'Recovery & travel','value'=>'Individual','text'=>'Clarify early recovery, travel fitness and when normal activity or work can reasonably resume.'],
                ['title'=>'Follow-up','value'=>'Plan before travel','text'=>'Leave with records, a named contact and a written return-home follow-up pathway.'],
            ],
        };
        if ($locale !== 'en') return $generic;

        $dental = [
            'all-on-4' => [
                ['title'=>'Treatment timeline','value'=>'Staged pathway','text'=>'Consultation and imaging → implant/provisional phase → healing → final restoration. The exact staging depends on bone, bite, healing and the clinic protocol.'],
                ['title'=>'Appointments & sessions','value'=>'Usually several visits','text'=>'The initial trip commonly includes multiple clinic appointments. A final restoration may be completed later after healing, depending on the provider plan.'],
                ['title'=>'Healing & recovery','value'=>'Days + months','text'=>'Early soft-tissue recovery happens first; biological implant integration takes substantially longer. Ask which milestones must be reached before final teeth are fitted.'],
                ['title'=>'Long-term follow-up','value'=>'Ongoing','text'=>'Request implant records, hygiene guidance, maintenance intervals and a named contact for problems after you return home.'],
            ],
            'all-on-6' => [
                ['title'=>'Treatment timeline','value'=>'Staged pathway','text'=>'Diagnostics → implant placement/provisional restoration → healing → definitive restoration. Bone, bite and implant stability determine the sequence.'],
                ['title'=>'Appointments & sessions','value'=>'Multiple visits','text'=>'Expect several appointments around surgery and prosthetic checks; some protocols require a later visit for the final bridge.'],
                ['title'=>'Healing & recovery','value'=>'Days + months','text'=>'Early soreness and swelling settle before the longer bone-integration phase. Your clinic should explain diet, hygiene and warning signs.'],
                ['title'=>'Long-term follow-up','value'=>'Maintenance matters','text'=>'Full-arch restorations need professional maintenance. Ask how the bridge is cleaned, reviewed and repaired if needed.'],
            ],
            'dental-implants' => [
                ['title'=>'Treatment timeline','value'=>'Often staged','text'=>'Assessment and imaging come first. Implant placement, healing and the final crown may happen in separate stages depending on stability and bone conditions.'],
                ['title'=>'Appointments & sessions','value'=>'One or more trips','text'=>'Single-stage and staged pathways both exist. Confirm which steps happen on the first trip and whether another visit is expected.'],
                ['title'=>'Healing & recovery','value'=>'Early days; integration months','text'=>'Comfort often improves well before the implant has biologically integrated. Ask when chewing, exercise and travel are appropriate.'],
                ['title'=>'Long-term follow-up','value'=>'Keep the implant record','text'=>'Take home the implant system details, restorative information, imaging and maintenance schedule for future dentists.'],
            ],
            'dental-veneers' => [
                ['title'=>'Treatment timeline','value'=>'Usually short, but plan-led','text'=>'Assessment and smile planning should precede preparation. Try-in, bite review and final checks need enough time before travel.'],
                ['title'=>'Appointments & sessions','value'=>'Several appointments','text'=>'Ask whether consultation, preparation, temporary veneers, try-in and final fitting are separate appointments.'],
                ['title'=>'Recovery','value'=>'Usually limited','text'=>'Sensitivity and gum irritation can occur. Ask what is expected, what is not, and when bite adjustment should be reviewed.'],
                ['title'=>'Long-term follow-up','value'=>'Protect tooth structure','text'=>'Keep material records and ask how chips, debonding, gum changes or future replacement would be managed at home.'],
            ],
            'dental-crowns' => [
                ['title'=>'Treatment timeline','value'=>'Diagnosis first','text'=>'The dentist should confirm why a crown is indicated, prepare the tooth, make the restoration and review fit, bite and margins before travel.'],
                ['title'=>'Appointments & sessions','value'=>'Often multiple visits','text'=>'Ask whether temporary crowns are required and how much time is reserved for fit and bite adjustment.'],
                ['title'=>'Recovery','value'=>'Monitor sensitivity','text'=>'Ask what sensitivity is expected and which symptoms require prompt review before you fly home.'],
                ['title'=>'Long-term follow-up','value'=>'Home dental care','text'=>'Keep material and treatment records and maintain routine checks with your dentist after return.'],
            ],
            'dental-bridges' => [
                ['title'=>'Treatment timeline','value'=>'Restorative pathway','text'=>'Assessment → preparation or implant planning → impression/scan → fitting → bite review. The design depends on how the bridge is supported.'],
                ['title'=>'Appointments & sessions','value'=>'Several appointments','text'=>'Confirm whether the bridge is tooth-supported or implant-supported and whether temporary work is required.'],
                ['title'=>'Recovery','value'=>'Usually limited','text'=>'Ask about cleaning, bite adaptation, sensitivity and warning signs around the supporting teeth or implants.'],
                ['title'=>'Long-term follow-up','value'=>'Cleaning is critical','text'=>'Get specific instructions for cleaning under the bridge and maintaining the supporting teeth or implants.'],
            ],
            'bone-grafting-and-sinus-lift' => [
                ['title'=>'Treatment timeline','value'=>'Often staged','text'=>'Bone augmentation may be completed before or with implant placement. Imaging, defect size and surgical plan determine the sequence.'],
                ['title'=>'Appointments & sessions','value'=>'Provider-dependent','text'=>'Ask whether grafting and implant placement happen together and whether a later trip is expected before restoration.'],
                ['title'=>'Healing & recovery','value'=>'Healing takes time','text'=>'Early swelling settles before graft maturation. Your surgeon should explain sinus precautions, activity and signs that need review.'],
                ['title'=>'Long-term follow-up','value'=>'Imaging + records','text'=>'Take home the graft material, operative record, implant plan and follow-up imaging recommendations.'],
            ],
            'orthodontics-and-aligners' => [
                ['title'=>'Treatment timeline','value'=>'Months, not a travel quick-fix','text'=>'Orthodontics is a longitudinal treatment. Overseas care only makes sense when adjustment, monitoring and retention are clearly arranged.'],
                ['title'=>'Appointments & sessions','value'=>'Repeated reviews','text'=>'Ask how remote monitoring works, who handles attachments or emergencies and where refinements are completed.'],
                ['title'=>'Adaptation','value'=>'Early discomfort possible','text'=>'Aligners or braces can cause temporary pressure and irritation. Your clinician should explain expected adaptation and red flags.'],
                ['title'=>'Long-term follow-up','value'=>'Retention required','text'=>'Retention after active treatment is essential. Confirm the retainer plan before starting treatment abroad.'],
            ],
            'periodontal-treatment' => [
                ['title'=>'Treatment timeline','value'=>'Diagnosis-led','text'=>'Gum treatment depends on disease severity and may involve non-surgical care, reassessment and surgery only where indicated.'],
                ['title'=>'Appointments & sessions','value'=>'Often staged','text'=>'Ask whether treatment requires several quadrants, a later reassessment or ongoing maintenance appointments.'],
                ['title'=>'Recovery','value'=>'Procedure-dependent','text'=>'Tenderness and sensitivity vary by treatment. Ask about hygiene, diet, smoking and medication instructions.'],
                ['title'=>'Long-term follow-up','value'=>'Maintenance is central','text'=>'Periodontal stability depends on home hygiene and regular professional maintenance after you return.'],
            ],
            'root-canal-treatment' => [
                ['title'=>'Treatment timeline','value'=>'Diagnosis → treatment → restoration','text'=>'Root canal treatment addresses infection inside the tooth; the tooth often also needs a definitive restoration such as a crown.'],
                ['title'=>'Appointments & sessions','value'=>'One or more visits','text'=>'Complex anatomy, infection or retreatment can require additional appointments. Ask when the final restoration will be completed.'],
                ['title'=>'Recovery','value'=>'Short-term tenderness possible','text'=>'Ask which symptoms are expected and which require urgent reassessment before travel.'],
                ['title'=>'Long-term follow-up','value'=>'Protect the tooth','text'=>'Keep radiographs and treatment records and arrange routine review of the restored tooth at home.'],
            ],
        ];
        return $dental[$slug] ?? $generic;
    }
}


if (! function_exists('procedure_card_summary')) {
    function procedure_card_summary($procedure): string
    {
        $locale = app()->getLocale();
        $summary = trim((string) data_get($procedure, 'summary', ''));
        $name = trim((string) data_get($procedure, 'name', ''));

        if ($summary === '' || str_contains($summary, 'coordinates the clinic, travel and follow-up steps') || str_contains($summary, 'clinic, travel and follow-up')) {
            return match ($locale) {
                'de' => $name . ': Verstehen Sie den Ablauf, bereiten Sie die wichtigsten Fragen vor und lassen Sie Klinik, Reise und Nachsorge praktisch koordinieren.',
                'ar' => $name . ': افهم مسار العلاج وجهّز الأسئلة المهمة ودعنا ننسق العيادة والسفر وخطوات المتابعة عملياً.',
                default => 'Understand how ' . $name . ' is planned, what questions matter, and how we coordinate clinic, travel and follow-up around your treatment journey.',
            };
        }

        return $summary;
    }
}

if (! function_exists('procedure_clinic_slugs')) {
    function procedure_clinic_slugs($procedure): array
    {
        $specialty = (string) data_get($procedure, 'specialty.slug', '');
        return match ($specialty) {
            'dental', 'hair-restoration', 'cosmetic-surgery', 'bariatric-surgery' => ['clinic-expert'],
            default => [],
        };
    }
}

