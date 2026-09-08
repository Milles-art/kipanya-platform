@extends('layouts.wear')
@section('content')
@php
    $featured = $featured->values();
    $new = $new->values();
    $deals = $deals->values();
    $hero = $featured->first() ?? $new->first();
@endphp
<div class="store-page wear-storefront">
    <section class="wear-hero-banner">
        <div class="wear-hero-copy">
            <span class="wear-eyebrow"><x-icon name="sparkles" size="13"/> New collection</span>
            <h1>Find what <em>fits your story.</em></h1>
            <p>Selected pieces for the way you move, make and show up every day.</p>
            <div class="wear-hero-actions">
                <a class="primary-button" href="{{ route('wear.catalog') }}">Shop the collection <x-icon name="arrow-right" size="14"/></a>
                <a class="text-button" href="{{ route('wear.new') }}">See new arrivals</a>
            </div>
            <div class="wear-hero-stats"><span><strong>{{ $new->count() }}</strong> new pieces</span><span><strong>24/7</strong> support</span></div>
        </div>
        <div class="wear-hero-art">
            @if($hero?->image_url)
                <img src="{{ $hero->image_url }}" alt="{{ $hero->name }}" loading="eager">
                <div class="wear-hero-product-label"><small>Featured pick</small><strong>{{ $hero->name }}</strong><span>TSh {{ number_format($hero->price, 0) }}</span></div>
            @else
                <div class="wear-hero-monogram">K<span>WEAR</span></div>
            @endif
            <span class="wear-hero-orbit orbit-one"></span><span class="wear-hero-orbit orbit-two"></span>
        </div>
    </section>

    <section class="wear-promo-grid">
        <a href="{{ route('wear.deals') }}" class="wear-promo-card promo-coral"><span>Limited edit</span><strong>Flash deals</strong><small>Save on selected pieces</small><b>Shop deals <x-icon name="arrow-right" size="13"/></b></a>
        <a href="{{ route('wear.catalog') }}" class="wear-promo-card promo-sage"><span>Made easier</span><strong>Delivery across Tanzania</strong><small>Simple checkout, clear updates.</small><b>Explore the shop <x-icon name="arrow-right" size="13"/></b></a>
        <a href="{{ route('wear.new') }}" class="wear-promo-card promo-sand"><span>Just in</span><strong>Fresh arrivals</strong><small>New pieces, ready to wear.</small><b>Shop new <x-icon name="arrow-right" size="13"/></b></a>
    </section>

    @if($deals->count())
        <section class="wear-product-section" id="popular-picks">
            <div class="wear-section-heading"><div><span>Curated for you</span><h2>Best deals.</h2></div><a href="{{ route('wear.deals') }}">View all <x-icon name="arrow-right" size="14"/></a></div>
            <div class="product-grid">@foreach($deals->take(4) as $product)<x-wear.nova-product-card :product="$product"/>@endforeach</div>
        </section>
    @endif
    <section class="wear-product-section" id="new-arrivals">
        <div class="wear-section-heading"><div><span>New on the rail</span><h2>Recommended for you.</h2></div><a href="{{ route('wear.catalog') }}">View all <x-icon name="arrow-right" size="14"/></a></div>
        <div class="product-grid">@foreach(($new->merge($featured)->unique('id'))->take(8) as $product)<x-wear.nova-product-card :product="$product"/>@endforeach</div>
    </section>
    <div class="wear-trust-row"><span><x-icon name="truck" size="18"/><b>Fast delivery</b><small>Across Tanzania</small></span><span><x-icon name="shield" size="18"/><b>Secure shopping</b><small>Protected checkout</small></span><span><x-icon name="package" size="18"/><b>Easy returns</b><small>Simple process</small></span><span><x-icon name="help" size="18"/><b>Human support</b><small>We're here to help</small></span></div>
</div>
@endsection
