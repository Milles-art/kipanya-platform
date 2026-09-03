@extends('layouts.cartoon')
@section('content')
<div class="cartoon-list-page cartoon-shell">
    <a href="{{ route('cartoon') }}#collections" class="cartoon-back"><x-icon name="chevron-left" size="15"/> Back to Collections</a>
    <header class="cartoon-collection-hero"><div class="cartoon-collection-hero-cover">@if($collection->resolved_cover_url)<img src="{{ $collection->resolved_cover_url }}" alt="{{ $collection->name }}">@elseif($collection->cartoons->first()?->resolved_thumbnail_url)<img src="{{ $collection->cartoons->first()->resolved_thumbnail_url }}" alt="{{ $collection->name }}">@endif</div><div><span class="cartoon-kicker"><x-icon name="collection" size="14"/> Collection</span><h1>{{ $collection->name }}</h1>@if($collection->description)<p>{{ $collection->description }}</p>@endif<span class="cartoon-count-label">{{ $collection->cartoons->count() }} {{ Str::plural('cartoon',$collection->cartoons->count()) }}</span></div></header>
    <div class="cartoon-grid-react">@forelse($collection->cartoons as $cartoon)<x-cartoon.card :cartoon="$cartoon"/>@empty<div class="cartoon-empty cartoon-empty-large"><h2>This collection is being prepared.</h2></div>@endforelse</div>
</div>
@endsection
