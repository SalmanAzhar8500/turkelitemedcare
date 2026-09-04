<!doctype html>
@php
    $site = website_settings();
    $brand = $site['brand'];
    $logoUrl = public_media_url((string) ($brand['logo_path'] ?? 'assets/img/turkelitemedcare-logo.svg'));
    $faviconUrl = public_media_url((string) ($brand['favicon_path'] ?? 'assets/img/favicon.svg'));
    $faviconType = website_favicon_type();
    $specialties = site_specialties();
    $locale = app()->getLocale();
    $concept = current_design_concept();
    $isTreatments = request()->routeIs('treatments.*', 'procedures.*');
    $isAbout = request()->routeIs('about');
    $isContact = request()->routeIs('contact');
    $isForClinics = request()->routeIs('for-clinics');
    $isHowItWorks = request()->routeIs('how-it-works');
    $isPatientServices = request()->routeIs('patient-services');
    $isClinics = request()->routeIs('clinics.*');
    $isGuides = request()->routeIs('guides.*');
    $isHome = request()->routeIs('home');
    $whatsappNumber = (string) website_setting('brand.whatsapp_number', website_setting('brand.phone', ''));
    $whatsappDigits = preg_replace('/\D+/', '', $whatsappNumber);
    $whatsappText = match ($locale) {
        'de' => 'Hallo, ich möchte mich über eine Behandlung in der Türkei informieren.',
        'ar' => 'مرحباً، أود الاستفسار عن العلاج في تركيا.',
        default => 'Hello, I would like to ask about treatment in Turkey.',
    };
    $supportHours = match ($locale) {
        'de' => 'Mo–Fr · 08:00–18:00 CET',
        'ar' => 'الإثنين–الجمعة · 08:00–18:00 CET',
        default => $site['support_hours']['weekday'],
    };
    $whatsappUrl = $whatsappDigits
        ? 'https://wa.me/'.$whatsappDigits.'?text='.rawurlencode($whatsappText)
        : website_setting('brand.whatsapp');
@endphp

<html lang="{{ $locale }}" dir="{{ in_array($locale, config('locales.rtl', []), true) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', $brand['name'])</title>
    <meta name="description" content="@yield('description', 'Medical travel coordination for international patients in Turkey.')">
    @hasSection('keywords')<meta name="keywords" content="@yield('keywords')">@endif
    <meta name="theme-color" content="#67afa4">
    <meta name="robots" content="@yield('robots', 'index,follow,max-image-preview:large,max-snippet:-1')">
    <link rel="canonical" href="@yield('canonical', localized_route_url($locale))">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', $brand['name'])">
    <meta property="og:description" content="@yield('description', 'Medical travel coordination for international patients in Turkey.')">
    <meta property="og:url" content="@yield('canonical', localized_route_url($locale))">
    <meta property="og:image" content="@yield('og_image', website_logo_url())">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $brand['name'])">
    <meta name="twitter:description" content="@yield('description', 'Medical travel coordination for international patients in Turkey.')">
    <meta name="twitter:image" content="@yield('og_image', website_logo_url())">
    @foreach(config('locales.supported', ['en']) as $altLocale)
        <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ localized_route_url($altLocale) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ localized_route_url('en') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/v104-showcase.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/v105-clinical.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/v108-polish.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/v109-release.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/final-release.css') }}">
    <link rel="icon" type="{{ $faviconType }}" href="{{ $faviconUrl }}">
    <link rel="alternate icon" sizes="any" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    @if($isHome)
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => url('/').'#organization',
                    'name' => $brand['name'],
                    'url' => url('/'),
                    'logo' => $logoUrl,
                    'email' => $brand['email'],
                    'telephone' => $brand['phone'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/').'#website',
                    'url' => url('/'),
                    'name' => $brand['name'],
                    'publisher' => ['@id' => url('/').'#organization'],
                    'inLanguage' => config('locales.supported', ['en']),
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @stack('head')
</head>
<body class="v104 final-release theme-{{ $concept }}">
    <a class="skip-link" href="#main-content">{{ $locale === 'de' ? 'Zum Inhalt springen' : ($locale === 'ar' ? 'الانتقال إلى المحتوى' : 'Skip to main content') }}</a>
    <div class="topbar">
        <div class="container topbar-inner">
            <div>
                <span>{{ __('site.support') }}</span>
                <span>{{ $brand['email'] }}</span>
            </div>
            <div>
                <span>{{ $supportHours }}</span>
                <span class="lang-switch" aria-label="Language selector">
                    @foreach(config('locales.supported', ['en']) as $languageCode)
                        @php
                            $flagAsset = match ($languageCode) {
                                'de' => 'assets/img/flags/de.svg',
                                'ar' => 'assets/img/flags/ae.svg',
                                default => 'assets/img/flags/gb.svg',
                            };
                        @endphp
                        <a class="lang flag-lang {{ $locale === $languageCode ? 'active' : '' }}" href="{{ localized_route_url($languageCode) }}" hreflang="{{ $languageCode }}" lang="{{ $languageCode }}" title="{{ config('locales.names.'.$languageCode) }}" aria-label="{{ config('locales.names.'.$languageCode) }}" @if($locale === $languageCode) aria-current="true" @endif><img src="{{ asset($flagAsset) }}" alt="" aria-hidden="true"><span class="sr-only">{{ config('locales.names.'.$languageCode) }}</span></a>
                    @endforeach
                </span>
            </div>
        </div>
    </div>

    <header class="site-header">
        <div class="container nav-wrap">
            <a aria-label="{{ $brand['name'] }} home" class="brand" href="{{ route('home') }}">
                <img alt="{{ $brand['name'] }}" class="brand-logo" src="{{ $logoUrl }}">
            </a>
            <button aria-expanded="false" aria-label="{{ $locale === 'de' ? 'Menü öffnen' : ($locale === 'ar' ? 'فتح القائمة' : 'Open menu') }}" class="mobile-toggle" type="button"><span></span><span></span><span></span></button>
            <nav aria-label="Main navigation" class="main-nav">
                <a class="{{ $isHome ? 'active' : '' }}" href="{{ route('home') }}" @if($isHome) aria-current="page" @endif>{{ site_ui('home') }}</a>
                <div class="nav-item has-mega treatments-mega">
                    <a aria-current="{{ $isTreatments ? 'page' : 'false' }}" class="nav-link treatments-parent {{ $isTreatments ? 'active' : '' }}" href="{{ route('treatments.index') }}">{{ __('site.treatments') }}</a>
                    <button aria-controls="treatments-mega-menu" aria-expanded="false" aria-label="Open treatments menu" class="mega-toggle" type="button"><span class="nav-chevron" aria-hidden="true"></span></button>
                    <div class="mega-menu" id="treatments-mega-menu">
                        <div class="mega-head">
                            <div>
                                <span class="eyebrow">{{ __('site.treatments') }}</span>
                                <h3>{{ __('site.treatment_index_title') }}</h3>
                            </div>
                            <a href="{{ route('treatments.index') }}">{{ __('site.all_treatments') }} →</a>
                        </div>
                        <div class="mega-grid">
                            @foreach($specialties as $specialty)
                                <a href="{{ route('treatments.show', $specialty['slug']) }}">
                                    <span>
                                        <svg aria-hidden="true" class="mini-icon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9"></circle>
                                        </svg>
                                    </span>
                                    <span>
                                        <b>{{ $specialty['name'] }}</b>
                                        <small>{{ $specialty['summary'] }}</small>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- <a class="{{ (request()->routeIs('treatments.index') || $isTreatments) ? 'active' : '' }}" href="{{ route('treatments.index') }}" @if(request()->routeIs('treatments.index')) aria-current="page" @endif>Treatments</a> -->
                <a class="{{ $isClinics ? 'active' : '' }}" href="{{ route('clinics.index') }}" @if($isClinics) aria-current="page" @endif>{{ site_ui('clinics') }}</a>
                <a class="{{ $isHowItWorks ? 'active' : '' }}" href="{{ route('how-it-works') }}" @if($isHowItWorks) aria-current="page" @endif>{{ site_ui('how_it_works') }}</a>
                <a class="{{ $isPatientServices ? 'active' : '' }}" href="{{ route('patient-services') }}" @if($isPatientServices) aria-current="page" @endif>{{ site_ui('patient_services') }}</a>
                <a class="{{ $isGuides ? 'active' : '' }}" href="{{ route('guides.index') }}" @if($isGuides) aria-current="page" @endif>{{ site_footer('guides') }}</a>
                <a class="{{ $isAbout ? 'active' : '' }}" href="{{ route('about') }}" @if($isAbout) aria-current="page" @endif>{{ site_ui('about') }}</a>
            </nav>
            <div class="nav-actions">
                <button aria-label="{{ __('site.search') }}" class="search-toggle" type="button">⌕</button>
                <a class="btn btn-primary btn-sm" href="{{ route('treatment-plan') }}">{{ site_ui('plan_journey') }}</a>
            </div>
        </div>
    </header>

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}">
                    <img alt="{{ $brand['name'] }}" class="brand-logo" src="{{ $logoUrl }}">
                </a>
                <p>{{ site_footer('description') }}</p>
                <div class="trust-note">{{ site_footer('trust_note') }}</div>
            </div>
            <div>
                <h4>{{ site_footer('treatments_heading') }}</h4>
                @foreach(array_slice($specialties, 0, 6) as $specialty)
                    <a href="{{ route('treatments.show', $specialty['slug']) }}">{{ $specialty['name'] }}</a>
                @endforeach
            </div>
            <div>
                <h4>{{ site_footer('support_heading') }}</h4>
                <a href="{{ route('about') }}">{{ site_footer('about') }}</a>
                <a href="{{ route('how-it-works') }}">{{ site_ui('how_it_works') }}</a>
                <a href="{{ route('patient-services') }}">{{ site_ui('patient_services') }}</a>
                <a class="{{ $isGuides ? 'active' : '' }}" href="{{ route('guides.index') }}" @if($isGuides) aria-current="page" @endif>{{ site_footer('guides') }}</a>
                <a href="{{ route('contact') }}">{{ site_footer('contact') }}</a>
            </div>
            <div>
                <h4>{{ site_footer('company_heading') }}</h4>
                <a href="{{ route('about') }}">{{ site_footer('about_us') }}</a>
                <a href="{{ route('for-clinics') }}">{{ site_footer('for_clinics') }}</a>
                <a href="{{ route('contact') }}">{{ site_footer('contact') }}</a>
                <a href="{{ route('legal') }}">{{ site_footer('legal_privacy') }}</a>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>{{ site_footer('copyright') }}</span>
            <span>{{ site_footer('medical_notice') }}</span>
            <span class="mgmt-disclosure">{{ site_footer('disclosure') }}</span>
        </div>
    </footer>

    <div aria-hidden="true" class="search-modal">
        <div class="search-panel">
            <div class="search-top">
                <div>
                    <span class="eyebrow">{{ __('site.search') }}</span>
                    <h3>{{ __('site.search_title') }}</h3>
                </div>
                <button aria-label="Close" class="search-close" type="button">×</button>
            </div>
            <input class="search-input" placeholder="{{ __('site.search_placeholder') }}" type="search">
            <div class="search-results"></div>
        </div>
    </div>
    <a class="floating-cta" href="{{ route('treatment-plan') }}">{{ site_ui('plan_journey') }} <span aria-hidden="true">&rarr;</span></a>
    @if(filled($whatsappUrl))
        <a class="wa-float" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ __('site.whatsapp') }}" title="{{ __('site.whatsapp') }}">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.5 3.5A11.8 11.8 0 0 0 12.08 0C5.54 0 .22 5.32.22 11.86c0 2.09.55 4.13 1.59 5.93L.12 24l6.36-1.67a11.85 11.85 0 0 0 5.6 1.42h.01c6.54 0 11.86-5.32 11.86-11.86 0-3.17-1.23-6.15-3.45-8.39Zm-8.42 18.2h-.01a9.83 9.83 0 0 1-5.01-1.37l-.36-.22-3.77.99 1.01-3.68-.24-.38a9.83 9.83 0 0 1-1.51-5.18C2.19 6.43 6.62 2 12.08 2c2.64 0 5.12 1.03 6.98 2.9a9.82 9.82 0 0 1 2.89 6.99c0 5.46-4.44 9.89-9.87 9.89Zm5.42-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.2-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49s1.07 2.89 1.22 3.09c.15.2 2.1 3.2 5.09 4.49.71.31 1.26.5 1.69.64.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35Z"/>
            </svg>
        </a>
    @endif

    @if(config('design.show_switcher') && app()->environment(['local', 'testing']))
        <nav class="concept-switcher" aria-label="{{ __('site.showcase') }}">
            <span>{{ __('site.showcase') }}</span>
            @foreach(config('design.allowed', []) as $design)
                <a class="{{ $concept === $design ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['concept' => $design]) }}">{{ __('site.'.($design === 'journey' ? 'journey_theme' : $design)) }}</a>
            @endforeach
        </nav>
    @endif

    @if(app()->environment(['local', 'testing', 'staging']))
        <div class="v109-build-badge" aria-label="Build version">{{ config('build.version') }} · {{ strtoupper(app()->environment()) }} · :{{ request()->getPort() }}</div>
    @endif

    <section class="cookie-consent" data-cookie-consent hidden aria-label="Cookie preferences">
        <div><strong>{{ __('site.privacy_preferences') }}</strong><p>{{ __('site.cookie_text') }}</p></div>
        <div class="cookie-consent-actions"><button type="button" data-cookie-essential>{{ __('site.essential_only') }}</button><button type="button" data-cookie-allow>{{ __('site.allow_optional') }}</button></div>
    </section>
    @php
        $runtimeCopy = match($locale) {
            'de' => ['searchPrompt'=>'Behandlungen, Fachgebiete und Anbieterprofile durchsuchen.','noResults'=>'Keine passende Seite gefunden.','backToTop'=>'Nach oben'],
            'ar' => ['searchPrompt'=>'ابحث في العلاجات والتخصصات وملفات مقدمي الرعاية.','noResults'=>'لم يتم العثور على صفحة مطابقة.','backToTop'=>'العودة إلى الأعلى'],
            default => ['searchPrompt'=>'Search treatments, specialties and provider profiles.','noResults'=>'No matching page found.','backToTop'=>'Back to top'],
        };
    @endphp
    <script>window.SITE_PREFIX = @json(rtrim(url('/'), '/').'/'); window.SITE_LOCALE = @json($locale); window.SITE_RUNTIME_COPY = @json($runtimeCopy, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);</script>
    <script defer src="{{ asset('assets/js/search-index.js') }}"></script>
    <script defer src="{{ asset('assets/js/site.js') }}"></script>
    <script defer src="{{ asset('assets/js/premium.js') }}"></script>
    @stack('scripts')
</body>
</html>


