<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <script>(()=>{try{const saved=localStorage.getItem('kipanya-theme');if(saved==='light'||saved==='dark')document.documentElement.dataset.theme=saved;}catch(_){} })();</script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cartoon Archive — Kipanya' }}</title>
    @vite(['resources/css/app.css','resources/css/public-redesign.css','resources/js/app.js'])
</head>
<body class="antialiased public-site cartoon-client public-cartoon-site">
    <x-client-nav
        active-app="cartoons"
        brand-subtitle="Cartoon Archive"
        search-action="{{ route('cartoon.search') }}"
        search-placeholder="Search cartoons..."
        search-label="Search cartoons"
        :show-favorites="true"
        :show-cart="false"
    />
    <main>@yield('content')</main>
    <footer class="public-footer cartoon-public-footer">
        <div><strong>Kipanya</strong><span>Cartoon Archive</span></div>
        <div>
            <a href="{{ route('home') }}">Kipanya</a>
            <a href="{{ route('cartoon') }}">Cartoons</a>
            <a href="{{ route('collections') }}">Collections</a>
            <a href="{{ route('discover') }}">Discover</a>
        </div>
        <small>© {{ date('Y') }} Kipanya · Made in Tanzania</small>
    </footer>
</body>
</html>
