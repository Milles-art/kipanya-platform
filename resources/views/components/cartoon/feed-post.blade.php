@props(['cartoon'])
<article class="cartoon-feed-post">
    <header class="cartoon-feed-post-head">
        <a href="{{ $cartoon->category ? route('category', $cartoon->category) : route('cartoon') }}" class="cartoon-feed-author">
            <span class="cartoon-feed-avatar">K</span>
            <span><strong>Kipanya Editorial</strong><small>{{ $cartoon->category?->name ?? 'Cartoon Archive' }}</small></span>
        </a>
        <a href="{{ route('cartoon.detail', $cartoon) }}" class="cartoon-feed-more" aria-label="Open {{ $cartoon->title }}">
            <x-icon name="external" size="16"/>
        </a>
    </header>

    <a href="{{ route('cartoon.detail', $cartoon) }}" class="cartoon-feed-art">
        @if($cartoon->resolved_thumbnail_url)
            <img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}" loading="lazy">
        @else
            <span>K</span>
        @endif
    </a>

    <div class="cartoon-feed-actions">
        <div>
            <x-cartoon.favorite-button :cartoon="$cartoon"/>
            <x-cartoon.share-button :title="$cartoon->title" :url="route('cartoon.detail', $cartoon)"/>
        </div>
        <a href="{{ route('cartoon.detail', $cartoon) }}" class="cartoon-feed-read">Read story <x-icon name="arrow-right" size="14"/></a>
    </div>

    <div class="cartoon-feed-copy">
        <h2><a href="{{ route('cartoon.detail', $cartoon) }}">{{ $cartoon->title }}</a></h2>
        @if($cartoon->caption || $cartoon->description)
            <p><strong>Kipanya Editorial</strong> {{ $cartoon->caption ?: \Illuminate\Support\Str::limit($cartoon->description, 180) }}</p>
        @endif
        <small class="cartoon-feed-date">{{ optional($cartoon->published_at)->format('d M Y') ?? 'Archive' }}</small>
    </div>
</article>