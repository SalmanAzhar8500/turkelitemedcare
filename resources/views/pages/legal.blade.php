@extends('layouts.site')

@section('title', $page->seo_title ?: data_get($page, 'title', 'Legal & Privacy | '.website_setting('brand.name')))
@section('description', $page->seo_description ?: data_get($page, 'description', 'Legal, privacy and compliance information for '.website_setting('brand.name').', including medical responsibility, privacy and complaints guidance.'))

@section('robots', 'noindex,nofollow')

@section('content')
    @php($content = $page->content ?? [])
    @php($legalUi = data_get($content, 'ui', []))
    @php($isGerman = str_starts_with(strtolower((string) app()->getLocale()), 'de'))
    <section class="page-hero">
        <div class="container page-hero-grid">
            <div>
                <x-breadcrumbs :items="[['label' => site_ui('home'), 'url' => route('home')], ['label' => data_get($content, 'hero.headline', 'Legal & Privacy')]]" />
                <span class="eyebrow">{{ data_get($content, 'hero.kicker') }}</span>
                <h1>{{ data_get($content, 'hero.headline') }}</h1>
                <p>{{ data_get($content, 'hero.lead') }}</p>
            </div>
            <div class="hero-art small" aria-hidden="true">
                <div class="art-orbit"></div>
                <div class="art-card">
                    <span>{{ data_get($content, 'hero_card.label') }}</span>
                    <strong>{{ data_get($content, 'hero_card.heading') }}</strong>
                    <small>{{ data_get($content, 'hero_card.text') }}</small>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container content-layout">
            <article class="article legal-doc">
                <div class="notice demo">
                    <b>{{ data_get($legalUi, 'important_notice_label', 'Important notice') }}</b>
                    <p>{{ data_get($content, 'notice') }}</p>
                </div>

                <h2>{{ data_get($content, 'documents.heading') }}</h2>
                <p>{{ data_get($content, 'documents.intro') }}</p>

                <div class="legal-index-grid">
                    @foreach($documents as $document)
                        <a class="legal-index-link" href="{{ route('legal.documents.show', $document) }}">
                            <b>{{ $document->title }}</b>
                            <span>{{ $document->summary ?: data_get($legalUi, 'draft_label', 'Draft - pending legal review') }}</span>
                        </a>
                    @endforeach
                </div>
                <p class="legal-flag">{{ data_get($legalUi, 'legal_flag', '') }}</p>

                <h2>{{ data_get($content, 'role_clarity.heading') }}</h2>
                @foreach(data_get($content, 'role_clarity.paragraphs', []) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </article>

            <aside class="sidebar-card sticky">
                <span class="eyebrow">{{ data_get($content, 'sidebar.kicker') }}</span>
                <h3>{{ data_get($content, 'sidebar.heading') }}</h3>
                <p>{{ data_get($content, 'sidebar.text') }}</p>
                <a class="btn btn-primary" href="{{ route('contact') }}">{{ data_get($content, 'sidebar.button') }}</a>
                <hr>
                <small>{{ data_get($legalUi, 'urgent_notice', '') }}</small>
            </aside>
        </div>
    </section>
@endsection
