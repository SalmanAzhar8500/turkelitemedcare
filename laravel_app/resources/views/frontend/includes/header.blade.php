
<header class="main-header">
    @php
        $headerFooterData = is_array($headerFooterData ?? null) ? $headerFooterData : [];
        $headerLogoRaw = $headerFooterData['header_logo'] ?? null;
        $headerLogo = filled($headerLogoRaw)
            ? asset('storage/' . ltrim((string) $headerLogoRaw, '/'))
            : asset('frontend/assets/images/logo.svg');
        $websiteName = $headerFooterData['website_name'] ?? config('app.name');
        $websiteTagline = $headerFooterData['website_tagline'] ?? 'Trusted care for every patient.';
        $headerHelpText = $headerFooterData['header_help_text'] ?? 'need help !';
        $headerPhone = $headerFooterData['header_phone'] ?? '(+01) 789 987 645';
        $headerPhoneDial = preg_replace('/[^0-9+]/', '', (string) $headerPhone);
    @endphp

    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo Start -->
                <a class="navbar-brand" href="/" title="{{ $websiteName }}{{ filled($websiteTagline) ? ' - ' . $websiteTagline : '' }}">
                    <img src="{{ $headerLogo }}" alt="{{ $websiteName }}">
                </a>
                <!-- Logo End -->

                <!-- Main Menu Start -->
                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">


                            <li class="nav-item"><a class="nav-link" href="/">Home</a>
                            <li class="nav-item"><a class="nav-link" href="/about-us">About Us</a>

                            <li class="nav-item submenu"><a class="nav-link" href="{{ route('services') }}">Services</a>
                                <ul>
                                    @foreach(($navbarServices ?? collect()) as $mainService)
                                        @php
                                            $mainUrl = filled($mainService->slug) ? route('services.details', $mainService->slug) : route('services');
                                        @endphp
                                        <li class="nav-item {{ $mainService->children->isNotEmpty() ? 'submenu' : '' }}">
                                            <a class="nav-link" href="{{ $mainUrl }}">{{ $mainService->name }}</a>

                                            @if($mainService->children->isNotEmpty())
                                                <ul class="sub-menu">
                                                    @foreach($mainService->children as $childService)
                                                        @php
                                                            $childUrl = filled($childService->slug) ? route('services.details', $childService->slug) : route('services');
                                                        @endphp
                                                        <li class="nav-item {{ $childService->children->isNotEmpty() ? 'submenu' : '' }}">
                                                            <a class="nav-link" href="{{ $childUrl }}">{{ $childService->name }}</a>

                                                            @if($childService->children->isNotEmpty())
                                                                <ul class="sub-menu">
                                                                    @foreach($childService->children as $preChildService)
                                                                        @php
                                                                            $preChildUrl = filled($preChildService->slug) ? route('services.details', $preChildService->slug) : route('services');
                                                                        @endphp
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" href="{{ $preChildUrl }}">{{ $preChildService->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item submenu"><a class="nav-link" href="{{ route('patient-guide') }}">Patient Guide</a>
                                <ul>
                                    @foreach(($navbarPatientGuides ?? collect()) as $mainGuide)
                                        @php
                                            $mainGuideUrl = filled($mainGuide->slug) ? route('patient-guide.details', $mainGuide->slug) : route('patient-guide');
                                        @endphp
                                        <li class="nav-item {{ $mainGuide->children->isNotEmpty() ? 'submenu' : '' }}">
                                            <a class="nav-link" href="{{ $mainGuideUrl }}">{{ $mainGuide->name }}</a>

                                            @if($mainGuide->children->isNotEmpty())
                                                <ul class="sub-menu">
                                                    @foreach($mainGuide->children as $childGuide)
                                                        @php
                                                            $childGuideUrl = filled($childGuide->slug) ? route('patient-guide.details', $childGuide->slug) : route('patient-guide');
                                                        @endphp
                                                        <li class="nav-item {{ $childGuide->children->isNotEmpty() ? 'submenu' : '' }}">
                                                            <a class="nav-link" href="{{ $childGuideUrl }}">{{ $childGuide->name }}</a>

                                                            @if($childGuide->children->isNotEmpty())
                                                                <ul class="sub-menu">
                                                                    @foreach($childGuide->children as $preChildGuide)
                                                                        @php
                                                                            $preChildGuideUrl = filled($preChildGuide->slug) ? route('patient-guide.details', $preChildGuide->slug) : route('patient-guide');
                                                                        @endphp
                                                                        <li class="nav-item">
                                                                            <a class="nav-link" href="{{ $preChildGuideUrl }}">{{ $preChildGuide->name }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="nav-item"><a class="nav-link" href="{{route('contact')}}">Contact Us</a></li>
                        </ul>
                    </div>
                    <!-- Contact Now Box Start -->
                    <div class="contact-now-box">
                        <div class="icon-box">
                            <img src="images/icon-phone.svg" alt="">
                        </div>
                        <div class="contact-now-box-content">
                            <p>{{ $headerHelpText }}</p>
                            <h3><a href="tel:{{ $headerPhoneDial }}">{{ $headerPhone }}</a></h3>
                        </div>
                    </div>
                    <!-- Contact Now Box End -->
                </div>
                <!-- Main Menu End -->
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
<!-- Header End -->
