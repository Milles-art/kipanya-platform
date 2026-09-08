@extends('layouts.wear')
@section('content')
<div class="store-page catalog-page"><div class="page-heading"><div><span class="eyebrow">My Kipanya</span><h1>Wishlist</h1><p>Save pieces you want to come back to.</p></div></div><div id="wishlist-grid" class="catalog-grid"></div><div id="wishlist-empty" class="empty-card"><div><x-icon name="heart" size="26"/></div><h2>Your wishlist is empty</h2><span>Tap the heart on any product to save it here.</span><a class="primary-button" href="{{ route('wear.catalog') }}">Explore shopping <x-icon name="arrow-right" size="15"/></a></div></div>
@push('scripts')<script>
const wishlistKey='kipanya_wear_wishlist';
const readWish=()=>{try{return JSON.parse(localStorage.getItem(wishlistKey)||'[]')}catch{return[]}};
const writeWish=v=>localStorage.setItem(wishlistKey,JSON.stringify(v));
const products=@json(\App\Models\WearProduct::query()->where('is_active',true)->with('variants')->orderBy('sort_order')->get()->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'category'=>$p->category,'price'=>(float)$p->price,'oldPrice'=>$p->compare_at_price?(float)$p->compare_at_price:null,'image'=>$p->image_url,'url'=>route('wear.product',$p)]));
function renderWish(){const ids=readWish().map(Number);const grid=document.getElementById('wishlist-grid');const empty=document.getElementById('wishlist-empty');grid.innerHTML='';const selected=products.filter(p=>ids.includes(Number(p.id)));empty.style.display=selected.length?'none':'flex';selected.forEach(p=>{const a=document.createElement('article');a.className='product-card';a.innerHTML=`<a class="product-link" href="${p.url}"><div class="product-image cream"><img src="${p.image}" alt="${p.name}"><button type="button" class="heart-button liked" data-remove="${p.id}"><span>♥</span></button></div><div class="product-info"><span>${p.category}</span><h3>${p.name}</h3><div class="rating">★ 5.0 <small>Saved</small></div><div class="price-line"><strong>TSh ${Math.round(p.price).toLocaleString()}</strong>${p.oldPrice?`<del>TSh ${Math.round(p.oldPrice).toLocaleString()}</del>`:''}</div></div></a>`;grid.appendChild(a)});}
document.addEventListener('click',e=>{const b=e.target.closest('[data-remove]');if(!b)return;e.preventDefault();writeWish(readWish().filter(id=>String(id)!==String(b.dataset.remove)));renderWish()});renderWish();
</script>@endpush
@endsection
