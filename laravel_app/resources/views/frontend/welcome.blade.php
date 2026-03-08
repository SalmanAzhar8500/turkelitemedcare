@extends('frontend.layouts.app')

@section('content')

    @php
        $defaultHeroItems = [
            'Education and Skill Development',
            'Women and Youth Empowerment',
            'help lorem families',
            'Your gift of $235 can feed 40 children',
        ];

        $aboutData = is_array($homeSetting?->about_data) ? $homeSetting->about_data : [];
        $aboutPageData = is_array($homeSetting?->about_page_data) ? $homeSetting->about_page_data : [];
        $servicesData = is_array($homeSetting?->services_data) ? $homeSetting->services_data : [];
        $whatWeDoData = is_array($homeSetting?->whatwedo_data) ? $homeSetting->whatwedo_data : [];
        $causesData = is_array($homeSetting?->causes_data) ? $homeSetting->causes_data : [];
        $whyChooseData = is_array($homeSetting?->whychoose_data) ? $homeSetting->whychoose_data : [];
        $howItWorkData = is_array($homeSetting?->howitwork_data) ? $homeSetting->howitwork_data : [];
        $testimonialsData = is_array($homeSetting?->testimonials_data) ? $homeSetting->testimonials_data : [];
        $galleryData = is_array($homeSetting?->gallery_data) ? $homeSetting->gallery_data : [];

        $whatWeDoFeatures = collect($whatWeDoData['features'] ?? [])->filter(function ($feature) {
            return filled($feature['title'] ?? null) || filled($feature['desc'] ?? null) || filled($feature['icon'] ?? null);
        })->values();

        if ($whatWeDoFeatures->isEmpty()) {
            $whatWeDoFeatures = collect([
                ['icon' => 'fas fa-stethoscope', 'title' => 'Preventive Care Programs', 'desc' => 'Comprehensive screening and wellness pathways focused on early detection and long-term health outcomes.'],
                ['icon' => 'fas fa-user-md', 'title' => 'Specialist-Led Planning', 'desc' => 'Multi-disciplinary consultations tailored to your condition, history, and treatment goals.'],
                ['icon' => 'fas fa-heartbeat', 'title' => 'Recovery & Follow-up', 'desc' => 'Post-treatment monitoring, lifestyle guidance, and continuity care for confident recovery.'],
            ]);
        }

        $causeItems = collect($causesData['items'] ?? [])->filter(function ($item) {
            return filled($item['title'] ?? null) || filled($item['desc'] ?? null) || filled($item['image'] ?? null);
        })->values();

        if ($causeItems->isEmpty()) {
            $causeItems = collect([
                ['title' => 'Early Diagnosis Access', 'desc' => 'Expanding timely diagnostic support and specialist consultations for better outcomes.', 'link' => route('contact'), 'image' => null],
                ['title' => 'Patient Education Programs', 'desc' => 'Helping patients and families understand treatment options, risks, and recovery plans.', 'link' => route('contact'), 'image' => null],
                ['title' => 'Post-Treatment Continuity', 'desc' => 'Structured follow-up support to improve recovery quality and long-term confidence.', 'link' => route('contact'), 'image' => null],
            ]);
        }

        $whyChoosePoints = collect($whyChooseData['whychoose_points'] ?? [])->filter()->values();
        if ($whyChoosePoints->isEmpty()) {
            $whyChoosePoints = collect([
                'Specialist-led treatment planning',
                'Transparent clinical communication',
                'Personalized care pathways',
                'Long-term follow-up support',
            ]);
        }

        $extractCounterValue = function ($value, $fallback = '0') {
            $raw = trim((string) ($value ?? ''));
            if ($raw === '') {
                $raw = (string) $fallback;
            }
            $digits = preg_replace('/[^0-9]/', '', $raw);
            return $digits !== '' ? $digits : (string) $fallback;
        };

        $howItWorkSteps = collect($howItWorkData['steps'] ?? [])->filter(function ($item) {
            return filled($item['title'] ?? null) || filled($item['desc'] ?? null) || filled($item['image'] ?? null);
        })->values();

        if ($howItWorkSteps->isEmpty()) {
            $howItWorkSteps = collect([
                ['title' => 'Assessment & Diagnosis', 'desc' => 'Detailed consultation to understand your condition and treatment goals.', 'image' => null],
                ['title' => 'Treatment Roadmap', 'desc' => 'Personalized plan with clear milestones, timelines, and expected outcomes.', 'image' => null],
                ['title' => 'Procedure & Care', 'desc' => 'Safe execution using evidence-based protocols and specialist supervision.', 'image' => null],
                ['title' => 'Follow-up & Recovery', 'desc' => 'Post-treatment monitoring and adjustments for durable results.', 'image' => null],
            ]);
        }

        $testimonialItems = collect($testimonialsData['items'] ?? [])->filter(function ($item) {
            return filled($item['name'] ?? null) || filled($item['quote'] ?? null) || filled($item['image'] ?? null);
        })->values();

        if ($testimonialItems->isEmpty()) {
            $testimonialItems = collect([
                ['name' => 'Ayesha Khan', 'designation' => 'Patient', 'quote' => 'The team explained everything clearly and gave me confidence at every step of treatment.', 'image' => null],
                ['name' => 'Usman Tariq', 'designation' => 'Patient', 'quote' => 'Professional doctors, smooth process, and excellent follow-up after the procedure.', 'image' => null],
                ['name' => 'Sara Ali', 'designation' => 'Patient', 'quote' => 'I truly appreciated the personalized care plan and transparent communication.', 'image' => null],
            ]);
        }

        $galleryItems = collect($galleryData['items'] ?? [])->filter(function ($item) {
            return filled($item['image'] ?? null);
        })->values();

        $galleryCategories = $galleryItems
            ->pluck('category')
            ->filter()
            ->map(fn ($item) => trim((string) $item))
            ->unique()
            ->values();

        $heroItems = $homeSetting?->hero_items;
        if (!is_array($heroItems) || count($heroItems) === 0) {
            $heroItems = $defaultHeroItems;
        }

        $heroListItems = count($heroItems) > 2 ? array_slice($heroItems, 0, count($heroItems) - 2) : $heroItems;
        $heroHelpTitle = count($heroItems) >= 2 ? $heroItems[count($heroItems) - 2] : 'help lorem families';
        $heroHelpText = count($heroItems) >= 1 ? $heroItems[count($heroItems) - 1] : 'Your gift of $235 can feed 40 children';
    @endphp



    <!-- Hero Section Start -->
    <div class="hero hero-video">
        <!-- Video Start -->
        <div class="hero-bg-video">
            <!-- Selfhosted Video Start -->
            @if(!empty($homeSetting?->hero_video))
                <video autoplay muted loop id="myVideo">
                    <source src="{{ asset('storage/' . $homeSetting->hero_video) }}" type="video/mp4">
                </video>
            @elseif(!empty($homeSetting?->hero_video_url))
                <video autoplay muted loop id="myVideo">
                    <source src="{{ $homeSetting->hero_video_url }}" type="video/mp4">
                </video>
            @elseif(!empty($homeSetting?->hero_image))
                <img src="{{ asset('storage/' . $homeSetting->hero_image) }}" alt="Hero Background" style="width:100%;height:100%;object-fit:cover;">
            @else
                <video autoplay muted loop id="myVideo">
                    <source src="https://demo.awaikenthemes.com/assets/videos/lenity-video.mp4" type="video/mp4">
                </video>
            @endif

            <!-- Selfhosted Video End -->

            <!-- Youtube Video Start -->
            <!-- <div id="herovideo" class="player" data-property="{videoURL:'74DWwSxsVSs',containment:'.hero-video', showControls:false, autoPlay:true, loop:true, vol:0, mute:false, startAt:0,  stopAt:296, opacity:1, addRaster:true, quality:'large', optimizeDisplay:true}"></div> -->
            <!-- Youtube Video End -->
        </div>
        <!-- Video End -->
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <!-- Hero Content Start -->
                    <div class="hero-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $homeSetting?->hero_subtitle ?: 'welcome our charity' }}</h3>
                            <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $homeSetting?->hero_title ?: 'Empower change, one act of kindness at a time' }}</h1>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $homeSetting?->hero_description ?: 'Join us in creating brighter futures by providing hope, delivering help, and fostering lasting change for communities in need around the world.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Hero Content Body Start -->
                        <div class="hero-body wow fadeInUp" data-wow-delay="0.4s">
                            <!-- Hero Button Start -->
                            <div class="hero-btn">
                                <a href="{{ $homeSetting?->hero_button_link ?: '#' }}" class="btn-default">{{ $homeSetting?->hero_button_text ?: 'donate now' }}</a>
                            </div>
                            <!-- Hero Button End -->

                            <!-- Video Play Button Start -->
                            <div class="video-play-button">
                                <p>play video</p>
                                <a href="https://www.youtube.com/watch?v=Y-x0efG1seA" class="popup-video" data-cursor-text="Play">
                                    <i class="fa-solid fa-play"></i>
                                </a>
                            </div>
                            <!-- Video Play Button End -->
                        </div>
                        <!-- Hero Content Body End -->

                        <!-- Hero Footer Start -->
                        <div class="hero-footer wow fadeInUp" data-wow-delay="0.6s">
                            <div class="hero-list">
                                <ul>
                                    @foreach($heroListItems as $heroListItem)
                                        <li>{{ $heroListItem }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="hero-help-families">
                                <h3>{{ $heroHelpTitle }}</h3>
                                <p>{{ $heroHelpText }}</p>
                            </div>
                        </div>
                        <!-- Hero Footer End -->
                    </div>
                    <!-- Hero Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- About Us Section Start -->
    <div class="about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                   <!-- About Us Images Start -->
<div class="about-us-images">
    
    <!-- About Image 1 Start -->
    <div class="about-img-1">
        <figure class="image-anime">
            <img src="{{ asset('storage/' . $aboutData['about_image']) }}" alt="Medical Team">
        </figure>
    </div>
    <!-- About Image 1 End -->

    <!-- About Image 2 Start -->
    <div class="about-img-2">
        <figure class="image-anime">
            <img src="{{ asset('storage/' . $aboutData['about_image2']) }}" alt="Doctor Consulting Patient">
        </figure>
    </div>
    <!-- About Image 2 End -->

    <!-- Need Fund Box Start -->
    <div class="need-fund-box">
        <img src="{{ asset('frontend/assets/images/icon-funded-dollar.svg') }}" alt="">
        <p>We've funded <span class="counter">75</span>k Dollars</p>
    </div>
    <!-- Need Fund Box End -->

</div>
<!-- About Us Images End -->
                </div>

                <div class="col-lg-6">
                    <!-- About Us Content Start -->
                    <div class="about-us-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $aboutData['about_title'] ?? 'about us' }} </h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutData['about_subtitle'] ?? 'Advanced care, trusted outcomes' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutData['about_description'] ?? 'We combine experienced specialists, evidence-based treatment plans, and compassionate support to deliver safe, personalized care for every patient.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- About Body Start -->
                        <div class="about-us-body">
                            <div class="about-us-body-content">
                                <!-- About Support Box Start -->
                                <div class="about-support-box wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon-box">
                                        @if(!empty($aboutData['about_support_icon_svg']) || !empty($aboutPageData['about_support_icon_svg']))
                                            {!! $aboutData['about_support_icon_svg'] ?? $aboutPageData['about_support_icon_svg'] !!}
                                        @elseif(!empty($aboutData['about_support_icon']) || !empty($aboutPageData['about_support_icon']))
                                            <img src="{{ !empty($aboutData['about_support_icon']) ? asset('storage/' . $aboutData['about_support_icon']) : asset('storage/' . $aboutPageData['about_support_icon']) }}" alt="Support Icon">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/icon-about-support.svg') }}" alt="Support Icon">
                                        @endif
                                    </div>
                                    <!-- About Support Content Start -->
                                    <div class="about-support-content">
                                        <h3>{{ $aboutData['about_support_title'] ?? ($aboutPageData['about_support_title'] ?? 'Patient-Centered Support') }}</h3>
                                        <p>{{ $aboutData['about_support_text'] ?? ($aboutData['about_support_description'] ?? ($aboutPageData['about_support_description'] ?? 'From first consultation to post-treatment follow-up, our team supports every step of your care journey.')) }}</p>
                                    </div>
                                    <!-- About Support Content End -->
                                </div>
                                <!-- About Support Box End -->

                                <!-- About Button Start -->
                                <div class="about-btn wow fadeInUp" data-wow-delay="0.6s">
                                    <a href="{{ route('about') }}" class="btn-default">about us</a>
                                </div>
                                <!-- About Button End -->
                            </div>

                            <!-- Helped Fund Item Start -->
                            <div class="helped-fund-item">
                                <!--<div class="helped-fund-img">-->
                                <!--    <figure class="image-anime">-->
                                <!--        <img src="{{asset('frontend/assets/images/helped-fund-img.jpg')}}" alt="">-->
                                <!--    </figure>-->
                                <!--</div>-->
                                <div class="helped-fund-content">
                                    <h2><span class="counter">{{ $aboutData['about_doctors'] ?? 75 }}</span>+</h2>
                                    <h3>specialist team</h3>
                                    <p>{{ ($aboutData['about_years'] ?? 25) }}+ years of clinical excellence and trusted patient care.</p>
                                </div>
                            </div>
                            <!-- Helped Fund Item End -->
                        </div>
                        <!-- About Body End -->
                    </div>
                    <!-- About Us Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- About Us Section End -->

    <!-- Our Services Section Start -->
    <div class="our-services">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $servicesData['services_subtitle'] ?? 'services' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $servicesData['services_title'] ?? 'Our comprehensive services' }}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $servicesData['services_description'] ?? 'Specialized treatment pathways designed for precision diagnosis, effective therapy, and long-term wellness.' }}</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                @forelse(($featuredServices ?? collect()) as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                            <div class="service-content">
                                <h3><a href="{{ route('services.details', $service->slug) }}">{{ $service->name }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $service->description), 90) }}</p>
                            </div>
                            <div class="service-image">
                                <figure class="image-anime">
                                    <img src="{{ !empty($service->image) ? asset('storage/' . $service->image) : asset('frontend/assets/images/services-image-1.jpg') }}" alt="{{ $service->name }}">
                                </figure>
                            </div>
                            <div class="service-btn">
                                <a href="{{ route('services.details', $service->slug) }}" class="readmore-btn">read more</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12">
                        <div class="alert alert-info text-center">No services found. Please add services from admin panel.</div>
                    </div>
                @endforelse

                <div class="col-lg-12">
                    <!-- Service Contact Text Start -->
                    <div class="section-footer-text wow fadeInUp" data-wow-delay="0.6s">
                        <p>{{ $servicesData['services_footer_text'] ?? 'Trusted care starts with one conversation. Contact us today' }} <a href="tel:{{ preg_replace('/[^0-9+]/', '', (string) ($servicesData['services_footer_phone'] ?? '+91 123 456 789')) }}">{{ $servicesData['services_footer_phone'] ?? '(+91) 123 456 789' }}</a></p>
                    </div>
                    <!-- Service Contact Text End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Services Section End -->

    <!-- What We Do Section Start -->
    <div class="what-we-do">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- What We Do Content Start -->
                    <div class="what-we-do-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $whatWeDoData['whatwedo_subtitle'] ?? 'what we do' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $whatWeDoData['whatwedo_title'] ?? 'Integrated care for lifelong wellness' }}</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- what We List Start -->
                        <div class="what-we-list">
                            @foreach($whatWeDoFeatures as $feature)
                                <div class="what-we-item wow fadeInUp" data-wow-delay="{{ ($loop->index + 1) * 0.2 }}s">
                                    <div class="icon-box">
                                        @if(filled($feature['icon'] ?? null) && \Illuminate\Support\Str::startsWith((string) $feature['icon'], ['http://', 'https://', '/']))
                                            <img src="{{ $feature['icon'] }}" alt="{{ $feature['title'] ?? 'Feature' }}">
                                        @else
                                            <i class="{{ $feature['icon'] ?? 'fas fa-check-circle' }}"></i>
                                        @endif
                                    </div>
                                    <div class="what-we-item-content">
                                        <h3>{{ $feature['title'] ?? 'Quality care' }}</h3>
                                        <p>{{ $feature['desc'] ?? 'Evidence-based treatment and personal support for better outcomes.' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- what We List End -->
                    </div>
                    <!-- What We Do Content End -->
                </div>
                <div class="col-lg-6">
                    <!-- What We Do Images Start -->
                    <div class="what-we-do-images">
                        <!-- What We Do Image 1 Start -->
                        <div class="what-we-do-img-1">
                            <figure class="image-anime reveal">
                                <img src="{{ !empty($whatWeDoData['whatwedo_image1']) ? asset('storage/' . $whatWeDoData['whatwedo_image1']) : asset('frontend/assets/images/what-we-do-image-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <!-- What We Do Image 1 End -->

                        <!-- What We Do Image 2 Start -->
                        <div class="what-we-do-img-2">
                            <figure class="image-anime">
                                <img src="{{ !empty($whatWeDoData['whatwedo_image2']) ? asset('storage/' . $whatWeDoData['whatwedo_image2']) : asset('frontend/assets/images/what-we-do-image-2.jpg') }}" alt="">
                            </figure>
                        </div>
                        <!-- What We Do Image 2 End -->

                        <!-- Donate Now Box Start -->
                        <div class="donate-now-box">
                            <a href="{{ route('contact') }}"><img src="{{asset('frontend/assets/images/icon-donate-now.svg')}}" alt="">book now</a>
                        </div>
                        <!-- Donate Now Box End -->
                    </div>
                    <!-- What We Do Images End -->
                </div>
            </div>
        </div>
    </div>
    <!-- What We Do Section End -->

    <!-- Our Causes Section Start -->
    <div class="our-causes">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $causesData['causes_subtitle'] ?? 'our causes' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $causesData['causes_title'] ?? 'Supporting better patient outcomes' }}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $causesData['causes_description'] ?? 'Focused initiatives that improve access, awareness, and continuity across the patient care journey.' }}</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                @foreach($causeItems as $causeItem)
                    <div class="col-lg-4 col-md-6">
                        <div class="causes-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                            <div class="causes-image">
                                <figure class="image-anime">
                                    <img src="{{ !empty($causeItem['image']) ? asset('storage/' . $causeItem['image']) : asset('frontend/assets/images/causes-img-1.jpg') }}" alt="{{ $causeItem['title'] ?? 'Cause' }}">
                                </figure>
                            </div>
                            <div class="causes-body">
                                <div class="causes-content">
                                    <h3>{{ $causeItem['title'] ?? 'Cause title' }}</h3>
                                    <p>{{ $causeItem['desc'] ?? 'Cause description goes here.' }}</p>
                                </div>
                                <div class="causes-button">
                                    <a href="{{ $causeItem['link'] ?? route('contact') }}" class="btn-default">read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Our Causes Section End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Why Choose Images Start -->
                    <div class="why-choose-images">
                        <div class="why-choose-image-1">
                            <figure class="image-anime">
                                <img src="{{ !empty($whyChooseData['whychoose_image1']) ? asset('storage/' . $whyChooseData['whychoose_image1']) : asset('frontend/assets/images/why-choose-img-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <div class="why-choose-image-2">
                            <figure class="image-anime">
                                <img src="{{ !empty($whyChooseData['whychoose_image2']) ? asset('storage/' . $whyChooseData['whychoose_image2']) : asset('frontend/assets/images/why-choose-img-2.jpg') }}" alt="">
                            </figure>
                        </div>
                    </div>
                    <!-- Why Choose Images End -->
                </div>

                <div class="col-lg-6">
                    <!-- Why Choose Content Start -->
                    <div class="why-choose-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $whyChooseData['whychoose_subtitle'] ?? 'why choose us' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $whyChooseData['whychoose_title'] ?? 'Why patients choose our team' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $whyChooseData['whychoose_description'] ?? 'Transparent communication, personalized planning, and specialist-driven care at every stage.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Why Choose List Start -->
                        <div class="why-choose-list wow fadeInUp" data-wow-delay="0.4s">
                            <ul>
                                @foreach($whyChoosePoints as $whyChoosePoint)
                                    <li>{{ $whyChoosePoint }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Why Choose List End -->

                        <!-- Why Choose Counters Start -->
                        <div class="why-choose-counters">
                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $extractCounterValue($whyChooseData['whychoose_counter1_number'] ?? '25') }}</span>+</h2>
                                <p>{{ $whyChooseData['whychoose_counter1_label'] ?? 'Years of experience' }}</p>
                            </div>
                            <!-- Why Choose Counters Item End -->

                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $extractCounterValue($whyChooseData['whychoose_counter2_number'] ?? '230') }}</span>+</h2>
                                <p>{{ $whyChooseData['whychoose_counter2_label'] ?? 'Successful treatment plans' }}</p>
                            </div>
                            <!-- Why Choose Counters Item End -->

                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $extractCounterValue($whyChooseData['whychoose_counter3_number'] ?? '400') }}</span>+</h2>
                                <p>{{ $whyChooseData['whychoose_counter3_label'] ?? 'Satisfied patients' }}</p>
                            </div>
                            <!-- Why Choose Counters Item End -->
                        </div>
                        <!-- Why Choose Counters End -->
                    </div>
                    <!-- Why Choose Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us Section End -->





    <!-- How It Work Section Start -->
    <div class="how-it-work">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $howItWorkData['howitwork_subtitle'] ?? 'How it work' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $howItWorkData['howitwork_title'] ?? 'Step by step working process' }}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $howItWorkData['howitwork_description'] ?? 'A clear, step-based process from assessment to long-term follow-up.' }}</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- How It Work List Start -->
                    <div class="how-it-work-list">
                        @foreach($howItWorkSteps as $step)
                            <div class="how-it-work-item">
                                <div class="how-it-work-image">
                                    <figure class="image-anime reveal">
                                        <img src="{{ !empty($step['image']) ? asset('storage/' . $step['image']) : asset('frontend/assets/images/how-it-work-img-1.jpg') }}" alt="{{ $step['title'] ?? 'Step' }}">
                                    </figure>
                                </div>

                                <div class="how-it-work-content wow fadeInUp" data-wow-delay="{{ ($loop->index + 1) * 0.2 }}s">
                                    <div class="icon-box">
                                        @if(!empty($step['icon_svg']))
                                            {!! $step['icon_svg'] !!}
                                        @else
                                            <img src="{{asset('frontend/assets/images/icon-how-it-work-1.svg')}}" alt="">
                                        @endif
                                    </div>
                                    <div class="how-it-work-body">
                                        <h3>{{ $step['title'] ?? 'Step title' }}</h3>
                                        <p>{{ $step['desc'] ?? 'Step description goes here.' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- How It Work List End -->
                </div>

                <div class="col-lg-12">
                    <!-- Service Contact Text Start -->
                    <div class="section-footer-text how-work-footer-text wow fadeInUp" data-wow-delay="0.8s">
                        <p>
                            {{ $howItWorkData['howitwork_description'] ?? 'Need help choosing the right treatment pathway?' }}
                            <a href="{{ $howItWorkData['howitwork_button_link'] ?? route('contact') }}">{{ $howItWorkData['howitwork_button_text'] ?? 'Get Started' }}</a>
                        </p>
                    </div>
                    <!-- Service Contact Text End -->
                </div>
            </div>
        </div>
    </div>
    <!-- How It Work Section End -->

    <!-- Our Testimonials Section Start -->
    <div class="our-testimonials">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Testimonials Image Start -->
                    <div class="testimonials-image">
                        <div class="testimonials-img">
                            <figure class="image-anime reveal">
                                <img src="{{ !empty($testimonialsData['testimonials_main_image']) ? asset('storage/' . $testimonialsData['testimonials_main_image']) : asset('frontend/assets/images/testimonials-image.jpg') }}" alt="Testimonials Main Image">
                            </figure>
                        </div>

                        <div class="helthcare-support-circle">
                            <a href="contact.html">
                                <img src="{{asset('frontend/assets/images/healthcare-support-circle.svg')}}" alt="">
                            </a>
                        </div>

                        <div class="client-review-box">
                            <h2><span class="counter">20</span>k</h2>
                            <p>customer review</p>
                        </div>
                    </div>
                    <!-- Testimonials Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- Testimonials Content Start -->
                    <div class="testimonials-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $testimonialsData['testimonials_subtitle'] ?? 'testimonials' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $testimonialsData['testimonials_title'] ?? 'What people say about us' }}</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- Testimonial Slider Start -->
                        <div class="testimonial-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper" data-cursor-text="Drag">
                                    @foreach($testimonialItems as $testimonial)
                                        <div class="swiper-slide">
                                            <div class="testimonial-item">
                                                <div class="testimonial-header">
                                                    <div class="author-info">
                                                        <div class="author-image">
                                                            <figure class="image-anime">
                                                                <img src="{{ !empty($testimonial['image']) ? asset('storage/' . $testimonial['image']) : asset('frontend/assets/images/author-1.jpg') }}" alt="{{ $testimonial['name'] ?? 'Author' }}">
                                                            </figure>
                                                        </div>

                                                        <div class="author-content">
                                                            <h3>{{ $testimonial['name'] ?? 'Patient' }}</h3>
                                                            <p>{{ $testimonial['designation'] ?? 'Client' }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="testimonial-rating">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                </div>

                                                <div class="testimonial-content">
                                                    <p>"{{ $testimonial['quote'] ?? 'Excellent patient care and highly professional staff.' }}"</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="testimonial-pagination"></div>
                            </div>
                        </div>
                        <!-- Testimonial Slider End -->
                    </div>
                    <!-- Testimonials Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Testimonials Section End -->

    <!-- Our Gallery Section Start -->
    <div class="our-gallery">
        <div class="container-fluid">
            <div class="row section-row no-gutters">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $galleryData['gallery_subtitle'] ?? 'gallery' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $galleryData['gallery_title'] ?? 'Our image gallery' }}</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Our Gallery Nav start -->
                    <div class="our-gallery-nav wow fadeInUp" data-wow-delay="0.2s">
                        <ul>
                            <li><a href="#" class="active-btn" data-filter="*">all</a></li>
                            @foreach($galleryCategories as $galleryCategory)
                                @php $galleryClass = \Illuminate\Support\Str::slug($galleryCategory); @endphp
                                <li><a href="#" data-filter=".{{ $galleryClass }}">{{ $galleryCategory }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Our Gallery Nav End -->
                </div>

                <div class="col-lg-12">
                    <!-- Gallery Item Boxes Start -->
                    <div class="gallery-item-boxes">
                        @forelse($galleryItems as $galleryItem)
                            @php $galleryClass = \Illuminate\Support\Str::slug($galleryItem['category'] ?? 'general'); @endphp
                            <div class="gallery-item-box {{ $galleryClass }}">
                                <figure class="image-anime">
                                    <img src="{{ asset('storage/' . $galleryItem['image']) }}" alt="{{ $galleryItem['title'] ?? 'Gallery image' }}">
                                </figure>
                            </div>
                        @empty
                            <div class="gallery-item-box">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/gallery-1.jpg')}}" alt="Gallery image">
                                </figure>
                            </div>
                        @endforelse
                    </div>
                    <!-- Gallery Item Boxes End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Gallery Section End -->

    <!-- Our Blog Section Start -->
    <!--<div class="our-blog">-->
    <!--    <div class="container">-->
    <!--        <div class="row section-row">-->
    <!--            <div class="col-lg-12">-->
                    <!-- Section Title Start -->
    <!--                <div class="section-title">-->
    <!--                    <h3 class="wow fadeInUp">latest post</h3>-->
    <!--                    <h2 class="text-anime-style-2" data-cursor="-opaque">Stories of impact and hope</h2>-->
    <!--                    <p class="wow fadeInUp" data-wow-delay="0.2s">Explore inspiring stories and updates about our initiatives, successes, and the lives we've touched. See how your support is creating real, lasting change in communities worldwide.</p>-->
    <!--                </div>-->
                    <!-- Section Title End -->
    <!--            </div>-->
    <!--        </div>-->

    <!--        <div class="row">-->
    <!--            <div class="col-lg-4 col-md-6">-->
                    <!-- Post Item Start -->
    <!--                <div class="post-item wow fadeInUp">-->
                        <!-- Post Item Header Start -->
    <!--                    <div class="post-item-header">-->
                            <!-- Post Item Tag Start -->
    <!--                        <div class="post-item-meta">-->
    <!--                            <ul>-->
    <!--                                <li>10 feb, 2025</li>-->
    <!--                            </ul>-->
    <!--                        </div>-->
                            <!-- Post Item Tag End -->

                            <!-- Post Item Content Start -->
    <!--                        <div class="post-item-content">-->
    <!--                            <h2><a href="blog-single.html">Youth Leadership Program Inspires the Next Generation</a></h2>-->
    <!--                        </div>-->
                            <!-- Post Item Content End -->
    <!--                    </div>-->
                        <!-- Post Item Header End -->

                        <!-- Post Featured Image Start-->
    <!--                    <div class="post-featured-image">-->
    <!--                        <a href="blog-single.html" data-cursor-text="View">-->
    <!--                            <figure class="image-anime">-->
    <!--                                <img src="{{asset('frontend/assets/images/post-1.jpg')}}" alt="">-->
    <!--                            </figure>-->
    <!--                        </a>-->
    <!--                    </div>-->
                        <!-- Post Featured Image End -->

                        <!-- Blog Item Button Start -->
    <!--                    <div class="blog-item-btn">-->
    <!--                        <a href="blog-single.html" class="readmore-btn">read more</a>-->
    <!--                    </div>-->
                        <!-- Blog Item Button End -->
    <!--                </div>-->
                    <!-- Post Item End -->
    <!--            </div>-->

    <!--            <div class="col-lg-4 col-md-6">-->
                    <!-- Post Item Start -->
    <!--                <div class="post-item wow fadeInUp" data-wow-delay="0.2s">-->
                        <!-- Post Item Header Start -->
    <!--                    <div class="post-item-header">-->
                            <!-- Post Item Tag Start -->
    <!--                        <div class="post-item-meta">-->
    <!--                            <ul>-->
    <!--                                <li>07 feb, 2025</li>-->
    <!--                            </ul>-->
    <!--                        </div>-->
                            <!-- Post Item Tag End -->

                            <!-- Post Item Content Start -->
    <!--                        <div class="post-item-content">-->
    <!--                            <h2><a href="blog-single.html">Protecting Forests, Futures Our Environmental Mission</a></h2>-->
    <!--                        </div>-->
                            <!-- Post Item Content End -->
    <!--                    </div>-->
                        <!-- Post Item Header End -->

                        <!-- Post Featured Image Start-->
    <!--                    <div class="post-featured-image">-->
    <!--                        <a href="blog-single.html" data-cursor-text="View">-->
    <!--                            <figure class="image-anime">-->
    <!--                                <img src="{{asset('frontend/assets/images/post-2.jpg')}}" alt="">-->
    <!--                            </figure>-->
    <!--                        </a>-->
    <!--                    </div>-->
                        <!-- Post Featured Image End -->

                        <!-- Blog Item Button Start -->
    <!--                    <div class="blog-item-btn">-->
    <!--                        <a href="blog-single.html" class="readmore-btn">read more</a>-->
    <!--                    </div>-->
                        <!-- Blog Item Button End -->
    <!--                </div>-->
                    <!-- Post Item End -->
    <!--            </div>-->

    <!--            <div class="col-lg-4 col-md-6">-->
                    <!-- Post Item Start -->
    <!--                <div class="post-item wow fadeInUp" data-wow-delay="0.4s">-->
                        <!-- Post Item Header Start -->
    <!--                    <div class="post-item-header">-->
                            <!-- Post Item Tag Start -->
    <!--                        <div class="post-item-meta">-->
    <!--                            <ul>-->
    <!--                                <li>04 feb, 2025</li>-->
    <!--                            </ul>-->
    <!--                        </div>-->
                            <!-- Post Item Tag End -->

                            <!-- Post Item Content Start -->
    <!--                        <div class="post-item-content">-->
    <!--                            <h2><a href="blog-single.html">Partnering for Collaborative Impact Stories</a></h2>-->
    <!--                        </div>-->
                            <!-- Post Item Content End -->
    <!--                    </div>-->
                        <!-- Post Item Header End -->

                        <!-- Post Featured Image Start-->
    <!--                    <div class="post-featured-image">-->
    <!--                        <a href="blog-single.html" data-cursor-text="View">-->
    <!--                            <figure class="image-anime">-->
    <!--                                <img src="{{asset('frontend/assets/images/post-3.jpg')}}" alt="">-->
    <!--                            </figure>-->
    <!--                        </a>-->
    <!--                    </div>-->
                        <!-- Post Featured Image End -->

                        <!-- Blog Item Button Start -->
    <!--                    <div class="blog-item-btn">-->
    <!--                        <a href="blog-single.html" class="readmore-btn">read more</a>-->
    <!--                    </div>-->
                        <!-- Blog Item Button End -->
    <!--                </div>-->
                    <!-- Post Item End -->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Our Blog Section End -->



@endsection


{{-- ...rest of your welcome content here... --}}
