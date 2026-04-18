@extends('frontend.layouts.app')

@section('content')

    @php
        $aboutPageData = $homeSetting->about_page_data ?? [];
        $whyPoints = $aboutPageData['why_points'] ?? ['community-centered approach', 'transparency and accountability', 'empowerment through partner', 'volunteer and donor engagement'];
        $howPoints = $aboutPageData['how_points'] ?? ['Community Development Programs', 'Women and Youth Empowerment', 'Advocacy and Awareness Campaigns'];
    @endphp


    <!-- Page Header Start -->
    <div class="page-header parallaxie" style="background-image: url('{{ $pageHeaderImageUrl }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque"> <span>{{ $aboutPageData['page_header_highlight'] ?? 'About' }}</span> {{ $aboutPageData['page_header_title'] ?? 'us' }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ $aboutPageData['breadcrumb_home_link'] ?? route('home') }}">{{ $aboutPageData['breadcrumb_home_text'] ?? 'home' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $aboutPageData['breadcrumb_current'] ?? 'about us' }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

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
                                <img src="{{ !empty($aboutPageData['about_image1']) ? asset('storage/' . $aboutPageData['about_image1']) : asset('frontend/assets/images/about-img-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <!-- About Image 1 End -->

                        <!-- About Image 2 Start -->
                        <div class="about-img-2">
                            <figure class="image-anime">
                                <img src="{{ !empty($aboutPageData['about_image2']) ? asset('storage/' . $aboutPageData['about_image2']) : asset('frontend/assets/images/about-img-2.jpg') }}" alt="">
                            </figure>
                        </div>
                        <!-- About Image 2 End -->

                        <!-- Need Fund Box Start -->
                        <div class="need-fund-box">
                            <img src="{{asset('frontend/assets/images/icon-funded-dollar.svg')}}" alt="">
                            <p>{{ str_replace('k', '', $aboutPageData['about_funded_label'] ?? "We've funded k Dollars") }} <span class="counter">{{ $aboutPageData['about_funded_amount'] ?? '75' }}</span>k</p>
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
                            <h3 class="wow fadeInUp">{{ $aboutPageData['about_subtitle'] ?? 'about us' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['about_title'] ?? 'United in compassion, changing lives' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['about_description'] ?? 'Driven by compassion and a shared vision, we work hand-in-hand with communities to create meaningful change.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- About Body Start -->
                        <div class="about-us-body">
                            <div class="about-us-body-content">
                                <!-- About Support Box Start -->
                                <div class="about-support-box wow fadeInUp" data-wow-delay="0.4s">
                                    <div class="icon-box">
                                        <img src="{{asset('frontend/assets/images/icon-about-support.svg')}}" alt="">
                                    </div>
                                    <!-- About Support Content Start -->
                                    <div class="about-support-content">
                                        <h3>{{ $aboutPageData['about_support_title'] ?? 'Healthcare Support' }}</h3>
                                        <p>{{ $aboutPageData['about_support_description'] ?? 'Providing essential healthcare services and resources to communities.' }}</p>
                                    </div>
                                    <!-- About Support Content End -->
                                </div>
                                <!-- About Support Box End -->

                                <!-- About Button Start -->
                                <div class="about-btn wow fadeInUp" data-wow-delay="0.6s">
                                    <a href="{{ $aboutPageData['about_donate_button_link'] ?? url('/contact-us') }}" class="btn-default">{{ $aboutPageData['about_donate_button_text'] ?? 'donate now' }}</a>
                                </div>
                                <!-- About Button End -->
                            </div>

                            <!-- Helped Fund Item Start -->
                            <div class="helped-fund-item">
                                <div class="helped-fund-img">
                                    <figure class="image-anime">
                                        <img src="{{ !empty($aboutPageData['about_helped_fund_image']) ? asset('storage/' . $aboutPageData['about_helped_fund_image']) : asset('frontend/assets/images/helped-fund-img.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div class="helped-fund-content">
                                    <h2><span class="counter">{{ $aboutPageData['about_helped_fund_count'] ?? '75958' }}</span></h2>
                                    <h3>{{ $aboutPageData['about_helped_fund_title'] ?? 'helped fund' }}</h3>
                                    <p>{{ $aboutPageData['about_helped_fund_description'] ?? 'Supporting growth through community- funding.' }}</p>
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

    <!-- Our Approah Section Start -->
    <div class="our-approach">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Our Approach Box Content Start -->
                    <div class="our-approach-box-content">
                        <!-- Our Approach Content Start -->
                        <div class="our-approach-content">
                            <!-- Section Title Start -->
                            <div class="section-title">
                                <h3 class="wow fadeInUp">{{ $aboutPageData['approach_subtitle'] ?? 'our approach' }}</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['approach_title'] ?? 'Compassionate solutions for lasting impact' }}</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['approach_description'] ?? 'Our approach focuses on creating sustainable change by addressing root causes, empowering communities, and delivering compassionate solutions.' }}</p>
                            </div>
                            <!-- Section Title End -->

                            <!-- Our Approach Button Start -->
                            <div class="our-approach-btn wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ $aboutPageData['approach_button_link'] ?? url('/contact-us') }}" class="btn-default">{{ $aboutPageData['approach_button_text'] ?? 'contact now' }}</a>
                            </div>
                            <!-- Our Approach Button End -->

                            <!-- Mission Vision Box Start -->
                            <div class="mission-vision-box wow fadeInUp" data-wow-delay="0.6s">
                                <!-- Mission Vision Item Start -->
                                <div class="mission-vision-item">
                                    <div class="icon-box">
                                        <img src="{{asset('frontend/assets/images/icon-our-mission.svg')}}" alt="">
                                    </div>

                                    <div class="mission-vision-content">
                                        <h3>{{ $aboutPageData['approach_mission_title'] ?? 'our mission' }}</h3>
                                        <p>{{ $aboutPageData['approach_mission_description'] ?? 'We strive to create positive change, empower.' }}</p>
                                    </div>
                                </div>
                                <!-- Mission Vision Item End -->

                                <!-- Mission Vision Item Start -->
                                <div class="mission-vision-item">
                                    <div class="icon-box">
                                        <img src="{{asset('frontend/assets/images/icon-our-vision.svg')}}" alt="">
                                    </div>

                                    <div class="mission-vision-content">
                                        <h3>{{ $aboutPageData['approach_vision_title'] ?? 'our vision' }}</h3>
                                        <p>{{ $aboutPageData['approach_vision_description'] ?? 'We strive to create positive change, empower.' }}</p>
                                    </div>
                                </div>
                                <!-- Mission Vision Item End -->

                                <!-- Mission Vision Item Start -->
                                <div class="mission-vision-item">
                                    <div class="icon-box">
                                        <img src="{{asset('frontend/assets/images/icon-our-value.svg')}}" alt="">
                                    </div>

                                    <div class="mission-vision-content">
                                        <h3>{{ $aboutPageData['approach_value_title'] ?? 'our value' }}</h3>
                                        <p>{{ $aboutPageData['approach_value_description'] ?? 'We strive to create positive change, empower.' }}</p>
                                    </div>
                                </div>
                                <!-- Mission Vision Item End -->
                            </div>
                            <!-- Mission Vision Box End -->
                        </div>
                        <!-- Our Approach Content End -->

                        <!-- Our Approach Image Start -->
                        <div class="our-approach-image">
                            <figure class="image-anime">
                                <img src="{{ !empty($aboutPageData['approach_image']) ? asset('storage/' . $aboutPageData['approach_image']) : asset('frontend/assets/images/our-approach-image.jpg') }}" alt="">
                            </figure>
                        </div>
                        <!-- Our Approach Image End -->
                    </div>
                    <!-- Our Approach Box Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Approah Section End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Why Choose Images Start -->
                    <div class="why-choose-images">
                        <div class="why-choose-image-1">
                            <figure class="image-anime">
                                <img src="{{ !empty($aboutPageData['why_image1']) ? asset('storage/' . $aboutPageData['why_image1']) : asset('frontend/assets/images/why-choose-img-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <div class="why-choose-image-2">
                            <figure class="image-anime">
                                <img src="{{ !empty($aboutPageData['why_image2']) ? asset('storage/' . $aboutPageData['why_image2']) : asset('frontend/assets/images/why-choose-img-2.jpg') }}" alt="">
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
                            <h3 class="wow fadeInUp">{{ $aboutPageData['why_subtitle'] ?? 'why choose us' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['why_title'] ?? 'Why we stand out together' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['why_description'] ?? 'Our dedication, transparency, and community-driven approach set us apart. partnering with us,programs that create meaningful change.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Why Choose List Start -->
                        <div class="why-choose-list wow fadeInUp" data-wow-delay="0.4s">
                            <ul>
                                @foreach($whyPoints as $whyPoint)
                                    <li>{{ $whyPoint }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Why Choose List End -->

                        <!-- Why Choose Counters Start -->
                        <div class="why-choose-counters">
                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $aboutPageData['why_counter1_number'] ?? '25' }}</span>+</h2>
                                <p>{{ $aboutPageData['why_counter1_label'] ?? 'Years of experience' }}</p>
                            </div>
                            <!-- Why Choose Counters Item End -->

                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $aboutPageData['why_counter2_number'] ?? '230' }}</span>+</h2>
                                <p>{{ $aboutPageData['why_counter2_label'] ?? 'Thousands volunteers' }}</p>
                            </div>
                            <!-- Why Choose Counters Item End -->

                            <!-- Why Choose Counters Item Start -->
                            <div class="why-choose-counter-item">
                                <h2><span class="counter">{{ $aboutPageData['why_counter3_number'] ?? '400' }}</span>+</h2>
                                <p>{{ $aboutPageData['why_counter3_label'] ?? 'Word wide office' }}</p>
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

    <!-- How We Help Section Start -->
    <div class="how-we-help">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- How We Help Content Start -->
                    <div class="how-we-help-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $aboutPageData['how_subtitle'] ?? 'how we help' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['how_title'] ?? 'Bringing hope to every community' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['how_description'] ?? 'We work tirelessly to uplift communities by providing resources, support, and sustainable solutions, fostering hope and creating brighter futures.' }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- How We Help Body Start -->
                        <div class="how-we-help-body wow fadeInUp" data-wow-delay="0.4s">
                            <ul>
                                @foreach($howPoints as $howPoint)
                                    <li>{{ $howPoint }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- How We Help Body End -->

                        <!-- How We Help Button Start -->
                        <div class="how-we-help-btn wow fadeInUp" data-wow-delay="0.6s">
                            <a href="{{ $aboutPageData['how_button_link'] ?? url('/contact-us') }}" class="btn-default">{{ $aboutPageData['how_button_text'] ?? 'contact now' }}</a>
                        </div>
                        <!-- How We Help Button End -->
                    </div>
                    <!-- How We Help Content End -->
                </div>

                <div class="col-lg-6">
                    <!-- How Help List Start -->
                    <div class="how-help-list">
                        <!-- How Help Item Start -->
                        <div class="how-help-item wow fadeInUp">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-help-1.svg')}}" alt="">
                            </div>
                            <div class="how-help-item-content">
                                <h3>{{ $aboutPageData['how_item1_title'] ?? 'healthcare access' }}</h3>
                                <p>{{ $aboutPageData['how_item1_description'] ?? 'Providing medical care, health education, and wellness resources.' }}</p>
                            </div>
                        </div>
                        <!-- How Help Item End -->

                        <!-- How Help Item Start -->
                        <div class="how-help-item wow fadeInUp" data-wow-delay="0.2s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-help-2.svg')}}" alt="">
                            </div>
                            <div class="how-help-item-content">
                                <h3>{{ $aboutPageData['how_item2_title'] ?? 'hunger relief' }}</h3>
                                <p>{{ $aboutPageData['how_item2_description'] ?? 'Providing medical care, health education, and wellness resources.' }}</p>
                            </div>
                        </div>
                        <!-- How Help Item End -->

                        <!-- How Help Item Start -->
                        <div class="how-help-item wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-help-3.svg')}}" alt="">
                            </div>
                            <div class="how-help-item-content">
                                <h3>{{ $aboutPageData['how_item3_title'] ?? 'educational support' }}</h3>
                                <p>{{ $aboutPageData['how_item3_description'] ?? 'Providing medical care, health education, and wellness resources.' }}</p>
                            </div>
                        </div>
                        <!-- How Help Item End -->

                        <!-- How Help Item Start -->
                        <div class="how-help-item wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-help-4.svg')}}" alt="">
                            </div>
                            <div class="how-help-item-content">
                                <h3>{{ $aboutPageData['how_item4_title'] ?? 'awareness campaigns' }}</h3>
                                <p>{{ $aboutPageData['how_item4_description'] ?? 'Providing medical care, health education, and wellness resources.' }}</p>
                            </div>
                        </div>
                        <!-- How Help Item End -->
                    </div>
                    <!-- How Help List End -->
                </div>
            </div>
        </div>
    </div>
    <!-- How We Help Section End -->

    <!-- Our Features Section Start -->
    <div class="our-features">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $aboutPageData['features_subtitle'] ?? 'our feature' }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['features_title'] ?? 'Highlights our impactful work' }}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['features_description'] ?? "Discover the positive change we've created through our programs, partnerships, and dedicated efforts. From healthcare and education to environmental sustainability." }}</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Our Features List Start -->
                    <div class="our-features-list">
                        <!-- Our Features Item Start -->
                        <div class="our-features-item">
                            <!-- Our Features Image Start -->
                            <div class="our-features-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ !empty($aboutPageData['features_item1_image']) ? asset('storage/' . $aboutPageData['features_item1_image']) : asset('frontend/assets/images/our-features-img-1.jpg') }}" alt="">
                                </figure>
                            </div>
                            <!-- Our Features Image End -->

                            <!-- Our Features Content Start -->
                            <div class="our-features-content">
                                <div class="our-features-body">
                                    <h2><span class="counter">{{ $aboutPageData['features_item1_percent'] ?? '96' }}</span>%</h2>
                                    <h3>{{ $aboutPageData['features_item1_title'] ?? 'healthcare support' }}</h3>
                                    <p>{{ $aboutPageData['features_item1_description'] ?? 'Provide essential healthcare services and resources to communities.' }}</p>
                                </div>
                                <div class="icon-box">
                                    <img src="{{asset('frontend/assets/images/icon-our-features-1.svg')}}" alt="">
                                </div>
                            </div>
                            <!-- Our Features Content End -->
                        </div>
                        <!-- Our Features Item End -->

                        <!-- Our Features Item Start -->
                        <div class="our-features-item">
                            <!-- Our Features Image Start -->
                            <div class="our-features-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ !empty($aboutPageData['features_item2_image']) ? asset('storage/' . $aboutPageData['features_item2_image']) : asset('frontend/assets/images/our-features-img-2.jpg') }}" alt="">
                                </figure>
                            </div>
                            <!-- Our Features Image End -->

                            <!-- Our Features Content Start -->
                            <div class="our-features-content">
                                <div class="our-features-body">
                                    <h2><span class="counter">{{ $aboutPageData['features_item2_percent'] ?? '94' }}</span>%</h2>
                                    <h3>{{ $aboutPageData['features_item2_title'] ?? 'education support' }}</h3>
                                    <p>{{ $aboutPageData['features_item2_description'] ?? 'Provide essential healthcare services and resources to communities.' }}</p>
                                </div>
                                <div class="icon-box">
                                    <img src="{{asset('frontend/assets/images/icon-our-features-2.svg')}}" alt="">
                                </div>
                            </div>
                            <!-- Our Features Content End -->
                        </div>
                        <!-- Our Features Item End -->

                        <!-- Our Features Item Start -->
                        <div class="our-features-item">
                            <!-- Our Features Image Start -->
                            <div class="our-features-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ !empty($aboutPageData['features_item3_image']) ? asset('storage/' . $aboutPageData['features_item3_image']) : asset('frontend/assets/images/our-features-img-3.jpg') }}" alt="">
                                </figure>
                            </div>
                            <!-- Our Features Image End -->

                            <!-- Our Features Content Start -->
                            <div class="our-features-content">
                                <div class="our-features-body">
                                    <h2><span class="counter">{{ $aboutPageData['features_item3_percent'] ?? '95' }}</span>%</h2>
                                    <h3>{{ $aboutPageData['features_item3_title'] ?? 'food support' }}</h3>
                                    <p>{{ $aboutPageData['features_item3_description'] ?? 'Provide essential healthcare services and resources to communities.' }}</p>
                                </div>
                                <div class="icon-box">
                                    <img src="{{asset('frontend/assets/images/icon-our-features-3.svg')}}" alt="">
                                </div>
                            </div>
                            <!-- Our Features Content End -->
                        </div>
                        <!-- Our Features Item End -->
                    </div>
                    <!-- Our Features List End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Features Section End -->

    <!-- Our Fact Section Start -->
    <div class="our-fact">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Our Fact Image Start -->
                    <div class="our-fact-image">
                        <figure class="image-anime reveal">
                            <img src="{{ !empty($aboutPageData['fact_image']) ? asset('storage/' . $aboutPageData['fact_image']) : asset('frontend/assets/images/our-fact-image.jpg') }}" alt="">
                        </figure>
                    </div>
                    <!-- Our Fact Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- Our Fact Content Start -->
                    <div class="our-fact-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $aboutPageData['fact_subtitle'] ?? 'some fact' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['fact_title'] ?? 'Impactful numbers that inspire change' }}</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">{{ $aboutPageData['fact_description'] ?? "Discover the transformative impact of our initiatives through key figures that highlight the progress we've made together in empowering communities and changing lives." }}</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Our Fact Body Start -->
                        <div class="our-fact-body">
                            <!-- Fact Counter List Start -->
                            <div class="fact-counter-list">
                                <!-- Fact Counter Item Start -->
                                <div class="fact-counter-item">
                                    <h2><span class="counter">{{ $aboutPageData['fact_counter1_number'] ?? '25' }}</span>+</h2>
                                    <p>{{ $aboutPageData['fact_counter1_label'] ?? 'years of experience' }}</p>
                                </div>
                                <!-- Fact Counter Item End -->

                                <!-- Fact Counter Item Start -->
                                <div class="fact-counter-item">
                                    <h2><span class="counter">{{ $aboutPageData['fact_counter2_number'] ?? '95' }}</span>%</h2>
                                    <p>{{ $aboutPageData['fact_counter2_label'] ?? 'food support' }}</p>
                                </div>
                                <!-- Fact Counter Item End -->
                            </div>
                            <!-- Fact Counter List End -->

                            <!-- Fact Body Image Start -->
                            <div class="fact-body-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ !empty($aboutPageData['fact_body_image']) ? asset('storage/' . $aboutPageData['fact_body_image']) : asset('frontend/assets/images/fact-body-image.jpg') }}" alt="">
                                </figure>
                            </div>
                            <!-- Fact Body Image End -->
                        </div>
                        <!-- Our Fact Body End -->
                    </div>
                    <!-- Our Fact Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Fact Section End -->

    <!-- Our Team Section Start -->
    <div class="our-team">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">our team</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Dedicated hearts behind our mission</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Meet the passionate individuals driving our mission forward, committed to creating meaningful change and building a brighter future for all.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Team Item Start -->
                    <div class="team-item wow fadeInUp">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <a href="team-single.html" data-cursor-text="View">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/team-1.jpg')}}" alt="">
                                </figure>
                            </a>
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3><a href="team-single.html">kristin watson</a></h3>
                            <p>founder & executive director</p>
                        </div>
                        <!-- Team Content End -->

                        <!-- Team Social Icon Start -->
                        <div class="team-social-icon">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- Team Social Icon End -->
                    </div>
                    <!-- Team Item Start -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Item Start -->
                    <div class="team-item wow fadeInUp" data-wow-delay="0.2s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <a href="team-single.html" data-cursor-text="View">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/team-2.jpg')}}" alt="">
                                </figure>
                            </a>
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3><a href="team-single.html">sophia martinez</a></h3>
                            <p>communications director</p>
                        </div>
                        <!-- Team Content End -->

                        <!-- Team Social Icon Start -->
                        <div class="team-social-icon">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- Team Social Icon End -->
                    </div>
                    <!-- Team Item Start -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Item Start -->
                    <div class="team-item wow fadeInUp" data-wow-delay="0.4s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <a href="team-single.html" data-cursor-text="View">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/team-3.jpg')}}" alt="">
                                </figure>
                            </a>
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3><a href="team-single.html">michael carter</a></h3>
                            <p>program manager</p>
                        </div>
                        <!-- Team Content End -->

                        <!-- Team Social Icon Start -->
                        <div class="team-social-icon">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- Team Social Icon End -->
                    </div>
                    <!-- Team Item Start -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Item Start -->
                    <div class="team-item wow fadeInUp"  data-wow-delay="0.6s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <a href="team-single.html" data-cursor-text="View">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/team-4.jpg')}}" alt="">
                                </figure>
                            </a>
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3><a href="team-single.html">olivia thompson</a></h3>
                            <p>community outreach specialist</p>
                        </div>
                        <!-- Team Content End -->

                        <!-- Team Social Icon Start -->
                        <div class="team-social-icon">
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- Team Social Icon End -->
                    </div>
                    <!-- Team Item Start -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Team Section End -->

    <!-- Our Testimonials Section Start -->
    <div class="our-testimonials">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Testimonials Image Start -->
                    <div class="testimonials-image">
                        <div class="testimonials-img">
                            <figure class="image-anime reveal">
                                <img src="{{ !empty($aboutPageData['testimonials_image']) ? asset('storage/' . $aboutPageData['testimonials_image']) : asset('frontend/assets/images/testimonials-image.jpg') }}" alt="">
                            </figure>
                        </div>

                        <div class="helthcare-support-circle">
                            <a href="contact.html">
                                <img src="{{asset('frontend/assets/images/healthcare-support-circle.svg')}}" alt="">
                            </a>
                        </div>

                        <div class="client-review-box">
                            <h2><span class="counter">{{ $aboutPageData['testimonials_review_count'] ?? '20' }}</span>k</h2>
                            <p>{{ $aboutPageData['testimonials_review_label'] ?? 'customer review' }}</p>
                        </div>
                    </div>
                    <!-- Testimonials Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- Testimonials Content Start -->
                    <div class="testimonials-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $aboutPageData['testimonials_subtitle'] ?? 'testimonials' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['testimonials_title'] ?? 'What people say about us' }}</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- Testimonial Slider Start -->
                        <div class="testimonial-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper" data-cursor-text="Drag">
                                    <!-- Testimonial Slide Start -->
                                    <div class="swiper-slide">
                                        <!-- Testimonial Item Start -->
                                        <div class="testimonial-item">
                                            <!-- Testimonial Header Start -->
                                            <div class="testimonial-header">
                                                <!-- Author Info Start -->
                                                <div class="author-info">
                                                    <!-- Author Image Start -->
                                                    <div class="author-image">
                                                        <figure class="image-anime">
                                                            <img src="{{ !empty($aboutPageData['testimonials_item1_image']) ? asset('storage/' . $aboutPageData['testimonials_item1_image']) : asset('frontend/assets/images/author-1.jpg') }}" alt="">
                                                        </figure>
                                                    </div>
                                                    <!-- Author Image End -->

                                                    <!-- Author Content Start -->
                                                    <div class="author-content">
                                                        <h3>{{ $aboutPageData['testimonials_item1_name'] ?? 'eleanor pena' }}</h3>
                                                        <p>{{ $aboutPageData['testimonials_item1_designation'] ?? 'volunteer coordinator' }}</p>
                                                    </div>
                                                    <!-- Author Content End -->
                                                </div>
                                                <!-- Author Info Author End -->

                                                <!-- Testimonial Rating Start -->
                                                <div class="testimonial-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                                <!-- Testimonial Rating End -->
                                            </div>
                                            <!-- Testimonial Header End -->

                                            <!-- Testimonial Content Start -->
                                            <div class="testimonial-content">
                                                <p>"{{ $aboutPageData['testimonials_item1_quote'] ?? "Working with our team has been a truly inspiring experience. Their dedication to uplifting communities and creating sustainable change is unmatched." }}"</p>
                                            </div>
                                            <!-- Testimonial Content End -->
                                        </div>
                                        <!-- Testimonial Item End -->
                                    </div>
                                    <!-- Testimonial Slide End -->

                                    <!-- Testimonial Slide Start -->
                                    <div class="swiper-slide">
                                        <!-- Testimonial Item Start -->
                                        <div class="testimonial-item">
                                            <!-- Testimonial Header Start -->
                                            <div class="testimonial-header">
                                                <!-- Author Info Start -->
                                                <div class="author-info">
                                                    <!-- Author Image Start -->
                                                    <div class="author-image">
                                                        <figure class="image-anime">
                                                            <img src="{{ !empty($aboutPageData['testimonials_item2_image']) ? asset('storage/' . $aboutPageData['testimonials_item2_image']) : asset('frontend/assets/images/author-2.jpg') }}" alt="">
                                                        </figure>
                                                    </div>
                                                    <!-- Author Image End -->

                                                    <!-- Author Content Start -->
                                                    <div class="author-content">
                                                        <h3>{{ $aboutPageData['testimonials_item2_name'] ?? 'michael carter' }}</h3>
                                                        <p>{{ $aboutPageData['testimonials_item2_designation'] ?? 'program manager' }}</p>
                                                    </div>
                                                    <!-- Author Content End -->
                                                </div>
                                                <!-- Author Info Author End -->

                                                <!-- Testimonial Rating Start -->
                                                <div class="testimonial-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                                <!-- Testimonial Rating End -->
                                            </div>
                                            <!-- Testimonial Header End -->

                                            <!-- Testimonial Content Start -->
                                            <div class="testimonial-content">
                                                <p>"{{ $aboutPageData['testimonials_item2_quote'] ?? "Their dedication to uplifting communities and creating sustainable change is unmatched." }}"</p>
                                            </div>
                                            <!-- Testimonial Content End -->
                                        </div>
                                        <!-- Testimonial Item End -->
                                    </div>
                                    <!-- Testimonial Slide End -->

                                    <!-- Testimonial Slide Start -->
                                    <div class="swiper-slide">
                                        <!-- Testimonial Item Start -->
                                        <div class="testimonial-item">
                                            <!-- Testimonial Header Start -->
                                            <div class="testimonial-header">
                                                <!-- Author Info Start -->
                                                <div class="author-info">
                                                    <!-- Author Image Start -->
                                                    <div class="author-image">
                                                        <figure class="image-anime">
                                                            <img src="{{ !empty($aboutPageData['testimonials_item3_image']) ? asset('storage/' . $aboutPageData['testimonials_item3_image']) : asset('frontend/assets/images/author-3.jpg') }}" alt="">
                                                        </figure>
                                                    </div>
                                                    <!-- Author Image End -->

                                                    <!-- Author Content Start -->
                                                    <div class="author-content">
                                                        <h3>{{ $aboutPageData['testimonials_item3_name'] ?? 'sophi martinez' }}</h3>
                                                        <p>{{ $aboutPageData['testimonials_item3_designation'] ?? 'communications director' }}</p>
                                                    </div>
                                                    <!-- Author Content End -->
                                                </div>
                                                <!-- Author Info Author End -->

                                                <!-- Testimonial Rating Start -->
                                                <div class="testimonial-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </div>
                                                <!-- Testimonial Rating End -->
                                            </div>
                                            <!-- Testimonial Header End -->

                                            <!-- Testimonial Content Start -->
                                            <div class="testimonial-content">
                                                <p>"{{ $aboutPageData['testimonials_item3_quote'] ?? "Through their programs, I've seen lives transformed and hope restored." }}"</p>
                                            </div>
                                            <!-- Testimonial Content End -->
                                        </div>
                                        <!-- Testimonial Item End -->
                                    </div>
                                    <!-- Testimonial Slide End -->
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

    <!-- Our Faqs Section Start -->
    <div class="our-faqs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="faqs-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ $aboutPageData['faq_subtitle'] ?? 'faq' }}</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $aboutPageData['faq_title'] ?? 'Answers to important your questions' }}</h2>
                        </div>
                        <!-- Section Title End -->

                        <!-- FAQ Accordion Start -->
                        <div class="faq-accordion" id="faqaccordion">
                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        {{ $aboutPageData['faq1_question'] ?? 'What is the mission of your organization?' }}
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>{{ $aboutPageData['faq1_answer'] ?? 'You can volunteer, donate, or partner with us to support our initiatives. Visit our Get Involved page for more details.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.2s">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        {{ $aboutPageData['faq2_question'] ?? 'How can I get involved?' }}
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse show" aria-labelledby="heading2" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>{{ $aboutPageData['faq2_answer'] ?? 'You can volunteer, donate, or partner with us to support our initiatives. Visit our Get Involved page for more details.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.4s">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        {{ $aboutPageData['faq3_question'] ?? 'Where do your donations go?' }}
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>{{ $aboutPageData['faq3_answer'] ?? 'You can volunteer, donate, or partner with us to support our initiatives. Visit our Get Involved page for more details.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.6s">
                                <h2 class="accordion-header" id="heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        {{ $aboutPageData['faq4_question'] ?? 'Are my donations tax-deductible?' }}
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>{{ $aboutPageData['faq4_answer'] ?? 'You can volunteer, donate, or partner with us to support our initiatives. Visit our Get Involved page for more details.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.8s">
                                <h2 class="accordion-header" id="heading5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                        {{ $aboutPageData['faq5_question'] ?? 'How can I volunteer with your organization?' }}
                                    </button>
                                </h2>
                                <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#faqaccordion">
                                    <div class="accordion-body">
                                        <p>{{ $aboutPageData['faq5_answer'] ?? 'You can volunteer, donate, or partner with us to support our initiatives. Visit our Get Involved page for more details.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->
                        </div>
                        <!-- FAQ Accordion End -->
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Faqs Image Start -->
                    <div class="faqs-image">
                        <div class="faqs-img-1">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/faqs-image-1.jpg')}}" alt="">
                            </figure>
                        </div>

                        <div class="faqs-img-2">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/faqs-image-2.jpg')}}" alt="">
                            </figure>
                        </div>
                    </div>
                    <!-- Faqs Image End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Faqs Section End -->

@endsection
