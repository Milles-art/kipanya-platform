@props([
    'price',
    'compareAt' => null,
    'size' => 'md', // sm | md | lg
])

@php
    $hasSale = $compareAt && $compareAt > $price;
    $classes = match($size) {
        'lg' => 'wear-detail-price-wrap',
        'sm' => 'wear-card-price',
        default => 'wear-detail-price-wrap',
    };
@endphp

<div class="{{ $classes }}" {{ $attributes }}>
    <div class="wear-detail-price">TSh {{ number_format($price, 0) }}</div>
    @if($hasSale)
        <del>TSh {{ number_format($compareAt, 0) }}</del>
        <span class="wear-detail-sale">Sale</span>
    @endif
</div>
