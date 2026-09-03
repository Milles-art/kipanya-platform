@extends('admin.control-layout')
@section('content')
<div class="control-welcome k-reveal">
    <div>
        <div class="k-label k-muted">Kipanya Platform</div>
        <h1>Welcome back, {{ $adminName }}!</h1>
        <p>Manage all Kipanya applications from one powerful dashboard.</p>
    </div>
</div>

<section class="control-metric-grid k-reveal k-delay-1" aria-label="Platform metrics">
    @foreach($metrics as $metric)
        <div class="control-metric-card">
            <span class="control-metric-icon control-metric-{{ $metric['tone'] }}"><x-icon name="{{ $metric['icon'] }}" size="21"/></span>
            <div><span>{{ $metric['label'] }}</span><strong>{{ $metric['value'] }}</strong><small>{{ $metric['note'] }}</small></div>
        </div>
    @endforeach
</section>

<div class="control-dashboard-grid k-reveal k-delay-2">
    <section>
        <div class="control-section-head"><div><h2>Your Applications</h2><p>Click any application to access its admin dashboard.</p></div></div>
        <div class="control-app-grid">
            @foreach($apps as $app)
                @if($app['route']) <a href="{{ $app['route'] }}" class="control-app-card control-app-{{ $app['accent'] }}"> @else <div class="control-app-card control-app-{{ $app['accent'] }} control-app-disabled"> @endif
                    <div class="control-app-top"><span class="control-app-icon"><x-icon name="{{ $app['icon'] }}" size="23"/></span><span class="control-status {{ $app['status'] === 'active' ? 'active' : '' }}">{{ $app['status'] === 'active' ? 'Active' : 'Coming soon' }}</span></div>
                    <div class="control-app-copy"><h3>{{ $app['name'] }}</h3><p>{{ $app['description'] }}</p></div>
                    <div class="control-app-stats">
                        @foreach($app['stats'] as $stat)<div><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div>@endforeach
                        @if(!$app['stats']) <div><strong>—</strong><span>{{ $app['stat_labels'][0] }}</span></div><div><strong>—</strong><span>{{ $app['stat_labels'][1] }}</span></div><div><strong>—</strong><span>{{ $app['stat_labels'][2] }}</span></div>@endif
                    </div>
                    <div class="control-app-action">{{ $app['action'] }} @if($app['status'] !== 'active')<x-icon name="lock" size="14"/>@else<x-icon name="arrow-right" size="15"/>@endif</div>
                @if($app['route'])</a>@else</div>@endif
            @endforeach
        </div>
    </section>

    <aside class="control-side-stack">
        <section class="control-panel">
            <div class="control-panel-head"><h2>Recent Platform Activity</h2><a href="{{ route('admin.activity') }}">View all</a></div>
            <div class="control-activity-list">
                @forelse($recent as $item)
                    <a href="{{ route('admin.activity') }}" class="control-activity-row">
                        <span class="control-activity-icon control-activity-purple"><x-icon name="activity" size="17"/></span>
                        <span class="control-activity-copy"><strong>{{ $item->action }}</strong><small>{{ $item->description }} · {{ $item->user?->name ?? 'System' }}</small></span>
                        <time>{{ $item->created_at?->diffForHumans(null, true) ?? '' }}</time>
                    </a>
                @empty
                    <div class="control-empty"><x-icon name="activity" size="20"/><p>No platform activity yet.</p></div>
                @endforelse
            </div>
        </section>

        <section class="control-panel control-system-panel">
            <div class="control-panel-head"><h2>System Status</h2></div>
            <div class="control-status-list">
                @foreach($systemStatus as $status)
                    <div><span>{{ $status['label'] }}</span><strong class="{{ $status['tone'] }}"><i></i>{{ $status['value'] }}</strong></div>
                @endforeach
            </div>
            <div class="control-system-ok">@if(collect($systemStatus)->contains(fn ($status) => $status['tone'] === 'bad'))<x-icon name="activity" size="15"/> One or more systems need attention.@else<x-icon name="check-circle" size="15"/> All monitored systems operational.@endif</div>
        </section>
    </aside>
</div>

<footer class="control-footer"><span>© {{ now()->year }} Kipanya Platform. All rights reserved.</span><span>Control Center · v1.0.0</span></footer>
@endsection
