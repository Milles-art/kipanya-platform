@php
    $featuredCartoon = $featured->first();
@endphp
<div class="public-home" data-home-page>
    <section class="public-home-hero">
        <div class="public-home-hero-copy">
            <span class="public-kicker">Kipanya · The universe</span>
            <h1>A world of <em>stories</em>, cartoons &amp; culture.</h1>
            <p>Discover the latest from Kipanya, browse the Cartoon Archive, and wear the stories that stay with you.</p>
            <div class="public-home-actions">
                <a class="public-button public-button-dark" href="{{ route('cartoon') }}">Explore Cartoons <x-icon name="arrow-right" size="16"/></a>
                <a class="public-button public-button-quiet" href="{{ route('wear') }}">Visit Wear</a>
            </div>
        </div>
        <div class="public-home-hero-art">
            @if($featuredCartoon?->resolved_thumbnail_url)
                <a href="{{ route('cartoon.detail', $featuredCartoon) }}" class="public-home-art-frame">
                    <img src="{{ $featuredCartoon->resolved_thumbnail_url }}" alt="{{ $featuredCartoon->title }}" loading="eager">
                    <span class="public-home-art-tag">Featured cartoon</span>
                </a>
            @else
                <div class="public-home-empty-art"><span>K</span></div>
            @endif
        </div>
    </section>

    <section class="public-home-section">
        <div class="public-section-head"><div><span class="public-kicker">Choose your world</span><h2>Start exploring.</h2></div><a href="{{ route('discover') }}">Discover everything <x-icon name="arrow-right" size="15"/></a></div>
        <div class="public-world-grid">
            <a href="{{ route('cartoon') }}" class="public-world-card public-world-card-main"><span class="public-world-number">01</span><div><span class="public-world-label">Cartoon Archive</span><h3>Sharp ideas. Drawn beautifully.</h3><p>Daily cartoons, collections and an evolving archive.</p></div><span class="public-world-arrow"><x-icon name="arrow-right" size="18"/></span></a>
            <a href="{{ route('wear') }}" class="public-world-card"><span class="public-world-number">02</span><div><span class="public-world-label">Kipanya Wear</span><h3>Wear the story.</h3><p>Shop selected pieces inspired by Kipanya.</p></div><span class="public-world-arrow"><x-icon name="arrow-right" size="18"/></span></a>
            <a href="{{ route('discover') }}" class="public-world-card"><span class="public-world-number">03</span><div><span class="public-world-label">Discover</span><h3>Find something worth keeping.</h3><p>Search across the public archive.</p></div><span class="public-world-arrow"><x-icon name="arrow-right" size="18"/></span></a>
        </div>
    </section>

    @if($latest->count())
    <section class="public-home-section public-home-latest">
        <div class="public-section-head"><div><span class="public-kicker">From the archive</span><h2>Fresh from Kipanya.</h2></div><a href="{{ route('cartoon') }}">Open archive <x-icon name="arrow-right" size="15"/></a></div>
        <div class="public-home-art-grid">
            @foreach($latest->take(6) as $cartoon)
                <a href="{{ route('cartoon.detail', $cartoon) }}" class="public-art-card {{ $loop->first ? 'public-art-card-featured' : '' }}">
                    @if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}" loading="lazy">@endif
                    <div class="public-art-card-copy"><span>{{ $cartoon->category?->name ?? 'Cartoon' }}</span><h3>{{ $cartoon->title }}</h3><small>{{ optional($cartoon->published_at)->format('d M Y') }}</small></div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($collections->count())
    <section class="public-home-section public-home-collections">
        <div class="public-section-head"><div><span class="public-kicker">Curated</span><h2>Stories in collections.</h2></div><a href="{{ route('collections') }}">View collections <x-icon name="arrow-right" size="15"/></a></div>
        <div class="public-collection-strip">
            @foreach($collections as $collection)
                <a href="{{ route('collection', $collection) }}" class="public-collection-card">
                    @php($collectionCartoon = $collection->cartoons->first())
                    @if($collectionCartoon?->resolved_thumbnail_url)<img src="{{ $collectionCartoon->resolved_thumbnail_url }}" alt="" loading="lazy">@endif
                    <div><span>Collection</span><h3>{{ $collection->name }}</h3><p>{{ $collection->cartoons->count() }} cartoons</p></div>
                </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
