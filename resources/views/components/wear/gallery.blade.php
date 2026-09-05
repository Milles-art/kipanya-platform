@props([
    'images',          // Collection|array of image URLs
    'productName',
])

@php
    $images = collect($images)->filter()->values();
    if ($images->isEmpty()) {
        $images = collect([]);
    }
    $main = $images->first();
@endphp

<div class="wear-detail-gallery" data-wear-gallery>
    <div class="wear-detail-main">
        @if($images->count() > 1)
            <button type="button" class="wear-gallery-prev" data-gallery-prev aria-label="Previous product image">
                <x-icon name="chevron-left" size="16"/>
            </button>
        @endif

        <img
            id="wear-detail-main-image"
            src="{{ $main }}"
            alt="{{ $productName }}"
            fetchpriority="high"
            data-gallery-main
        >

        @if($images->count() > 1)
            <button type="button" class="wear-gallery-next" data-gallery-next aria-label="Next product image">
                <x-icon name="chevron" size="16"/>
            </button>
        @endif
    </div>

    @if($images->count() > 1)
        <div class="wear-detail-thumbs" aria-label="Product images">
            @foreach($images as $index => $image)
                <button
                    type="button"
                    class="{{ $index === 0 ? 'is-active' : '' }}"
                    data-gallery-image="{{ $image }}"
                    aria-label="View {{ $productName }} image {{ $index + 1 }}"
                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                >
                    <img src="{{ $image }}" alt="" loading="lazy">
                </button>
            @endforeach
        </div>
    @endif
</div>
