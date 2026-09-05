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
    <div>
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

        <section class="platform-discovery" aria-labelledby="platform-discovery-heading">
            <div class="platform-discovery-intro">
                <div>
                    <span class="platform-eyebrow">Start exploring</span>
                    <h2 id="platform-discovery-heading">More than a place to scroll.</h2>
                </div>
                <p>Kipanya brings stories, style and culture into one easy-to-explore universe. Pick a world, find something you love, and make it yours.</p>
            </div>

            <div class="platform-discovery-grid">
                <a href="{{ route('cartoon') }}" class="platform-discovery-card platform-discovery-card-featured">
                    <span class="platform-discovery-icon"><x-icon name="film" size="20"/></span>
                    <div><span>Cartoon Archive</span><h3>Discover a new story.</h3><p>Browse fresh artwork, daily cartoons and curated collections.</p></div>
                    <x-icon name="arrow-right" size="18"/>
                </a>
                <a href="{{ route('wear') }}" class="platform-discovery-card">
                    <span class="platform-discovery-icon platform-discovery-icon-wear"><x-icon name="shirt" size="20"/></span>
                    <div><span>Kipanya Wear</span><h3>Wear the culture.</h3><p>Shop originals or turn a cartoon you love into a custom tee.</p></div>
                    <x-icon name="arrow-right" size="18"/>
                </a>
                <a href="{{ route('discover') }}" class="platform-discovery-card">
                    <span class="platform-discovery-icon platform-discovery-icon-search"><x-icon name="search" size="20"/></span>
                    <div><span>Discover</span><h3>Find your next favorite.</h3><p>Search the archive by title, category, mood or story.</p></div>
                    <x-icon name="arrow-right" size="18"/>
                </a>
            </div>
        </section>

        @if($latest->count())
            <section class="platform-cartoon-preview" aria-labelledby="platform-latest-heading">
                <div class="platform-section-heading">
                    <div><span class="platform-eyebrow">From the archive</span><h2 id="platform-latest-heading">Fresh stories to explore.</h2></div>
                    <a href="{{ route('cartoon') }}">View Cartoon Archive <x-icon name="arrow-right" size="15"/></a>
                </div>
                <div class="platform-cartoon-grid">
                    @foreach($latest->take(4) as $cartoon)
                        <a href="{{ route('cartoon.detail', $cartoon) }}" class="platform-cartoon-card">
                            <div class="platform-cartoon-art">
                                @if($cartoon->resolved_thumbnail_url)
                                    <img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="platform-cartoon-meta"><small>{{ $cartoon->category?->name ?? 'Cartoon Archive' }}</small><strong>{{ $cartoon->title }}</strong></div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</div>
