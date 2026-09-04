@php
    $activeApp = $activeApp ?? null;
    $brandSubtitle = $brandSubtitle ?? match ($activeApp) {
        'cartoons' => 'Cartoon Archive',
        'wear' => 'Wear',
        default => 'Kipanya',
    };
    $searchAction = $searchAction ?? route('discover');
    $searchPlaceholder = $searchPlaceholder ?? 'Search Kipanya...';
    $searchLabel = $searchLabel ?? 'Search Kipanya';
    $showFavorites = $showFavorites ?? $activeApp === 'cartoons';
    $showCart = $showCart ?? $activeApp === 'wear';
    $platformApps = [
        ['id' => 'cartoons', 'name' => 'Cartoon Archive', 'short' => 'Cartoons', 'accent' => '#18bfa3', 'icon' => 'film', 'url' => route('cartoon')],
        ['id' => 'wear', 'name' => 'Kipanya Wear', 'short' => 'Wear', 'accent' => '#f39a2f', 'icon' => 'shirt', 'url' => route('wear')],
        ['id' => 'books', 'name' => 'Kipanya Book', 'short' => 'Books', 'accent' => '#62a8ff', 'icon' => 'book', 'url' => null],
        ['id' => 'motors', 'name' => 'Kaypee Motors', 'short' => 'Motors', 'accent' => '#ff8a5c', 'icon' => 'car', 'url' => null],
        ['id' => 'tv', 'name' => 'Kipanya TV', 'short' => 'TV', 'accent' => '#a78bfa', 'icon' => 'tv', 'url' => null],
    ];
    $accountName = auth()->user()?->name;
    $accountInitials = $accountName
        ? collect(preg_split('/\s+/', trim($accountName)))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('')
        : '';
@endphp

<header class="kipanya-client-nav" data-client-nav>
    <div class="client-nav-primary">
        <a href="{{ route('home') }}" class="client-nav-brand" aria-label="Kipanya home">
            <span class="client-nav-mark">K</span>
            <span class="client-nav-brand-copy"><strong>Kipanya</strong><small>{{ $brandSubtitle }}</small></span>
        </a>

        <nav class="client-app-links" aria-label="Kipanya applications">
            @foreach($platformApps as $app)
                @if($app['url'])
                    <a href="{{ $app['url'] }}" class="client-app-link {{ $activeApp === $app['id'] ? 'is-active' : '' }}" style="--app-accent:{{ $app['accent'] }}" aria-label="{{ $app['name'] }}">
                        <x-icon name="{{ $app['icon'] }}" size="17"/><span>{{ $app['short'] }}</span>
                    </a>
                @else
                    <button type="button" class="client-app-link" style="--app-accent:{{ $app['accent'] }}" data-coming-soon-app aria-label="{{ $app['name'] }} — coming soon">
                        <x-icon name="{{ $app['icon'] }}" size="17"/><span>{{ $app['short'] }}</span>
                    </button>
                @endif
            @endforeach
        </nav>

        <form class="client-nav-search" action="{{ $searchAction }}" method="GET" role="search">
            <x-icon name="search" size="17"/>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $searchPlaceholder }}" aria-label="{{ $searchLabel }}">
            <kbd>Ctrl&nbsp;/</kbd>
        </form>

        <div class="client-nav-actions">
            @if($showFavorites)
                <a href="{{ auth()->check() ? route('favorites') : route('account.login') }}" class="client-nav-icon" aria-label="Favorites" title="Favorites"><x-icon name="heart" size="18"/></a>
            @endif
            @if($showCart)
                @php($wearNavCartCount = app(\App\Services\Commerce\WearCartService::class)->count(request()))
                <a href="{{ route('wear.cart') }}" class="client-nav-icon client-nav-cart" aria-label="Cart" title="Cart"><x-icon name="cart" size="18"/>@if($wearNavCartCount > 0)<span>{{ $wearNavCartCount }}</span>@endif</a>
            @endif
            <div class="client-account" data-client-account>
                <button type="button" class="client-account-trigger" data-client-account-toggle aria-expanded="false" aria-haspopup="menu">
                    @if($accountInitials)
                        <span class="client-avatar">{{ $accountInitials }}</span>
                        <span class="client-account-copy"><strong>{{ $accountName }}</strong><small>My Kipanya</small></span>
                    @else
                        <span class="client-avatar client-avatar-guest"><x-icon name="user" size="16"/></span>
                        <span class="client-account-copy"><strong>Account</strong><small>Sign in</small></span>
                    @endif
                    <x-icon name="chevron-down" size="14"/>
                </button>
                <div class="client-account-menu" data-client-account-menu hidden role="menu">
                    @auth
                        <a href="{{ route('account') }}" role="menuitem"><x-icon name="user" size="15"/><span>My account</span></a>
                        <a href="{{ route('favorites') }}" role="menuitem"><x-icon name="heart" size="15"/><span>Favorites</span></a>
                        @if($showCart)
                            <a href="{{ route('wear.cart') }}" role="menuitem"><x-icon name="cart" size="15"/><span>Wear cart</span></a>
                        @endif
                        <form method="POST" action="{{ route('account.logout') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" role="menuitem"><x-icon name="logout" size="15"/><span>Sign out</span></button></form>
                    @else
                        <a href="{{ route('account.login') }}" role="menuitem"><x-icon name="log-in" size="15"/><span>Sign in</span></a>
                        <a href="{{ route('account.register') }}" role="menuitem"><x-icon name="plus" size="15"/><span>Create account</span></a>
                    @endauth
                </div>
            </div>
            <button type="button" class="client-theme-toggle" data-theme-toggle aria-label="Toggle color theme" title="Toggle color theme"><span class="theme-dark-icon"><x-icon name="moon" size="17"/></span><span class="theme-light-icon"><x-icon name="sun" size="17"/></span></button>
            <button type="button" class="client-mobile-toggle" data-client-mobile-toggle aria-expanded="false" aria-label="Open navigation"><x-icon name="menu" size="18"/></button>
        </div>
    </div>

    <div class="client-mobile-panel" data-client-mobile-panel hidden>
        <div class="client-mobile-panel-title">Kipanya</div>
        <div class="client-mobile-panel-apps">
            @foreach($platformApps as $app)
                @if($app['url'])
                    <a href="{{ $app['url'] }}" class="client-mobile-app {{ $activeApp === $app['id'] ? 'is-active' : '' }}"><x-icon name="{{ $app['icon'] }}" size="17"/><span>{{ $app['short'] }}</span></a>
                @else
                    <button type="button" class="client-mobile-app" data-coming-soon-app><x-icon name="{{ $app['icon'] }}" size="17"/><span>{{ $app['short'] }}</span></button>
                @endif
            @endforeach
        </div>
        <div class="client-mobile-quick">
            @if($activeApp === 'wear')
                <a href="{{ route('wear') }}#new-arrivals"><x-icon name="sparkles" size="16"/><span>New arrivals</span></a>
                <a href="{{ route('wear') }}#popular-picks"><x-icon name="star" size="16"/><span>Popular picks</span></a>
                <a href="{{ route('wear.cart') }}"><x-icon name="cart" size="16"/><span>Cart</span></a>
                <a href="{{ auth()->check() ? route('account') : route('account.login') }}"><x-icon name="user" size="16"/><span>Account</span></a>
            @elseif($activeApp === 'cartoons')
                <a href="{{ route('cartoon') }}#daily-stories"><x-icon name="clock" size="16"/><span>Daily Stories</span></a>
                <a href="{{ route('cartoon') }}#categories"><x-icon name="grid" size="16"/><span>Categories</span></a>
                <a href="{{ route('collections') }}"><x-icon name="bookmark" size="16"/><span>Collections</span></a>
                <a href="{{ auth()->check() ? route('favorites') : route('account.login') }}"><x-icon name="heart" size="16"/><span>Favorites</span></a>
            @else
                <a href="{{ route('home') }}"><x-icon name="home" size="16"/><span>Home</span></a>
                <a href="{{ route('cartoon') }}"><x-icon name="film" size="16"/><span>Cartoons</span></a>
                <a href="{{ route('wear') }}"><x-icon name="shirt" size="16"/><span>Wear</span></a>
                <a href="{{ auth()->check() ? route('account') : route('account.login') }}"><x-icon name="user" size="16"/><span>My Kipanya</span></a>
            @endif
        </div>
    </div>
</header>

<script>
(() => {
    const account = document.querySelector('[data-client-account]');
    const accountToggle = document.querySelector('[data-client-account-toggle]');
    const accountMenu = document.querySelector('[data-client-account-menu]');
    const mobileToggle = document.querySelector('[data-client-mobile-toggle]');
    const mobilePanel = document.querySelector('[data-client-mobile-panel]');

    accountToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const open = accountToggle.getAttribute('aria-expanded') === 'true';
        accountToggle.setAttribute('aria-expanded', open ? 'false' : 'true');
        if (accountMenu) accountMenu.hidden = open;
    });

    mobileToggle?.addEventListener('click', () => {
        const open = mobileToggle.getAttribute('aria-expanded') === 'true';
        mobileToggle.setAttribute('aria-expanded', open ? 'false' : 'true');
        if (mobilePanel) mobilePanel.hidden = open;
    });

    document.addEventListener('click', (event) => {
        if (account && !account.contains(event.target)) {
            accountToggle?.setAttribute('aria-expanded', 'false');
            if (accountMenu) accountMenu.hidden = true;
        }
        const soon = event.target.closest('[data-coming-soon-app]');
        if (soon) {
            event.preventDefault();
            soon.classList.remove('is-pulsing');
            requestAnimationFrame(() => soon.classList.add('is-pulsing'));
        }
    });

    document.addEventListener('keydown', (event) => {
        if ((event.ctrlKey || event.metaKey) && event.key === '/') {
            event.preventDefault();
            document.querySelector('.client-nav-search input')?.focus();
        }
        if (event.key === 'Escape') {
            accountToggle?.setAttribute('aria-expanded', 'false');
            if (accountMenu) accountMenu.hidden = true;
            mobileToggle?.setAttribute('aria-expanded', 'false');
            if (mobilePanel) mobilePanel.hidden = true;
        }
    });
})();
</script>
