@extends('frontend.layouts.app')

@section('content')
    @php
        $headImageRaw = $detailContent['head_image'] ?? null;
        $headImageUrl = filled($headImageRaw)
            ? (\Illuminate\Support\Str::startsWith((string) $headImageRaw, ['http://', 'https://', '/', 'data:image/'])
                ? $headImageRaw
                : asset('storage/' . ltrim((string) $headImageRaw, '/')))
            : null;
        $tabOneLabel = $detailContent['tab_one_label'] ?? 'Online Hair Analysis';
        $tabTwoLabel = $detailContent['tab_two_label'] ?? 'Booking';
        $tabOneTitle = $detailContent['tab_one_title'] ?? 'Get a Free Online Hair Analysis';
        $tabTwoTitle = $detailContent['tab_two_title'] ?? 'Book Consultation';
        $formMode = $detailContent['form_mode'] ?? 'tabs';
        $isAnalysisOnly = $formMode === 'analysis_only';
        $isOnlineHairAnalysisTitle = \Illuminate\Support\Str::lower(trim((string) ($guide->name ?? ''))) === 'online hair analysis';
        $hairPaneClass = $isAnalysisOnly ? '' : 'tab-pane fade show active';
        $bookingPaneClass = 'tab-pane fade';
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ $pageHeaderImageUrl }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $guide->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('patient-guide') }}">patient guide</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $guide->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-service-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-donation" style="padding: 0px 0;">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="donation-box" style="padding: 10px">
                                        <div class="donate-form campaign-donate-form">
                                            <div id="guideFormTabContent">
                                                @if($isOnlineHairAnalysisTitle)
                                                <div id="hair-analysis-pane">
                                                    <form action="{{ route('patient-guide.submit.analysis', $guide->slug) }}" method="POST" enctype="multipart/form-data" class="js-recaptcha-form" data-recaptcha-action="hair_analysis">
                                                        @csrf
                                                        <input type="hidden" name="recaptcha_token" class="js-recaptcha-token">
                                                        <div class="donar-personal-info">
                                                            @if($headImageUrl)
                                                                <div class="service-feature-image mb-3">
                                                                    <figure class="image-anime reveal">
                                                                        <img src="{{ $headImageUrl }}" alt="Head Image">
                                                                    </figure>
                                                                </div>
                                                            @endif

                                                            <div class="section-title">
                                                                <h2 class="text-anime-style-2" data-cursor="-opaque">Get a Free Online Hair Analysis</h2>
                                                            </div>

                                                            <div class="row wow fadeInUp" data-wow-delay="0.8s" style="visibility: visible; animation-delay: 0.8s; animation-name: fadeInUp;">
                                                                <div class="form-group col-md-12 mb-4">
                                                                    <input type="text" name="fname" class="form-control" placeholder="First name" required="">
                                                                </div>
                                                                <div class="form-group col-md-12 mb-4">
                                                                    <input type="text" name="lname" class="form-control" placeholder="Last name" required="">
                                                                </div>
                                                                <div class="form-group col-md-12 mb-4">
                                                                    <input type="text" name="phone" class="form-control" placeholder="Enter your phone no." required="">
                                                                </div>
                                                                <div class="form-group col-md-12 mb-4">
                                                                    <input type="email" name="email" class="form-control" placeholder="Email address" required="">
                                                                </div>
                                                                <div class="form-group col-md-12 mb-5">
                                                                    <textarea name="message" class="form-control" rows="4" placeholder="Write message"></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12 mb-5">
                                                                    <div class="mb-3 p-3" style="background:#f6f6f6;border-radius:10px;">
                                                                        <p class="mb-2" style="font-weight:600;">Upload clear head photos from these angles:</p>
                                                                        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:10px;">
                                                                            <div class="text-center" style="min-width:72px;"><div style="width:64px;height:64px;border:2px solid #cfd4da;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;background:#fff;">Front</div><small>Front</small></div>
                                                                            <div class="text-center" style="min-width:72px;"><div style="width:64px;height:64px;border:2px solid #cfd4da;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;background:#fff;">Back</div><small>Back</small></div>
                                                                            <div class="text-center" style="min-width:72px;"><div style="width:64px;height:64px;border:2px solid #cfd4da;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;background:#fff;">Side</div><small>Side</small></div>
                                                                            <div class="text-center" style="min-width:72px;"><div style="width:64px;height:64px;border:2px solid #cfd4da;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 6px;background:#fff;">Top</div><small>Top</small></div>
                                                                        </div>
                                                                    </div>

                                                                    <label class="mb-2">Upload Head Image</label>
                                                                    <input type="file" id="hairAnalysisHeadImage" name="head_image" class="filepond" accept="image/png,image/jpeg,image/webp">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group-btn wow fadeInUp" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: fadeInUp;">
                                                            <button type="submit" class="btn-default">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                @else
                                                    <div id="booking-pane">
                                                        @if(session('booking_success'))
                                                            <div class="alert alert-success mb-4" role="alert" style="border-radius:8px;">
                                                                {{ session('booking_success') }}
                                                            </div>
                                                        @endif
                                                        <form action="{{ route('patient-guide.submit.booking', $guide->slug) }}" method="POST" class="js-recaptcha-form" data-recaptcha-action="booking_request">
                                                            @csrf
                                                            <input type="hidden" name="recaptcha_token" class="js-recaptcha-token">
                                                            <div class="donar-personal-info">
                                                                <div class="section-title">
                                                                    <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $tabTwoTitle }}</h2>
                                                                </div>

                                                                <div class="row wow fadeInUp" data-wow-delay="0.8s" style="visibility: visible; animation-delay: 0.8s; animation-name: fadeInUp;">
                                                                    <div class="form-group col-md-12 mb-4">
                                                                        <input type="text" name="booking_name" class="form-control" placeholder="Full name" required="">
                                                                    </div>
                                                                    <div class="form-group col-md-12 mb-4">
                                                                        <input type="email" name="booking_email" class="form-control" placeholder="Email address" required="">
                                                                    </div>
                                                                    <div class="form-group col-md-12 mb-4">
                                                                        <input type="text" name="booking_phone" class="form-control" placeholder="Phone number" required="">
                                                                    </div>
                                                                    <div class="form-group col-md-12 mb-4">
                                                                        <select name="booking_service_id" class="form-control" required>
                                                                            <option value="">Select Service</option>
                                                                            @foreach(($mainServices ?? collect()) as $mainService)
                                                                                <option value="{{ $mainService->id }}">{{ $mainService->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-12 mb-4">
                                                                        <input type="date" name="booking_date" class="form-control" required="">
                                                                    </div>
                                                                    <div class="form-group col-md-12 mb-5">
                                                                        <textarea name="booking_notes" class="form-control" rows="4" placeholder="Reason / Notes"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group-btn wow fadeInUp" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: fadeInUp;">
                                                                <button type="submit" class="btn-default">Book now</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

{{--                    <div class="page-single-sidebar">--}}
{{--                        <div class="page-sidebar-catagery-list wow fadeInUp">--}}
{{--                            <h3>{{ $detailContent['sidebar_title'] ?? 'Patient Guide Categories' }}</h3>--}}
{{--                            <ul>--}}
{{--                                @forelse($relatedGuides as $relatedGuide)--}}
{{--                                    <li><a href="{{ route('patient-guide.details', $relatedGuide->slug) }}">{{ $relatedGuide->name }}</a></li>--}}
{{--                                @empty--}}
{{--                                    <li><a href="{{ route('patient-guide') }}">{{ $guide->name }}</a></li>--}}
{{--                                @endforelse--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>

                <div class="col-lg-6">
                    <div class="service-single-contemt">
                        <div class="service-entry">
                            @php
                                $decodeRichText = function ($value) {
                                    $decoded = html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                    return html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                };
                            @endphp

                            <h2 class="text-anime-style-2 mb-3" data-cursor="-opaque">{{ implode(' → ', $breadcrumb) }}</h2>


                            @if(!empty($guide->description))
                                @php
                                    $guideDescriptionRaw = (string) $guide->description;
                                    $guideDescription = $decodeRichText($guideDescriptionRaw);
                                    $guideDescriptionHasHtml = $guideDescription !== strip_tags($guideDescription);
                                @endphp
                                @if($guideDescriptionHasHtml)
                                    <div class="wow fadeInUp">{!! $guideDescription !!}</div>
                                @else
                                    <p class="wow fadeInUp">{!! nl2br(e($guideDescriptionRaw)) !!}</p>
                                @endif
                            @endif

                            @if(!empty($detailContent['content_text']))
                                @php
                                    $contentTextRaw = (string) $detailContent['content_text'];
                                    $contentText = $decodeRichText($contentTextRaw);
                                    $contentTextHasHtml = $contentText !== strip_tags($contentText);
                                @endphp
                                @if($contentTextHasHtml)
                                    <div class="wow fadeInUp">{!! $contentText !!}</div>
                                @else
                                    <p class="wow fadeInUp">{!! nl2br(e($contentTextRaw)) !!}</p>
                                @endif
                            @endif

                            @if($guide->children->isNotEmpty())
                                <div class="bringing-quality-box">
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">Sub Guides</h2>
                                    <ul class="wow fadeInUp">
                                        @foreach($guide->children as $childGuide)
                                            <li>
                                                <a href="{{ route('patient-guide.details', $childGuide->slug) }}">{{ $childGuide->name }}</a>
                                                @if($childGuide->children->isNotEmpty())
                                                    <ul>
                                                        @foreach($childGuide->children as $preChildGuide)
                                                            <li><a href="{{ route('patient-guide.details', $preChildGuide->slug) }}">{{ $preChildGuide->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://unpkg.com/filepond@^4/dist/filepond.min.css" rel="stylesheet">
@endpush

@push('js')
    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
    <script src="https://unpkg.com/filepond@^4/dist/filepond.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const recaptchaSiteKey = @json(config('services.recaptcha.site_key'));

            const headImageInput = document.getElementById('hairAnalysisHeadImage');
            if (!headImageInput || typeof FilePond === 'undefined') {
                // continue for reCAPTCHA handling even when FilePond is absent
            } else {
                FilePond.create(headImageInput, {
                    allowMultiple: false,
                    acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp'],
                    labelIdle: 'Drag & Drop your head image or <span class="filepond--label-action">Browse</span>',
                    maxFiles: 1,
                });
            }

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
