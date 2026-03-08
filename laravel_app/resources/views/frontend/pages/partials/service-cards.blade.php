@foreach($services as $service)
    <div class="col-lg-4 col-md-6">
        <div class="service-item wow fadeInUp">
            <div class="service-content">
                <h3>
                    <a href="{{ route('services.details', $service->slug) }}">{{ $service->name }}</a>
                </h3>
                <p>{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
            </div>
            <div class="service-image">
                <figure class="image-anime">
                    <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/services-image-1.jpg') }}" alt="{{ $service->name }}">
                </figure>
            </div>
            <div class="service-btn">
                <a href="{{ route('services.details', $service->slug) }}" class="readmore-btn">read more</a>
            </div>
        </div>
    </div>
@endforeach
