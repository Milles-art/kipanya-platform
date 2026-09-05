@extends('layouts.wear')

@section('content')
@php
    $categoryMeta = [
        'T-Shirts' => ['icon' => 'shirt', 'copy' => 'Everyday essentials'],
        'Hoodies' => ['icon' => 'layers', 'copy' => 'Soft layers'],
        'Caps' => ['icon' => 'sparkles', 'copy' => 'Finish the fit'],
        'Accessories' => ['icon' => 'bag', 'copy' => 'Kipanya extras'],
        'Long Sleeves' => ['icon' => 'shirt', 'copy' => 'Cool-weather staples'],
        'Kids Wear' => ['icon' => 'users', 'copy' => 'For the little ones'],
        'Jackets' => ['icon' => 'layers', 'copy' => 'Outerwear essentials'],
    ];

    $newArrivals = $newArrivals ?? collect();
    $bestSellers = $popular ?? collect();
    $cartCount = app(\App\Services\Commerce\WearCartService::class)->count(request());
@endphp

<div class="wear-store">
    <aside class="wear-store-sidebar" aria-label="Wear categories">
        <a href="{{ route('wear') }}" class="wear-store-side-logo">
            <span class="wear-store-side-logo-mark">K</span>
            <span><strong>KIPANYA</strong><small>WEAR</small></span>
        </a>

        <div class="wear-side-title">SHOP</div>
        <nav class="wear-side-nav">
            <a href="{{ route('wear') }}" class="{{ request('category') ? '' : 'is-active' }}"><x-icon name="grid" size="17"/><span>All Products</span></a>
            @foreach($categoryMeta as $category => $meta)
                <a href="{{ route('wear', ['category' => $category]) }}" class="{{ request('category') === $category ? 'is-active' : '' }}"><x-icon name="{{ $meta['icon'] }}" size="17"/><span>{{ $category }}</span></a>
            @endforeach
        </nav>

        <div class="wear-side-divider"></div>
        <div class="wear-side-title">DISCOVER</div>
        <nav class="wear-side-nav">
            <a href="#new-arrivals"><x-icon name="sparkles" size="17"/><span>New arrivals</span><b>NEW</b></a>
            <a href="#popular-picks"><x-icon name="star" size="17"/><span>Popular picks</span></a>
        </nav>

        <div class="wear-side-trust">
            <div><span><x-icon name="shield" size="16"/></span><strong>Original Art</strong><small>Kipanya designs</small></div>
            <div><span><x-icon name="package" size="16"/></span><strong>Fast Delivery</strong><small>Across Tanzania</small></div>
            <div><span><x-icon name="lock" size="16"/></span><strong>Secure Payment</strong><small>100% protected</small></div>
        </div>
    </aside>

    <div class="wear-store-main">
        <section class="wear-store-hero" data-wear-slider>
            <div class="wear-store-slides">
                <article class="wear-store-slide is-active" data-wear-slide>
                    <img src="https://images.pexels.com/photos/16532060/pexels-photo-16532060.jpeg?cs=srgb&dl=pexels-moh-abdelghaffar-16532060.jpg&fm=jpg" alt="Kipanya clothing collection" class="wear-store-hero-image">
                    <div class="wear-store-hero-overlay"></div>
                    <div class="wear-store-hero-copy">
                        <span class="wear-store-kicker">KIPANYA WEAR</span>
                        <h1>WEAR THE CULTURE.<br><strong>LIVE THE STORY.</strong></h1>
                        <p>Premium everyday pieces with bold Kipanya energy. Made for the people who carry the story with them.</p>
                        <a href="#new-arrivals" class="wear-store-primary">Shop collection <x-icon name="arrow-right" size="16"/></a>
                    </div>
                </article>
                <article class="wear-store-slide" data-wear-slide>
                    <img src="https://images.pexels.com/photos/8072616/pexels-photo-8072616.jpeg?cs=srgb&dl=pexels-qim-manifester-61823229-8072616.jpg&fm=jpg" alt="Man wearing a black polo" class="wear-store-hero-image">
                    <div class="wear-store-hero-overlay"></div>
                    <div class="wear-store-hero-copy">
                        <span class="wear-store-kicker">THE EVERYDAY EDIT</span>
                        <h1>KEEP IT CLEAN.<br><strong>KEEP IT KIPANYA.</strong></h1>
                        <p>Tees, polos and easy layers designed to work from weekday to weekend.</p>
                        <a href="{{ route('wear', ['category' => 'T-Shirts']) }}" class="wear-store-primary">Shop T-Shirts <x-icon name="arrow-right" size="16"/></a>
                    </div>
                </article>
                <article class="wear-store-slide" data-wear-slide>
                    <img src="https://images.pexels.com/photos/7346409/pexels-photo-7346409.jpeg?cs=srgb&dl=pexels-aviz-7346409.jpg&fm=jpg" alt="Casual streetwear outfit" class="wear-store-hero-image">
                    <div class="wear-store-hero-overlay"></div>
                    <div class="wear-store-hero-copy">
                        <span class="wear-store-kicker">NEW DROP</span>
                        <h1>FRESH PIECES.<br><strong>BOLD VIBES.</strong></h1>
                        <p>Build a stronger everyday rotation with jackets, layers and statement essentials.</p>
                        <a href="#popular-picks" class="wear-store-primary">Explore popular picks <x-icon name="arrow-right" size="16"/></a>
                    </div>
                </article>
            </div>
            <button type="button" class="wear-store-slider-btn prev" data-wear-prev aria-label="Previous hero slide"><x-icon name="chevron-left" size="17"/></button>
            <button type="button" class="wear-store-slider-btn next" data-wear-next aria-label="Next hero slide"><x-icon name="chevron" size="17"/></button>
            <div class="wear-store-dots" aria-label="Hero slides">
                @for($i = 0; $i < 3; $i++)
                    <button type="button" data-wear-dot="{{ $i }}" class="{{ $i === 0 ? 'is-active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                @endfor
            </div>
        </section>

        <section class="wear-store-category-strip wear-store-category-strip-7" aria-label="Shop categories">
            @foreach($categoryMeta as $category => $meta)
                <a href="{{ route('wear', ['category' => $category]) }}" class="wear-store-category-item">
                    <span><x-icon name="{{ $meta['icon'] }}" size="21"/></span>
                    <strong>{{ $category }}</strong>
                    <small>{{ $meta['copy'] }}</small>
                </a>
            @endforeach
        </section>

        <section class="wear-store-section" id="new-arrivals">
            <div class="wear-store-section-head">
                <div><span>NEW THIS WEEK</span><h2>New arrivals</h2><p>Fresh pieces added to the Kipanya Wear collection.</p></div>
                <a href="{{ route('wear') }}">View all <x-icon name="arrow-right" size="15"/></a>
            </div>
            <div class="wear-store-product-grid">
                @forelse($newArrivals as $product)
                    <a href="{{ route('wear.product', $product) }}" class="wear-store-product-card">
                        <div class="wear-store-product-image">
                            @if($product->badge)<span class="wear-store-badge">{{ $product->badge }}</span>@elseif($product->compare_at_price && $product->compare_at_price > $product->price)<span class="wear-store-badge sale">Sale</span>@endif
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="wear-store-product-copy">
                            <small>{{ $product->category }}</small>
                            <h3>{{ $product->name }}</h3>
                            <strong>TSh {{ number_format($product->price, 0) }}</strong>@if($product->compare_at_price && $product->compare_at_price > $product->price)<del>TSh {{ number_format($product->compare_at_price, 0) }}</del>@endif
                        </div>
                    </a>
                @empty
                    <div class="wear-store-empty">No new arrivals yet.</div>
                @endforelse
            </div>
        </section>

        <section class="wear-store-section" id="popular-picks">
            <div class="wear-store-section-head">
                <div><span>KIPANYA PICKS</span><h2>Popular picks</h2><p>Easy-to-wear pieces selected for the everyday rotation.</p></div>
                <a href="{{ route('wear') }}">Shop all <x-icon name="arrow-right" size="15"/></a>
            </div>
            <div class="wear-store-product-grid">
                @forelse($bestSellers as $product)
                    <a href="{{ route('wear.product', $product) }}" class="wear-store-product-card">
                        <div class="wear-store-product-image">
                            <span class="wear-store-badge best">BEST</span>
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        <div class="wear-store-product-copy">
                            <small>{{ $product->category }}</small>
                            <h3>{{ $product->name }}</h3>
                            <strong>TSh {{ number_format($product->price, 0) }}</strong>
                        </div>
                    </a>
                @empty
                    <div class="wear-store-empty">No products available yet.</div>
                @endforelse
            </div>
        </section>

        <section class="wear-store-benefits" aria-label="Store benefits">
            <div><span><x-icon name="lock" size="20"/></span><strong>Secure payment</strong><small>Safe & protected checkout</small></div>
            <div><span><x-icon name="package" size="20"/></span><strong>Fast delivery</strong><small>Across Tanzania</small></div>
            <div><span><x-icon name="check-circle" size="20"/></span><strong>Easy returns</strong><small>Simple return process</small></div>
            <div><span><x-icon name="help" size="20"/></span><strong>Need help?</strong><small>We are here for you</small></div>
        </section>

        <div class="wear-store-mobile-cart">
            <a href="{{ route('wear.cart') }}"><x-icon name="cart" size="18"/><span>Cart</span>@if($cartCount > 0)<b>{{ $cartCount }}</b>@endif</a>
        </div>
    </div>
</div>
@endsection
