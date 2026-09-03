@php
    $applications = [
        ['name' => 'Cartoon Archive', 'meta' => 'Cartoon Studio', 'icon' => 'film', 'accent' => 'purple', 'route' => route('admin.cartoon.dashboard'), 'active' => request()->routeIs('admin.cartoon.*')],
        ['name' => 'Kipanya Wear', 'meta' => 'Coming soon', 'icon' => 'bag', 'accent' => 'green', 'route' => null, 'active' => false],
        ['name' => 'Kipanya Book', 'meta' => 'Coming soon', 'icon' => 'book', 'accent' => 'blue', 'route' => null, 'active' => false],
        ['name' => 'Kaypee Motors', 'meta' => 'Coming soon', 'icon' => 'car', 'accent' => 'orange', 'route' => null, 'active' => false],
        ['name' => 'Kipanya TV', 'meta' => 'Coming soon', 'icon' => 'tv', 'accent' => 'pink', 'route' => null, 'active' => false],
    ];
    $current = request()->routeIs('admin.cartoon.*') ? 'Cartoon Archive' : 'Control Center';
@endphp

<div class="app-switcher" data-app-switcher>
    <button class="app-switcher-trigger" type="button" aria-expanded="false" aria-haspopup="menu" data-app-switcher-toggle>
        <span class="app-switcher-brand">K</span>
        <span class="app-switcher-current"><strong>{{ $current }}</strong><small>{{ $current === 'Control Center' ? 'Platform administration' : 'Application workspace' }}</small></span>
        <x-icon name="chevron-down" size="14"/>
    </button>
    <div class="app-switcher-menu" role="menu" hidden>
        <div class="app-switcher-heading">Switch application</div>
        @foreach($applications as $app)
            @if($app['route'])
                <a href="{{ $app['route'] }}" class="app-switcher-item {{ $app['active'] ? 'active' : '' }}" role="menuitem">
            @else
                <div class="app-switcher-item disabled" role="menuitem" aria-disabled="true">
            @endif
                <span class="app-switcher-icon app-switcher-{{ $app['accent'] }}"><x-icon name="{{ $app['icon'] }}" size="16"/></span>
                <span><strong>{{ $app['name'] }}</strong><small>{{ $app['meta'] }}</small></span>
                @if($app['active']) <span class="app-switcher-check"><x-icon name="check-circle" size="15"/></span> @elseif(!$app['route']) <span class="app-switcher-lock"><x-icon name="lock" size="13"/></span> @endif
            @if($app['route'])</a>@else</div>@endif
        @endforeach
        <div class="app-switcher-divider"></div>
        <a href="{{ route('admin.dashboard') }}" class="app-switcher-center" role="menuitem"><x-icon name="layers" size="15"/><span>Control Center</span></a>
    </div>
</div>
