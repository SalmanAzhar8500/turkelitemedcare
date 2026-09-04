@extends('layouts.site')
@php
    $locale = app()->getLocale();
    $translation = $specialty->translation($locale);
    $seoTitle = $locale === 'en' ? $specialty->seo_title : data_get($translation,'seo_title');
    $seoDescription = $locale === 'en' ? $specialty->seo_description : data_get($translation,'seo_description');
    $priorityProcedures = $procedures->filter(fn($procedure) => is_priority_procedure($procedure->slug))->values();
    $copy = match($locale) {
      'de'=>['overview'=>'Fachgebiet','explore'=>'Behandlungen entdecken','lead'=>'Jede Seite hilft Ihnen, den Eingriff zu verstehen, die richtigen Fragen vorzubereiten und Klinik, Reise und Nachsorge klar zu koordinieren.','consider'=>'Worauf Sie vor der Entscheidung achten sollten','integrity'=>'Diese lokale Datenbank enthält nicht den vollständigen FINAL-Inhalt. Bitte führen Sie die Datenprüfung aus.'],
      'ar'=>['overview'=>'نظرة على التخصص','explore'=>'استكشف العلاجات','lead'=>'كل صفحة تساعدك على فهم العلاج وتجهيز الأسئلة المهمة وترتيب العيادة والسفر وخطوات المتابعة بوضوح.','consider'=>'ما الذي يجب أن يكون واضحاً قبل اتخاذ القرار','integrity'=>'قاعدة البيانات المحلية لا تحتوي على محتوى FINAL الكامل. يرجى تشغيل فحص قاعدة البيانات.'],
      default=>['overview'=>'Specialty overview','explore'=>'Explore treatments','lead'=>'Each treatment page helps you understand the procedure, prepare the right questions, and keep clinic, travel and follow-up steps clearly coordinated.','consider'=>'What should be clear before you choose','integrity'=>'This local database does not contain the complete FINAL content pack. Run the database integrity check before presenting.'],
    };
@endphp
@section('title', $seoTitle ?: ($specialty->name.' | Turkelite Medcare'))
@section('description', $seoDescription ?: $specialty->description)
@section('robots', 'index,follow,max-image-preview:large,max-snippet:-1')
@section('og_image', specialty_visual_url($specialty))

@section('content')
<section class="final-specialty-hero">
  <div class="v104-container final-specialty-hero-grid">
    <div>
      <nav class="v104-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('treatments.index') }}">{{ __('site.treatments') }}</a><span>/</span><b>{{ $specialty->name }}</b></nav>
      <p class="v104-kicker">{{ $copy['overview'] }}</p>
      <h1>{{ $specialty->name }}</h1>
      <p class="final-lead small">{{ $specialty->description ?: $specialty->summary }}</p>
      <div class="v104-actions"><a class="v104-btn cn-btn-primary" href="{{ route('treatment-plan',['specialty'=>$specialty->slug]) }}">{{ __('site.plan') }} →</a><a class="v104-btn cn-btn-secondary" href="#procedures">{{ $copy['explore'] }} ↓</a></div>
      <div class="final-specialty-facts"><span><strong>{{ $totalProcedureCount ?? $procedures->count() }}</strong>{{ __('site.treatments') }}</span><span><strong>{{ $priorityProcedures->count() }}</strong>{{ __('site.in_depth_guides') }}</span><span><strong>EN · DE · AR</strong>{{ __('site.languages_label') }}</span></div>
      @if(($totalProcedureCount ?? $procedures->count()) === 0 && app()->environment(['local','testing','staging']))<div class="v109-integrity-alert"><strong>FINAL DATA INTEGRITY</strong><br>{{ $copy['integrity'] }}<br><code>php artisan site:factory:verify-db</code></div>@endif
    </div>
    <figure class="final-specialty-visual"><img src="{{ specialty_visual_url($specialty) }}" alt="{{ $specialty->name }} treatment planning" fetchpriority="high"><figcaption><strong>{{ $specialty->name }}</strong><span>{{ $copy['lead'] }}</span></figcaption></figure>
  </div>
</section>

<section class="v104-section final-procedure-library" id="procedures">
  <div class="v104-container">
    <div class="final-section-head"><div><p class="v104-kicker">{{ __('site.treatments') }}</p><h2>{{ $copy['explore'] }}</h2></div><p>{{ $copy['lead'] }}</p></div>
    <div class="final-procedure-grid">
      @forelse($procedures as $procedure)
        <a class="final-procedure-card" href="{{ route('procedures.show',[$specialty,$procedure]) }}">
          <figure><img src="{{ procedure_visual_url($procedure) }}" alt="{{ $procedure->name }}" loading="lazy" decoding="async">@if(is_priority_procedure($procedure->slug))<span>{{ __('site.priority_badge') }}</span>@endif</figure>
          <div><small>{{ $specialty->name }}</small><h3>{{ $procedure->name }}</h3><p>{{ procedure_card_summary($procedure) }}</p><b>{{ __('site.view_guide') }} →</b></div>
        </a>
      @empty
        <div class="v104-empty">{{ __('site.no_sample_matches') }}</div>
      @endforelse
    </div>
  </div>
</section>

<section class="final-consider"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">{{ __('site.decision_snapshot') }}</p><h2>{{ $copy['consider'] }}</h2></div></div><div class="final-consider-grid">
  @foreach([
    [__('site.plan_answer_1'),__('site.plan_answer_1_text')],
    [__('site.plan_answer_2'),__('site.plan_answer_2_text')],
    [__('site.plan_answer_3'),__('site.plan_answer_3_text')],
    [__('site.plan_answer_4'),__('site.plan_answer_4_text')]
  ] as $item)<article><span>0{{ $loop->iteration }}</span><h3>{{ $item[0] }}</h3><p>{{ $item[1] }}</p></article>@endforeach
</div></div></section>

<section class="v109-band"><div class="v104-container v109-band-grid"><div><p class="v104-kicker light">{{ __('site.next_step') }}</p><h2>{{ __('site.present_cta') }}</h2><p>{{ __('site.present_cta_text') }}</p><a class="v104-btn cn-btn-light" href="{{ route('treatment-plan',['specialty'=>$specialty->slug]) }}">{{ __('site.plan') }} →</a></div><img src="{{ asset('assets/img/final/coordination.webp') }}" alt="International patient coordinator" loading="lazy"></div></section>
@endsection
