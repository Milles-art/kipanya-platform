@extends('layouts.wear')

@section('content')
@php
    $variantPayload = $product->variants->map(function ($variant) {
        return [
            'id' => $variant->id,
            'size' => $variant->size,
            'color' => $variant->color,
            'stock' => (int) $variant->stock,
        ];
    })->values()->all();
    $hasSale = $product->compare_at_price && $product->compare_at_price > $product->price;
@endphp

<div class="wear-product-detail">
    <div class="wear-breadcrumb">
        <a href="{{ route('wear') }}">Wear</a><span>/</span><span>{{ $product->category }}</span><span>/</span><strong>{{ $product->name }}</strong>
    </div>

    <div class="wear-detail-grid">
        <div class="wear-detail-gallery">
            <div class="wear-detail-main">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
            <div class="wear-detail-thumbs" aria-label="Product images">
                <button type="button" class="is-active" aria-label="{{ $product->name }} preview">
                    <img src="{{ $product->image_url }}" alt="">
                </button>
            </div>
        </div>

        <div class="wear-detail-copy">
            <div class="wear-detail-overline">
                <span class="wear-product-badge">{{ $product->badge ?? 'Kipanya Wear' }}</span>
                <span>{{ $product->category }}</span>
            </div>

            <h1>{{ $product->name }}</h1>
            <div class="wear-detail-rating" aria-label="Rated 4.9 out of 5">
                <span aria-hidden="true">★★★★★</span><span class="wear-rating-copy">4.9 · Kipanya Wear</span>
            </div>

            <div class="wear-detail-price-wrap">
                <div class="wear-detail-price">TSh {{ number_format($product->price, 0) }}</div>
                @if($hasSale)
                    <del>TSh {{ number_format($product->compare_at_price, 0) }}</del>
                    <span class="wear-detail-sale">Sale</span>
                @endif
            </div>

            <p>{{ $product->description }}</p>

            @if($product->variants->count())
                <form method="POST" action="{{ route('wear.cart.items.store') }}" id="wear-product-form">
                    @csrf
                    <input type="hidden" name="variant_id" id="wear-variant-id" value="">
                    <input type="hidden" name="quantity" id="wear-quantity" value="1">

                    <div class="wear-detail-field">
                        <label>Size</label>
                        <div class="wear-size-grid" data-size-grid>
                            @foreach($product->variants->pluck('size')->unique()->values() as $size)
                                <button type="button" data-size="{{ $size }}">{{ $size }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="wear-detail-field">
                        <label>Color</label>
                        <div class="wear-color-grid" data-color-grid>
                            @foreach($product->variants->pluck('color')->unique()->values() as $color)
                                <button type="button" data-color="{{ $color }}">
                                    <i style="background:{{ $color === 'white' ? '#f8fafc' : ($color === 'navy' ? '#1e3a5f' : ($color === 'teal' ? '#0f766e' : ($color === 'blue' ? '#3b82f6' : ($color === 'olive' ? '#66704d' : ($color === 'natural' ? '#d7c7a4' : '#111827'))))) }}"></i>
                                    {{ ucfirst($color) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="wear-variant-status" data-variant-status role="status" aria-live="polite">Choose a size and color.</div>

                    <div class="wear-detail-field">
                        <label>Quantity</label>
                        <div class="wear-qty">
                            <button type="button" data-qty-minus aria-label="Decrease quantity">−</button>
                            <span data-qty-value>1</span>
                            <button type="button" data-qty-plus aria-label="Increase quantity">+</button>
                        </div>
                    </div>

                    <button class="wear-btn wear-btn-gold wear-detail-add" type="submit">
                        <x-icon name="cart" size="18"/> Add to cart
                    </button>
                </form>
            @else
                <div class="wear-alert">This product is currently unavailable.</div>
            @endif

            <a class="wear-buy-btn wear-buy-link" href="{{ route('wear') }}">Continue shopping</a>

            <div class="wear-detail-notes">
                <span><x-icon name="package" size="17"/> Fast delivery across Tanzania</span>
                <span><x-icon name="shield" size="17"/> Secure checkout</span>
                <span><x-icon name="heart" size="17"/> Made with care</span>
            </div>
        </div>
    </div>

    @if($related->count())
        <section class="wear-related" aria-labelledby="related-products-heading">
            <div class="wear-store-section-head">
                <div>
                    <span>YOU MAY ALSO LIKE</span>
                    <h2 id="related-products-heading">More from {{ $product->category }}</h2>
                    <p>Keep the rotation going with pieces from the same collection.</p>
                </div>
                <a href="{{ route('wear', ['category' => $product->category]) }}">View category <x-icon name="arrow-right" size="15"/></a>
            </div>
            <div class="wear-store-product-grid">
                @foreach($related as $item)
                    <a class="wear-store-product-card" href="{{ route('wear.product', $item) }}">
                        <div class="wear-store-product-image">
                            @if($item->badge)
                                <span class="wear-store-badge">{{ $item->badge }}</span>
                            @elseif($item->compare_at_price && $item->compare_at_price > $item->price)
                                <span class="wear-store-badge sale">Sale</span>
                            @endif
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy">
                        </div>
                        <div class="wear-store-product-copy">
                            <small>{{ $item->category }}</small>
                            <h3>{{ $item->name }}</h3>
                            <strong>TSh {{ number_format($item->price, 0) }}</strong>
                            @if($item->compare_at_price && $item->compare_at_price > $item->price)
                                <del>TSh {{ number_format($item->compare_at_price, 0) }}</del>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

<script>
(function () {
    const form = document.getElementById('wear-product-form');
    if (!form) return;

    const variants = @json($variantPayload);
    const id = document.getElementById('wear-variant-id');
    const quantityInput = document.getElementById('wear-quantity');
    const status = form.querySelector('[data-variant-status]');
    const quantityLabel = form.querySelector('[data-qty-value]');
    let size = null;
    let color = null;
    let quantity = 1;

    function firstAvailable(filter) {
        return variants.find((variant) => variant.stock > 0 && (!filter || filter(variant)));
    }

    const first = firstAvailable();
    size = first?.size ?? null;
    color = first?.color ?? null;

    function currentVariant() {
        return variants.find((variant) => variant.size === size && variant.color === color && variant.stock > 0) || null;
    }

    function render() {
        form.querySelectorAll('[data-size]').forEach((button) => {
            const selected = button.dataset.size === size;
            button.classList.toggle('is-selected', selected);
            button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });

        form.querySelectorAll('[data-color]').forEach((button) => {
            const selected = button.dataset.color === color;
            button.classList.toggle('is-selected', selected);
            button.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });

        const variant = currentVariant();
        if (variant) {
            quantity = Math.max(1, Math.min(quantity, variant.stock));
            id.value = variant.id;
            status.textContent = variant.stock + ' available';
            status.classList.add('is-ready');
        } else {
            id.value = '';
            status.textContent = 'That size and color combination is unavailable.';
            status.classList.remove('is-ready');
        }

        quantityInput.value = quantity;
        quantityLabel.textContent = quantity;
        form.querySelector('[type="submit"]').disabled = !variant;
    }

    form.querySelectorAll('[data-size]').forEach((button) => {
        button.addEventListener('click', () => {
            size = button.dataset.size;
            if (!variants.some((variant) => variant.size === size && variant.color === color && variant.stock > 0)) {
                color = firstAvailable((variant) => variant.size === size)?.color ?? color;
            }
            render();
        });
    });

    form.querySelectorAll('[data-color]').forEach((button) => {
        button.addEventListener('click', () => {
            color = button.dataset.color;
            if (!variants.some((variant) => variant.size === size && variant.color === color && variant.stock > 0)) {
                size = firstAvailable((variant) => variant.color === color)?.size ?? size;
            }
            render();
        });
    });

    form.querySelector('[data-qty-minus]')?.addEventListener('click', () => {
        quantity = Math.max(1, quantity - 1);
        render();
    });

    form.querySelector('[data-qty-plus]')?.addEventListener('click', () => {
        const maximum = currentVariant()?.stock ?? 20;
        quantity = Math.min(maximum, quantity + 1);
        render();
    });

    render();
})();
</script>
@endsection
