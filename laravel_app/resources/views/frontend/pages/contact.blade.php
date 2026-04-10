
@extends('frontend.layouts.app')

@section('content')

    @php
        $contactData = is_array($contactData ?? null) ? $contactData : [];
        $pageTitle = $contactData['contact_page_title'] ?? 'Contact';
        $pageHeading = $contactData['contact_page_heading'] ?? 'Contact us';
        $formSubtitle = $contactData['contact_form_subtitle'] ?? 'contact us';
        $formTitle = $contactData['contact_form_title'] ?? 'Get in to touch';

        $contactPhone = $contactData['contact_phone'] ?? '+123 456 789';
        $contactPhoneAlt = $contactData['contact_phone_alt'] ?? '+123 456 789';
        $contactPhoneDial = preg_replace('/[^0-9+]/', '', (string) $contactPhone);
        $contactPhoneAltDial = preg_replace('/[^0-9+]/', '', (string) $contactPhoneAlt);

        $contactEmail = $contactData['contact_email'] ?? 'example@mail.com';
        $contactEmailAlt = $contactData['contact_email_alt'] ?? 'domainname@gmail.com';
        $contactAddress = $contactData['contact_address'] ?? '12345 Unity Avenue Suite 100 Springfield, USA 54321';
        $contactMapIframe = $contactData['contact_map_iframe'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96737.10562045308!2d-74.08535042841811!3d40.739265258395164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1703158537552!5m2!1sen!2sin';
    @endphp

    <!-- Page Header Start -->
    <div class="page-header parallaxie" @if(!empty($contactData['page_header_image'])) style="background-image: url('{{ asset('storage/' . $contactData['page_header_image']) }}');" @endif>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $pageHeading }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::lower($pageTitle) }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container mt-4">
        @if(session('contact_success'))
            <div class="alert alert-success" role="alert">{{ session('contact_success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif
    </div>

<!-- Page Contact Us Start -->
<div class="page-contact-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Contact Info Box Start -->
                <div class="contact-info-box">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp">
                        <div class="icon-box">
                            <img src="images/icon-phone-primary.svg" alt="">
                        </div>
                        <div class="contact-info-content">
                            <h3>contact us</h3>
                            <p><a href="tel:{{ $contactPhoneDial }}">{{ $contactPhone }}</a></p>
                            <p><a href="tel:{{ $contactPhoneAltDial }}">{{ $contactPhoneAlt }}</a></p>
                        </div>
                    </div>
                    <!-- Contact Info Item End -->

                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="icon-box">
                            <img src="images/icon-mail.svg" alt="">
                        </div>
                        <div class="contact-info-content">
                            <h3>e-mail us</h3>
                            <p><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                            <p><a href="mailto:{{ $contactEmailAlt }}">{{ $contactEmailAlt }}</a></p>
                        </div>
                    </div>
                    <!-- Contact Info Item End -->

                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="icon-box">
                            <img src="images/icon-location.svg" alt="">
                        </div>
                        <div class="contact-info-content">
                            <h3>location</h3>
                            <p>{{ $contactAddress }}</p>
                        </div>
                    </div>
                    <!-- Contact Info Item End -->
                </div>
                <!-- Contact Info Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Contact Us End -->

<!-- Contact Form Section Start -->
<div class="contact-form-section">
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-lg-6 order-lg-1 order-2">
                <!-- Google Map Start -->
                <div class="google-map-iframe">
                    <iframe src="{{ $contactMapIframe }}" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <!-- Google Map End -->
            </div>

            <div class="col-lg-6 order-lg-2 order-1">
                <!-- Contact Form Start -->
                <div class="contact-form-box">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ $formSubtitle }}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $formTitle }}</h2>
                    </div>
                    <!-- Section Title End -->

                    <div class="contact-form">
                        <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" data-toggle="validator" class="wow fadeInUp js-recaptcha-form" data-wow-delay="0.2s" data-recaptcha-action="contact_submit">
                            @csrf
                            <input type="hidden" name="recaptcha_token" class="js-recaptcha-token">
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="fname" class="form-control" id="fname" placeholder="First name" required>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="lname" class="form-control" id="lname" placeholder="Last name" required>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-4">
                                    <input type="email" name ="email" class="form-control" id="email" placeholder="Enter your e-mail" required>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-4">
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter your phone no." required>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-5">
                                    <textarea name="message" class="form-control" id="message" rows="4" placeholder="Write message"></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-default"><span>send message</span></button>
                                    <div id="msgSubmit" class="h3 hidden"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Contact Form End -->
            </div>
        </div>
    </div>
</div>
<!-- Contact Form Section End -->
@endsection

@push('js')
    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const recaptchaSiteKey = @json(config('services.recaptcha.site_key'));
            if (!recaptchaSiteKey || typeof grecaptcha === 'undefined') {
                return;
            }

            document.querySelectorAll('form.js-recaptcha-form').forEach(function (formElement) {
                formElement.addEventListener('submit', function (event) {
                    if (formElement.dataset.recaptchaReady === '1') {
                        return;
                    }

                    event.preventDefault();

                    const tokenInput = formElement.querySelector('.js-recaptcha-token');
                    const action = formElement.dataset.recaptchaAction || 'submit';

                    grecaptcha.ready(function () {
                        grecaptcha.execute(recaptchaSiteKey, { action: action }).then(function (token) {
                            if (tokenInput) {
                                tokenInput.value = token;
                            }
                            formElement.dataset.recaptchaReady = '1';
                            formElement.submit();
                        });
                    });
                });
            });
        });
    </script>
@endpush
