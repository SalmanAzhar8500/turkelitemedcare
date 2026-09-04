@php
    $locale = app()->getLocale();
    $copy = match($locale) {
        'de' => [
            'kicker'=>'Medizinische Reise, klar koordiniert','title'=>'Behandlung in der Türkei. Klar geplant. Persönlich begleitet.','lead'=>'Sobald der behandelnde Anbieter den medizinischen Plan bestätigt, organisieren wir die praktische Reise darum: Klinikkontakt, Flugplanung, Abholung und Transfers, Unterkunft, Termine, Rückreise und Nachsorgekoordination.','search'=>'Welche Behandlung interessiert Sie?','specialties'=>'Behandlungen entdecken','specialtiesLead'=>'Starten Sie mit dem Fachgebiet und gehen Sie anschließend so tief, wie Sie es für Ihre Entscheidung brauchen.','how'=>'So funktioniert es','howLead'=>'Ein klarer Weg vom ersten Anliegen bis zur Rückkehr nach Hause.','clinics'=>'Klinikumgebungen vergleichen','clinicsLead'=>'Die aktuell verfügbare Partnerklinik zeigt Behandlungsbereiche, Kontaktdaten und die Punkte, die Sie vor der Reise prüfen sollten.','services'=>'Wir organisieren die Reisedetails.','servicesLead'=>'Ein praktischer Ablauf verbindet Klinikkommunikation, Reise, Unterkunft, Transfers und die Nachsorge nach Ihrer Rückkehr.','guides'=>'Hilfreiche Ratgeber für Ihre Entscheidung','stories'=>'Patientenreise','storiesLead'=>'Wie eine internationale Behandlung praktisch organisiert werden kann.'],
        'ar' => [
            'kicker'=>'رحلة علاجية بتنسيق واضح','title'=>'العلاج في تركيا. خطة واضحة. دعم شخصي.','lead'=>'بعد أن يؤكد مقدم العلاج الخطة الطبية، ننظم الرحلة العملية حولها: التواصل مع العيادة، توقيت الرحلات، الاستقبال والتنقلات، الإقامة، المواعيد، العودة وتنسيق المتابعة.','search'=>'ما العلاج الذي تفكر فيه؟','specialties'=>'استكشف التخصصات','specialtiesLead'=>'ابدأ بالتخصص ثم انتقل إلى التفاصيل التي تحتاجها لاتخاذ قرار واعٍ.','how'=>'كيف تعمل الرحلة','howLead'=>'مسار واضح من أول استفسار حتى العودة إلى المنزل.','clinics'=>'قارن بيئات الرعاية','clinicsLead'=>'تعرض العيادة الشريكة المتاحة حالياً مجالات العلاج وبيانات التواصل وما ينبغي التحقق منه قبل السفر.','services'=>'ندير تفاصيل الرحلة.','servicesLead'=>'خطة عملية واحدة تربط التواصل مع العيادة والسفر والإقامة والتنقلات والمتابعة بعد العودة.','guides'=>'أدلة عملية قبل اتخاذ القرار','stories'=>'رحلة المريض','storiesLead'=>'كيف يمكن تنظيم رحلة علاج دولية بطريقة عملية وواضحة.'],
        default => [
            'kicker'=>'Medical travel, clearly coordinated','title'=>'Treatment in Turkey. Planned around you.','lead'=>'Once the treating clinic confirms the medical plan, we organise the practical journey around it: clinic communication, flight timing, pickup and transfers, accommodation, appointments, return-home handover and follow-up coordination.','search'=>'What treatment are you considering?','specialties'=>'Explore treatment specialties','specialtiesLead'=>'Start with the specialty, then go as deep as you need before making a decision.','how'=>'How the journey works','howLead'=>'A clear path from the first question to your return home.','clinics'=>'Compare care environments','clinicsLead'=>'Featured clinic pages show the core details and contact route you should review before you choose.','services'=>'We manage the journey details.','servicesLead'=>'One practical plan connects your clinic communication, travel, stay, transfers and return-home follow-up.','guides'=>'Useful reading before you decide','stories'=>'Patient journey','storiesLead'=>'What an international treatment journey can look like when the practical pieces stay connected.'],
    };
    $featureSpecialties = $specialties->filter(fn($s)=>in_array($s->slug,['dental','hair-restoration','cosmetic-surgery','eye-care','orthopedics'],true))->values();
    if ($featureSpecialties->count() < 5) $featureSpecialties = $specialties->take(5);
    $featureGuides = $guides->take(4);
    $searchItems = collect()
        ->merge($priorityProcedures->take(20)->map(fn($p)=>['title'=>$p->name,'subtitle'=>$p->specialty?->name,'url'=>route('procedures.show',['specialty'=>$p->specialty,'procedure'=>$p]),'terms'=>trim(($p->summary ?? '').' '.($p->specialty?->name ?? ''))]))
        ->merge($specialties->map(fn($s)=>['title'=>$s->name,'subtitle'=>__('site.treatments'),'url'=>route('treatments.show',$s),'terms'=>trim(($s->summary ?? '').' '.($s->description ?? ''))]))
        ->values();
@endphp

<section class="final-hero">
    <div class="v104-container final-hero-grid">
        <div class="final-hero-copy">
            <p class="v104-kicker">{{ $copy['kicker'] }}</p>
            <h1>{{ $copy['title'] }}</h1>
            <p class="final-lead">{{ $copy['lead'] }}</p>
            <div class="v104-actions">
                <a class="v104-btn cn-btn-primary" href="{{ route('treatment-plan') }}">{{ __('site.plan') }} →</a>
                <a class="v104-btn cn-btn-secondary" href="{{ route('how-it-works') }}">{{ __('site.how_it_works') }}</a>
            </div>
            <div class="final-proof-row">
                <span>✓ {{ __('site.provider_title') }}</span>
                <span>✓ {{ __('site.turkelite_title') }}</span>
                <span>✓ EN · DE · AR</span>
            </div>
            <div class="final-search" data-home-search>
                <label>
                    <span aria-hidden="true">⌕</span>
                    <input type="search" autocomplete="off" placeholder="{{ $copy['search'] }}" aria-label="{{ __('site.search') }}" data-home-search-input>
                    <kbd>↵</kbd>
                </label>
                <div class="v108-search-results" data-home-search-results hidden></div>
            </div>
        </div>
        <figure class="final-hero-photo">
            <img src="{{ asset('assets/img/final/home-hero.webp') }}" alt="International patient discussing a coordinated treatment journey in Istanbul" width="1672" height="941" fetchpriority="high">
            <figcaption>
                <strong>{{ __('site.plan_first') }}</strong>
                <span>{{ __('site.provider_confirms') }}</span>
            </figcaption>
        </figure>
    </div>
</section>

<section class="final-trust-strip">
    <div class="v104-container">
        @foreach([__('site.proof1'),__('site.proof2'),__('site.proof3'),__('site.proof4')] as $proof)
            <div><span>0{{ $loop->iteration }}</span><p>{{ $proof }}</p></div>
        @endforeach
    </div>
</section>

<section class="v104-section final-specialties">
    <div class="v104-container">
        <div class="final-section-head"><div><p class="v104-kicker">{{ __('site.treatments') }}</p><h2>{{ $copy['specialties'] }}</h2></div><p>{{ $copy['specialtiesLead'] }}</p></div>
        <div class="final-specialty-grid">
            @foreach($featureSpecialties as $specialty)
                <a href="{{ route('treatments.show',$specialty) }}" class="final-specialty-card">
                    <img src="{{ specialty_visual_url($specialty) }}" alt="{{ $specialty->name }}" loading="lazy">
                    <div><h3>{{ $specialty->name }}</h3><p>{{ $specialty->summary }}</p><b>{{ __('site.explore_short') }} →</b></div>
                </a>
            @endforeach
        </div>
        <a class="final-text-link" href="{{ route('treatments.index') }}">{{ __('site.all_treatments') }} →</a>
    </div>
</section>

<section class="final-how">
    <div class="v104-container">
        <div class="final-section-head"><div><p class="v104-kicker">{{ __('site.how_it_works') }}</p><h2>{{ $copy['how'] }}</h2></div><p>{{ $copy['howLead'] }}</p></div>
        <div class="final-how-grid">
            @foreach([[__('site.step1'),__('site.step1_text')],[__('site.step2'),__('site.step2_text')],[__('site.step3'),__('site.step3_text')],[__('site.step4'),__('site.step4_text')]] as $step)
                <article><span>0{{ $loop->iteration }}</span><div><h3>{{ $step[0] }}</h3><p>{{ $step[1] }}</p></div></article>
            @endforeach
        </div>
    </div>
</section>

<section class="v104-section final-clinics-home">
    <div class="v104-container">
        <div class="final-section-head"><div><p class="v104-kicker">{{ __('site.clinics') }}</p><h2>{{ $copy['clinics'] }}</h2></div><p>{{ $copy['clinicsLead'] }}</p></div>
        <div class="final-clinic-grid">
            @foreach($clinics->take(3) as $clinic)
                @php($meta = clinic_demo_meta($clinic))
                <article class="final-clinic-card">
                    <a href="{{ route('clinics.show',$clinic) }}" class="final-clinic-image"><img src="{{ clinic_visual_url($clinic,0) }}" alt="Illustrative care context" loading="lazy"><span>{{ clinic_live_ui('partner') }}</span></a>
                    <div class="final-clinic-body">
                        <div class="final-clinic-title"><div><small>{{ clinic_live_ui('partner') }}</small><h3>{{ $clinic->name }}</h3></div><b>{{ $meta['response'] }}</b></div>
                        <p>{{ $clinic->summary }}</p>
                        <div class="final-clinic-metrics"><div><strong>{{ preg_replace('#^https?://#','', rtrim($clinic->website ?? 'Clinic website','/')) }}</strong><span>{{ clinic_live_ui('website') }}</span></div><div><strong>{{ $meta['strengths'][0] ?? 'Dental Aesthetics' }}</strong><span>{{ clinic_live_ui('focus') }}</span></div><div><strong>{{ $clinic->phone ?: $meta['response'] }}</strong><span>{{ clinic_live_ui('contact') }}</span></div></div>
                        <div class="final-tags">@foreach($meta['strengths'] as $tag)<span>{{ $tag }}</span>@endforeach</div>
                        <a href="{{ route('clinics.show',$clinic) }}">{{ __('site.view_profile') }} →</a>
                    </div>
                </article>
            @endforeach
        </div>
        <p class="final-demo-note">{{ clinic_live_ui('verify_note') }}</p>
        <a class="final-text-link" href="{{ route('clinics.index') }}">{{ __('site.compare_clinics') }} →</a>
    </div>
</section>

<section class="v104-section final-services">
    <div class="v104-container final-services-grid">
        <figure><img src="{{ asset('assets/img/final/coordination.webp') }}" alt="International patient coordination consultation" loading="lazy"></figure>
        <div><p class="v104-kicker">{{ __('site.patient_services') }}</p><h2>{{ $copy['services'] }}</h2><p class="final-lead small">{{ $copy['servicesLead'] }}</p><div class="final-service-list">@foreach($services as $service)<a href="{{ route('patient-services') }}"><span>✓</span><div><strong>{{ $service->name }}</strong><p>{{ $service->summary }}</p></div></a>@endforeach</div></div>
    </div>
</section>

<section class="v104-section final-guides">
    <div class="v104-container">
        <div class="final-section-head"><div><p class="v104-kicker">{{ __('site.guides') }}</p><h2>{{ $copy['guides'] }}</h2></div><a href="{{ route('guides.index') }}">{{ __('site.guides') }} →</a></div>
        <div class="final-guide-grid">@foreach($featureGuides as $guide)<a href="{{ route('guides.show',$guide) }}"><img src="{{ guide_visual_url($guide) }}" alt="" loading="lazy"><div><small>{{ __('site.article_kicker') }}</small><h3>{{ $guide->name }}</h3><p>{{ $guide->summary }}</p><b>{{ __('site.read_article') }} →</b></div></a>@endforeach</div>
    </div>
</section>

@if($stories->isNotEmpty())
<section class="final-story"><div class="v104-container final-story-grid"><div><p class="v104-kicker">{{ $copy['stories'] }}</p><h2>{{ $copy['storiesLead'] }}</h2><p>{{ $stories->first()->summary }}</p><a href="{{ route('stories.index') }}">{{ __('site.patient_stories') }} →</a></div><img src="{{ story_visual_url($stories->first()) }}" alt="Patient journey illustration" loading="lazy"></div></section>
@endif

@push('scripts')
<script>
(() => {
    const root=document.querySelector('[data-home-search]'); if(!root) return;
    const input=root.querySelector('[data-home-search-input]'), results=root.querySelector('[data-home-search-results]');
    const items=@json($searchItems, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    const esc=s=>String(s||'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c]));
    const render=()=>{const q=input.value.trim().toLowerCase();if(q.length<2){results.hidden=true;results.innerHTML='';return;}const hits=items.map(item=>{const title=(item.title||'').toLowerCase(),hay=(title+' '+(item.subtitle||'')+' '+(item.terms||'')).toLowerCase();let score=title===q?100:title.startsWith(q)?80:title.includes(q)?60:hay.includes(q)?30:0;return{item,score};}).filter(x=>x.score>0).sort((a,b)=>b.score-a.score).slice(0,6);results.innerHTML=hits.length?hits.map(({item})=>`<a href="${esc(item.url)}"><span><b>${esc(item.title)}</b><small>${esc(item.subtitle||'')}</small></span><i>→</i></a>`).join(''):`<div class="v108-search-empty">{{ __('site.no_sample_matches') }}</div>`;results.hidden=false;};
    input.addEventListener('input',render);input.addEventListener('keydown',e=>{if(e.key==='Enter'){const first=results.querySelector('a');if(first){e.preventDefault();location.href=first.href;}}if(e.key==='Escape')results.hidden=true;});document.addEventListener('click',e=>{if(!root.contains(e.target))results.hidden=true;});
})();
</script>
@endpush
