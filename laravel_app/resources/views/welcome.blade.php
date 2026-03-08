@extends('layouts.app')

@section('content')



<!-- Hero Section Start -->
<div class="hero hero-video">
    <!-- Video Start -->
    <div class="hero-bg-video">
        <!-- Selfhosted Video Start -->
        <!-- <video autoplay muted loop id="myVideo"><source src="{{asset('frontend/assets/images/hero-bg-video.mp4')}}" type="video/mp4"></video> -->
        <video autoplay muted loop id="myVideo"><source src="https://demo.awaikenthemes.com/assets/videos/lenity-video.mp4')}}" type="video/mp4"></video>

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
                        <h3 class="wow fadeInUp">welcome our charity</h3>
                        <h1 class="text-anime-style-2" data-cursor="-opaque"><span>Empower change</span>, one act of kindness at a time</h1>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Join us in creating brighter futures by providing hope, delivering help, and fostering lasting change for communities in need around the world.</p>
                    </div>
                    <!-- Section Title End -->

                    <!-- Hero Content Body Start -->
                    <div class="hero-body wow fadeInUp" data-wow-delay="0.4s">
                        <!-- Hero Button Start -->
                        <div class="hero-btn">
                            <a href="donation.html" class="btn-default">donate now</a>
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
                                <li>Education and Skill Development</li>
                                <li>Women and Youth Empowerment</li>
                            </ul>
                        </div>

                        <div class="hero-help-families">
                            <h3>help lorem families</h3>
                            <p>Your gift of $235 can feed 40 children</p>
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
                            <img src="{{asset('frontend/assets/images/about-img-1.jpg')}}" alt="">
                        </figure>
                    </div>
                    <!-- About Image 1 End -->

                    <!-- About Image 2 Start -->
                    <div class="about-img-2">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/about-img-2.jpg')}}" alt="">
                        </figure>
                    </div>
                    <!-- About Image 2 End -->

                    <!-- Need Fund Box Start -->
                    <div class="need-fund-box">
                        <img src="{{asset('frontend/assets/images/icon-funded-dollar.svg')}}" alt="">
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
                        <h3 class="wow fadeInUp">about us</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">United in compassion, changing lives</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Driven by compassion and a shared vision, we work hand-in-hand with communities to create meaningful change.</p>
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
                                    <h3>Healthcare Support</h3>
                                    <p>Providing essential healthcare services and resources to communities.</p>
                                </div>
                                <!-- About Support Content End -->
                            </div>
                            <!-- About Support Box End -->

                            <!-- About Button Start -->
                            <div class="about-btn wow fadeInUp" data-wow-delay="0.6s">
                                <a href="about.html" class="btn-default">about us</a>
                            </div>
                            <!-- About Button End -->
                        </div>

                        <!-- Helped Fund Item Start -->
                        <div class="helped-fund-item">
                            <div class="helped-fund-img">
                                <figure class="image-anime">
                                    <img src="{{asset('frontend/assets/images/helped-fund-img.jpg')}}" alt="">
                                </figure>
                            </div>
                            <div class="helped-fund-content">
                                <h2><span class="counter">75,958</span></h2>
                                <h3>helped fund</h3>
                                <p>Supporting growth through community- funding.</p>
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
                    <h3 class="wow fadeInUp">services</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Our comprehensive services</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.2s">Our services are focused on creating lasting change through community development, healthcare access, educational support, and emergency relief.</p>
                </div>
                <!-- Section Title End -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <!-- Services Item Start -->
                <div class="service-item wow fadeInUp">
                    <div class="service-content">
                        <h3><a href="service-single.html">food security initiatives</a></h3>
                        <p>Addressing hunger and malnutrition by providing nutritious meals</p>
                    </div>
                    <div class="service-image">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/services-image-1.jpg')}}" alt="">
                        </figure>
                    </div>
                    <div class="service-btn">
                        <a href="service-single.html" class="readmore-btn">read more</a>
                    </div>
                </div>
                <!-- Services Item End -->
            </div>

            <div class="col-lg-4 col-md-6">
                <!-- Services Item Start -->
                <div class="service-item wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-content">
                        <h3><a href="service-single.html">healthcare access</a></h3>
                        <p>Addressing hunger and malnutrition by providing nutritious meals</p>
                    </div>
                    <div class="service-image">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/services-image-2.jpg')}}" alt="">
                        </figure>
                    </div>
                    <div class="service-btn">
                        <a href="service-single.html" class="readmore-btn">read more</a>
                    </div>
                </div>
                <!-- Services Item End -->
            </div>

            <div class="col-lg-4 col-md-6">
                <!-- Services Item Start -->
                <div class="service-item wow fadeInUp" data-wow-delay="0.4s">
                    <div class="service-content">
                        <h3><a href="service-single.html">educational support</a></h3>
                        <p>Addressing hunger and malnutrition by providing nutritious meals</p>
                    </div>
                    <div class="service-image">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/services-image-3.jpg')}}" alt="">
                        </figure>
                    </div>
                    <div class="service-btn">
                        <a href="service-single.html" class="readmore-btn">read more</a>
                    </div>
                </div>
                <!-- Services Item End -->
            </div>

            <div class="col-lg-12">
                <!-- Service Contact Text Start -->
                <div class="section-footer-text wow fadeInUp" data-wow-delay="0.6s">
                    <p>You will be satisfy with our work. Contact us today <a href="tel:+91123456789">(+91) 123 456 789</a></p>
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
                        <h3 class="wow fadeInUp">what we do</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Building hope creating lasting change</h2>
                    </div>
                    <!-- Section Title End -->

                    <!-- what We List Start -->
                    <div class="what-we-list">
                        <!-- What We Item Start -->
                        <div class="what-we-item wow fadeInUp" data-wow-delay="0.2s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-what-we-1.svg')}}" alt="">
                            </div>
                            <div class="what-we-item-content">
                                <h3>economic empowerment</h3>
                                <p>Empowering individuals through job training, financial literacy, and small business support to create sustainable livelihoods.</p>
                            </div>
                        </div>
                        <!-- What We Item End -->

                        <!-- What We Item Start -->
                        <div class="what-we-item wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-what-we-2.svg')}}" alt="">
                            </div>
                            <div class="what-we-item-content">
                                <h3>clean water and sanitation</h3>
                                <p>Empowering individuals through job training, financial literacy, and small business support to create sustainable livelihoods.</p>
                            </div>
                        </div>
                        <!-- What We Item End -->

                        <!-- What We Item Start -->
                        <div class="what-we-item wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-what-we-3.svg')}}" alt="">
                            </div>
                            <div class="what-we-item-content">
                                <h3>economic empowerment</h3>
                                <p>Empowering individuals through job training, financial literacy, and small business support to create sustainable livelihoods.</p>
                            </div>
                        </div>
                        <!-- What We Item End -->
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
                            <img src="{{asset('frontend/assets/images/what-we-do-image-1.jpg')}}" alt="">
                        </figure>
                    </div>
                    <!-- What We Do Image 1 End -->

                    <!-- What We Do Image 2 Start -->
                    <div class="what-we-do-img-2">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/what-we-do-image-2.jpg')}}" alt="">
                        </figure>
                    </div>
                    <!-- What We Do Image 2 End -->

                    <!-- Donate Now Box Start -->
                    <div class="donate-now-box">
                        <a href="donation.html"><img src="{{asset('frontend/assets/images/icon-donate-now.svg')}}" alt="">donate now</a>
                    </div>
                    <!-- Donate Now Box End -->
                </div>
                <!-- What We Do Images End -->
            </div>
        </div>
    </div>
</div>
<!-- What We Do Section End -->



<!-- Why Choose Us Section Start -->
<div class="why-choose-us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <!-- Why Choose Images Start -->
                <div class="why-choose-images">
                    <div class="why-choose-image-1">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/why-choose-img-1.jpg')}}" alt="">
                        </figure>
                    </div>
                    <div class="why-choose-image-2">
                        <figure class="image-anime">
                            <img src="{{asset('frontend/assets/images/why-choose-img-2.jpg')}}" alt="">
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
                        <h3 class="wow fadeInUp">why choose us</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">Why we stand out together</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">Our dedication, transparency, and community-driven approach set us apart. partnering with us,programs that create meaningful change.</p>
                    </div>
                    <!-- Section Title End -->

                    <!-- Why Choose List Start -->
                    <div class="why-choose-list wow fadeInUp" data-wow-delay="0.4s">
                        <ul>
                            <li>community-centered approach</li>
                            <li>transparency and accountability</li>
                            <li>empowerment through partner</li>
                            <li>volunteer and donor engagement</li>
                        </ul>
                    </div>
                    <!-- Why Choose List End -->

                    <!-- Why Choose Counters Start -->
                    <div class="why-choose-counters">
                        <!-- Why Choose Counters Item Start -->
                        <div class="why-choose-counter-item">
                            <h2><span class="counter">25</span>+</h2>
                            <p>Years of experience</p>
                        </div>
                        <!-- Why Choose Counters Item End -->

                        <!-- Why Choose Counters Item Start -->
                        <div class="why-choose-counter-item">
                            <h2><span class="counter">230</span>+</h2>
                            <p>Thousands volunteers</p>
                        </div>
                        <!-- Why Choose Counters Item End -->

                        <!-- Why Choose Counters Item Start -->
                        <div class="why-choose-counter-item">
                            <h2><span class="counter">400</span>+</h2>
                            <p>Word wide office</p>
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
                    <h3 class="wow fadeInUp">How it work</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">Step by step working process</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.2s">Our step-by-step process ensures meaningful change: identifying community needs, designing tailored programs, implementing sustainable solutions.</p>
                </div>
                <!-- Section Title End -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <!-- How It Work List Start -->
                <div class="how-it-work-list">
                    <!-- How It Work Item Start -->
                    <div class="how-it-work-item">
                        <!-- How It Work Image Start -->
                        <div class="how-it-work-image">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/how-it-work-img-1.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- How It Work Image End -->

                        <!-- How It Work Content Start -->
                        <div class="how-it-work-content wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-it-work-1.svg')}}" alt="">
                            </div>
                            <div class="how-it-work-body">
                                <h3>healthcare support</h3>
                                <p>Provide essential healthcare service and resources to communities.</p>
                            </div>
                        </div>
                        <!-- How It Work Content End -->
                    </div>
                    <!-- How It Work Item End -->

                    <!-- How It Work Item Start -->
                    <div class="how-it-work-item">
                        <!-- How It Work Image Start -->
                        <div class="how-it-work-image">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/how-it-work-img-2.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- How It Work Image End -->

                        <!-- How It Work Content Start -->
                        <div class="how-it-work-content wow fadeInUp" data-wow-delay="0.4s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-it-work-2.svg')}}" alt="">
                            </div>
                            <div class="how-it-work-body">
                                <h3>Plan and design</h3>
                                <p>Provide essential healthcare service and resources to communities.</p>
                            </div>
                        </div>
                        <!-- How It Work Content End -->
                    </div>
                    <!-- How It Work Item End -->

                    <!-- How It Work Item Start -->
                    <div class="how-it-work-item">
                        <!-- How It Work Image Start -->
                        <div class="how-it-work-image">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/how-it-work-img-3.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- How It Work Image End -->

                        <!-- How It Work Content Start -->
                        <div class="how-it-work-content wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-it-work-3.svg')}}" alt="">
                            </div>
                            <div class="how-it-work-body">
                                <h3>Implement solutions</h3>
                                <p>Provide essential healthcare service and resources to communities.</p>
                            </div>
                        </div>
                        <!-- How It Work Content End -->
                    </div>
                    <!-- How It Work Item End -->

                    <!-- How It Work Item Start -->
                    <div class="how-it-work-item">
                        <!-- How It Work Image Start -->
                        <div class="how-it-work-image">
                            <figure class="image-anime reveal">
                                <img src="{{asset('frontend/assets/images/how-it-work-img-4.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- How It Work Image End -->

                        <!-- How It Work Content Start -->
                        <div class="how-it-work-content wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{asset('frontend/assets/images/icon-how-it-work-4.svg')}}" alt="">
                            </div>
                            <div class="how-it-work-body">
                                <h3>Report and share</h3>
                                <p>Provide essential healthcare service and resources to communities.</p>
                            </div>
                        </div>
                        <!-- How It Work Content End -->
                    </div>
                    <!-- How It Work Item End -->
                </div>
                <!-- How It Work List End -->
            </div>

            <div class="col-lg-12">
                <!-- Service Contact Text Start -->
                <div class="section-footer-text how-work-footer-text wow fadeInUp" data-wow-delay="0.8s">
                    <p><span>$250</span> Help Our Kids with Education, Food, Health Support. <a href="donation.html">Donate now</a></p>
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
                            <img src="{{asset('frontend/assets/images/testimonials-image.jpg')}}" alt="">
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
                        <h3 class="wow fadeInUp">testimonials</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">What people say about us</h2>
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
                                                        <img src="{{asset('frontend/assets/images/author-1.jpg')}}" alt="">
                                                    </figure>
                                                </div>
                                                <!-- Author Image End -->

                                                <!-- Author Content Start -->
                                                <div class="author-content">
                                                    <h3>eleanor pena</h3>
                                                    <p>volunteer coordinator</p>
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
                                            <p>"Working with [NGO Name] has been a truly inspiring experience. Their dedication to uplifting communities and creating sustainable change is unmatched. Through their programs, I've seen lives transformed and hope restored"</p>
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
                                                        <img src="{{asset('frontend/assets/images/author-2.jpg')}}" alt="">
                                                    </figure>
                                                </div>
                                                <!-- Author Image End -->

                                                <!-- Author Content Start -->
                                                <div class="author-content">
                                                    <h3>michael carter</h3>
                                                    <p>program manager</p>
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
                                            <p>"Working with [NGO Name] has been a truly inspiring experience. Their dedication to uplifting communities and creating sustainable change is unmatched. Through their programs, I've seen lives transformed and hope restored"</p>
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
                                                        <img src="{{asset('frontend/assets/images/author-3.jpg')}}" alt="">
                                                    </figure>
                                                </div>
                                                <!-- Author Image End -->

                                                <!-- Author Content Start -->
                                                <div class="author-content">
                                                    <h3>sophi martinez</h3>
                                                    <p>communications director</p>
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
                                            <p>"Working with [NGO Name] has been a truly inspiring experience. Their dedication to uplifting communities and creating sustainable change is unmatched. Through their programs, I've seen lives transformed and hope restored"</p>
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




@endsection
