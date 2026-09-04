@extends('layouts.site')

@section('title', $document->seo_title ?: $document->title.' | '.website_setting('brand.name'))
@section('description', $document->seo_description ?: $document->summary)
@section('keywords', $document->seo_keywords)
@section('robots', 'noindex,nofollow')

@section('content')
    @php
        $locale = app()->getLocale();
        $ui = match ($locale) {
            'de' => [
                'home' => 'Startseite', 'legal' => 'Rechtliches & Datenschutz', 'kicker' => 'Vertrauen & Compliance',
                'draft' => 'Dieser Rechtstext wird derzeit rechtlich geprüft und sprachlich freigegeben. Die endgültige Fassung wird erst nach Freigabe veröffentlicht.',
                'label' => 'Entwurf – rechtliche Prüfung ausstehend',
                'body' => 'Für die Präsentationsversion zeigen wir bewusst keine ungeprüfte Übersetzung als verbindlichen Rechtstext. Die englische Arbeitsfassung bleibt intern erhalten; vor dem öffentlichen Start werden die länderspezifischen Fassungen durch geeignete Rechtsberatung geprüft.',
                'back' => 'Zur Übersicht Rechtliches',
            ],
            'ar' => [
                'home' => 'الرئيسية', 'legal' => 'القانون والخصوصية', 'kicker' => 'الثقة والامتثال',
                'draft' => 'يخضع هذا المستند حالياً للمراجعة القانونية واللغوية. لن تُنشر النسخة النهائية قبل اعتمادها.',
                'label' => 'مسودة — بانتظار المراجعة القانونية',
                'body' => 'في نسخة العرض لا نعرض ترجمة قانونية غير مراجعة وكأنها نص نهائي ملزم. تبقى مسودة العمل الإنجليزية محفوظة داخلياً، وتُراجع النسخ الخاصة بكل سوق قانونياً قبل الإطلاق العام.',
                'back' => 'العودة إلى صفحة القانون والخصوصية',
            ],
            default => [
                'home' => 'Home', 'legal' => 'Legal & Privacy', 'kicker' => 'Trust & compliance',
                'draft' => 'Draft prepared for legal review. Do not publish until reviewed and signed off.',
                'label' => 'Draft — legal review pending',
                'body' => 'The working draft is intentionally withheld from the customer-facing presentation until qualified counsel completes the operating-entity details, jurisdiction-specific disclosures and final sign-off. The structured draft remains in the content system for legal review.',
                'back' => 'Back to Legal & Privacy',
            ],
        };
    @endphp
    <section class="page-hero">
        <div class="container page-hero-grid">
            <div>
                <x-breadcrumbs :items="[['label' => $ui['home'], 'url' => route('home')], ['label' => $ui['legal'], 'url' => route('legal')], ['label' => $document->title]]" />
                <span class="eyebrow">{{ $ui['kicker'] }}</span>
                <h1>{{ $document->title }}</h1>
                <p>{{ $document->summary ?: $ui['draft'] }}</p>
            </div>
        </div>
    </section>
    <section class="template-section">
        <div class="container legal-doc rich-content">
            <div class="notice demo"><b>{{ $ui['label'] }}</b><p>{{ $ui['draft'] }}</p></div>
            <p>{{ $ui['body'] }}</p>
            <p><a class="btn btn-secondary" href="{{ route('legal') }}">{{ $ui['back'] }}</a></p>
        </div>
    </section>
@endsection
