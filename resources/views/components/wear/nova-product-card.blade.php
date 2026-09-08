@props(['product'])
@php $variant=$product->variants->first(fn($v)=>(int)$v->stock>0); @endphp
<article class="product-card" data-product-id="{{ $product->id }}">
    <div class="product-image {{ $product->badge ? 'pink' : 'cream' }}">
        <a class="product-link" href="{{ route('wear.product',$product) }}" aria-label="View {{ $product->name }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
            @if($product->badge)<span class="sale-badge">{{ $product->badge }}</span>@elseif($product->compare_at_price > $product->price)<span class="sale-badge">Sale</span>@endif
        </a>
        <button class="heart-button" type="button" data-wish="{{ $product->id }}" aria-label="Add {{ $product->name }} to wishlist" aria-pressed="false"><x-icon name="heart" size="15"/></button>
    </div>
    <a class="product-info-link" href="{{ route('wear.product',$product) }}">
        <div class="product-info"><span>{{ $product->category }}</span><h3>{{ $product->name }}</h3><div class="rating"><x-icon name="star" size="12"/> 5.0 <small>New</small></div><div class="price-line"><strong>TSh {{ number_format($product->price,0) }}</strong>@if($product->compare_at_price)<del>TSh {{ number_format($product->compare_at_price,0) }}</del>@endif</div></div>
    </a>
    @if($variant)
    <form method="POST" action="{{ route('wear.cart.items.store') }}" class="card-add-form" data-live-cart>@csrf<input type="hidden" name="variant_id" value="{{ $variant->id }}"><input type="hidden" name="quantity" value="1"><button class="add-cart" type="submit" aria-label="Add {{ $product->name }} to cart"><x-icon name="cart" size="14"/></button></form>
    @else
    <button class="add-cart" disabled title="Out of stock" aria-label="{{ $product->name }} is out of stock"><x-icon name="cart" size="14"/></button>
    @endif
</article>
