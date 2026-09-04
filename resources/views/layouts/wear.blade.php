<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light dark"><script>(()=>{try{const saved=localStorage.getItem('kipanya-theme');if(saved==='light'||saved==='dark')document.documentElement.dataset.theme=saved;}catch(_){} })();</script><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Kipanya Wear' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased kipanya-platform cartoon-client wear-site">
<x-client-nav active-app="wear" brand-subtitle="Wear" search-action="{{ route('wear') }}" search-placeholder="Search shirts, hoodies, accessories..." search-label="Search Kipanya Wear" :show-favorites="false" :show-cart="true" />
<main>{{ $slot ?? '' }}@yield('content')</main>
<footer class="wear-footer" id="contact"><div class="wear-container wear-footer-grid"><div><div class="wear-brand wear-brand-footer"><span class="wear-brand-mark">K</span><span><strong>Kipanya</strong><em>Wear</em></span></div><p>Premium everyday wear inspired by Kipanya stories, characters and culture.</p></div><div><h3>Shop</h3><a href="{{ route('wear') }}">All products</a><a href="{{ route('wear') }}?category=T-Shirts">T-Shirts</a><a href="{{ route('wear') }}?category=Hoodies">Hoodies</a><a href="{{ route('wear') }}?category=Caps">Caps</a></div><div><h3>Help</h3><a href="#contact">Delivery</a><a href="#contact">Returns</a><a href="#contact">Contact</a><a href="{{ auth()->check() ? route('account') : route('account.login') }}">My account</a></div><div><h3>Made for Kipanya</h3><p class="wear-footer-note">Wear the stories you love. New drops and custom designs will live here.</p></div></div><div class="wear-footer-bottom wear-container"><span>© {{ date('Y') }} Kipanya Wear</span><span>Part of the Kipanya ecosystem</span></div></footer>
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
