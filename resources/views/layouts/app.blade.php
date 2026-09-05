<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light dark"><script>(()=>{try{const saved=localStorage.getItem('kipanya-theme');if(saved==='light'||saved==='dark')document.documentElement.dataset.theme=saved;}catch(_){} })();</script><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Kipanya' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased kipanya-platform platform-client">
<x-client-nav active-app="home" brand-subtitle="Kipanya" search-action="{{ route('discover') }}" search-placeholder="Search stories, cartoons..." search-label="Search Kipanya" :show-favorites="false" :show-cart="false" />
<main>{{ $slot ?? '' }}@yield('content')</main>
<footer class="platform-footer"><div class="platform-shell"><div class="platform-footer-brand"><a href="{{ route('home') }}" class="platform-wordmark"><span class="platform-mark">K</span><span>Kipanya</span></a><p>Stories, culture, style and moments worth sharing.</p><small>One ecosystem. Five worlds. Built in Tanzania.</small></div><div class="platform-footer-links"><div><span>Explore</span><a href="{{ route('cartoon') }}">Cartoon Archive</a><a href="{{ route('wear') }}">Kipanya Wear</a><a href="{{ route('discover') }}">Discover stories</a></div><div><span>Your Kipanya</span><a href="{{ auth()->check() ? route('account') : route('account.login') }}">My account</a><a href="{{ route('collections') }}">Collections</a></div></div></div><div class="platform-footer-bottom"><span>© {{ date('Y') }} Kipanya</span><span>Made for curious people</span></div></footer>
</body></html>
