@extends('frontend.layouts.app')

@section('content')

    <div class="page-header parallaxie">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque"><span><div style="position:relative;display:inline-block;"><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">O</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">u</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">r</div></div></span> <div style="position:relative;display:inline-block;"> <div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">s</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">e</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">r</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">v</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">i</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">c</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">e</div><div style="position: relative; display: inline-block; opacity: 1; visibility: inherit; transform: translate(0px, 0px);">s</div></div></h1>
                        <nav class="wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">services</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>


    <div class="page-services">
        <div class="container">
            <div class="row" id="servicesContainer">
                @include('frontend.pages.partials.service-cards', ['services' => $services])
            </div>

            @if($totalServices > $services->count())
                <div class="row">
                    <div class="col-lg-12 text-center mt-4">
                        <button id="loadMoreServicesBtn" class="btn-default">load more services</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loadMoreButton = document.getElementById('loadMoreServicesBtn');

            if (!loadMoreButton) {
                return;
            }

            const servicesContainer = document.getElementById('servicesContainer');
            let offset = {{ $services->count() }};
            const limit = 6;
            let isLoading = false;

            loadMoreButton.addEventListener('click', function () {
                if (isLoading) {
                    return;
                }

                isLoading = true;
                loadMoreButton.disabled = true;
                loadMoreButton.textContent = 'loading...';

                fetch(`{{ route('services.load-more') }}?offset=${offset}&limit=${limit}`)
                    .then(response => response.json())
                    .then(data => {
                        servicesContainer.insertAdjacentHTML('beforeend', data.html);
                        offset = data.nextOffset;

                        if (!data.hasMore) {
                            loadMoreButton.style.display = 'none';
                        }
                    })
                    .catch(() => {
                        loadMoreButton.textContent = 'load more services';
                    })
                    .finally(() => {
                        isLoading = false;
                        if (loadMoreButton.style.display !== 'none') {
                            loadMoreButton.disabled = false;
                            loadMoreButton.textContent = 'load more services';
                        }
                    });
            });
        });
    </script>
@endpush
