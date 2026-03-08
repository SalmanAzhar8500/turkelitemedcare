@extends('frontend.layouts.app')

@section('content')
    @php
        $detailContent = is_array($detailContent ?? null) ? $detailContent : [];

        $sidebarTitle = $detailContent['sidebar_title'] ?? ($service->name . ' category');
        $ctaText = $detailContent['cta_text'] ?? ($service->name . ' support');
        $ctaTitle = $detailContent['cta_title'] ?? ('Our team is here to help you with ' . strtolower($service->name));
        $ctaButtonText = $detailContent['cta_button_text'] ?? 'Contact us';
        $ctaImageRaw = $detailContent['cta_image'] ?? null;
        $ctaImage = filled($ctaImageRaw)
            ? (\Illuminate\Support\Str::startsWith((string) $ctaImageRaw, ['http://', 'https://']) ? $ctaImageRaw : asset('storage/' . ltrim((string) $ctaImageRaw, '/')))
            : ($service->image ? asset('storage/' . $service->image) : asset('images/icon-cta.svg'));
        $introTitle = $detailContent['intro_title'] ?? $service->name;
        $introText = $detailContent['intro_text'] ?? ($service->description ?: 'This service is currently available. Please contact our team for complete details and support.');
        $highlightsTitle = $detailContent['highlights_title'] ?? ($service->name . ' highlights');
        $highlightsText = $detailContent['highlights_text'] ?? ('Explore the key areas covered under ' . strtolower($service->name) . ' and how it can support your needs.');
        $featuresTitle = $detailContent['features_title'] ?? ($service->name . ' feature services');
        $stepsHeading = $detailContent['steps_heading'] ?? ($detailContent['steps_title'] ?? ('Simple steps for ' . strtolower($service->name)));
        $stepsTitle = $detailContent['steps_title'] ?? ('Simple steps for ' . strtolower($service->name));
        $stepsText = $detailContent['steps_text'] ?? ('Follow these easy steps to access ' . strtolower($service->name) . ' and receive the right support from our team.');
        $showStepsSectionTitle = filled(trim(strip_tags((string) $stepsTitle)))
            && strcasecmp(trim(strip_tags((string) $stepsTitle)), trim(strip_tags((string) $stepsHeading))) !== 0;
        $showStepsText = filled(trim(strip_tags((string) $stepsText)))
            && strcasecmp(trim(strip_tags((string) $stepsText)), trim(strip_tags((string) $stepsHeading))) !== 0
            && strcasecmp(trim(strip_tags((string) $stepsText)), trim(strip_tags((string) $stepsTitle))) !== 0;
        $faqHeading = $detailContent['faq_heading'] ?? ($detailContent['faq_title'] ?? 'Frequently asked questions');
        $faqTitle = $detailContent['faq_title'] ?? 'Frequently asked questions';
        $showFaqSectionTitle = filled(trim(strip_tags((string) $faqTitle)))
            && strcasecmp(trim(strip_tags((string) $faqTitle)), trim(strip_tags((string) $faqHeading))) !== 0;

        $sidebarServices = collect($service->children ?? [])
            ->filter(fn ($item) => filled($item->slug))
            ->values();

        if ($sidebarServices->isEmpty()) {
            $sidebarServices = collect($relatedServices ?? [])
                ->filter(fn ($item) => filled($item->slug))
                ->values();
        }

        $featureItems = collect($featureItems ?? []);
        if ($featureItems->isEmpty()) {
            $featureItems = collect($relatedServices)->take(2);
        }

        $faqItems = collect($faqItems ?? []);
    @endphp

    <div class="page-header parallaxie">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $service->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('services') }}">services</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
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
                
                 <div class="col-lg-8">
                    <div class="service-single-contemt">
                        <div class="service-feature-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/services-image-1.jpg') }}" alt="{{ $service->name }}">
                            </figure>
                        </div>

                        <div class="service-entry">
                            <h2 class="mb-3">{{ $introTitle }}</h2>
                            <p class="wow fadeInUp">{!! $introText !!}</p>

                            <div class="bringing-quality-box">
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $highlightsTitle }}</h2>
                                <p class="wow fadeInUp">{{ $highlightsText }}</p>
                                <ul class="wow fadeInUp" data-wow-delay="0.2s">
                                    @forelse($serviceHighlights as $highlight)
                                        <li>{{ $highlight }}</li>
                                    @empty
                                        <li>{{ $service->name }}</li>
                                    @endforelse
                                </ul>
                            </div>

                            <h3 class="mb-3">{{ $featuresTitle }}</h3>
                            <div class="service-entry-content-list">
                                @foreach($featureItems as $item)
                                    @php
                                        $itemTitle = is_array($item) ? ($item['title'] ?? null) : ($item->name ?? null);
                                        $itemDescription = is_array($item) ? ($item['description'] ?? null) : ($item->description ?? null);
                                        $itemImage = is_array($item) ? ($item['image'] ?? null) : ($item->image ?? null);
                                        $itemIcon = is_array($item) ? ($item['icon'] ?? null) : ($item->icon ?? null);
                                        $itemIconString = trim((string) ($itemIcon ?? ''));
                                        $itemIconStringDecoded = html_entity_decode($itemIconString, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                        $isInlineSvg = $itemIconStringDecoded !== '' && preg_match('/<svg[\s\S]*<\/svg>/i', $itemIconStringDecoded);
                                        $isIconImage = $itemIconStringDecoded !== '' && (
                                            \Illuminate\Support\Str::startsWith($itemIconStringDecoded, ['http://', 'https://', '/', 'data:image/'])
                                            || preg_match('/\.(svg|png|jpe?g|webp)(\?.*)?$/i', $itemIconStringDecoded)
                                        );
                                        $itemIconUrl = $isIconImage
                                            ? (\Illuminate\Support\Str::startsWith($itemIconStringDecoded, ['http://', 'https://', '/', 'data:image/']) ? $itemIconStringDecoded : asset('storage/' . ltrim($itemIconStringDecoded, '/')))
                                            : asset('images/icon-service-entry-content-' . (($loop->index % 3) + 1) . '.svg');
                                    @endphp
                                    <div class="service-entry-content-item">
                                        <div class="service-entry-image">
                                            <figure class="image-anime reveal">
                                                <img src="{{ $itemImage ? asset('storage/' . $itemImage) : asset('images/service-entry-image-' . (($loop->index % 2) + 1) . '.jpg') }}" alt="{{ $itemTitle ?: $service->name }}">
                                            </figure>
                                        </div>
                                        <div class="service-entry-content-box wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                                            <div class="icon-box">
                                                @if($isInlineSvg)
                                                    {!! $itemIconStringDecoded !!}
                                                @elseif($itemIconStringDecoded !== '' && !$isIconImage)
                                                    <i class="{{ $itemIconStringDecoded }}"></i>
                                                @else
                                                    <img src="{{ $itemIconUrl }}" alt="Icon">
                                                @endif
                                            </div>
                                            <div class="service-entry-content">
                                                <h3>{{ $itemTitle ?: $service->name }}</h3>
                                                <p>{{ \Illuminate\Support\Str::limit($itemDescription ?: $service->description, 170, '...') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="service-entry-steps">
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $stepsHeading }}</h2>
                                @if($showStepsSectionTitle)
                                    <h3 class="mb-3">{{ $stepsTitle }}</h3>
                                @endif
                                @if($showStepsText)
                                    <p class="wow fadeInUp">{{ $stepsText }}</p>
                                @endif

                                <div class="service-entry-step-list">
                                    @foreach($serviceSteps as $step)
                                        @php
                                            $stepIcon = trim((string) ($step['icon'] ?? ''));
                                            $stepIconDecoded = html_entity_decode($stepIcon, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                            $isStepInlineSvg = $stepIconDecoded !== '' && preg_match('/<svg[\s\S]*<\/svg>/i', $stepIconDecoded);
                                            $isStepIconImage = $stepIconDecoded !== '' && (
                                                \Illuminate\Support\Str::startsWith($stepIconDecoded, ['http://', 'https://', '/', 'data:image/'])
                                                || preg_match('/\.(svg|png|jpe?g|webp)(\?.*)?$/i', $stepIconDecoded)
                                            );
                                            $stepIconUrl = $isStepIconImage
                                                ? (\Illuminate\Support\Str::startsWith($stepIconDecoded, ['http://', 'https://', '/', 'data:image/']) ? $stepIconDecoded : asset('storage/' . ltrim($stepIconDecoded, '/')))
                                                : asset('images/icon-service-entry-content-' . (($loop->index % 3) + 1) . '.svg');
                                        @endphp
                                        <div class="service-entry-step-item {{ $loop->first ? 'active' : '' }} wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                                            <div class="service-entry-step-box">
                                                <div class="service-entry-step-no">
                                                    <h2>{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</h2>
                                                </div>
                                                <div class="service-entry-step-content">
                                                    <h3>{{ $step['title'] }}</h3>
                                                    <p>{{ $step['description'] }}</p>
                                                </div>
                                            </div>
                                            <div class="icon-box">
                                                @if($isStepInlineSvg)
                                                    {!! $stepIconDecoded !!}
                                                @elseif($stepIconDecoded !== '' && !$isStepIconImage)
                                                    <i class="{{ $stepIconDecoded }}"></i>
                                                @else
                                                    <img src="{{ $stepIconUrl }}" alt="Icon">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="page-single-faqs">
                            <div class="section-title">
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{{ $faqHeading }}</h2>
                                @if($showFaqSectionTitle)
                                    <h3 class="mb-0">{{ $faqTitle }}</h3>
                                @endif
                            </div>

                            <div class="faq-accordion" id="faqaccordion">
                                @foreach($faqItems as $faq)
                                    @php
                                        $headingId = 'faqheading' . ($loop->index + 1);
                                        $collapseId = 'faqcollapse' . ($loop->index + 1);
                                    @endphp
                                    <div class="accordion-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
                                        <h2 class="accordion-header" id="{{ $headingId }}">
                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                                {{ $faq['question'] }}
                                            </button>
                                        </h2>
                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="{{ $headingId }}" data-bs-parent="#faqaccordion">
                                            <div class="accordion-body">
                                                <p>{!! $faq['answer'] !!}  </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="page-single-sidebar mt-2">
                        <div class="page-sidebar-catagery-list wow fadeInUp">
                            <h3>{{ $sidebarTitle }}</h3>
                            <ul>
                                @forelse($sidebarServices as $sidebarService)
                                    <li>
                                        <a href="{{ route('services.details', $sidebarService->slug) }}">{{ $sidebarService->name }}</a>
                                    </li>
                                @empty
                                    <li><a href="{{ route('services') }}">{{ $service->name }}</a></li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.2s" style="background-image: url('{{ $ctaImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

                            <div class="sidebar-cta-content">
                                <p>{{ $ctaText }}</p>
                                <h3>{{ $ctaTitle }}</h3>
                            </div>
                            <div class="sidebar-cta-btn">
                                <a href="{{ route('contact') }}" class="btn-default">{{ $ctaButtonText }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="page-donation" style="padding: 0px 0;">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <!-- Donation Box Start -->
                                    <div class="donation-box" style="padding: 10px">


                                        <!-- Campaign Donation Form Start -->
                                        <div class="donate-form campaign-donate-form">
                                            <form id="donateForm" action="#" method="POST">


                                                <!-- Donar Personal Info Start -->
                                                <div class="donar-personal-info">
                                                    <!-- Section Title Start -->
                                                    <div class="section-title">
                                                        <h2 class="text-anime-style-2" data-cursor="-opaque">

                                                            <span>
                                                                <div style="position:relative;display:inline-block;">
                                                                    <div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">Book Free Consultation</div>
                                                                </div>
                                                            </span>
                                                        </h2>
                                                    </div>
                                                    <!-- Section Title End -->

                                                    <div class="row wow fadeInUp" data-wow-delay="0.8s" style="visibility: visible; animation-delay: 0.8s; animation-name: fadeInUp;">
                                                        <div class="form-group col-md-12 mb-4">
                                                            <input type="text" name="fname" class="form-control" id="fname" placeholder="First name" required="">
                                                            <div class="help-block with-errors"></div>
                                                        </div>

                                                        <div class="form-group col-md-12 mb-4">
                                                            <input type="text" name="lname" class="form-control" id="lname" placeholder="Last name" required="">
                                                            <div class="help-block with-errors"></div>
                                                        </div>



                                                        <div class="form-group col-md-12 mb-4">
                                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Enter your phone no." required="">
                                                            <div class="help-block with-errors"></div>
                                                        </div>

                                                        <div class="form-group col-md-12 mb-5">
                                                            <textarea name="message" class="form-control" id="message" rows="4" placeholder="Write message"></textarea>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Donar Personal Info End -->

                                                <!-- Donar Info Form Button Start -->
                                                <div class="form-group-btn wow fadeInUp" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: fadeInUp;">
                                                    <button type="submit" class="btn-default" fdprocessedid="y2txpd">Book now</button>
                                                    <div id="msgSubmit" class="h3 hidden"></div>
                                                </div>
                                                <!-- Donar Info Form Button End -->
                                            </form>
                                        </div>
                                        <!-- Campaign Donation Form End -->
                                    </div>
                                    <!-- Donation Box End -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

               
            </div>
        </div>
    </div>
@endsection
