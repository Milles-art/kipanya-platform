@extends('layouts.wear')
@section('content')
<div class="store-page catalog-page">
 <div class="page-heading"><div><span class="eyebrow">Kipanya Wear</span><h1>{{ $title }}</h1><p>{{ $copy }}</p></div><div class="catalog-meta" aria-live="polite"><span>{{ $products->total() }} {{ Str::plural('piece', $products->total()) }}</span></div></div>
 @if($categories->count())<nav class="category-strip" aria-label="Filter by category"><a href="{{ route('wear.catalog', request()->except('page', 'category')) }}" class="{{ !request('category') ? 'active' : '' }}"><x-icon name="grid" size="14"/> All</a>@foreach($categories as $category)<a href="{{ route('wear.catalog', array_merge(request()->except('page'), ['category' => $category])) }}" class="{{ request('category') === $category ? 'active' : '' }}"><x-icon name="tag" size="14"/>{{ $category }}</a>@endforeach</nav>@endif
 <div class="catalog-grid">@forelse($products as $product)<x-wear.nova-product-card :product="$product"/>@empty<div class="empty-card" style="grid-column:1/-1"><div><x-icon name="search" size="26"/></div><h2>No products found</h2><span>Try another search or category.</span><a class="primary-button" href="{{ route('wear.catalog') }}">View all products <x-icon name="arrow-right" size="15"/></a></div>@endforelse</div>
 @if($products->hasPages())<div class="pagination">{{ $products->onEachSide(1)->links('pagination::simple-tailwind') }}</div>@endif
</div>
@endsection
