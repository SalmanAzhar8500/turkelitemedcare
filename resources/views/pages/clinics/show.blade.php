@extends('layouts.site')
@section('title', $clinic->name.' | Partner clinic | Turkelite Medcare')
@section('description', $clinic->summary)
@section('robots', 'index,follow,max-image-preview:large,max-snippet:-1')
@php
  $locale = app()->getLocale();
  $location = $clinic->location ?: 'Istanbul, Turkey';
  $meta = clinic_demo_meta($clinic);
  $copy = match($locale) {
    'de' => [
      'partner'=>'Partnerklinik','official'=>'Offizielle Klinikseite','enquire'=>'Behandlungsanfrage starten','published'=>'Veröffentlichte Informationen','about'=>'Über Clinic Expert','aboutText'=>'Clinic Expert beschreibt sich als in Istanbul ansässiges medizinisches Zentrum mit mehr als zehn Jahren Erfahrung im Gesundheitstourismus. Auf den offiziellen Seiten werden Haartransplantation, plastische Chirurgie, Adipositaschirurgie und Zahnästhetik sowie Unterstützung für internationale Patienten veröffentlicht.',
      'service'=>'Veröffentlichte Leistungsbereiche','support'=>'Internationale Patientenbetreuung','supportText'=>'Clinic Expert veröffentlicht Beratung für internationale Patienten sowie Unterstützung bei Unterkunft und Transfers. Vor einer Buchung sollten Behandler, Klinikstandort, Leistungen, Preis und Nachsorge schriftlich bestätigt werden.',
      'contact'=>'Direkter Kontakt','turkelite'=>'Was Turkelite für Sie organisiert','journey'=>'Ihre Reise rund um den bestätigten Behandlungsplan','steps'=>[
        ['Anfrage & Unterlagen','Wir strukturieren Ihre Anfrage und helfen, die benötigten Unterlagen für die Klinik vorzubereiten.'],
        ['Reiseplanung','Nach Bestätigung des medizinischen Zeitplans koordinieren wir Flugplanung, Abholung, Transfers und Unterkunft.'],
        ['Vor Ort','Wir halten die praktischen Termine rund um den bestätigten klinischen Plan zusammen.'],
        ['Rückkehr & Übergabe','Wir koordinieren Rückreise und Nachsorgeübergabe, damit Sie wissen, wen Sie nach der Rückkehr kontaktieren.'],
      ],
      'verify'=>'Vor der Buchung schriftlich bestätigen','verifyItems'=>['Behandelnder Arzt und patientenspezifischer Behandlungsplan','Endgültiger Preis und alle enthaltenen Leistungen','Aufenthaltsdauer, Medikamente und Fit-to-Fly-Zeitpunkt','Nachsorge, Notfallkontakt und Unterlagen für zuhause'],
      'context'=>'Repräsentative Versorgungsbilder','contextText'=>'Die Bilder auf dieser Seite sind hochwertige repräsentative Versorgungskontexte und keine Fotografien von Clinic Expert. Nutzen Sie die offizielle Klinikseite für aktuelle Klinikbilder und Einrichtungen.'
    ],
    'ar' => [
      'partner'=>'العيادة الشريكة','official'=>'الموقع الرسمي للعيادة','enquire'=>'ابدأ طلب العلاج','published'=>'معلومات منشورة','about'=>'عن Clinic Expert','aboutText'=>'تصف Clinic Expert نفسها كمركز طبي في إسطنبول يتمتع بخبرة تتجاوز عشر سنوات في السياحة العلاجية. تنشر مواقعها الرسمية خدمات زراعة الشعر والجراحة التجميلية وجراحات السمنة وتجميل الأسنان، إضافة إلى دعم المرضى الدوليين.',
      'service'=>'مجالات الخدمة المنشورة','support'=>'دعم المرضى الدوليين','supportText'=>'تنشر Clinic Expert خدمات استشارة للمرضى الدوليين ودعماً للسكن والتنقلات. قبل الحجز يجب تأكيد الطبيب والمنشأة والخدمات والسعر والمتابعة كتابةً.',
      'contact'=>'التواصل المباشر','turkelite'=>'ما الذي تنظمه Turkelite لك','journey'=>'رحلتك حول الخطة العلاجية المؤكدة','steps'=>[
        ['الطلب والملفات','ننظم طلبك ونساعدك على تجهيز المعلومات التي تحتاجها العيادة.'],
        ['تخطيط السفر','بعد تأكيد الجدول الطبي ننسق توقيت الرحلة والاستقبال والتنقلات والسكن.'],
        ['أثناء الإقامة','نحافظ على ترابط المواعيد العملية حول الخطة السريرية المؤكدة.'],
        ['العودة والمتابعة','ننسق العودة وتسليم المتابعة حتى تعرف جهة الاتصال بعد الرجوع إلى بلدك.'],
      ],
      'verify'=>'أكد هذه النقاط كتابةً قبل الحجز','verifyItems'=>['الطبيب المعالج والخطة الخاصة بحالتك','السعر النهائي وكل الخدمات المشمولة','مدة الإقامة والأدوية وموعد صلاحية السفر','المتابعة وجهة الطوارئ والسجلات التي ستأخذها معك'],
      'context'=>'صور تمثيلية لبيئة الرعاية','contextText'=>'الصور في هذه الصفحة تمثل بيئة رعاية عالية الجودة وليست صوراً فعلية لـ Clinic Expert. استخدم الموقع الرسمي للعيادة لمشاهدة صور المنشأة الحالية.'
    ],
    default => [
      'partner'=>'Partner clinic','official'=>'Official clinic website','enquire'=>'Start a treatment enquiry','published'=>'Published information','about'=>'About Clinic Expert','aboutText'=>'Clinic Expert describes itself as an Istanbul-based medical centre with more than ten years of medical-tourism experience. Its official sites publish pathways in hair transplantation, plastic surgery, obesity surgery and dental aesthetics, together with international-patient support.',
      'service'=>'Published service areas','support'=>'International-patient support','supportText'=>'Clinic Expert publishes international consultation support together with accommodation and city-transfer coordination. Before booking, the treating clinician, facility, inclusions, final quote and aftercare should still be confirmed in writing for your case.',
      'contact'=>'Direct clinic contact','turkelite'=>'What Turkelite organises for you','journey'=>'Your journey around the confirmed treatment plan','steps'=>[
        ['Enquiry & records','We structure your enquiry and help prepare the information the clinic needs for review.'],
        ['Travel planning','Once the medical timetable is confirmed, we coordinate flight timing, pickup, transfers and accommodation.'],
        ['In Turkey','We keep the practical appointments and travel pieces connected around the confirmed clinical plan.'],
        ['Return & aftercare handover','We coordinate the return journey and follow-up handover so you know who to contact after you are home.'],
      ],
      'verify'=>'Confirm these in writing before booking','verifyItems'=>['Treating clinician and patient-specific written treatment plan','Final quotation and every included service','Expected stay, medication instructions and fit-to-fly timing','Aftercare, escalation contact and records to take home'],
      'context'=>'Representative care environments','contextText'=>'The visuals on this page are premium representative care-context images, not photographs of Clinic Expert. Use the official clinic website for current facility photography.'
    ],
  };
@endphp
@section('content')
<section class="final-clinic-profile-hero">
  <div class="v104-container final-clinic-profile-grid">
    <div>
      <p class="v104-kicker">{{ $copy['partner'] }} · {{ $location }}</p>
      <h1>{{ $clinic->name }}</h1>
      <p class="final-lead small">{{ $clinic->summary }}</p>
      <div class="final-tags">@foreach($meta['strengths'] as $tag)<span>{{ $tag }}</span>@endforeach</div>
      <div class="v104-actions">
        <a class="v104-btn cn-btn-primary" href="{{ route('treatment-plan') }}">{{ $copy['enquire'] }} →</a>
        @if($clinic->website)<a class="v104-btn cn-btn-secondary" href="{{ $clinic->website }}" target="_blank" rel="noopener noreferrer">{{ $copy['official'] }} ↗</a>@endif
      </div>
    </div>
    <figure>
      <img src="{{ clinic_visual_url($clinic,0) }}" alt="Representative premium clinic environment" fetchpriority="high">
      <figcaption><strong>{{ $meta['response'] }}</strong><span>{{ $clinic->phone }}</span></figcaption>
    </figure>
  </div>
</section>

<section class="final-clinic-snapshot">
  <div class="v104-container">
    <div class="final-clinic-metrics large">
      <div><strong>{{ $meta['established'] }}</strong><span>{{ $copy['published'] }}</span></div>
      <div><strong>4</strong><span>{{ $copy['service'] }}</span></div>
      <div><strong>International</strong><span>{{ $copy['support'] }}</span></div>
      <div><strong>WhatsApp / phone</strong><span>{{ $copy['contact'] }}</span></div>
    </div>
  </div>
</section>

<section class="clinic-live-overview">
  <div class="v104-container clinic-live-overview-grid">
    <article class="clinic-live-card">
      <p class="v104-kicker">{{ $copy['published'] }}</p>
      <h2>{{ $copy['about'] }}</h2>
      <p>{{ $copy['aboutText'] }}</p>
      <h3>{{ $copy['support'] }}</h3>
      <p>{{ $copy['supportText'] }}</p>
      <div class="clinic-live-list">
        @foreach($copy['verifyItems'] as $item)
          <div><i>✓</i><div><strong>{{ $copy['verify'] }}</strong><span>{{ $item }}</span></div></div>
        @endforeach
      </div>
    </article>
    <aside class="clinic-live-card clinic-contact-card">
      <p class="v104-kicker">{{ $copy['contact'] }}</p>
      <h2>{{ $clinic->name }}</h2>
      <p>{{ $location }}</p>
      <div class="clinic-contact-row">
        @if($clinic->phone)<a href="tel:{{ preg_replace('/\s+/','',$clinic->phone) }}">{{ $clinic->phone }}</a>@endif
        @if($clinic->email)<a href="mailto:{{ $clinic->email }}">{{ $clinic->email }}</a>@endif
        @if($clinic->website)<a href="{{ $clinic->website }}" target="_blank" rel="noopener noreferrer">clinicexpert.com ↗</a>@endif
      </div>
      <h3>{{ $copy['turkelite'] }}</h3>
      <p>{{ __('site.turkelite_text') }}</p>
    </aside>
  </div>
</section>

<section class="v104-section">
  <div class="v104-container">
    <div class="final-section-head"><div><p class="v104-kicker">Turkelite Medcare</p><h2>{{ $copy['journey'] }}</h2></div></div>
    <div class="clinic-journey-grid">
      @foreach($copy['steps'] as $step)
        <article><span>0{{ $loop->iteration }}</span><h3>{{ $step[0] }}</h3><p>{{ $step[1] }}</p></article>
      @endforeach
    </div>
  </div>
</section>

@if($clinicSpecialties->isNotEmpty())
<section class="v104-section final-clinic-specialties">
  <div class="v104-container">
    <div class="final-section-head"><div><p class="v104-kicker">{{ $copy['service'] }}</p><h2>{{ __('site.treatments') }}</h2></div></div>
    <div class="final-specialty-grid">
      @foreach($clinicSpecialties as $specialty)
        <a class="final-specialty-card" href="{{ route('treatments.show',$specialty) }}"><img src="{{ specialty_visual_url($specialty) }}" alt="{{ $specialty->name }}" loading="lazy"><div><h3>{{ $specialty->name }}</h3><p>{{ $specialty->summary }}</p><b>{{ __('site.explore_short') }} →</b></div></a>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="v104-section final-clinic-gallery">
  <div class="v104-container">
    <div class="final-section-head"><div><p class="v104-kicker">{{ $copy['context'] }}</p><h2>{{ $copy['context'] }}</h2></div><p>{{ $copy['contextText'] }}</p></div>
    <div class="final-gallery-grid"><img src="{{ clinic_visual_url($clinic,1) }}" alt="Representative dental care environment" loading="lazy"><img src="{{ clinic_visual_url($clinic,2) }}" alt="Representative hair restoration environment" loading="lazy"><img src="{{ clinic_visual_url($clinic,3) }}" alt="Representative patient coordination environment" loading="lazy"></div>
  </div>
</section>

<section class="cn-final"><div class="v104-container cn-final-inner"><div><span>{{ __('site.next_step') }}</span><h2>{{ __('site.present_cta') }}</h2><p>{{ __('site.present_cta_text') }}</p></div><a class="v104-btn cn-btn-light" href="{{ route('treatment-plan') }}">{{ $copy['enquire'] }} →</a></div></section>
@endsection
