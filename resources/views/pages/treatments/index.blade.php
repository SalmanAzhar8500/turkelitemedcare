@extends('layouts.site')
@php
    $locale = app()->getLocale();
    $title = $locale === 'en' ? ($page->seo_title ?: __('site.treatment_index_title')) : __('site.treatment_index_title').' | Turkelite Medcare';
    $description = $locale === 'en' ? ($page->seo_description ?: __('site.treatment_index_lead')) : __('site.treatment_index_lead');
    $priority = $featuredProcedures;
    $copy = match($locale) {
        'de' => ['kicker'=>'Behandlungen in der Türkei','headline'=>'Beginnen Sie mit der Behandlung, nicht mit dem Flug.','lead'=>'Unsere vertieften Behandlungsseiten helfen Ihnen, die richtigen Fragen zu stellen, Zuständigkeiten zu verstehen und eine medizinische Reise erst dann zu planen, wenn der behandelnde Anbieter einen individuellen Plan bestätigt.','priority'=>'Vertiefte Behandlungsratgeber','library'=>'Alle Fachgebiete','library_lead'=>'Die breitere Bibliothek bleibt zur Orientierung verfügbar. Die ausführlichen Ratgeber gehen bei Planung, Anbieterfragen, Reise und Nachsorge deutlich tiefer.','plan'=>'Behandlungsanfrage starten'],
        'ar' => ['kicker'=>'العلاج في تركيا','headline'=>'ابدأ بالعلاج، لا بالرحلة.','lead'=>'تساعدك صفحات العلاج المتعمقة على طرح الأسئلة الصحيحة وفهم المسؤوليات وعدم التخطيط للسفر إلا بعد أن يؤكد مقدم الرعاية المعالج خطة فردية لحالتك.','priority'=>'أدلة العلاج المتعمقة','library'=>'جميع التخصصات','library_lead'=>'تبقى المكتبة الأوسع متاحة للاستكشاف، بينما تتعمق الأدلة التفصيلية أكثر في التخطيط وأسئلة مقدم الرعاية والسفر والمتابعة.','plan'=>'ابدأ طلب العلاج'],
        default => ['kicker'=>'Treatment in Turkey','headline'=>'Start with the treatment, not the flight.','lead'=>'Our in-depth treatment pages help you ask the right questions, understand responsibility and plan travel only after the treating provider confirms an individual plan.','priority'=>'In-depth treatment guides','library'=>'All specialties','library_lead'=>'The wider library remains available for orientation. In-depth guides go further on planning, provider questions, travel and follow-up.','plan'=>'Start a treatment enquiry'],
    };
@endphp
@section('title', $title)
@section('description', $description)
@section('robots', 'index,follow,max-image-preview:large,max-snippet:-1')

@section('content')
<section class="v109-split-hero">
    <div class="v104-container v109-split-grid">
        <div class="v109-split-copy">
            <p class="v104-kicker">{{ $copy['kicker'] }}</p>
            <h1>{{ $copy['headline'] }}</h1>
            <p>{{ $copy['lead'] }}</p>
            <div class="v104-actions"><a class="v104-btn v104-btn-dark" href="#priority">{{ $copy['priority'] }} ↓</a><a class="v104-btn v104-btn-plain" href="{{ route('treatment-plan') }}">{{ $copy['plan'] }} ↗</a></div>
            <div class="v109-stat-row"><span>20 {{ __('site.in_depth_guides') }}</span><span>10 {{ __('site.specialty_pathways') }}</span><span>EN · DE · AR</span></div>
        </div>
        <figure class="v109-human-card"><img src="{{ asset('assets/img/v109/hero-patient.webp') }}" alt="International patient journey in Turkey" fetchpriority="high"><figcaption><small>{{ __('site.decision_snapshot') }}</small><strong>{{ __('site.what_good_plan') }}</strong><p>{{ __('site.what_good_plan_lead') }}</p></figcaption></figure>
    </div>
</section>

<section class="v104-section" id="priority">
    <div class="v104-container">
        <div class="v109-section-head"><div><p class="v104-kicker">{{ __('site.most_requested') }}</p><h2>{{ $copy['priority'] }}</h2></div><div><p>{{ __('site.compare_lead') }}</p></div></div>
        <div class="v109-priority-grid">
            @foreach($priority as $procedure)
                <a class="v109-priority-card" href="{{ route('procedures.show', [$procedure->specialty, $procedure]) }}">
                    <img src="{{ procedure_visual_url($procedure) }}" alt="{{ $procedure->name }}" loading="lazy" decoding="async">
                    <div><small>{{ $procedure->specialty?->name }}</small><h3>{{ $procedure->name }}</h3><p>{{ procedure_card_summary($procedure) }}</p><b>{{ __('site.view_guide') }} →</b></div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="v104-section" style="background:#f6f8f7">
    <div class="v104-container">
        <div class="v109-section-head"><div><p class="v104-kicker">{{ __('site.treatments') }}</p><h2>{{ $copy['library'] }}</h2></div><div><p>{{ $copy['library_lead'] }}</p></div></div>
        <div class="v109-specialty-list">
            @foreach($specialties as $specialty)
                <a href="{{ route('treatments.show', $specialty) }}"><img src="{{ specialty_visual_url($specialty) }}" alt="{{ $specialty->name }}" loading="lazy"><div><h3>{{ $specialty->name }}</h3><p>{{ $specialty->summary }}</p></div><b>{{ $locale === 'en' ? $specialty->procedures_count.' topics' : __('site.explore_short') }} →</b></a>
            @endforeach
        </div>
    </div>
</section>

<section class="v109-band"><div class="v104-container v109-band-grid"><div><p class="v104-kicker light">{{ __('site.proof_kicker') }}</p><h2>{{ __('site.proof_title') }}</h2><p>{{ __('site.decision_text') }}</p><div class="v104-actions"><a class="v104-btn cn-btn-light" href="{{ route('how-it-works') }}">{{ __('site.how_it_works') }} →</a><a class="cn-final-link" href="{{ route('treatment-plan') }}">{{ $copy['plan'] }}</a></div></div><img src="{{ asset('assets/img/v109/coordinator.webp') }}" alt="Patient and clinician reviewing a treatment plan" loading="lazy"></div></section>
@endsection
