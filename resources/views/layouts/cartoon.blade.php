<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <script>
        (() => {
            try {
                const saved = localStorage.getItem('kipanya-theme');
                if (saved === 'light' || saved === 'dark') document.documentElement.dataset.theme = saved;
            } catch (_) {}
        })();
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cartoon Archive — Kipanya' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased kipanya-platform cartoon-client">
<x-client-nav active-app="cartoons" brand-subtitle="Cartoon Archive" search-action="{{ route('cartoon.search') }}" search-placeholder="Search cartoons, characters, stories..." search-label="Search cartoons, characters and stories" :show-favorites="true" :show-cart="false" />

<main>@yield('content')</main>

<footer class="cartoon-react-footer">
    <div class="cartoon-shell cartoon-footer-grid">
        <div><a href="{{ route('home') }}" class="platform-wordmark footer-brand"><span class="platform-mark">K</span><span>Kipanya</span></a><p>One ecosystem. Five worlds. The Cartoon Archive is the home for illustrated stories.</p></div>
        <div class="cartoon-footer-links"><a href="{{ route('cartoon') }}">Cartoons</a><a href="{{ route('cartoon.search') }}">Search</a><a href="{{ auth()->check() ? route('favorites') : route('account.login') }}">Favorites</a><a href="{{ route('collections') }}">Collections</a><a href="{{ route('home') }}">Kipanya</a></div>
    </div>
</footer>
</body>
</html>
