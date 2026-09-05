@props([
    'product',
    'mode' => 'interactive', // interactive | simple | related
    'badge' => null,
])

@php
    $isInteractive = $mode === 'interactive';
    $isWishlisted = $isInteractive && auth()->check()
        && auth()->user()->wearWishlists()->where('wear_product_id', $product->id)->exists();

    $productPayload = $isInteractive ? [
        'id' => $product->id,
        'name' => $product->name,
        'slug' => $product->slug,
        'category' => $product->category,
        'price' => (float) $product->price,
        'image' => $product->image_url,
        'url' => route('wear.product', $product),
        'default_variant_id' => optional($product->variants->firstWhere('stock', '>', 0))->id,
        'variants' => $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'size' => $v->size,
            'color' => $v->color,
            'stock' => (int) $v->stock,
        ])->values()->all(),
    ] : null;

    $showBadge = $badge
        ?? ($product->badge ?? null)
        ?? (($product->compare_at_price && $product->compare_at_price > $product->price) ? 'SALE' : null);

    $rating = (float) ($product->approved_reviews_avg_rating ?? 0);
    $reviewCount = (int) ($product->reviews_count ?? 0);
@endphp

<article
    class="wear-store-product-card"
    @if($isInteractive)
        data-product-card
        data-product='@json($productPayload)'
    @endif
>
    <div class="wear-store-product-image">
        <a href="{{ route('wear.product', $product) }}" class="wear-product-image-link" aria-label="View {{ $product->name }}">
            @if($showBadge)
                <span class="wear-store-badge {{ strtolower($showBadge) === 'sale' ? 'sale' : (strtolower($showBadge) === 'best' ? 'best' : '') }}">
                    {{ $showBadge }}
                </span>
            @endif
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        </a>

        @if($isInteractive)
            <div class="wear-card-actions">
                <button type="button" data-add-product aria-label="Add {{ $product->name }} to cart">
                    <x-icon name="cart" size="15"/>
                </button>

                @auth
                    <form method="POST" action="{{ route('wear.wishlist.toggle', $product) }}" data-wishlist-form>
                        @csrf
                        <button
                            type="submit"
                            class="{{ $isWishlisted ? 'is-saved' : '' }}"
                            aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Save to wishlist' }}"
                            aria-pressed="{{ $isWishlisted ? 'true' : 'false' }}"
                        >
                            <x-icon name="heart" size="15"/>
                        </button>
                    </form>
                @else
                    <button
                        type="button"
                        data-wishlist-local="{{ $product->id }}"
                        aria-label="Save for later"
                        aria-pressed="false"
                    >
                        <x-icon name="heart" size="15"/>
                    </button>
                @endauth
            </div>
        @endif
    </div>

    <div class="wear-store-product-copy">
        <small>{{ $product->category }}</small>
        <a href="{{ route('wear.product', $product) }}">
            <h3>{{ $product->name }}</h3>
        </a>

        @if($isInteractive && $reviewCount > 0)
            <div class="wear-card-rating">
                <span>★</span> {{ number_format($rating, 1) }}
                <em>({{ $reviewCount }})</em>
            </div>
        @endif

        <div class="wear-card-price">
            <strong>TSh {{ number_format($product->price, 0) }}</strong>
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <del>TSh {{ number_format($product->compare_at_price, 0) }}</del>
            @endif
        </div>

        @if($isInteractive)
            <button type="button" class="wear-card-add" data-add-product>
                <x-icon name="cart" size="15"/> Add to cart
            </button>
        @endif
    </div>
</article>
