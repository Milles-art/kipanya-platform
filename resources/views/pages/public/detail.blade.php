@extends('layouts.cartoon')

@section('content')
<div class="cartoon-detail-page cartoon-shell">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('cartoon') }}" class="cartoon-back">
        <x-icon name="chevron-left" size="15"/> Back to Archive
    </a>

    <div class="cartoon-detail-grid">
        <div class="cartoon-detail-art-wrap">
            <div class="cartoon-detail-art">
                @if($cartoon->resolved_thumbnail_url)
                    <img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}">
                @else
                    <div class="cartoon-art-placeholder">K</div>
                @endif
            </div>
        </div>

        <div class="cartoon-detail-copy">
            <div class="cartoon-section-label">{{ $cartoon->category?->name ?? 'Cartoon' }}</div>
            <h1>{{ $cartoon->title }}</h1>

            <div class="cartoon-detail-meta">
                <span>
                    <x-icon name="calendar" size="13"/>
                    {{ $cartoon->published_at?->format('d M Y') ?? 'Archive' }}
                </span>
                <span>{{ ucfirst($cartoon->artwork_format ?? 'landscape') }}</span>
            </div>

            @if($cartoon->caption)
                <blockquote>“{{ $cartoon->caption }}”</blockquote>
            @endif

            @if($cartoon->description)
                <p class="cartoon-detail-description">{{ $cartoon->description }}</p>
            @endif

            <div class="cartoon-action-row">
                <x-cartoon.favorite-button :cartoon="$cartoon"/>
                <x-cartoon.share-button :title="$cartoon->title"/>
                <a href="{{ route('wear.design', $cartoon) }}" class="cartoon-btn cartoon-btn-primary">
                    <x-icon name="shirt" size="16"/> Make this a T-shirt
                </a>
            </div>

            @if($cartoon->collections->count())
                <div class="cartoon-related-collections">
                    <span>In collection</span>
                    <div>
                        @foreach($cartoon->collections as $collection)
                            <a href="{{ route('collection', $collection) }}">{{ $collection->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($related->count())
        <section class="cartoon-related-section">
            <div class="cartoon-section-head-react">
                <div>
                    <div class="cartoon-section-label">More from {{ $cartoon->category?->name }}</div>
                    <h2>Related Cartoons</h2>
                </div>
            </div>
            <div class="cartoon-grid-react">
                @foreach($related as $item)
                    <x-cartoon.card :cartoon="$item"/>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
