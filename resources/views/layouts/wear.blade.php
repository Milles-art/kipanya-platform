<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light dark"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Kipanya Wear' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased wear-site">
<div class="wear-topbar"><div class="wear-container wear-topbar-inner"><span>Wear your story. Make it Kipanya.</span><div><span>Fast delivery</span><span>Premium quality</span><span>Designed in Tanzania</span></div></div></div>
<header class="wear-nav"><div class="wear-container wear-nav-inner">
<a href="{{ route('home') }}" class="wear-brand"><span class="wear-brand-mark">K</span><span><strong>Kipanya</strong><em>Wear</em></span></a>
<nav class="wear-desktop-nav"><a class="{{ request()->routeIs('wear') && !request('category') ? 'is-active' : '' }}" href="{{ route('wear') }}">Home</a><a href="{{ route('wear') }}#products">Products</a><a href="{{ route('wear') }}#categories">Categories</a><a href="{{ route('wear') }}#custom-wear">Custom Wear</a><a href="#about">About</a><a href="#contact">Contact</a></nav>
<div class="wear-nav-actions">
<form class="wear-search" action="{{ route('wear') }}" method="GET"><x-icon name="search" size="17"/><input name="q" value="{{ request('q') }}" placeholder="Search shirts, hoodies..." aria-label="Search Kipanya Wear"><button aria-label="Search"><x-icon name="search" size="16"/></button></form>
<a href="{{ auth()->check() ? route('account') : route('account.login') }}" class="wear-icon-btn" aria-label="Account"><x-icon name="user" size="18"/></a>
<button class="wear-icon-btn" type="button" aria-label="Wishlist"><x-icon name="heart" size="18"/><span class="wear-count">0</span></button>
<a class="wear-icon-btn" href="{{ route('wear.cart') }}" aria-label="Cart"><x-icon name="cart" size="18"/>@php($wearCartCount = app(\App\Services\Commerce\WearCartService::class)->count(request()))<span class="wear-count">{{ $wearCartCount }}</span></a>
<button class="wear-menu" data-menu-toggle="#wear-mobile-menu" aria-label="Open menu"><x-icon name="menu" size="20"/></button>
</div></div>
<div id="wear-mobile-menu" class="wear-mobile-menu wear-container hidden"><a href="{{ route('wear') }}">Home</a><a href="{{ route('wear') }}#products">Products</a><a href="{{ route('wear') }}#categories">Categories</a><a href="{{ route('wear') }}#custom-wear">Custom Wear</a><a href="#about">About</a><a href="#contact">Contact</a></div></header>
<main>{{ $slot ?? '' }}@yield('content')</main>
<footer class="wear-footer" id="contact"><div class="wear-container wear-footer-grid"><div><div class="wear-brand wear-brand-footer"><span class="wear-brand-mark">K</span><span><strong>Kipanya</strong><em>Wear</em></span></div><p>Premium everyday wear inspired by Kipanya stories, characters and culture.</p></div><div><h3>Shop</h3><a href="{{ route('wear') }}">All products</a><a href="{{ route('wear') }}?category=T-Shirts">T-Shirts</a><a href="{{ route('wear') }}?category=Hoodies">Hoodies</a><a href="{{ route('wear') }}?category=Caps">Caps</a></div><div><h3>Help</h3><a href="#contact">Delivery</a><a href="#contact">Returns</a><a href="#contact">Contact</a><a href="{{ route('account') }}">My account</a></div><div><h3>Made for Kipanya</h3><p class="wear-footer-note">Wear the stories you love. New drops and custom designs will live here.</p></div></div><div class="wear-footer-bottom wear-container"><span>© {{ date('Y') }} Kipanya Wear</span><span>Part of the Kipanya ecosystem</span></div></footer>
<script>
(function(){
 const root=document.querySelector('[data-wear-slider]'); if(!root)return;
 const slides=[...root.querySelectorAll('[data-wear-slide]')], dots=[...root.querySelectorAll('[data-wear-dot]')]; let index=0, timer;
 function show(i){index=(i+slides.length)%slides.length; slides.forEach((s,n)=>s.classList.toggle('is-active',n===index)); dots.forEach((d,n)=>d.classList.toggle('is-active',n===index));}
 function start(){clearInterval(timer);timer=setInterval(()=>show(index+1),6500)}
 root.querySelector('[data-wear-next]')?.addEventListener('click',()=>{show(index+1);start()}); root.querySelector('[data-wear-prev]')?.addEventListener('click',()=>{show(index-1);start()});
 dots.forEach(d=>d.addEventListener('click',()=>{show(Number(d.dataset.wearDot));start()})); root.addEventListener('mouseenter',()=>clearInterval(timer)); root.addEventListener('mouseleave',start); show(0); start();
})();
</script>
</body></html>
