@php
    $featuredCartoon = $featured->first();
    $platformApps = [
        'cartoons' => [
            'name' => 'Cartoon Archive', 'short' => 'Cartoons', 'accent' => '#44c79d', 'icon' => 'film',
            'tagline' => 'Discover, browse and watch.',
            'description' => 'A living library of cartoons and episodes — from beloved classics to fresh originals. Browse by era, studio, or mood.',
            'image' => $featuredCartoon?->resolved_thumbnail_url ?: 'https://images.pexels.com/photos/998067/pexels-photo-998067.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
            'layout' => 'bottom-left', 'motion' => 'up', 'mood' => 'Watch a cartoon',
            'live' => [
                ['label' => 'New today', 'text' => $featuredCartoon?->title ? $featuredCartoon->title . ' — now available' : 'Fresh cartoons are landing in the archive', 'badge' => 'New'],
                ['label' => 'Trending', 'text' => 'Discover what readers are watching this week', 'badge' => 'Trending'],
                ['label' => 'Just added', 'text' => 'Explore the newest stories in the archive'],
            ],
            'cross' => ['text' => 'Make this a T-shirt', 'target' => 'wear', 'url' => $featuredCartoon ? route('wear.design', $featuredCartoon) : null],
            'url' => route('cartoon'),
        ],
        'wear' => [
            'name' => 'Kipanya Wear', 'short' => 'Wear', 'accent' => '#ff6b3d', 'icon' => 'shirt',
            'tagline' => 'Wear the stories you love.',
            'description' => 'Premium tees and apparel featuring artwork from the Kipanya universe. Design your own from any cartoon, or shop ready-made drops.',
            'image' => 'https://images.pexels.com/photos/10264891/pexels-photo-10264891.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
            'layout' => 'split-left', 'motion' => 'right', 'mood' => 'Shop some merch',
            'live' => [
                ['label' => 'Drop alert', 'text' => 'New Kipanya Wear drops are coming soon', 'badge' => 'Drop'],
                ['label' => 'Custom', 'text' => 'Design your own tee from any cartoon artwork', 'badge' => 'Custom'],
                ['label' => 'Coming soon', 'text' => 'Your custom T-shirt journey will live here'],
            ],
            'cross' => ['text' => 'From the Cartoon Archive', 'target' => 'cartoons', 'url' => route('cartoon')], 'url' => null,
        ],
        'books' => [
            'name' => 'Kipanya Book', 'short' => 'Books', 'accent' => '#7cb5ff', 'icon' => 'book',
            'tagline' => 'Stories worth your time.',
            'description' => 'Books, comics, and reading guides curated for every age. Read online, track your progress, and build a shelf that feels yours.',
            'image' => 'https://images.pexels.com/photos/10604308/pexels-photo-10604308.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
            'layout' => 'centered', 'motion' => 'fade', 'mood' => 'Read something',
            'live' => [
                ['label' => 'New release', 'text' => 'New stories and books are coming soon', 'badge' => 'New'],
                ['label' => 'Popular', 'text' => 'A future shelf for your favorite Kipanya reads', 'badge' => 'Popular'],
                ['label' => 'Just added', 'text' => 'Curated reading experiences are on the way'],
            ], 'cross' => null, 'url' => null,
        ],
        'motors' => [
            'name' => 'Kaypee Motors', 'short' => 'Motors', 'accent' => '#ff8a5c', 'icon' => 'car',
            'tagline' => 'For the road ahead.',
            'description' => 'Browse vehicles, automotive stories, and services. From concept reveals to ownership guides — built for enthusiasts and the merely curious.',
            'image' => 'https://images.pexels.com/photos/19076555/pexels-photo-19076555.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
            'layout' => 'top-left', 'motion' => 'left', 'mood' => 'Explore motors',
            'live' => [
                ['label' => 'Concept reveal', 'text' => 'Kaypee Motors is being prepared for launch', 'badge' => 'New'],
                ['label' => 'Featured', 'text' => 'Vehicle discovery and automotive stories are coming', 'badge' => 'Featured'],
                ['label' => 'Coming soon', 'text' => 'Explore the road ahead with Kipanya'],
            ], 'cross' => null, 'url' => null,
        ],
        'tv' => [
            'name' => 'Kipanya TV', 'short' => 'TV', 'accent' => '#a78bfa', 'icon' => 'tv',
            'tagline' => 'Always something on.',
            'description' => 'Live channels and on-demand video curated across the Kipanya universe. Tune in, catch up, or let it play in the background.',
            'image' => 'https://images.pexels.com/photos/7991318/pexels-photo-7991318.jpeg?auto=compress&cs=tinysrgb&h=650&w=940',
            'layout' => 'bottom-right', 'motion' => 'up', 'mood' => 'Watch live TV',
            'live' => [
                ['label' => 'On now', 'text' => 'Kipanya TV is coming soon', 'badge' => 'Soon'],
                ['label' => 'New', 'text' => 'A future home for live and on-demand video', 'badge' => 'New'],
                ['label' => 'Coming soon', 'text' => 'Tune in when Kipanya TV launches'],
            ], 'cross' => null, 'url' => null,
        ],
    ];
@endphp

<div class="platform-home" data-platform-home data-apps='@json($platformApps, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)'>
    <header class="platform-topbar">
        <nav class="platform-nav" aria-label="Kipanya primary navigation">
            <a href="{{ route('home') }}" class="platform-wordmark" aria-label="Kipanya home">
                <span class="platform-mark">K</span><span>Kipanya</span>
            </a>
            <span class="platform-divider"></span>
            <div class="platform-app-switcher" aria-label="Kipanya applications">
                @foreach($platformApps as $id => $item)
                    @if($loop->first)
                        <a href="{{ route('cartoon') }}" class="platform-app-nav is-active" style="--app-accent:{{ $item['accent'] }}" aria-label="{{ $item['name'] }}">
                            <x-icon name="{{ $item['icon'] }}" size="19" />
                            <span>{{ $item['short'] }}</span>
                        </a>
                    @else
                        <button type="button" class="platform-app-nav" data-platform-select="{{ $id }}" style="--app-accent:{{ $item['accent'] }}" aria-label="{{ $item['name'] }}">
                            <x-icon name="{{ $item['icon'] }}" size="19" />
                            <span>{{ $item['short'] }}</span>
                        </button>
                    @endif
                @endforeach
            </div>
            <span class="platform-divider"></span>
            <a href="{{ route('discover') }}" class="platform-nav-action"><x-icon name="search" size="18"/><span>Search</span></a>
            <a href="{{ auth()->check() ? route('account') : route('account.login') }}" class="platform-account"><x-icon name="user" size="18"/><span>{{ auth()->check() ? 'Account' : 'Sign in' }}</span></a>
        </nav>
    </header>

    <header class="platform-mobile-topbar">
        <a href="{{ route('home') }}" class="platform-wordmark"><span class="platform-mark">K</span><span>Kipanya</span></a>
        <div class="platform-mobile-actions">
            <a href="{{ route('discover') }}" class="platform-circle-btn" aria-label="Search"><x-icon name="search" size="18"/></a>
            <a href="{{ auth()->check() ? route('account') : route('account.login') }}" class="platform-circle-btn" aria-label="Account"><x-icon name="user" size="18"/></a>
            <button type="button" class="platform-circle-btn" data-platform-menu aria-label="Open applications"><x-icon name="menu" size="18"/></button>
        </div>
    </header>

    <main>
        <section class="platform-hero" data-platform-hero aria-label="Kipanya applications">
            <div class="platform-hero-bg" data-platform-bg></div>
            <div class="platform-hero-overlay"></div>
            <div class="platform-intro" data-platform-intro>
                <div>One universe.</div><strong>Five worlds.</strong>
            </div>

            <div class="platform-hero-content" data-platform-content>
                <div class="platform-hero-copy" data-platform-copy>
                    <div class="platform-app-badge" data-platform-badge>
                        <span class="platform-app-icon" data-platform-icon><x-icon name="film" size="18"/></span>
                        <span data-platform-name>Cartoon Archive</span>
                    </div>
                    <h1 class="platform-hero-title" data-platform-title>Discover, browse and watch.</h1>
                    <p class="platform-hero-description" data-platform-description></p>
                    <div class="platform-live" data-platform-live></div>
                    <div class="platform-hero-actions">
                        <a class="platform-primary-cta" data-platform-cta href="{{ route('discover') }}">Enter Cartoons <x-icon name="arrow-right" size="16"/></a>
                        <button type="button" class="platform-cross-link" data-platform-cross hidden></button>
                    </div>
                </div>
            </div>

            <div class="platform-timeline" data-platform-timeline>
                @foreach($platformApps as $id => $item)
                    <button type="button" class="platform-segment {{ $loop->first ? 'is-active' : '' }}" data-platform-select="{{ $id }}" style="--app-accent:{{ $item['accent'] }}" aria-label="Show {{ $item['name'] }}"><span></span></button>
                @endforeach
            </div>

            <button type="button" class="platform-arrow platform-arrow-left" data-platform-prev aria-label="Previous application"><x-icon name="chevron-left" size="20"/></button>
            <button type="button" class="platform-arrow platform-arrow-right" data-platform-next aria-label="Next application"><x-icon name="chevron" size="20"/></button>
        </section>

        @if($latest->count())

        @endif

    </main>

    <nav class="platform-mobile-dock" aria-label="Kipanya applications">
        @foreach($platformApps as $id => $item)
            @if($loop->first)
                <a href="{{ route('cartoon') }}" class="platform-dock-item is-active" style="--app-accent:{{ $item['accent'] }}" aria-label="{{ $item['name'] }}"><x-icon name="{{ $item['icon'] }}" size="25"/><span>{{ $item['short'] }}</span></a>
            @else
                <button type="button" class="platform-dock-item" data-platform-select="{{ $id }}" style="--app-accent:{{ $item['accent'] }}" aria-label="{{ $item['name'] }}"><x-icon name="{{ $item['icon'] }}" size="25"/><span>{{ $item['short'] }}</span></button>
            @endif
        @endforeach
    </nav>

    <div class="platform-mobile-menu" data-platform-mobile-menu hidden>
        <div class="platform-mobile-menu-backdrop" data-platform-menu-close></div>
        <div class="platform-mobile-menu-panel">
            <div class="platform-mobile-menu-head"><strong>Explore Kipanya</strong><button type="button" class="platform-circle-btn" data-platform-menu-close aria-label="Close"><x-icon name="x" size="18"/></button></div>
            <div class="platform-mobile-menu-list">
                @foreach($platformApps as $id => $item)
                    @if($loop->first)
                        <a href="{{ route('cartoon') }}" class="platform-mobile-app" style="--app-accent:{{ $item['accent'] }}"><span><x-icon name="{{ $item['icon'] }}" size="22"/></span><div><strong>{{ $item['name'] }}</strong><small>Open Cartoon Archive</small></div></a>
                    @else
                        <button type="button" class="platform-mobile-app" data-platform-select="{{ $id }}" style="--app-accent:{{ $item['accent'] }}"><span><x-icon name="{{ $item['icon'] }}" size="22"/></span><div><strong>{{ $item['name'] }}</strong><small>{{ $item['mood'] }}</small></div></button>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
