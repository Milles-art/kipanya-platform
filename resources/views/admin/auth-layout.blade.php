<!doctype html>
<html lang="en" data-theme="dark">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="color-scheme" content="light dark"><title>{{ $title ?? 'Kipanya Admin' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen admin-auth-page">
<div class="admin-auth-shell">
<header class="admin-auth-topbar">
<a href="{{ route('home') }}" class="control-brand"><span class="control-brand-mark"><x-icon name="film" size="19"/></span><span class="control-brand-copy"><strong>KIPANYA</strong><small>Admin</small></span></a>
<div class="admin-auth-actions"><button data-theme-toggle class="k-btn k-btn-light h-10 w-10 p-0" type="button" aria-label="Toggle color theme"><span data-theme-icon data-theme-icon-light='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>' data-theme-icon-dark='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>'></span></button><a href="{{ route('home') }}" class="k-btn k-btn-light">Public site</a></div>
</header>
<main class="admin-auth-main">@yield('content')</main>
</div>
</body></html>