@extends('layouts.cartoon')
@section('content')
<div class="cartoon-list-page cartoon-shell">
    <a href="{{ route('cartoon') }}#collections" class="cartoon-back"><x-icon name="chevron-left" size="15"/> Back to Archive</a>
    <header class="cartoon-page-intro"><span class="cartoon-kicker"><x-icon name="collection" size="14"/> Curated Archive</span><h1>Collections.</h1><p>Curated groups of Cartoon Archive artwork, organized around an idea, theme or moment.</p></header>
    @if($collections->count())
        <div class="cartoon-collection-grid-react">
            @foreach($collections as $collection)
                <a href="{{ route('collection', $collection) }}" class="cartoon-collection-card-react">
                    <div class="cartoon-collection-cover">
                        @if($collection->resolved_cover_url)<img src="{{ $collection->resolved_cover_url }}" alt="{{ $collection->name }}">@elseif($collection->cartoons->first()?->resolved_thumbnail_url)<img src="{{ $collection->cartoons->first()->resolved_thumbnail_url }}" alt="{{ $collection->name }}">@endif
                        <span class="cartoon-collection-count">{{ $collection->cartoons_count }} {{ Str::plural('cartoon', $collection->cartoons_count) }}</span>
                    </div>
                    <div class="cartoon-collection-copy"><h3>{{ $collection->name }}</h3><p>{{ $collection->description ?: 'A curated collection from the Cartoon Archive.' }}</p><span>Browse collection <x-icon name="arrow-right" size="14"/></span></div>
                </a>
            @endforeach
        </div>
        <div class="cartoon-pagination">{{ $collections->links() }}</div>
    @else
        <div class="cartoon-empty cartoon-empty-large"><x-icon name="collection" size="28"/><h2>No collections yet.</h2><p>Curated collections will appear here when artwork is grouped.</p></div>
    @endif
</div>
@endsection
