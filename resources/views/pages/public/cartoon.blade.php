@extends('layouts.cartoon')
@section('content')
@php($hero = $featured->first() ?? $latest->first())
<div class="cartoon-archive">
  <section class="cartoon-archive-hero">
    <div class="cartoon-archive-hero-art">
      @if($hero?->resolved_thumbnail_url)
        <img src="{{ $hero->resolved_thumbnail_url }}" alt="{{ $hero->title }}">
      @else
        <div class="cartoon-empty-art">Cartoon Archive</div>
      @endif
    </div>
    <div class="cartoon-archive-hero-copy">
      <div class="cartoon-overline">Kipanya · Cartoon Archive</div>
      <h1>Everyday life,<br><span>drawn differently.</span></h1>
      <p>A living archive of cartoons, observations and stories from the world around us.</p>
      @if($hero)
        <div class="cartoon-archive-hero-meta">
          <span>{{ $hero->category?->name ?? 'Cartoon' }}</span>
          @if($hero->published_at)<span>{{ $hero->published_at->format('d M Y') }}</span>@endif
        </div>
        <div class="cartoon-archive-actions">
          <a href="{{ route('watch', $hero) }}" class="cartoon-archive-btn cartoon-archive-btn-dark">Open cartoon <x-icon name="arrow-right" size="15"/></a>
          <a href="{{ route('wear.design', $hero) }}" class="cartoon-archive-btn cartoon-archive-btn-light"><x-icon name="shirt" size="15"/> Make a T-shirt</a>
        </div>
      @endif
    </div>
  </section>

  @if($latest->count())
  <section class="cartoon-archive-section">
    <div class="cartoon-archive-heading">
      <div><div class="cartoon-overline">The latest</div><h2>Fresh from the archive</h2></div>
      <a href="{{ route('discover') }}">View all <x-icon name="arrow-right" size="14"/></a>
    </div>
    <div class="cartoon-editorial-grid">
      @foreach($latest->take(9) as $cartoon)
        <article class="cartoon-editorial-card">
          <a href="{{ route('watch', $cartoon) }}" class="cartoon-editorial-art">
            @if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}" loading="lazy">@endif
          </a>
          <div class="cartoon-editorial-meta">
            <div class="cartoon-editorial-line"><span>{{ $cartoon->category?->name ?? 'Cartoon' }}</span>@if($cartoon->published_at)<time>{{ $cartoon->published_at->format('d M Y') }}</time>@endif</div>
            <h3><a href="{{ route('watch', $cartoon) }}">{{ $cartoon->title }}</a></h3>
            @if($cartoon->description)<p>{{ $cartoon->description }}</p>@endif
            <a class="cartoon-card-wear" href="{{ route('wear.design', $cartoon) }}"><x-icon name="shirt" size="13"/> Make a T-shirt</a>
          </div>
        </article>
      @endforeach
    </div>
  </section>
  @endif

  @if($collections->count())
  <section class="cartoon-archive-section cartoon-archive-rule-section" id="collections">
    <div class="cartoon-archive-heading"><div><div class="cartoon-overline">Curated</div><h2>Collections</h2></div></div>
    <div class="cartoon-collection-editorial-grid">
      @foreach($collections as $collection)
        <a href="{{ route('collection', $collection) }}" class="cartoon-collection-editorial-card">
          <div class="cartoon-collection-editorial-art">
            @if($collection->resolved_cover_url)<img src="{{ $collection->resolved_cover_url }}" alt="{{ $collection->name }}">@elseif($collection->cartoons->first()?->resolved_thumbnail_url)<img src="{{ $collection->cartoons->first()->resolved_thumbnail_url }}" alt="{{ $collection->name }}">@endif
          </div>
          <div><span>{{ $collection->cartoons->count() }} stories</span><h3>{{ $collection->name }}</h3>@if($collection->description)<p>{{ $collection->description }}</p>@endif</div>
          <x-icon name="arrow-right" size="16"/>
        </a>
      @endforeach
    </div>
  </section>
  @endif

  @if($categories->count())
  <section class="cartoon-archive-section cartoon-archive-rule-section">
    <div class="cartoon-archive-heading"><div><div class="cartoon-overline">Browse</div><h2>Find a subject</h2></div></div>
    <div class="cartoon-category-editorial-list">
      @foreach($categories as $category)
        <a href="{{ route('category', $category) }}"><span>{{ $category->name }}</span><small>{{ $category->cartoons_count }}</small><x-icon name="arrow-right" size="15"/></a>
      @endforeach
    </div>
  </section>
  @endif
</div>
@endsection
