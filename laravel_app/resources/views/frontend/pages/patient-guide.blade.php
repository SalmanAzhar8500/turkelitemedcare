@extends('frontend.layouts.app')

@section('content')
    <div class="page-header parallaxie">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Patient Guide</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">patient guide</li>
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
                <div class="col-lg-12">
                    @if($guides->isEmpty())
                        <p class="wow fadeInUp">No patient guide items found.</p>
                    @endif

                    @foreach($guides as $mainGuide)
                        <div class="bringing-quality-box mb-4">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                <a href="{{ route('patient-guide.details', $mainGuide->slug) }}">{{ $mainGuide->name }}</a>
                            </h2>

                            @if($mainGuide->children->isNotEmpty())
                                <ul class="wow fadeInUp">
                                    @foreach($mainGuide->children as $childGuide)
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
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
