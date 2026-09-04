@extends('layouts.site')

@section('title', $page->seo_title ?: $page->title)
@section('description', $page->seo_description ?: $page->description)
@section('robots', $page->seo_robots ?: 'index,follow')

@section('content')
@php
$locale = app()->getLocale();
$copy = match($locale) {
    'de' => [
        'kicker'=>'So funktioniert es','title'=>'Erst die medizinische Entscheidung. Dann die Reise.','lead'=>'Turkelite Medcare organisiert die gesamte praktische Reise rund um die unabhängige medizinische Versorgung. Nach Bestätigung des Behandlungsplans verbinden wir Klinikkontakt, Abholung, Flüge, Unterkunft, Transfers, Termine, Rückreise und Nachsorgekoordination.',
        'principle'=>'Drei Rollen, eine klare Grenze','patient'=>'Sie entscheiden','patient_text'=>'Sie beschreiben Ihr Anliegen, stellen Fragen, vergleichen Optionen und wählen die behandelnde Klinik.','provider'=>'Die behandelnde Klinik entscheidet medizinisch','provider_text'=>'Die unabhängige Klinik oder der Arzt prüft Eignung, erklärt Risiken und Alternativen, holt die Einwilligung ein und behandelt.','us'=>'Wir organisieren die Reise','us_text'=>'Wir strukturieren Informationen, halten Kommunikation und Leistungen nachvollziehbar und organisieren die praktische Reise rund um bestätigte Termine.',
        'steps_title'=>'Von der ersten Frage bis zur Rückkehr','s1'=>'Anliegen beschreiben','s1t'=>'Starten Sie mit einem Eingriff, einem Problem oder einfach Ihrer Frage. Es besteht keine Buchungsverpflichtung.','s2'=>'Informationen strukturieren','s2t'=>'Wir ordnen die Informationen und Fragen, die ein passender Anbieter für eine erste Prüfung benötigt.','s3'=>'Wir führen die Anfrage zur klinischen Prüfung','s3t'=>'Wir leiten die Anfrage an einen geeigneten unabhängigen Anbieter weiter. Dieser entscheidet, ob weitere Unterlagen, Diagnostik oder ein persönlicher Termin nötig sind und ob ein Behandlungsplan angeboten werden kann.','s4'=>'Reise von Tür zu Tür planen','s4t'=>'Sobald die medizinischen Termine bestätigt sind, organisieren wir Abholung, Flugzeiten, Unterkunft, lokale Transfers, Kliniktermine, Rückreise und die Übergabe nach der Heimkehr rund um den Behandlungsplan.',
        'before'=>'Vor jeder Buchung sollten vier Punkte schriftlich klar sein','d1'=>'Behandlungsplan','d1t'=>'Was ist vorgeschlagen, was ist noch offen und welche Alternativen wurden besprochen?','d2'=>'Verantwortlicher Anbieter','d2t'=>'Welche Klinik und welcher behandelnde Arzt übernehmen die medizinische Verantwortung?','d3'=>'Leistungsumfang','d3t'=>'Was enthält die individuelle Klinikofferte – und was ausdrücklich nicht?','d4'=>'Nachsorge & Rückkehr','d4t'=>'Welche Kontrollen sind geplant, welche Warnzeichen gelten und wen kontaktieren Sie nach der Rückreise?',
        'rule'=>'Unsere wichtigste Planungsregel','rule_title'=>'Flug und Hotel dürfen den medizinischen Plan nicht bestimmen.','rule_text'=>'Buchen Sie irreversible Reiseleistungen erst, wenn der unabhängige Anbieter den medizinischen Ablauf und die benötigte Aufenthaltsdauer bestätigt hat. Änderungen können trotzdem erforderlich werden.',
        'cta'=>'Bereit, Ihre Fragen zu strukturieren?','cta_text'=>'Starten Sie mit dem, was Sie bereits wissen. Wir ordnen den nächsten sinnvollen Schritt – ohne Buchungsdruck.','button'=>'Behandlungsanfrage starten'
    ],
    'ar' => [
        'kicker'=>'كيف تعمل الخدمة','title'=>'القرار الطبي أولاً. ثم تُبنى الرحلة حوله.','lead'=>'تنظم Turkelite Medcare الرحلة العملية كاملة حول الرعاية الطبية المستقلة. بعد تأكيد الخطة نربط التواصل مع العيادة والاستقبال والرحلات والإقامة والتنقلات والمواعيد والعودة وتنسيق المتابعة.',
        'principle'=>'ثلاثة أدوار وحدود واضحة','patient'=>'أنت تختار','patient_text'=>'تشرح ما تحتاجه، وتطرح الأسئلة، وتقارن الخيارات وتختار مقدم الرعاية.','provider'=>'العيادة المعالجة تقرر طبياً','provider_text'=>'العيادة أو الطبيب المستقل يقيّم الملاءمة ويشرح المخاطر والبدائل ويحصل على الموافقة ويقدم العلاج.','us'=>'نحن ننظم الرحلة','us_text'=>'نرتب المعلومات ونوضح التواصل وما يشمله العرض وننظم الرحلة العملية حول المواعيد المؤكدة.',
        'steps_title'=>'من أول سؤال حتى العودة إلى المنزل','s1'=>'اشرح ما تفكر فيه','s1t'=>'ابدأ بعلاج أو مشكلة أو سؤال. إرسال الاستفسار لا يلزمك بالحجز.','s2'=>'نرتب المعلومات','s2t'=>'نرتب المعلومات والأسئلة التي يحتاجها مقدم رعاية مناسب للمراجعة الأولية.','s3'=>'نوجه الاستفسار للمراجعة السريرية','s3t'=>'نوجه الاستفسار إلى مقدم رعاية مستقل مناسب. وهو يقرر ما إذا كانت هناك حاجة إلى تقارير أو فحوص أو زيارة شخصية إضافية، وما إذا كان يمكن تقديم خطة علاج.','s4'=>'نبني رحلة من الباب إلى الباب','s4t'=>'بعد تأكيد المواعيد طبياً، ننظم الاستقبال وتوقيت الرحلات والإقامة والتنقلات المحلية وزيارات العيادة والعودة وتسليم المتابعة حول خطة العلاج.',
        'before'=>'أربعة أمور يجب أن تكون واضحة كتابةً قبل الحجز','d1'=>'خطة العلاج','d1t'=>'ما المقترح؟ وما الذي لم يُحسم بعد؟ وما البدائل التي نوقشت؟','d2'=>'مقدم الرعاية المسؤول','d2t'=>'أي عيادة وأي طبيب سيتحملان المسؤولية الطبية عن الرعاية؟','d3'=>'ما يشمله العرض','d3t'=>'ما الذي يشمله عرض العيادة الخاص بحالتك وما الذي لا يشمله بوضوح؟','d4'=>'المتابعة والعودة','d4t'=>'ما المراجعات المتوقعة؟ وما علامات التحذير؟ ومن تتواصل معه بعد العودة؟',
        'rule'=>'قاعدة التخطيط الأهم','rule_title'=>'لا تجعل الطيران أو الفندق يحددان الخطة الطبية.','rule_text'=>'لا تحجز تكاليف سفر غير قابلة للاسترداد قبل أن يؤكد مقدم الرعاية المستقل الجدول الطبي ومدة الإقامة المطلوبة. وقد تظل التغييرات ضرورية حسب الحالة.',
        'cta'=>'هل تريد ترتيب أسئلتك؟','cta_text'=>'ابدأ بما تعرفه الآن. ننظم الخطوة التالية من دون ضغط للحجز.','button'=>'ابدأ استفسار العلاج'
    ],
    default => [
        'kicker'=>'How it works','title'=>'Medical decision first. Travel second.','lead'=>'Turkelite Medcare organises the full practical journey around independent medical care. Once the treating clinic confirms the plan, we connect clinic communication, pickup, flights, accommodation, transfers, appointments, return travel and follow-up coordination.',
        'principle'=>'Three roles. One clear boundary.','patient'=>'You choose','patient_text'=>'You describe the need, ask questions, compare options and choose the treating clinic.','provider'=>'The treating clinic decides clinically','provider_text'=>'The independent clinic or doctor assesses suitability, explains risks and alternatives, obtains consent and provides treatment.','us'=>'We organise the journey','us_text'=>'We structure information, keep clinic communication and inclusions clear, and organise the practical journey around confirmed appointments.',
        'steps_title'=>'From first question to return home','s1'=>'Tell us what you are considering','s1t'=>'Start with a procedure, a concern or simply your question. Sending an enquiry does not commit you to book.','s2'=>'Structure the information','s2t'=>'We organise the information and questions a suitable clinic needs for an initial review.','s3'=>'We route the enquiry for clinical review','s3t'=>'We route the enquiry to an appropriate independent treating clinic. The treating clinic decides whether more records, diagnostics or an in-person assessment are needed and whether a treatment plan can be offered.','s4'=>'Build the door-to-door journey','s4t'=>'Once medical dates are confirmed, we organise pickup, flight timing, accommodation, local transfers, clinic visits, return travel and the return-home handover around the care plan.',
        'before'=>'Four things to settle in writing before booking','d1'=>'Treatment plan','d1t'=>'What is proposed, what is still uncertain and what alternatives have been discussed?','d2'=>'Responsible clinic & clinician','d2t'=>'Which clinic and treating doctor take responsibility for the medical care?','d3'=>'Scope of quotation','d3t'=>'What exactly is included in your individual clinic quotation, and what is explicitly excluded?','d4'=>'Follow-up & return','d4t'=>'What reviews are planned, what warning signs matter and who should you contact after returning home?',
        'rule'=>'Our most important planning rule','rule_title'=>'Flights and hotels should never dictate the medical plan.','rule_text'=>'Avoid non-refundable travel commitments until the independent treating clinic has confirmed the medical schedule and expected stay. Patient-specific changes can still become necessary.',
        'cta'=>'Ready to structure your questions?','cta_text'=>'Start with what you know today. We organise the next sensible step without booking pressure.','button'=>'Start treatment enquiry'
    ],
};
@endphp

<section class="v104-content-hero">
  <div class="v104-container v104-content-hero-grid">
    <div>
      <x-breadcrumbs :items="[['label'=>site_ui('home'),'url'=>route('home')],['label'=>site_ui('how_it_works')]]" />
      <p class="v104-kicker">{{ $copy['kicker'] }}</p>
      <h1>{{ $copy['title'] }}</h1>
      <p class="v104-lead">{{ $copy['lead'] }}</p>
      <div class="v104-actions"><a class="v104-btn v104-btn-dark" href="{{ route('treatment-plan') }}">{{ $copy['button'] }} ↗</a><a class="v104-btn v104-btn-plain" href="{{ route('treatments.index') }}">{{ site_ui('treatments') }}</a></div>
    </div>
    <aside class="v104-principle-card">
      <span>01 / {{ $copy['principle'] }}</span>
      <div><b>{{ $copy['patient'] }}</b><p>{{ $copy['patient_text'] }}</p></div>
      <div><b>{{ $copy['provider'] }}</b><p>{{ $copy['provider_text'] }}</p></div>
      <div><b>{{ $copy['us'] }}</b><p>{{ $copy['us_text'] }}</p></div>
    </aside>
  </div>
</section>

<section class="v108-media-band"><div class="v104-container"><div class="v108-media-band-inner"><img src="{{ asset('assets/img/v109/coordinator.webp') }}" alt="" loading="lazy" aria-hidden="true"><div class="v108-media-caption"><strong>{{ __('site.provider_confirms') }}</strong><p>{{ __('site.we_coordinate') }}</p></div></div></div></section>

<section class="v104-section v104-process-section"><div class="v104-container">
  <p class="v104-kicker">02 / {{ site_ui('journey') }}</p><h2 class="v104-section-title">{{ $copy['steps_title'] }}</h2>
  <div class="v104-process-grid">
    @foreach([['01',$copy['s1'],$copy['s1t']],['02',$copy['s2'],$copy['s2t']],['03',$copy['s3'],$copy['s3t']],['04',$copy['s4'],$copy['s4t']]] as $step)
      <article><b>{{ $step[0] }}</b><h3>{{ $step[1] }}</h3><p>{{ $step[2] }}</p></article>
    @endforeach
  </div>
</div></section>

<section class="v104-section v104-check-section"><div class="v104-container">
  <div class="v104-heading-row"><div><p class="v104-kicker">03 / {{ site_ui('compare_clinics') }}</p><h2>{{ $copy['before'] }}</h2></div><p class="v104-lead">{{ __('site.clinical_disclaimer') }}</p></div>
  <div class="v104-check-grid">
    @foreach([['01',$copy['d1'],$copy['d1t']],['02',$copy['d2'],$copy['d2t']],['03',$copy['d3'],$copy['d3t']],['04',$copy['d4'],$copy['d4t']]] as $item)
      <article><span>{{ $item[0] }}</span><h3>{{ $item[1] }}</h3><p>{{ $item[2] }}</p></article>
    @endforeach
  </div>
</div></section>

<section class="v104-rule-section"><div class="v104-container v104-rule-grid">
  <p class="v104-kicker light">04 / {{ $copy['rule'] }}</p><div><h2>{{ $copy['rule_title'] }}</h2><p>{{ $copy['rule_text'] }}</p></div>
</div></section>

<section class="v104-end-cta"><div class="v104-container"><div><p class="v104-kicker">{{ site_ui('next_step') }}</p><h2>{{ $copy['cta'] }}</h2><p>{{ $copy['cta_text'] }}</p></div><a class="v104-btn v104-btn-dark" href="{{ route('treatment-plan') }}">{{ $copy['button'] }} ↗</a></div></section>
@endsection
