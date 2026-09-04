@extends('layouts.site')
@section('title', $guide->seo_title ?: ($guide->name.' | Guides | Turkelite Medcare'))
@section('description', $guide->seo_description ?: $guide->summary)
@section('keywords', $guide->seo_keywords)
@section('robots', $guide->seo_robots ?: 'index,follow')
@section('og_image', guide_visual_url($guide))

@php
    $blocks = preg_split('/\R{2,}/u', trim((string) $guide->content)) ?: [];
    $sections = [];
    $current = ['title' => 'Guide', 'blocks' => []];
    foreach ($blocks as $block) {
        $block = trim($block);
        if ($block === '' || str_starts_with($block, 'Imported from static HTML:')) continue;
        $isHeading = !str_starts_with($block, '- ') && mb_strlen($block) <= 80 && !preg_match('/[.!?]$/u', $block);
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
    <div class="v104-container">
        <nav class="v104-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ site_ui('home') }}</a><span>/</span>
            <a href="{{ route('guides.index') }}">Guides</a><span>/</span><b>{{ $guide->name }}</b>
        </nav>
        <div class="v109-guide-grid">
            <div>
                <p class="v104-kicker">Decision article</p>
                <h1>{{ $guide->name }}</h1>
                <p>{{ $guide->summary }}</p>
                <div class="v109-stat-row"><span>Educational guidance</span><span>Not individual medical advice</span></div>
            </div>
            <figure class="v109-human-card">
                <img src="{{ guide_visual_url($guide) }}" alt="" fetchpriority="high">
                <figcaption><small>Use this article to prepare</small><strong>Better questions before booking</strong><p>Use the checklist to structure provider questions, clarify responsibilities and plan the practical next step.</p></figcaption>
            </figure>
        </div>
    </div>
</section>

<section class="v108-procedure-article">
    <div class="v104-container v108-article-layout">
        <aside class="v108-article-rail">
            <span class="v104-kicker">Guide</span>
            @foreach($sections as $section)
                <a href="#guide-section-{{ $loop->iteration }}"><span>{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }}</span>{{ $section['title'] }}</a>
            @endforeach
            <a class="v108-rail-cta" href="{{ route('treatment-plan') }}">Start enquiry →</a>
        </aside>
        <article class="v108-article-copy">
            <div class="v104-medical-note"><strong>This content supports discussion with qualified professionals and does not replace individual medical or legal advice.</strong></div>
            @foreach($sections as $section)
                <section id="guide-section-{{ $loop->iteration }}">
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

<section class="v109-band">
    <div class="v104-container v109-band-grid">
        <div><p class="v104-kicker light">Next step</p><h2>Move from reading to a structured enquiry.</h2><p>Tell us what treatment you are considering and what still needs clarification. We organise the practical next steps and provider communication; the treating provider remains responsible for assessment and treatment.</p><a class="v104-btn cn-btn-light" href="{{ route('treatment-plan') }}">Build treatment enquiry →</a></div>
        <img src="{{ asset('assets/img/v109/coordinator.webp') }}" alt="Patient coordinator" loading="lazy">
    </div>
</section>
@endsection
