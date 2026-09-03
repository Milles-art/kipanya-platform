<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{ $title ?? 'Kipanya Studio' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen">
<div class="studio-app">
    <aside class="studio-sidebar">
        <div class="studio-sidebar-inner">
            <a href="{{ route('admin.cartoon.dashboard') }}" class="studio-brand">
                <span class="studio-brand-mark">K</span>
                <span>KIPANYA <small>STUDIO</small></span>
            </a>

            @php
                $items = [
                    ['admin.cartoon.dashboard', 'Overview', 'grid'],
                    ['admin.content', 'Cartoons', 'film'],
                    ['admin.episodes', 'Episodes', 'play'],
                    ['admin.calendar', 'Calendar', 'calendar'],
                    ['admin.categories', 'Categories', 'grid'],
                    ['admin.collections', 'Collections', 'collection'],
                ];
            @endphp

            <nav class="studio-nav" aria-label="Studio navigation">
                <div class="studio-nav-label">Workspace</div>
                @foreach($items as [$route, $label, $icon])
                    <a class="studio-nav-link {{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}">
                        <x-icon name="{{ $icon }}" size="17"/>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="studio-sidebar-footer">
                <a href="{{ route('admin.dashboard') }}" class="studio-nav-link"><x-icon name="layers" size="17"/><span>Back to Control Center</span></a>
                <button data-theme-toggle class="studio-nav-link studio-theme-button" type="button">
                    <span data-theme-icon data-theme-icon-light='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>' data-theme-icon-dark='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>'></span>
                    <span data-theme-label>Dark mode</span>
                </button>
                <a href="{{ route('home') }}" class="studio-nav-link"><span>View public site</span></a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="studio-nav-link w-full text-left"><span>Sign out</span></button></form>
            </div>
        </div>
    </aside>

    <main class="studio-main">
        <header class="studio-topbar">
            <div class="studio-topbar-left">
                <button data-menu-toggle="#studio-mobile" class="k-btn k-btn-light studio-mobile-menu" type="button" aria-label="Open navigation">
                    <x-icon name="menu" size="17"/>
                </button>
                <x-app-switcher />
            </div>
            <div class="studio-topbar-actions">
                <button data-theme-toggle class="k-btn k-btn-light h-9 w-9 p-0" type="button" aria-label="Toggle color theme">
                    <span data-theme-icon data-theme-icon-light='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>' data-theme-icon-dark='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/></svg>'></span>
                </button>
                <form method="POST" action="{{ route('admin.logout') }}" class="hidden sm:block">@csrf<button type="submit" class="k-btn k-btn-light">Sign out</button></form>
            </div>
        </header>

        <div id="studio-mobile" class="studio-mobile-nav hidden">
            @foreach($items as [$route, $label, $icon])
                <a class="studio-nav-link {{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}"><x-icon name="{{ $icon }}" size="16"/><span>{{ $label }}</span></a>
            @endforeach
            <div class="studio-mobile-nav-divider"></div>
            <a class="studio-nav-link" href="{{ route('admin.dashboard') }}"><x-icon name="layers" size="16"/><span>Back to Control Center</span></a>
            <a class="studio-nav-link" href="{{ route('home') }}"><x-icon name="external" size="16"/><span>View public site</span></a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit" class="studio-nav-link w-full text-left"><x-icon name="logout" size="16"/><span>Sign out</span></button></form>
        </div>

        <div class="studio-content k-shell">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
