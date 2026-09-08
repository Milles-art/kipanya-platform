<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <script>(()=>{try{const saved=localStorage.getItem('kipanya-theme');if(saved==='light'||saved==='dark')document.documentElement.dataset.theme=saved;}catch(_){} })();</script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kipanya Wear' }}</title>
    @vite(['resources/css/app.css','resources/css/wear-novashop.css','resources/css/public-redesign.css','resources/js/app.js'])
</head>
<body class="public-site public-wear-site antialiased">
    <x-client-nav
        active-app="wear"
        brand-subtitle="Wear"
        search-action="{{ route('wear.search') }}"
        search-placeholder="Search products..."
        search-label="Search Kipanya Wear"
        :show-favorites="true"
        :show-cart="true"
    />
    <main class="wear-public-main">
        @if(session('cart_status') && !request()->routeIs('wear.cart'))<div class="wear-flash" role="status" aria-live="polite">{{ session('cart_status') }}</div>@endif
        @if(session('order_status'))<div class="wear-flash" role="status" aria-live="polite">{{ session('order_status') }}</div>@endif
        @yield('content')
    </main>
    <footer class="public-footer wear-public-footer">
        <div><strong>Kipanya</strong><span>Wear · Everyday pieces with a point of view</span></div>
        <div>
            <a href="{{ route('wear') }}">Shop</a>
            <a href="{{ route('wear.new') }}">New arrivals</a>
            <a href="{{ route('wear.deals') }}">Deals</a>
            <a href="{{ route('wear.wishlist') }}">Wishlist</a>
        </div>
        <small>© {{ date('Y') }} Kipanya · Tanzania</small>
    </footer>
    <script>
        (() => {
            const key = 'kipanya_wear_wishlist';
            const read = () => { try { return JSON.parse(localStorage.getItem(key) || '[]'); } catch { return []; } };
            const write = (value) => localStorage.setItem(key, JSON.stringify(value.slice(0, 50)));
            const sync = () => document.querySelectorAll('[data-wish]').forEach((button) => {
                const liked = read().map(String).includes(String(button.dataset.wish));
                button.classList.toggle('liked', liked);
                button.setAttribute('aria-pressed', liked ? 'true' : 'false');
                button.querySelector('svg')?.setAttribute('fill', liked ? 'currentColor' : 'none');
            });
            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-wish]');
                if (!button) return;
                event.preventDefault();
                event.stopPropagation();
                const id = String(button.dataset.wish);
                const ids = read().map(String);
                write(ids.includes(id) ? ids.filter((value) => value !== id) : [id, ...ids]);
                sync();
            });
            sync();
        })();
    </script>
    @stack('scripts')
</body>
</html>