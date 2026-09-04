@extends('layouts.site')

@section('title', $story->name.' | '.__('site.sample_profile').' | Turkelite Medcare')
@section('description', $story->summary)
@section('robots', 'noindex,follow')

@php
    $blocks = preg_split('/\R{2,}/u', trim((string) $story->content)) ?: [];
    $sections = [];
    $current = ['title' => __('site.journey'), 'blocks' => []];
    foreach ($blocks as $block) {
        $block = trim($block);
        if ($block === '') continue;
        $isHeading = !str_starts_with($block, '- ') && mb_strlen($block) < 85 && !preg_match('/[.!?]$/u', $block);
        if ($isHeading) {
            if ($current['blocks'] !== []) $sections[] = $current;
            $current = ['title' => $block, 'blocks' => []];
        } else {
            $current['blocks'][] = $block;
        }
    }
    if ($current['blocks'] !== [] || $sections === []) $sections[] = $current;
@endphp

@section('content')
<section class="v109-guide-hero">
    <div class="v104-container v109-guide-grid">
        <div>
            <p class="v104-kicker">{{ __('site.sample_profile') }}</p>
            <h1>{{ $story->name }}</h1>
            <p>{{ $story->summary }}</p>
            <div class="v109-integrity-alert" style="border-color:#cfe2df;background:#edf7f5;color:#42686d">{{ __('site.presentation_notice') }} {{ __('site.profile_not_endorsement') }}</div>
        </div>
        <figure class="v109-human-card">
            <img src="{{ story_visual_url($story) }}" alt="" fetchpriority="high">
            <figcaption><small>{{ __('site.patient_stories') }}</small><strong>{{ __('site.journey_title') }}</strong><p>{{ __('site.what_we_do_lead') }}</p></figcaption>
        </figure>
    </div>
</section>

<section class="v108-procedure-article">
    <div class="v104-container v108-article-layout">
        <aside class="v108-article-rail">
            <span class="v104-kicker">{{ __('site.journey') }}</span>
            @foreach($sections as $section)
                <a href="#story-section-{{ $loop->iteration }}"><span>{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $section['title'] }}</a>
            @endforeach
            <a class="v108-rail-cta" href="{{ route('treatment-plan') }}">{{ __('site.plan') }} →</a>
        </aside>
        <article class="v108-article-copy">
            @foreach($sections as $section)
                <section id="story-section-{{ $loop->iteration }}">
                    <span class="v108-section-number">{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }}</span>
                    <h2>{{ $section['title'] }}</h2>
                    @foreach($section['blocks'] as $block)
                        @if(str_starts_with($block,'- '))
                            <ul>
                                @foreach(preg_split('/\R/u',$block) as $line)
                                    <li>{{ ltrim(trim($line),'- ') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ $block }}</p>
                        @endif
                    @endforeach
                </section>
            @endforeach
        </article>
    </div>
</section>
@endsection
