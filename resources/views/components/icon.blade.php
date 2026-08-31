@props(['name', 'size' => 18])
@php
$paths = [
'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9.5V21h13V9.5"/><path d="M9.5 21v-6h5v6"/>',
'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
'search' => '<circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/>',
'bookmark' => '<path d="M6 4.5A2.5 2.5 0 0 1 8.5 2h7A2.5 2.5 0 0 1 18 4.5V21l-6-3.5L6 21Z"/>',
'collection' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h6M7 16h4"/>',
'play' => '<path d="m8 5 11 7-11 7Z"/>',
'chevron' => '<path d="m9 18 6-6-6-6"/>',
'moon' => '<path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 8.5 8.5 0 1 0 20.5 15.5Z"/>',
'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>',
'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
'x' => '<path d="m6 6 12 12M18 6 6 18"/>',
'chart' => '<path d="M4 19V5M4 19h17"/><path d="m7 15 4-4 3 2 6-7"/>',
'film' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 4v16M16 4v16M3 9h5M16 9h5M3 15h5M16 15h5"/>',
'users' => '<circle cx="9" cy="8" r="3"/><path d="M3 20c.7-3.3 2.7-5 6-5s5.3 1.7 6 5"/><path d="M16 5.5a3 3 0 0 1 0 5.8M18 15c2 .8 3 2.4 3 5"/>',
'settings' => '<path d="M12 15.2a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4Z"/><path d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.2a2 2 0 1 1-4 0v-.2a2 2 0 0 0-3.4-1.4l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1A2 2 0 0 0 4.4 11H4.2a2 2 0 1 1 0-4h.2A2 2 0 0 0 5.8 3.6l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1A2 2 0 0 0 12 2.2V2a2 2 0 1 1 4 0v.2a2 2 0 0 0 3.4 1.4l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1A2 2 0 0 0 20.6 7h.2a2 2 0 1 1 0 4h-.2a2 2 0 0 0-1.2 4Z" transform="scale(.78) translate(3.4 3.4)"/>',
];
@endphp
<svg {{ $attributes->merge(['width' => $size, 'height' => $size, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>{!! $paths[$name] ?? $paths['grid'] !!}</svg>
