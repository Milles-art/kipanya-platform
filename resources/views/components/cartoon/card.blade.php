@props(['cartoon'])
@php
    $format = $cartoon->artwork_format ?: 'landscape';
    $aspect = match($format) { 'portrait' => '3 / 4', 'square' => '1 / 1', default => '4 / 3' };
@endphp
<article class="cartoon-react-card" data-cartoon-card data-href="{{ route('cartoon.detail', $cartoon) }}" tabindex="0" role="link" aria-label="View {{ $cartoon->title }}">
    <div class="cartoon-card-media" style="--card-aspect:{{ $aspect }}">
        @if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}" loading="lazy">@else<div class="cartoon-art-placeholder">K</div>@endif
        <div class="cartoon-card-gradient"></div>
        <div class="cartoon-card-category">{{ $cartoon->category?->name ?? 'Cartoon' }}</div>
        <div class="cartoon-card-fav"><x-cartoon.favorite-button :cartoon="$cartoon" size="sm"/></div>
        <div class="cartoon-card-overlay"><h3>{{ $cartoon->title }}</h3><span><x-icon name="calendar" size="12"/> {{ $cartoon->published_at?->format('d M Y') ?? 'Archive' }}</span></div>
    </div>
    <div class="cartoon-card-footer"><p>{{ $cartoon->caption ?: \Illuminate\Support\Str::limit($cartoon->description, 80) }}</p><span class="cartoon-card-arrow"><x-icon name="arrow-right" size="15"/></span></div>
</article>
