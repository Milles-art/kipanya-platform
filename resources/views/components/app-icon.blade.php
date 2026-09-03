@props([
    'name',
    'accent' => '#ffffff',
    'size' => 48,
])
@php
    $icons = [
        'film' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 4v16M16 4v16M3 9h5M16 9h5M3 15h5M16 15h5"/>',
        'shirt' => '<path d="M8 5.5 5 4 2.5 8l3.5 2v10h12V10l3.5-2L19 4l-3 1.5a5 5 0 0 1-8 0Z"/><path d="M8 5.5c.5 2 1.8 3 4 3s3.5-1 4-3"/>',
        'book-open' => '<path d="M12 7v14"/><path d="M3 18a4 4 0 0 1 4-4h5V5a4 4 0 0 0-4-4H3v17Z"/><path d="M21 18a4 4 0 0 0-4-4h-5V5a4 4 0 0 1 4-4h5v17Z"/>',
        'car' => '<path d="m5 17 1.5-6h11L19 17"/><path d="M7 11 8.5 7h7L17 11"/><path d="M4 17h16v3H4z"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
        'tv' => '<rect x="3" y="5" width="18" height="13" rx="2"/><path d="m9 22 3-4 3 4M8 2l4 3 4-3"/>',
    ];
    $icon = $icons[$name] ?? $icons['film'];
@endphp

<div {{ $attributes->merge(['class' => 'app-icon-wrapper']) }} style="--app-accent: {{ $accent }}; --app-icon-size: {{ is_numeric($size) ? $size . 'px' : $size }};">
    <svg width="50%" height="50%" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--app-accent)" aria-hidden="true">{!! $icon !!}</svg>
</div>
