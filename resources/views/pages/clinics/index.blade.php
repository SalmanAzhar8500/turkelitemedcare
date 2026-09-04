@extends('layouts.site')
@section('title', __('site.clinics').' | Turkelite Medcare')
@section('description', clinic_live_ui('index_description'))
@section('robots', 'index,follow,max-image-preview:large,max-snippet:-1')
@php
$locale = app()->getLocale();
$ui = match($locale) {
    'de' => ['kicker'=>'Partnerklinik','title'=>'Aktuell verfügbare Partnerklinik','lead'=>'Prüfen Sie Standort, Behandlungsbereiche und direkten Kontakt, bevor wir Ihre Reise um den bestätigten Behandlungsplan organisieren.','all'=>'Alle Bereiche','clear'=>'Filter zurücksetzen','image'=>'Repräsentative Versorgungsumgebung','response'=>'Beratung','est'=>'Erfahrung','rooms'=>'Behandlungsbereiche','coord'=>'Sprachen','note'=>'Behandlungsplan, Preis, Leistungen, Behandler und Nachsorge vor der Reise immer schriftlich bestätigen.','verifyHeading'=>'Vor der Reise prüfen','verification'=>'Nutzen Sie die offiziellen Klinikangaben und Ihren schriftlichen Behandlungsplan, um Behandler, Leistungen und Nachsorge vor der Reise zu prüfen.'],
    'ar' => ['kicker'=>'العيادة الشريكة','title'=>'العيادة الشريكة المتاحة حالياً','lead'=>'راجع الموقع ومجالات العلاج ووسيلة التواصل المباشر قبل أن ننظم الرحلة حول الخطة العلاجية المؤكدة.','all'=>'كل المجالات','clear'=>'مسح الفلاتر','image'=>'بيئة رعاية تمثيلية','response'=>'الاستشارة','est'=>'الخبرة','rooms'=>'مجالات العلاج','coord'=>'اللغات','note'=>'أكد كتابةً الخطة والسعر والخدمات والطبيب المعالج والمتابعة قبل السفر.','verifyHeading'=>'تحقق قبل السفر','verification'=>'استخدم المعلومات الرسمية للعيادة وخطتك العلاجية المكتوبة للتحقق من الطبيب والخدمات والمتابعة قبل السفر.'],
    default => ['kicker'=>'Partner clinic','title'=>'The partner clinic currently available','lead'=>'Review location, treatment areas and direct contact information before we organise your journey around the confirmed clinical plan.','all'=>'All care areas','clear'=>'Clear filters','image'=>'Representative care environment','response'=>'Consultation','est'=>'Experience','rooms'=>'Care areas','coord'=>'Languages','note'=>'Always confirm the treatment plan, quote, inclusions, treating clinician and aftercare in writing before travel.','verifyHeading'=>'Verify before travel','verification'=>'Use the clinic’s official information and your written treatment plan to verify clinician assignment, inclusions and aftercare before travel.'],
};
$filterTags=['Dental','Hair Restoration','Cosmetic Surgery','Bariatric'];
@endphp

@section('content')
<section class="final-clinics-hero">
  <div class="v104-container final-clinics-hero-grid">
    <div><p class="v104-kicker">{{ $ui['kicker'] }}</p><h1>{{ $ui['title'] }}</h1><p class="final-lead small">{{ $ui['lead'] }}</p><div class="final-proof-row"><span>✓ {{ __('site.verify_facility') }}</span><span>✓ {{ __('site.verify_followup') }}</span><span>✓ {{ __('site.verify_commercial') }}</span></div></div>
    <figure><img src="{{ asset('assets/img/final/coordination.webp') }}" alt="International patient coordination context" fetchpriority="high"><figcaption><strong>{{ $ui['verifyHeading'] }}</strong><span>{{ $ui['verification'] }}</span></figcaption></figure>
  </div>
</section>

<section class="v104-section final-clinic-directory">
  <div class="v104-container">
    <div class="final-clinic-filter" data-clinic-filter>
      <button class="active" type="button" data-filter="all">{{ $ui['all'] }}</button>
      @foreach($filterTags as $tag)<button type="button" data-filter="{{ \Illuminate\Support\Str::slug($tag) }}">{{ $tag }}</button>@endforeach
      <button class="clear" type="button" data-filter-clear>{{ $ui['clear'] }}</button>
    </div>
    <div class="final-clinic-grid directory" data-clinic-grid>
      @forelse($clinics as $clinic)
        @php($meta=clinic_demo_meta($clinic))
        @php($tagKeys=collect($meta['strengths'])->map(fn($x)=>\Illuminate\Support\Str::slug($x))->implode(' '))
        <article class="final-clinic-card" data-clinic-tags="{{ $tagKeys }}">
          <a href="{{ route('clinics.show',$clinic) }}" class="final-clinic-image"><img src="{{ clinic_visual_url($clinic,0) }}" alt="{{ $ui['image'] }}" loading="lazy"><span>{{ clinic_live_ui('partner') }}</span></a>
          <div class="final-clinic-body">
            <div class="final-clinic-title"><div><small>{{ $clinic->location ?: 'Turkey' }}</small><h3>{{ $clinic->name }}</h3></div><b>{{ $meta['response'] }}</b></div>
            <p>{{ $clinic->summary }}</p>
            <div class="final-clinic-metrics"><div><strong>{{ $meta['established'] }}</strong><span>{{ $ui['est'] }}</span></div><div><strong>{{ $meta['rooms'] }}</strong><span>{{ $ui['rooms'] }}</span></div><div><strong>{{ $meta['coordinators'] }}</strong><span>{{ $ui['coord'] }}</span></div></div>
            <div class="final-tags">@foreach($meta['strengths'] as $tag)<span>{{ $tag }}</span>@endforeach</div>
            <div class="final-clinic-links"><a href="{{ route('clinics.show',$clinic) }}">{{ __('site.view_profile') }} →</a><a href="{{ route('treatment-plan') }}">{{ __('site.generic_enquiry') }} →</a></div>
          </div>
        </article>
      @empty<div class="v104-empty">{{ __('site.no_sample_matches') }}</div>@endforelse
    </div>
    <p class="final-demo-note">{{ $ui['note'] }}</p>
  </div>
</section>

<section class="final-compare-principles"><div class="v104-container"><div class="final-section-head"><div><p class="v104-kicker">{{ __('site.compare_clinics') }}</p><h2>{{ clinic_live_ui('compare_title') }}</h2></div></div><div class="final-consider-grid">@foreach([__('site.verify_facility'),__('site.verify_clinician'),__('site.verify_followup'),__('site.verify_commercial')] as $check)<article><span>0{{ $loop->iteration }}</span><h3>{{ $ui['verifyHeading'] }}</h3><p>{{ $check }}</p></article>@endforeach</div></div></section>

<section class="cn-final"><div class="v104-container cn-final-inner"><div><span>{{ __('site.next_step') }}</span><h2>{{ __('site.present_cta') }}</h2><p>{{ __('site.present_cta_text') }}</p></div><a class="v104-btn cn-btn-light" href="{{ route('treatment-plan') }}">{{ __('site.plan') }} →</a></div></section>
@endsection

@push('scripts')
<script>
(() => {
 const root=document.querySelector('[data-clinic-filter]'), grid=document.querySelector('[data-clinic-grid]'); if(!root||!grid)return;
 const cards=[...grid.querySelectorAll('[data-clinic-tags]')], buttons=[...root.querySelectorAll('[data-filter]')];
 const apply=key=>{buttons.forEach(b=>b.classList.toggle('active',b.dataset.filter===key));cards.forEach(c=>{c.hidden=key!=='all'&&!c.dataset.clinicTags.split(' ').includes(key);});};
 buttons.forEach(b=>b.addEventListener('click',()=>apply(b.dataset.filter)));root.querySelector('[data-filter-clear]')?.addEventListener('click',()=>apply('all'));
})();
</script>
@endpush
