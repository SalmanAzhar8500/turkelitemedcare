@props(['items' => []])

@if(count($items) > 1)
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        @foreach($items as $item)
            @if(! $loop->first)<b aria-hidden="true">&gt;</b>@endif

            @if(! empty($item['url']) && ! $loop->last)
                <a href="{{ $item['url'] }}">{{ localized_label($item['label']) }}</a>
            @else
                <span @if($loop->last) aria-current="page" @endif>{{ localized_label($item['label']) }}</span>
            @endif
        @endforeach
    </nav>
@endif
