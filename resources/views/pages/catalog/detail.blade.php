@extends('layouts.site')

@php
    $locale = app()->getLocale();
    $translation = $item->translation($locale);
    $seoTitle = $locale === 'en' ? $item->seo_title : data_get($translation, 'seo_title');
    $seoDescription = $locale === 'en' ? $item->seo_description : data_get($translation, 'seo_description');
    $priority = is_priority_procedure($item->slug);
    $robots = $priority ? 'index,follow,max-image-preview:large,max-snippet:-1' : ($item->seo_robots ?: 'noindex,follow');
    $heroImage = procedure_visual_url($item);
    $guidance = procedure_guidance($item);
    $snapshot = procedure_snapshot($item);
    $blocks = preg_split('/\R{2,}/u', trim((string)$item->content)) ?: [];
    $sections = [];
    $current = ['title' => __('site.procedure_overview'), 'blocks' => []];
    foreach ($blocks as $block) {
        $block = trim($block);
        if ($block === '') continue;
        $isHeading = !str_starts_with($block, '- ') && mb_strlen($block) < 115 && !str_contains($block, '.');
        if ($isHeading) {
            if ($current['blocks'] !== []) $sections[] = $current;
            $current = ['title' => $block, 'blocks' => []];
        } else {
            $current['blocks'][] = $block;
        }
    }
    if ($current['blocks'] !== [] || $sections === []) $sections[] = $current;

    $copy = match($locale) {
        'de' => [
            'overview'=>'Überblick','why'=>'Warum diese Behandlung erwogen wird','suitability'=>'Eignung & Planung','how'=>'Wie der Ablauf geplant wird','timeline'=>'Vorher → Behandlung → Erholung → Rückkehr','recovery'=>'Erholung & Ergebnis','risks'=>'Wichtige Aspekte','journey'=>'Ihre Reise mit Turkelite','clinic'=>'Verfügbare Partnerklinik','questions'=>'Fragen vor Ihrer Entscheidung','details'=>'Vertiefender Behandlungsleitfaden','alternatives'=>'Verwandte Behandlungen',
            'whyText'=>'Der Ausgangspunkt ist nicht die Technik, sondern das Problem oder Ziel, das Sie mit einem qualifizierten Behandler klären möchten. Die Behandlung sollte erst nach einer individuellen Beurteilung bestätigt werden.',
            'suitText'=>'Eignung hängt von Befund, Vorgeschichte, Anatomie, Erwartungen und möglichen Alternativen ab. Die behandelnde Klinik legt fest, welche Untersuchungen oder Unterlagen vor einer verbindlichen Planung nötig sind.',
            'howText'=>'Die technische Durchführung variiert nach Methode, Befund und Klinik. Lassen Sie sich den konkreten Ablauf, die verantwortlichen Behandler und mögliche Planänderungen nach der persönlichen Untersuchung erklären.',
            'recoveryText'=>'Frühe Erholung und endgültiges Ergebnis sind zwei verschiedene Zeitpunkte. Klären Sie Beschwerden der ersten Tage, Rückkehr in Alltag oder Arbeit, Reisefähigkeit und den Zeitpunkt der sinnvollen Ergebniskontrolle.',
            'riskText'=>'Risiken, Grenzen und Warnzeichen gehören in eine gute Aufklärung. Fragen Sie, welche Beschwerden erwartet werden können, was nicht normal ist und welche medizinische Hilfe nach Ihrer Rückkehr verfügbar sein muss.',
            'journeyText'=>'Sobald der medizinische Plan und die Termine bestätigt sind, organisiert Turkelite Medcare die praktische Reise darum herum: Informationsfluss, Flugplanung, Abholung, Transfers, Unterkunft, Kliniktermine, Rückreise und Übergabe der Nachsorge.',
            'provider'=>'Die behandelnde Klinik übernimmt','turkelite'=>'Turkelite Medcare übernimmt','providerItems'=>['Medizinische Beurteilung und Diagnose','Aufklärung und Einwilligung','Behandlung und klinische Entscheidungen','Medizinische Nachsorge und Notfallanweisungen'],'turkeliteItems'=>['Koordination von Unterlagen und Terminen','Reise-, Transfer- und Unterkunftsplanung','Ein zentraler praktischer Ansprechpartner','Organisation der Rückkehr und Nachsorgeübergabe'],
            'timelineItems'=>[['Vor der Reise','Unterlagen prüfen, Behandler und Plan bestätigen, Reise erst danach fixieren.'],['Behandlungstag','Anmeldung, letzte klinische Prüfung, Behandlung und unmittelbare Überwachung nach Klinikprotokoll.'],['Frühe Erholung','Medikamente, Wund- oder Symptomkontrolle, Essen/Trinken/Bewegung nach Anweisung.'],['Rückkehr & Nachsorge','Reisefähigkeit bestätigen, Unterlagen mitnehmen und klaren Kontaktweg für zuhause haben.']],
            'questionItems'=>['Warum ist diese Behandlung in meinem Fall sinnvoll und welche Alternativen gibt es?','Wer beurteilt mich persönlich und wer führt die wesentlichen Behandlungsschritte durch?','Was ist im Angebot enthalten und was kann sich nach der Untersuchung ändern?','Welche Erholungsziele müssen vor meiner Rückreise erreicht sein?','Wer ist nach meiner Rückkehr mein klinischer Ansprechpartner?'],
            'clinicLead'=>'Wir zeigen nur aktuell veröffentlichte Partnerkliniken, deren Behandlungsbereich zu dieser Seite passt.','detailsText'=>'Wenn Sie tiefer einsteigen möchten, öffnen Sie den vollständigen redaktionellen Leitfaden.','noClinic'=>'Für diesen Behandlungsbereich ist derzeit keine Partnerklinik öffentlich gelistet. Wir veröffentlichen keine erfundenen Anbieterprofile.',
        ],
        'ar' => [
            'overview'=>'نظرة عامة','why'=>'لماذا قد يفكر المريض في هذا العلاج','suitability'=>'الملاءمة والتخطيط','how'=>'كيف يتم التخطيط للإجراء','timeline'=>'قبل العلاج ← يوم العلاج ← التعافي ← العودة','recovery'=>'التعافي والنتائج','risks'=>'أمور مهمة يجب معرفتها','journey'=>'رحلتك مع Turkelite','clinic'=>'العيادة الشريكة المتاحة','questions'=>'أسئلة قبل اتخاذ القرار','details'=>'الدليل التفصيلي للعلاج','alternatives'=>'علاجات مرتبطة',
            'whyText'=>'نقطة البداية ليست اسم التقنية بل المشكلة أو الهدف الذي تريد مناقشته مع طبيب مؤهل. لا ينبغي تأكيد العلاج إلا بعد تقييم فردي مناسب.',
            'suitText'=>'تعتمد الملاءمة على التشخيص والتاريخ الطبي والتشريح والتوقعات والبدائل المتاحة. تحدد العيادة المعالجة الفحوصات أو السجلات اللازمة قبل تثبيت الخطة.',
            'howText'=>'تختلف الخطوات التقنية حسب الطريقة والحالة والعيادة. اطلب شرحاً واضحاً لما سيحدث فعلياً، ومن المسؤول عن الخطوات السريرية الأساسية، وما الذي قد يغير الخطة بعد الفحص المباشر.',
            'recoveryText'=>'التعافي المبكر والنتيجة النهائية مرحلتان مختلفتان. اسأل عن الأيام الأولى والعودة إلى النشاط أو العمل وتوقيت السفر ومتى يمكن تقييم النتيجة بصورة منطقية.',
            'riskText'=>'المخاطر والحدود والعلامات التحذيرية جزء أساسي من الموافقة المستنيرة. تأكد مما يعتبر متوقعاً وما يحتاج إلى مراجعة عاجلة وما الرعاية المتاحة بعد العودة.',
            'journeyText'=>'بعد تأكيد الخطة الطبية والمواعيد، تنظم Turkelite Medcare الجوانب العملية حولها: تبادل المعلومات، توقيت الرحلة، الاستقبال، التنقلات، الإقامة، مواعيد العيادة، العودة وتسليم المتابعة.',
            'provider'=>'مسؤولية العيادة المعالجة','turkelite'=>'مسؤولية Turkelite Medcare','providerItems'=>['التقييم الطبي والتشخيص','الموافقة المستنيرة','العلاج والقرارات السريرية','المتابعة الطبية وتعليمات الطوارئ'],'turkeliteItems'=>['تنسيق السجلات والمواعيد','تنظيم السفر والتنقلات والإقامة','جهة اتصال عملية واحدة','تنظيم العودة وتسليم المتابعة'],
            'timelineItems'=>[['قبل السفر','مراجعة السجلات وتأكيد الطبيب والخطة ثم تثبيت ترتيبات السفر.'],['يوم العلاج','التسجيل والمراجعة السريرية الأخيرة والعلاج والمراقبة المباشرة حسب بروتوكول العيادة.'],['التعافي المبكر','الأدوية ومراقبة الأعراض أو الجرح والطعام والحركة حسب تعليمات الفريق المعالج.'],['العودة والمتابعة','تأكيد القدرة على السفر والاحتفاظ بالتقارير وخطة تواصل واضحة بعد العودة.']],
            'questionItems'=>['لماذا يُنصح بمناقشة هذا العلاج في حالتي وما البدائل؟','من سيقيّم حالتي شخصياً ومن سينفذ الخطوات السريرية الأساسية؟','ما الذي يشمله العرض وما الذي قد يتغير بعد الفحص؟','ما معايير التعافي المطلوبة قبل السفر للعودة؟','من سيكون جهة التواصل السريرية بعد عودتي؟'],
            'clinicLead'=>'نعرض فقط العيادات الشريكة المنشورة حالياً عندما يتوافق مجال علاجها مع هذه الصفحة.','detailsText'=>'إذا أردت تفاصيل أعمق، افتح الدليل التحريري الكامل أدناه.','noClinic'=>'لا توجد حالياً عيادة شريكة منشورة لهذا المجال. لا ننشئ ملفات مزيفة لعيادات غير حقيقية.',
        ],
        default => [
            'overview'=>'Overview','why'=>'Why patients consider this treatment','suitability'=>'Suitability & planning','how'=>'How the procedure is planned','timeline'=>'Before → treatment day → recovery → return home','recovery'=>'Recovery & results','risks'=>'Important considerations','journey'=>'Your journey with Turkelite','clinic'=>'Available partner clinic','questions'=>'Questions before you decide','details'=>'Deeper treatment guide','alternatives'=>'Related treatments',
            'whyText'=>'Start with the problem or goal, not the technique. A qualified treating clinician should confirm whether this procedure is relevant after reviewing your individual circumstances.',
            'suitText'=>'Suitability depends on diagnosis, history, anatomy, expectations and reasonable alternatives. The treating clinic decides which records, tests or examination are needed before a plan can be confirmed.',
            'howText'=>'The technical steps vary by method, findings and clinic. Ask what will actually happen, who performs the key clinical steps and what could change after the in-person assessment.',
            'recoveryText'=>'Early recovery and final outcome are different milestones. Clarify what to expect in the first days, when routine or work may resume, when travel is considered appropriate and when results can be meaningfully assessed.',
            'riskText'=>'Risks, limits and warning signs belong in informed consent. Ask what is expected, what is not, and what medical help should be available after you return home.',
            'journeyText'=>'Once the medical plan and dates are confirmed, Turkelite Medcare organises the practical journey around them: information flow, flight timing, pickup, transfers, accommodation, clinic appointments, return travel and follow-up handover.',
            'provider'=>'Your treating clinic handles','turkelite'=>'Turkelite Medcare handles','providerItems'=>['Medical assessment and diagnosis','Consent and clinical decisions','The treatment itself','Clinical follow-up and urgent-care instructions'],'turkeliteItems'=>['Records and appointment coordination','Travel, transfers and accommodation planning','One practical coordination contact','Return-home and follow-up handover'],
            'timelineItems'=>[['Before travel','Records reviewed, clinician and plan confirmed, then practical travel is finalised.'],['Treatment day','Arrival, final clinical checks, treatment and immediate monitoring under the clinic protocol.'],['Early recovery','Medication, wound or symptom care, food, fluids and movement according to clinical instructions.'],['Return & follow-up','Confirm fitness to travel, take records home and know exactly who to contact after return.']],
            'questionItems'=>['Why is this treatment being considered for me, and what are the reasonable alternatives?','Who assesses me personally, and who performs the key clinical steps?','What is included in the quote, and what may change after examination?','What recovery milestones should I reach before return travel?','Who is my clinical contact after I return home?'],
            'clinicLead'=>'We show only currently published partner clinics when their treatment scope matches this page.','detailsText'=>'For deeper detail, open the full editorial treatment guide below.','noClinic'=>'No partner clinic is currently published for this treatment area. We do not invent clinic profiles.',
        ],
    };
@endphp

@section('title', $seoTitle ?: ($item->name.' | Turkelite Medcare'))
@section('description', $seoDescription ?: $item->summary)
@section('robots', $robots)
@section('og_image', $heroImage)

@push('head')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type'=>'ListItem','position'=>1,'name'=>site_ui('home'),'item'=>route('home')],
        ['@type'=>'ListItem','position'=>2,'name'=>__('site.treatments'),'item'=>route('treatments.index')],
        ['@type'=>'ListItem','position'=>3,'name'=>$specialty->name,'item'=>route('treatments.show',['specialty'=>$specialty])],
        ['@type'=>'ListItem','position'=>4,'name'=>$item->name,'item'=>localized_route_url($locale)],
    ],
], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
<section class="final-procedure-hero">
  <div class="v104-container">
    <nav class="v104-breadcrumb" aria-label="Breadcrumb"><a href="{{ route('treatments.index') }}">{{ __('site.treatments') }}</a><span>/</span><a href="{{ route('treatments.show',['specialty'=>$specialty]) }}">{{ $specialty->name }}</a><span>/</span><b>{{ $item->name }}</b></nav>
    <div class="final-procedure-hero-grid">
      <div>
        <span class="v104-priority-pill">{{ $priority ? __('site.priority_badge') : __('site.library_badge') }}</span>
        <h1>{{ $item->name }}</h1>
        <p class="final-lead small">{{ $item->summary }}</p>
        <div class="v104-actions"><a class="v104-btn cn-btn-primary" href="{{ route('treatment-plan',['specialty'=>$specialty->slug,'procedure'=>$item->name]) }}">{{ site_ui('plan_journey') }} →</a><a class="v104-btn cn-btn-secondary" href="#overview">{{ $copy['overview'] }} ↓</a></div>
        <p class="v108-no-pressure">{{ __('site.no_pressure') }}</p>
      </div>
      <figure class="final-procedure-visual"><img src="{{ $heroImage }}" alt="{{ $item->name }}" fetchpriority="high"><figcaption><strong>{{ $copy['overview'] }}</strong><span>{{ __('site.evidence_not_marketing') }}</span></figcaption></figure>
    </div>
  </div>
</section>

<section class="final-procedure-snapshot" aria-label="Procedure snapshot"><div class="v104-container"><div class="final-procedure-snapshot-grid">@foreach($snapshot as $metric)<div><span>{{ $metric['label'] }}</span><strong>{{ $metric['value'] }}</strong></div>@endforeach</div></div></section>

<nav class="procedure-jumpbar" aria-label="On this page"><div class="v104-container procedure-jumpbar-inner">
  <a href="#overview">{{ $copy['overview'] }}</a><a href="#suitability">{{ $copy['suitability'] }}</a><a href="#how-it-works">{{ $copy['how'] }}</a><a href="#timeline">{{ $copy['timeline'] }}</a><a href="#recovery">{{ $copy['recovery'] }}</a><a href="#considerations">{{ $copy['risks'] }}</a><a href="#journey">{{ $copy['journey'] }}</a><a href="#questions">{{ $copy['questions'] }}</a>
</div></nav>

<section class="procedure-decision-section" id="overview"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">01 · {{ $copy['why'] }}</p><h2>{{ $copy['why'] }}</h2><p>{{ $copy['whyText'] }}</p></div><div class="decision-cards">@foreach(array_slice($guidance,0,2) as $row)<article><span>0{{ $loop->iteration }}</span><h3>{{ $row['title'] }}</h3><p>{{ $row['text'] }}</p></article>@endforeach</div></div></section>

<section class="procedure-decision-section alt" id="suitability"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">02 · {{ $copy['suitability'] }}</p><h2>{{ $copy['suitability'] }}</h2><p>{{ $copy['suitText'] }}</p></div><div class="decision-cards">@foreach($guidance as $row)<article><span>0{{ $loop->iteration }}</span><h3>{{ $row['title'] }}</h3><p>{{ $row['text'] }}</p></article>@endforeach</div></div></section>

<section class="procedure-decision-section" id="how-it-works"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">03 · {{ $copy['how'] }}</p><h2>{{ $copy['how'] }}</h2><p>{{ $copy['howText'] }}</p></div><figure class="final-procedure-visual"><img src="{{ $heroImage }}" alt="{{ $item->name }} procedure visual" loading="lazy"></figure></div></section>

<section class="procedure-decision-section alt" id="timeline"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">04</p><h2>{{ $copy['timeline'] }}</h2></div></div><div class="procedure-timeline">@foreach($copy['timelineItems'] as $step)<article><span class="step">0{{ $loop->iteration }}</span><h3>{{ $step[0] }}</h3><p>{{ $step[1] }}</p></article>@endforeach</div></div></section>

<section class="procedure-decision-section" id="recovery"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">05 · {{ $copy['recovery'] }}</p><h2>{{ $copy['recovery'] }}</h2><p>{{ $copy['recoveryText'] }}</p></div><div class="decision-cards">@foreach(array_slice($snapshot,3,3) as $metric)<article><span>{{ $metric['label'] }}</span><h3>{{ $metric['value'] }}</h3><p>{{ $locale === 'de' ? 'Patientenspezifische Zeiten bestätigt die behandelnde Klinik.' : ($locale === 'ar' ? 'تؤكد العيادة المعالجة التوقيت المناسب لحالتك.' : 'Your treating clinic confirms patient-specific timing.') }}</p></article>@endforeach</div></div></section>

<section class="procedure-decision-section alt" id="considerations"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">06 · {{ $copy['risks'] }}</p><h2>{{ $copy['risks'] }}</h2><p>{{ $copy['riskText'] }}</p></div><div class="decision-cards"><article><span>01</span><h3>{{ __('site.clinical_disclaimer') }}</h3><p>{{ $locale === 'de' ? 'Diese Seite unterstützt Ihre Fragen, ersetzt aber keine persönliche medizinische Beurteilung.' : ($locale === 'ar' ? 'تساعدك هذه الصفحة على طرح أسئلة أفضل ولا تستبدل التقييم الطبي الفردي.' : 'This page supports better questions; it does not replace individual medical assessment.') }}</p></article><article><span>02</span><h3>{{ $locale === 'de' ? 'Planänderungen' : ($locale === 'ar' ? 'تغييرات الخطة' : 'Plan changes') }}</h3><p>{{ $locale === 'de' ? 'Befund und Untersuchung können Methode, Termine, Kosten oder Aufenthaltsdauer verändern.' : ($locale === 'ar' ? 'قد يغيّر الفحص الطريقة أو المواعيد أو التكلفة أو مدة الإقامة.' : 'Assessment can change method, dates, cost or length of stay.') }}</p></article></div></div></section>

<section class="procedure-decision-section" id="journey"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">07 · {{ $copy['journey'] }}</p><h2>{{ $copy['journey'] }}</h2></div><p>{{ $copy['journeyText'] }}</p></div><div class="responsibility-grid"><article><h3>{{ $copy['provider'] }}</h3><ul>@foreach($copy['providerItems'] as $x)<li>{{ $x }}</li>@endforeach</ul></article><article><h3>{{ $copy['turkelite'] }}</h3><ul>@foreach($copy['turkeliteItems'] as $x)<li>{{ $x }}</li>@endforeach</ul></article></div></div></section>

<section class="procedure-decision-section alt" id="questions"><div class="v104-container procedure-decision-grid"><div><p class="v104-kicker">08 · {{ $copy['questions'] }}</p><h2>{{ $copy['questions'] }}</h2><p>{{ $locale === 'de' ? 'Nehmen Sie diese Fragen in die Beratung mit. Gute Antworten sind konkreter als Marketingversprechen.' : ($locale === 'ar' ? 'خذ هذه الأسئلة معك إلى الاستشارة. الإجابات الجيدة تكون أكثر تحديداً من الوعود التسويقية.' : 'Take these questions into the consultation. Good answers are more useful than marketing promises.') }}</p></div><div class="decision-cards">@foreach($copy['questionItems'] as $q)<article><span>0{{ $loop->iteration }}</span><h3>{{ $q }}</h3></article>@endforeach</div></div></section>

@if(isset($clinics) && $clinics->isNotEmpty())
<section class="v104-section final-procedure-clinics" id="clinic"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">09 · {{ $copy['clinic'] }}</p><h2>{{ $copy['clinic'] }}</h2></div><p>{{ $copy['clinicLead'] }}</p></div><div class="final-clinic-grid">@foreach($clinics as $clinic)@php($meta=clinic_demo_meta($clinic))<article class="final-clinic-card compact"><a class="final-clinic-image" href="{{ route('clinics.show',$clinic) }}"><img src="{{ clinic_visual_url($clinic,0) }}" alt="Representative clinic environment" loading="lazy"><span>{{ clinic_live_ui('partner') }}</span></a><div class="final-clinic-body"><div class="final-clinic-title"><div><small>{{ $clinic->location }}</small><h3>{{ $clinic->name }}</h3></div></div><p>{{ $clinic->summary }}</p><div class="final-tags">@foreach($meta['strengths'] as $tag)<span>{{ $tag }}</span>@endforeach</div><div class="final-clinic-links"><a href="{{ route('clinics.show',$clinic) }}">{{ __('site.view_profile') }} →</a><a href="{{ route('treatment-plan',['specialty'=>$specialty->slug,'procedure'=>$item->name]) }}">{{ site_ui('plan_journey') }} →</a></div></div></article>@endforeach</div></div></section>
@endif

<section class="procedure-decision-section procedure-deeper"><div class="v104-container"><details><summary>{{ $copy['details'] }}</summary><div class="v108-article-copy final-article-copy">@foreach($sections as $section)<section><h2>{{ $section['title'] }}</h2>@foreach($section['blocks'] as $block)@if(str_starts_with($block,'- '))<ul>@foreach(preg_split('/\R/u',$block) as $line)<li>{{ ltrim(trim($line),'- ') }}</li>@endforeach</ul>@else<p>{{ $block }}</p>@endif @endforeach</section>@endforeach</div></details></div></section>

@if(isset($decisionGuides) && $decisionGuides->isNotEmpty())<section class="v104-section final-guides"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">{{ site_footer('guides') }}</p><h2>{{ $locale === 'de' ? 'Vor der Entscheidung lesen' : ($locale === 'ar' ? 'اقرأ قبل اتخاذ القرار' : 'Read before you decide') }}</h2></div></div><div class="final-guide-grid">@foreach($decisionGuides as $guide)<a href="{{ route('guides.show',$guide) }}"><img src="{{ guide_visual_url($guide) }}" alt="" loading="lazy"><div><small>{{ site_footer('guides') }}</small><h3>{{ $guide->name }}</h3><p>{{ $guide->summary }}</p><b>{{ __('site.view_guide') }} →</b></div></a>@endforeach</div></div></section>@endif

@if($relatedProcedures->isNotEmpty())<section class="v104-section final-related"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">{{ $copy['alternatives'] }}</p><h2>{{ $specialty->name }}</h2></div></div><div class="final-related-grid">@foreach($relatedProcedures as $related)<a href="{{ route('procedures.show',['specialty'=>$specialty,'procedure'=>$related]) }}"><img src="{{ procedure_visual_url($related) }}" alt="{{ $related->name }}" loading="lazy"><div><small>{{ is_priority_procedure($related->slug)?__('site.priority_badge'):__('site.library_badge') }}</small><h3>{{ $related->name }}</h3><p>{{ procedure_card_summary($related) }}</p><b>{{ __('site.view_guide') }} →</b></div></a>@endforeach</div></div></section>@endif

<section class="cn-final"><div class="v104-container cn-final-inner"><div><span>{{ __('site.next_step') }}</span><h2>{{ site_ui('plan_journey') }}</h2><p>{{ __('site.request_review_text') }}</p><small>{{ __('site.no_obligation') }}</small></div><a class="v104-btn cn-btn-light" href="{{ route('treatment-plan',['specialty'=>$specialty->slug,'procedure'=>$item->name]) }}">{{ site_ui('plan_journey') }} →</a></div></section>
@endsection
