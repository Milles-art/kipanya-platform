@extends('layouts.cartoon')
@section('content')
<div class="cartoon-archive-page">
    <div class="cartoon-archive-workspace">
        <div class="cartoon-archive-main">
            @if($daily->count())
            <section class="cartoon-home-section cartoon-daily-section cartoon-daily-first" id="daily-stories" aria-labelledby="daily-cartoon-heading">
                <div class="cartoon-section-head-react">
                    <div><div class="cartoon-section-label">Every day</div><h1 id="daily-cartoon-heading">Daily Cartoon</h1></div>
                    <a href="{{ route('cartoon.search', ['daily' => 1]) }}">View all <x-icon name="arrow-right" size="15"/></a>
                </div>
                <div class="cartoon-story-row" data-daily-stories aria-label="Daily Cartoon stories">
                    @foreach($daily as $index => $item)
                        @php
                            $storyDate = $item->daily_date?->format('D') ?? $item->published_at?->format('D');
                            $storyFullDate = ($item->daily_date ?: $item->published_at)?->format('l, d M Y');
                            $storyCaption = trim((string) ($item->caption ?: $item->description));
                            $storyIsToday = (($item->daily_date ?: $item->published_at)?->isToday()) === true;
                        @endphp
                        <button type="button" class="cartoon-story" data-story-index="{{ $index }}" data-story-image="{{ $item->resolved_thumbnail_url }}" data-story-title="{{ $item->title }}" data-story-caption="{{ $storyCaption }}" data-story-date="{{ $storyFullDate }}" data-story-detail="{{ route('cartoon.detail', $item) }}" data-story-wear="{{ route('wear.design', $item) }}" aria-label="Open {{ $item->title }} story">
                            <span class="cartoon-story-ring {{ $index === 0 ? 'is-today' : '' }}"><span class="cartoon-story-image">@if($item->resolved_thumbnail_url)<img src="{{ $item->resolved_thumbnail_url }}" alt="" loading="lazy">@endif</span></span>
                            <strong>{{ $storyIsToday ? 'Today' : $storyDate }}</strong>
                            <small>{{ $item->daily_date?->format('d M') ?? $item->published_at?->format('d M') }}</small>
                        </button>
                    @endforeach
                </div>
            </section>
            @endif

            @php
                $categoryIcons = [
                    'everyday-life' => 'home',
                    'humor' => 'chat',
                    'society' => 'users',
                    'family' => 'heart',
                    'culture' => 'book',
                    'work-business' => 'bag',
                ];
            @endphp
            <section class="cartoon-home-section cartoon-categories-top" id="categories">
                <div class="cartoon-section-head-react"><div><div class="cartoon-section-label">Explore</div><h2>Browse Categories</h2></div><a href="{{ route('cartoon.search') }}">View all <x-icon name="arrow-right" size="15"/></a></div>
                <div class="cartoon-category-grid-react">
                    @foreach($categories as $category)
                        @php $categoryIcon = $categoryIcons[$category->slug] ?? 'grid'; @endphp
                        <a href="{{ route('category', $category) }}" class="cartoon-category-card-react">
                            <span class="cartoon-category-icon"><x-icon name="{{ $categoryIcon }}" size="20"/></span>
                            <strong>{{ $category->name }}</strong>
                            <small>{{ $category->cartoons_count }} cartoons</small>
                            <x-icon name="arrow-right" size="13"/>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="cartoon-home-section" id="latest">
                <div class="cartoon-section-head-react"><div><div class="cartoon-section-label">Archive</div><h2>Latest from the Archive</h2></div><a href="{{ route('cartoon.search') }}">View all <x-icon name="arrow-right" size="15"/></a></div>
                <div class="cartoon-grid-react">
                    @forelse($latest as $cartoon)<x-cartoon.card :cartoon="$cartoon"/>@empty<div class="cartoon-empty">No published cartoons yet.</div>@endforelse
                </div>
            </section>

            <section class="cartoon-home-section" id="collections">
                <div class="cartoon-section-head-react"><div><div class="cartoon-section-label">Curated</div><h2>Collections</h2></div><a href="{{ route('collections') }}">Explore <x-icon name="arrow-right" size="15"/></a></div>
                <div class="cartoon-collection-grid-react">
                    @forelse($collections as $collection)
                        <a href="{{ route('collection', $collection) }}" class="cartoon-collection-card-react">
                            <div class="cartoon-collection-cover">
                                @if($collection->resolved_cover_url)<img src="{{ $collection->resolved_cover_url }}" alt="{{ $collection->name }}" loading="lazy">
                                @elseif($collection->cartoons->first()?->resolved_thumbnail_url)<img src="{{ $collection->cartoons->first()->resolved_thumbnail_url }}" alt="{{ $collection->name }}" loading="lazy">@endif
                                <span>{{ $collection->cartoons_count }} cartoons</span>
                            </div>
                            <div class="cartoon-collection-copy"><h3>{{ $collection->name }}</h3><p>{{ $collection->description }}</p><span class="collection-link">Explore <x-icon name="arrow-right" size="14"/></span></div>
                        </a>
                    @empty
                        <div class="cartoon-empty">Collections will appear here when published artwork is grouped.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="cartoon-wear-rail" aria-label="Kipanya Wear preview">
            @if($featured)
            <div class="cartoon-wear-panel">
                <div class="cartoon-wear-panel-head">
                    <div><div class="cartoon-kicker"><x-icon name="shirt" size="14"/> Make it a T-shirt</div><h2>Wear the art.</h2><p>Start a conversation.</p></div>
                </div>
                <div class="cartoon-wear-preview" data-home-shirt-preview data-color="black" data-size="M" data-placement="front-center">
                    <div class="wear-glow"></div>
                    <div class="real-shirt-photo" aria-label="Blank T-shirt preview"><img src="{{ asset('assets/wear/shirts/black.png') }}" alt="Kipanya T-shirt in Black" data-home-shirt-base="{{ asset('assets/wear/shirts') }}"></div>
                </div>
                <div class="cartoon-wear-controls">
                    <div class="wear-step"><strong>1. Choose Color</strong><div class="wear-color-options">
                        @foreach($wearColors as $key=>$color)
                            <button type="button" class="wear-color-dot {{ $key === 'black' ? 'is-selected' : '' }}" data-home-color="{{ $key }}" style="--dot:{{ $color['hex'] }}" aria-label="{{ $color['label'] }}" title="{{ $color['label'] }}"></button>
                        @endforeach
                    </div></div>
                    <div class="wear-step"><strong>2. Choose Size</strong><div class="wear-size-options">@foreach(['XS','S','M','L','XL','XXL'] as $size)<button type="button" class="wear-size {{ $size === 'M' ? 'is-selected' : '' }}" data-home-size="{{ $size }}">{{ $size }}</button>@endforeach</div></div>
                    <div class="wear-step"><strong>3. Placement</strong><div class="wear-placement-options"><button type="button" class="wear-placement is-selected" data-home-placement="front-center"><span>Front Center</span></button><button type="button" class="wear-placement" data-home-placement="front-pocket"><span>Front Pocket</span></button></div></div>
                    <a href="{{ route('wear.design', $featured) }}?color=black&amp;size=M&amp;placement=front-center" class="cartoon-wear-continue" data-home-wear-continue>Customize &amp; Continue <x-icon name="arrow-right" size="17"/></a>
                </div>
            </div>
            @endif
        </aside>
    </div>
</div>

<script>
(() => {
    const preview = document.querySelector('[data-home-shirt-preview]');
    if (!preview) return;
    const shirt = preview.querySelector('[data-home-shirt-base]');
    const continueLink = document.querySelector('[data-home-wear-continue]');
    const colorButtons = [...document.querySelectorAll('[data-home-color]')];
    const sizeButtons = [...document.querySelectorAll('[data-home-size]')];
    const placementButtons = [...document.querySelectorAll('[data-home-placement]')];
    const base = shirt?.dataset.homeShirtBase || '';
    if (continueLink) continueLink.dataset.baseUrl = continueLink.href.split('?')[0];
    const sync = () => {
        const color = preview.dataset.color || 'black';
        const size = preview.dataset.size || 'M';
        const placement = preview.dataset.placement || 'front-center';
        if (shirt && base) shirt.src = `${base}/${color}.png`;
        colorButtons.forEach((button) => button.classList.toggle('is-selected', button.dataset.homeColor === color));
        sizeButtons.forEach((button) => button.classList.toggle('is-selected', button.dataset.homeSize === size));
        placementButtons.forEach((button) => button.classList.toggle('is-selected', button.dataset.homePlacement === placement));
        if (continueLink) continueLink.href = `${continueLink.dataset.baseUrl}?color=${encodeURIComponent(color)}&size=${encodeURIComponent(size)}&placement=${encodeURIComponent(placement)}`;
    };
    colorButtons.forEach((button) => button.addEventListener('click', () => { preview.dataset.color = button.dataset.homeColor; sync(); }));
    sizeButtons.forEach((button) => button.addEventListener('click', () => { preview.dataset.size = button.dataset.homeSize; sync(); }));
    placementButtons.forEach((button) => button.addEventListener('click', () => { preview.dataset.placement = button.dataset.homePlacement; sync(); }));
    sync();
})();
</script>

@if($daily->count())
<div class="cartoon-story-viewer" data-story-viewer hidden aria-hidden="true">
    <div class="cartoon-story-backdrop" data-story-close></div>
    <div class="cartoon-story-modal" role="dialog" aria-modal="true" aria-label="Daily Cartoon story viewer" tabindex="-1">
        <div class="cartoon-story-progress" data-story-progress aria-hidden="true"></div>
        <button type="button" class="cartoon-story-close" data-story-close aria-label="Close story"><x-icon name="x" size="20"/></button>
        <button type="button" class="cartoon-story-arrow cartoon-story-prev" data-story-prev aria-label="Previous story"><x-icon name="chevron-left" size="22"/></button>
        <div class="cartoon-story-art"><img data-story-image src="" alt=""><div class="cartoon-story-shade"></div></div>
        <div class="cartoon-story-copy">
            <div class="cartoon-story-topline"><span class="cartoon-story-account-dot"></span><span>Kipanya Cartoon Archive</span></div>
            <div class="cartoon-section-label" data-story-date></div>
            <h2 data-story-title></h2>
            <p data-story-caption></p>
            <div class="cartoon-story-actions"><a data-story-open class="cartoon-btn cartoon-btn-primary">View Cartoon <x-icon name="arrow-right" size="15"/></a><a data-story-wear class="cartoon-btn cartoon-btn-outline"><x-icon name="shirt" size="15"/> Make a T-shirt</a></div>
        </div>
        <button type="button" class="cartoon-story-arrow cartoon-story-next" data-story-next aria-label="Next story"><x-icon name="chevron-right" size="22"/></button>
    </div>
</div>
<script>
(() => {
    const viewer = document.querySelector('[data-story-viewer]');
    const stories = Array.from(document.querySelectorAll('[data-story-index]'));
    if (!viewer || !stories.length) return;

    const modal = viewer.querySelector('.cartoon-story-modal');
    const art = viewer.querySelector('.cartoon-story-art');
    const image = viewer.querySelector('[data-story-image]');
    const title = viewer.querySelector('[data-story-title]');
    const caption = viewer.querySelector('[data-story-caption]');
    const date = viewer.querySelector('[data-story-date]');
    const openLink = viewer.querySelector('[data-story-open]');
    const wearLink = viewer.querySelector('[data-story-wear]');
    const progress = viewer.querySelector('[data-story-progress]');
    const prev = viewer.querySelector('[data-story-prev]');
    const next = viewer.querySelector('[data-story-next]');
    const duration = 5000;
    let current = 0;
    let timer = null;
    let touchStartX = null;
    let touchStartY = null;
    let paused = false;

    const stopTimer = () => {
        if (timer) clearTimeout(timer);
        timer = null;
    };

    const startTimer = () => {
        stopTimer();
        if (paused || viewer.hidden || stories.length < 2) return;
        timer = setTimeout(() => render(current + 1), duration);
    };

    const renderProgress = () => {
        progress.innerHTML = stories.map((_, index) => {
            const state = index < current ? 'is-complete' : index === current ? 'is-active' : '';
            return `<span class="${state}"><i></i></span>`;
        }).join('');
    };

    const render = (index) => {
        current = (index + stories.length) % stories.length;
        const story = stories[current];
        const src = story.dataset.storyImage || '';
        image.src = src;
        image.alt = story.dataset.storyTitle || 'Daily Cartoon';
        art.style.setProperty('--story-image', `url("${src.replaceAll('"', '%22')}")`);
        title.textContent = story.dataset.storyTitle || '';
        caption.textContent = story.dataset.storyCaption || '';
        caption.hidden = !story.dataset.storyCaption;
        date.textContent = story.dataset.storyDate || '';
        openLink.href = story.dataset.storyDetail || '#';
        wearLink.href = story.dataset.storyWear || '#';
        renderProgress();
        startTimer();
    };

    const show = (index) => {
        viewer.hidden = false;
        viewer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('cartoon-story-open');
        current = index;
        render(current);
        modal.focus({ preventScroll: true });
    };

    const hide = () => {
        stopTimer();
        viewer.hidden = true;
        viewer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('cartoon-story-open');
    };

    stories.forEach((story, index) => story.addEventListener('click', () => show(index)));
    viewer.querySelectorAll('[data-story-close]').forEach((button) => button.addEventListener('click', hide));
    prev?.addEventListener('click', (event) => { event.stopPropagation(); render(current - 1); });
    next?.addEventListener('click', (event) => { event.stopPropagation(); render(current + 1); });

    art?.addEventListener('pointerdown', (event) => {
        touchStartX = event.clientX;
        touchStartY = event.clientY;
        paused = true;
        stopTimer();
    });

    art?.addEventListener('pointerup', (event) => {
        if (touchStartX === null || touchStartY === null) return;
        const dx = event.clientX - touchStartX;
        const dy = event.clientY - touchStartY;
        touchStartX = touchStartY = null;
        paused = false;
        if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
            render(dx > 0 ? current - 1 : current + 1);
            return;
        }
        startTimer();
    });

    art?.addEventListener('pointercancel', () => { touchStartX = touchStartY = null; paused = false; startTimer(); });

    viewer.addEventListener('click', (event) => {
        if (event.target.closest('a,button,.cartoon-story-copy')) return;
        const rect = modal.getBoundingClientRect();
        if (event.clientX < rect.left + rect.width * 0.32) render(current - 1);
        else if (event.clientX > rect.left + rect.width * 0.68) render(current + 1);
    });

    // Instagram-style stories should keep progressing on desktop; only direct interaction pauses them.
    document.addEventListener('keydown', (event) => {
        if (viewer.hidden) return;
        if (event.key === 'Escape') hide();
        if (event.key === 'ArrowLeft') { event.preventDefault(); render(current - 1); }
        if (event.key === 'ArrowRight') { event.preventDefault(); render(current + 1); }
    });
})();
</script>
@endif
@endsection
