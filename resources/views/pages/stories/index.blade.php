@extends('layouts.site')
@section('title', __('site.patient_stories').' | Turkelite Medcare')
@section('description', __('site.presentation_notice'))
@section('robots', 'noindex,follow')
@section('content')
@php
$storyIntro = match(app()->getLocale()) {
    'de' => 'Diese Beispiele zeigen den Informationsfluss, den später reale und freigegebene Patientengeschichten erklären sollen: Vorbereitung, Anbieterprüfung, Reise, Behandlung und Nachsorgeübergabe.',
    'ar' => 'توضح هذه الأمثلة مسار المعلومات الذي ستشرحه لاحقاً قصص مرضى حقيقية وبموافقتهم: التحضير، مراجعة مقدم الرعاية، السفر، العلاج وتسليم المتابعة بعد العودة.',
    default => 'These examples show the information flow that real, consented patient journeys should explain later: preparation, provider review, travel, treatment and the return-home handover.',
};
@endphp
<section class="v109-split-hero"><div class="v104-container v109-split-grid"><div class="v109-split-copy"><p class="v104-kicker">{{ __('site.patient_stories') }}</p><h1>{{ __('site.journey_title') }}</h1><p>{{ __('site.presentation_notice') }} {{ $storyIntro }}</p></div><figure class="v109-human-card"><img src="{{ asset('assets/img/v109/hero-patient.webp') }}" alt="International patient journey in Turkey" fetchpriority="high"><figcaption><small>{{ __('site.sample_profile') }}</small><strong>{{ __('site.patient_stories') }}</strong><p>{{ __('site.profile_not_endorsement') }}</p></figcaption></figure></div></section>
<section class="v104-section"><div class="v104-container"><div class="v109-section-head"><div><p class="v104-kicker">{{ __('site.sample_profile') }}</p><h2>{{ __('site.patient_stories') }}</h2></div><div><p>{{ __('site.presentation_notice') }}</p></div></div><div class="v109-story-grid">@forelse($stories as $story)<a class="v109-story-card" href="{{ route('stories.show',$story) }}"><img src="{{ story_visual_url($story) }}" alt="" loading="lazy"><div><small>{{ __('site.sample_profile') }}</small><h3>{{ $story->name }}</h3><p>{{ $story->summary }}</p></div></a>@empty<div class="v104-empty">{{ __('site.presentation_notice') }}</div>@endforelse</div></div></section>
@endsection
