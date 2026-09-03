@extends('layouts.wear')
@section('content')
<div class="wear-container wear-product-detail">
<div class="wear-breadcrumb"><a href="{{ route('wear') }}">Wear</a><span>/</span><span>{{ $product->category }}</span><span>/</span><strong>{{ $product->name }}</strong></div>
<div class="wear-detail-grid">
<div class="wear-detail-gallery"><div class="wear-detail-main"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div><div class="wear-detail-thumbs"><button class="is-active"><img src="{{ $product->image_url }}" alt=""></button></div></div>
<div class="wear-detail-copy">
<span class="wear-product-badge">{{ $product->badge ?? 'Kipanya Wear' }}</span><h1>{{ $product->name }}</h1><div class="wear-detail-rating">★★★★★ <span>4.9 · Kipanya Wear</span></div><div class="wear-detail-price">TSh {{ number_format($product->price,0) }}</div><p>{{ $product->description }}</p>
@if($product->variants->count())
<form method="POST" action="{{ route('wear.cart.items.store') }}" id="wear-product-form">@csrf
<input type="hidden" name="variant_id" id="wear-variant-id" value="{{ $product->variants->where('stock','>',0)->first()?->id }}">
<input type="hidden" name="quantity" id="wear-quantity" value="1">
<div class="wear-detail-field"><label>Size</label><div class="wear-size-grid" data-size-grid>@foreach($product->variants->pluck('size')->unique()->values() as $size)<button type="button" data-size="{{ $size }}">{{ $size }}</button>@endforeach</div></div>
<div class="wear-detail-field"><label>Color</label><div class="wear-color-grid" data-color-grid>@foreach($product->variants->pluck('color')->unique()->values() as $color)<button type="button" data-color="{{ $color }}"><i style="background:{{ $color === 'white' ? '#f8fafc' : ($color === 'navy' ? '#1e3a5f' : ($color === 'teal' ? '#0f766e' : '#111827')) }}"></i>{{ ucfirst($color) }}</button>@endforeach</div></div>
<div class="wear-variant-status" data-variant-status>Choose a size and color.</div>
<div class="wear-detail-field"><label>Quantity</label><div class="wear-qty"><button type="button" data-qty-minus>−</button><span data-qty-value>1</span><button type="button" data-qty-plus>+</button></div></div>
<button class="wear-btn wear-btn-gold wear-detail-add" type="submit"><x-icon name="cart" size="17"/> Add to cart</button>
</form>
@else <div class="wear-alert">This product is currently unavailable.</div>@endif
<a class="wear-buy-btn wear-buy-link" href="{{ route('wear.cart') }}">View cart</a>
<div class="wear-detail-notes"><span><x-icon name="package" size="16"/> Fast delivery across Tanzania</span><span><x-icon name="shield" size="16"/> Secure checkout</span><span><x-icon name="heart" size="16"/> Made with care</span></div>
</div></div>
<section class="wear-related"><div class="wear-section-head"><div><span class="wear-eyebrow">You may also like</span><h2>More from {{ $product->category }}</h2></div></div><div class="wear-product-grid">@foreach($related as $item)<a class="wear-product-card" href="{{ route('wear.product',$item) }}"><div class="wear-product-image"><span class="wear-product-badge">{{ $item->badge ?? 'Featured' }}</span><img src="{{ $item->image_url }}" alt="{{ $item->name }}"></div><div class="wear-product-info"><strong>{{ $item->name }}</strong><div class="wear-product-price">TSh {{ number_format($item->price,0) }}</div><span class="wear-add">View product <x-icon name="arrow-right" size="15"/></span></div></a>@endforeach</div></section>
</div>
<script>
(function(){
 const form=document.getElementById('wear-product-form'); if(!form)return;
 const variants=@json($product->variants->map(fn($v)=>['id'=>$v->id,'size'=>$v->size,'color'=>$v->color,'stock'=>$v->stock]));
 let size=variants.find(v=>v.stock>0)?.size||null, color=variants.find(v=>v.stock>0)?.color||null, qty=1;
 const id=document.getElementById('wear-variant-id'), q=document.getElementById('wear-quantity'), status=form.querySelector('[data-variant-status]');
 function current(){return variants.find(v=>v.size===size&&v.color===color&&v.stock>0)}
 function render(){form.querySelectorAll('[data-size]').forEach(b=>b.classList.toggle('is-selected',b.dataset.size===size));form.querySelectorAll('[data-color]').forEach(b=>b.classList.toggle('is-selected',b.dataset.color===color));const v=current();id.value=v?.id||'';q.value=qty;if(v){qty=Math.min(qty,v.stock);q.value=qty;status.textContent=v.stock+' available';status.classList.add('is-ready');}else status.textContent='That combination is unavailable.';form.querySelector('[type=submit]').disabled=!v;}
 form.querySelectorAll('[data-size]').forEach(b=>b.addEventListener('click',()=>{size=b.dataset.size; if(!variants.some(v=>v.size===size&&v.color===color&&v.stock>0)) color=variants.find(v=>v.size===size&&v.stock>0)?.color||color;render()}));
 form.querySelectorAll('[data-color]').forEach(b=>b.addEventListener('click',()=>{color=b.dataset.color;if(!variants.some(v=>v.size===size&&v.color===color&&v.stock>0)) size=variants.find(v=>v.color===color&&v.stock>0)?.size||size;render()}));
 form.querySelector('[data-qty-minus]').addEventListener('click',()=>{qty=Math.max(1,qty-1);render()});form.querySelector('[data-qty-plus]').addEventListener('click',()=>{qty=Math.min(current()?.stock||20,qty+1);render()});render();
})();
</script>
@endsection
