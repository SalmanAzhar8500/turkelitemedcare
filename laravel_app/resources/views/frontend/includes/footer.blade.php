
<footer class="main-footer">
    @php
        $headerFooterData = is_array($headerFooterData ?? null) ? $headerFooterData : [];
        $footerLogoRaw = $headerFooterData['footer_logo'] ?? null;
        $footerLogo = filled($footerLogoRaw)
            ? asset('storage/' . ltrim((string) $footerLogoRaw, '/'))
            : asset('frontend/assets/images/footer-logo.svg');
        $websiteName = $headerFooterData['website_name'] ?? config('app.name');
        $footerAboutText = $headerFooterData['footer_about_text'] ?? 'Committed to compassionate care and better outcomes.';
        $footerPhoneLabel = $headerFooterData['footer_phone_label'] ?? 'Toll free customer care';
        $footerPhone = $headerFooterData['footer_phone'] ?? '+123 456 789';
        $footerPhoneDial = preg_replace('/[^0-9+]/', '', (string) $footerPhone);
        $footerSupportLabel = $headerFooterData['footer_support_label'] ?? 'Need live support!';
        $footerEmail = $headerFooterData['footer_email'] ?? 'info@domainname.com';
        $footerCopyright = $headerFooterData['footer_copyright'] ?? 'Copyright © 2025 All Rights Reserved.';
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Main Footer Box Start -->
                <div class="main-footer-box">
                    <!-- Footer About Start -->
                    <div class="footer-about">
                        <!-- Footer Logo Start -->
                        <div class="footer-logo">
                            <img src="{{ $footerLogo }}" alt="{{ $websiteName }}">
                        </div>
                        <!-- Footer Logo End -->

                        <p>{{ $footerAboutText }}</p>

                        <!-- Footer Contact Detail Start -->
                        <div class="footer-contact-detail">
                            <div class="footer-contact-item">
                                <p>{{ $footerPhoneLabel }}</p>
                                <h3><a href="tel:{{ $footerPhoneDial }}">{{ $footerPhone }}</a></h3>
                            </div>

                            <div class="footer-contact-item">
                                <p>{{ $footerSupportLabel }}</p>
                                <h3><a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></h3>
                            </div>
                        </div>
                        <!-- Footer Contact Detail End -->

                        <!-- Footer Social Links Start -->
                        <div class="footer-social-links">
                            <h3>Follow on</h3>
                            <ul>
                                <li><a href="#"><i class="fa-brands fa-pinterest-p"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                        <!-- Footer Social Links End -->
                    </div>
                    <!-- Footer About End -->

                    <!-- Footer Links Box Start -->
                    <div class="footer-links-box">
                        <!-- Newsletter Form Start -->
                        <div class="newsletter-form">
                            <form id="newsletterForm" action="#" method="POST">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="mail" placeholder="Enter Your Email" required="">
                                    <button type="submit" class="newsletter-btn"><i class="fa-regular fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>
                        <!-- Newsletter Form End -->

                        <!-- Footer Links Start -->
                        <div class="footer-links">
                            <h3>Quick link</h3>
                            <ul>
                                <li><a href="/">home</a></li>
                                <li><a href="/about-us">about us</a></li>
                                <li><a href="/services">services</a></li>
                                <li><a href="/contact-us">Contact Us</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links End -->

                        <!-- Footer Links Start -->
                        <div class="footer-links footer-service-links">
                            <h3>Services</h3>
                            <ul>
                                @foreach($services as $service)
                                    <li>
                                        <a href="{{ url('services/'.$service->slug) }}">
                                            {{ $service->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Footer Links End -->

                        <!-- Footer Links Start -->
                        <div class="footer-links">
                            <h3>support</h3>
                            <ul>
                                <!--<li><a href="#">help</a></li>-->
                                <!--<li><a href="#">privacy policy</a></li>-->
                                <!--<li><a href="#">term's & condition</a></li>-->
                                <li><a href="/contact-us">support</a></li>
                            </ul>
                        </div>
                        <!-- Footer Links End -->
                    </div>
                    <!-- Footer Links Box End -->
                </div>
                <!-- Main Footer Box End -->
            </div>
        </div>
    </div>

    <!-- Footer Copyright Start -->
    <div class="footer-copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- Copyright Text Start -->
                    <div class="copyright-text">
                        <p>{{ $footerCopyright }}</p>
                    </div>
                    <!-- Copyright Text End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Copyright End -->
</footer>
<!-- Main Footer Section End -->
