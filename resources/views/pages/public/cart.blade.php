@extends('layouts.wear')
@section('content')
<div class="wear-container wear-cart-page">
    <div class="wear-breadcrumb"><a href="{{ route('wear') }}">Wear</a><span>/</span><strong>Your cart</strong></div>
    <div class="wear-cart-head"><div><span class="wear-eyebrow">Kipanya Wear</span><h1>Your cart</h1><p>Review your pieces before you check out.</p></div><a class="wear-btn wear-btn-ghost" href="{{ route('wear') }}">Continue shopping <x-icon name="arrow-right" size="16"/></a></div>
    @if(session('cart_status'))<div class="wear-alert">{{ session('cart_status') }}</div>@endif
    @if($errors->any())<div class="wear-alert wear-alert-error">{{ $errors->first() }}</div>@endif
    @if(count($items))
    <div class="wear-cart-layout">
        <section class="wear-cart-items">
            @foreach($items as $item)
            <article class="wear-cart-item">
                <a href="{{ route('wear.product', $item['product']) }}" class="wear-cart-item-image"><img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}"></a>
                <div class="wear-cart-item-copy"><a href="{{ route('wear.product', $item['product']) }}"><strong>{{ $item['product']->name }}</strong></a><span>{{ $item['variant']->size }} · {{ ucfirst($item['variant']->color) }}</span><span>SKU {{ $item['variant']->sku }}</span></div>
                <div class="wear-cart-item-qty"><form method="POST" action="{{ route('wear.cart.items.update', $item['variant']->id) }}">@csrf @method('PATCH')<button name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">−</button><b>{{ $item['quantity'] }}</b><button name="quantity" value="{{ min($item['variant']->stock, $item['quantity'] + 1) }}">+</button></form></div>
                <strong class="wear-cart-line">TSh {{ number_format($item['line_total'], 0) }}</strong>
                <form method="POST" action="{{ route('wear.cart.items.remove', $item['variant']->id) }}">@csrf @method('DELETE')<button class="wear-remove" aria-label="Remove {{ $item['product']->name }}"><x-icon name="close" size="16"/></button></form>
            </article>
            @endforeach
        </section>
        <aside class="wear-order-summary"><div class="wear-summary-top"><span>Order summary</span><strong>{{ count($items) }} {{ count($items) === 1 ? 'item' : 'items' }}</strong></div><div class="wear-summary-row"><span>Subtotal</span><strong>TSh {{ number_format($subtotal, 0) }}</strong></div><div class="wear-summary-row"><span>Delivery</span><span>Calculated at checkout</span></div><div class="wear-summary-total"><span>Total</span><strong>TSh {{ number_format($subtotal, 0) }}</strong></div><a class="wear-btn wear-btn-gold wear-checkout-btn" href="{{ route('wear.checkout') }}">Proceed to checkout <x-icon name="arrow-right" size="17"/></a><div class="wear-secure-note"><x-icon name="shield" size="15"/> Secure checkout · Tanzania delivery</div></aside>
    </div>
    @else
    <div class="wear-empty-cart"><div class="wear-empty-cart-icon"><x-icon name="cart" size="30"/></div><h2>Your cart is empty</h2><p>Find something you love and bring it home.</p><a class="wear-btn wear-btn-gold" href="{{ route('wear') }}">Explore Wear <x-icon name="arrow-right" size="16"/></a></div>
    @endif
</div>
@endsection
