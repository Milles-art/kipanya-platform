<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="dark light"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ $title ?? 'Cartoon Archive — Kipanya' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="cartoon-client cartoon-editorial antialiased">
<header class="cartoon-nav-wrap cartoon-editorial-nav-wrap">
  <nav class="cartoon-nav" aria-label="Cartoon Archive navigation">
    <a href="{{ route('home') }}" class="cartoon-brand" aria-label="Back to Kipanya">
      <span class="cartoon-brand-mark">K</span><span>Kipanya</span>
    </a>
    <span class="cartoon-nav-divider"></span>
    <a href="{{ route('cartoon') }}" class="cartoon-nav-link {{ request()->routeIs('cartoon') ? 'is-active' : '' }}">Archive</a>
    <a href="{{ route('discover') }}" class="cartoon-nav-link {{ request()->routeIs('discover','category','collection') ? 'is-active' : '' }}">Discover</a>
    <a href="{{ route('cartoon') }}#collections" class="cartoon-nav-link hidden sm:inline-flex">Collections</a>
    <div class="cartoon-nav-spacer"></div>
    <a href="{{ route('discover') }}" class="cartoon-nav-icon" aria-label="Search"><x-icon name="search" size="17"/></a>
    <a href="{{ auth()->check() ? route('account') : route('account.login') }}" class="cartoon-account">
      <span class="cartoon-account-icon"><x-icon name="user" size="15"/></span>
      <span class="hidden md:inline">{{ auth()->check() ? 'Account' : 'Sign in' }}</span>
    </a>
  </nav>
</header>
<main>@yield('content')</main>
<footer class="cartoon-footer">
  <div class="cartoon-shell cartoon-footer-inner">
    <div><div class="cartoon-brand"><span class="cartoon-brand-mark">K</span><span>Kipanya</span></div><p>Stories, characters and everyday moments.</p></div>
    <div class="cartoon-footer-links"><a href="{{ route('home') }}">Kipanya</a><a href="{{ route('discover') }}">Discover</a><a href="{{ auth()->check() ? route('account') : route('account.login') }}">Account</a></div>
  </div>
</footer>
</body></html>
