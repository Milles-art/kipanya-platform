<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light dark"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Kipanya' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased k-public">
<header class="k-public-nav k-shell">
 <div class="k-public-nav-inner k-glass-strong">
  <a href="{{ route('home') }}" class="k-logo"><span class="k-logo-mark">K</span><span>KIPANYA</span></a>
  <nav class="hidden items-center gap-1 md:flex"><a class="k-nav-link rounded-lg px-3 py-2 text-sm {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a><a class="k-nav-link rounded-lg px-3 py-2 text-sm {{ request()->routeIs('discover','category','collection') ? 'active' : '' }}" href="{{ route('discover') }}">Cartoons</a><a class="k-nav-link rounded-lg px-3 py-2 text-sm" href="{{ route('home') }}#collections">Collections</a></nav>
  <div class="ml-auto flex items-center gap-2">
   <form action="{{ route('discover') }}" method="GET" class="hidden lg:block"><div class="k-control flex w-56 items-center gap-2 rounded-xl px-3"><x-icon name="search" size="16"/><input name="q" value="{{ request('q') }}" placeholder="Search cartoons..." class="w-full bg-transparent py-2.5 text-xs outline-none"/></div></form>
   <button data-theme-toggle class="k-btn k-btn-light h-10 w-10 p-0" aria-label="Toggle color theme"><span data-theme-icon data-theme-icon-light='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>' data-theme-icon-dark='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>'></span></button>
   <a href="{{ auth()->check() ? route('account') : route('account.login') }}" class="hidden k-btn k-btn-light sm:inline-flex">{{ auth()->check() ? 'My Kipanya' : 'Sign in' }}</a>
   <button data-menu-toggle="#mobile-menu" class="k-btn k-btn-light md:hidden" aria-label="Open menu"><x-icon name="menu"/></button>
  </div>
 </div>
 <div id="mobile-menu" class="k-glass-strong mt-2 hidden rounded-2xl p-3 md:hidden"><div class="grid gap-1"><a class="k-nav-link rounded-lg px-3 py-3" href="{{ route('home') }}">Home</a><a class="k-nav-link rounded-lg px-3 py-3" href="{{ route('discover') }}">Cartoons</a><a class="k-nav-link rounded-lg px-3 py-3" href="{{ route('home') }}#collections">Collections</a><a class="k-nav-link rounded-lg px-3 py-3" href="{{ auth()->check() ? route('account') : route('account.login') }}">My Kipanya</a></div></div>
</header>
<main>{{ $slot ?? '' }}@yield('content')</main>
<footer class="mt-20 border-t k-divider"><div class="k-shell flex flex-col gap-6 py-12 text-sm sm:flex-row sm:items-end sm:justify-between"><div><div class="k-logo"><span class="k-logo-mark">K</span>KIPANYA</div><p class="mt-3 max-w-sm leading-6 k-muted">Stories, characters and moments worth sharing.</p></div><div class="flex items-center gap-4"><button data-theme-toggle class="k-btn k-btn-light" type="button"><span data-theme-label>Dark mode</span></button><span class="k-muted">© {{ date('Y') }} Kipanya</span></div></div></footer>
</body></html>
