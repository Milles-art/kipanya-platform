@extends('layouts.cartoon')
@section('content')
<div class="cartoon-detail-modern cartoon-shell">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('cartoon') }}" class="modern-back"><x-icon name="chevron-left" size="15"/> Archive</a>
    <div class="detail-modern-grid">
        <figure class="detail-art-stage">
            @if($cartoon->resolved_thumbnail_url)<img src="{{ $cartoon->resolved_thumbnail_url }}" alt="{{ $cartoon->title }}">@else<div class="detail-art-empty">K</div>@endif
            <figcaption><span>{{ $cartoon->category?->name ?? 'Editorial cartoon' }}</span><span>{{ $cartoon->published_at?->format('d M Y') ?? 'Archive' }}</span></figcaption>
        </figure>
        <article class="detail-story">
            <span class="public-kicker">Cartoon archive</span>
            <h1>{{ $cartoon->title }}</h1>
            <div class="detail-rule"></div>
            @if($cartoon->caption)<p class="detail-caption">{{ $cartoon->caption }}</p>@endif
            @if($cartoon->description)<p class="detail-description">{{ $cartoon->description }}</p>@endif
            <div class="detail-social-actions"><x-cartoon.like-button :cartoon="$cartoon"/><a href="#comments" class="kipanya-comment-action"><x-icon name="chat" size="17"/><span>Comment</span><b>{{ number_format($cartoon->comments_count ?? 0) }}</b></a><x-cartoon.share-button :title="$cartoon->title" :url="route('cartoon.detail', $cartoon)" :count="$cartoon->shares_count ?? 0" :endpoint="route('cartoon.share', $cartoon)"/></div>
            @if($cartoon->collections->count())
                <div class="detail-collections"><span>Part of</span><div>@foreach($cartoon->collections as $collection)<a href="{{ route('collection', $collection) }}">{{ $collection->name }} <x-icon name="external" size="13"/></a>@endforeach</div></div>
            @endif
            <a href="{{ route('wear.design', $cartoon) }}" class="detail-wear-link">Turn this artwork into a T-shirt <x-icon name="arrow-right" size="15"/></a>
        </article>
    </div>
<section class="kipanya-detail-comments" id="comments">
    <div class="kipanya-comments-head"><div><span class="public-kicker">Community</span><h2>What do you think?</h2></div><strong data-detail-comment-count>{{ number_format($cartoon->comments_count ?? 0) }} {{ Str::plural('comment', $cartoon->comments_count ?? 0) }}</strong></div>
    @auth
    <form class="kipanya-comment-form kipanya-comment-form-large" data-social-comment data-url="{{ route('cartoon.comment', $cartoon) }}">@csrf<span class="kipanya-mini-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span><input name="body" maxlength="2000" placeholder="Write a comment…" autocomplete="off"><button type="submit">Post</button></form>
    @else
    <div class="kipanya-comment-login-panel"><p>Join the conversation around Masoud's work.</p><a href="{{ route('account.login') }}">Log in to comment</a></div>
    @endauth
    <div class="kipanya-comments-list" data-comments-list>
        @foreach($comments as $comment)
        <article class="kipanya-comment" data-comment-id="{{ $comment->id }}"><span class="kipanya-mini-avatar">{{ strtoupper(substr($comment->user->name,0,1)) }}</span><div><div class="kipanya-comment-bubble"><strong>{{ $comment->user->name }}</strong><p>{{ $comment->body }}</p></div><small>{{ $comment->created_at->diffForHumans() }}</small></div></article>
        @endforeach
    </div>
</section>

    @if($related->count())
    <section class="detail-related"><div class="modern-section-head"><div><span class="public-kicker">Keep exploring</span><h2>More cartoons.</h2></div><a href="{{ route('discover') }}">Browse archive <x-icon name="arrow-right" size="15"/></a></div><div class="cartoon-masonry">@foreach($related as $item)<a href="{{ route('cartoon.detail', $item) }}" class="cartoon-masonry-card"><img src="{{ $item->resolved_thumbnail_url }}" alt="{{ $item->title }}" loading="lazy"><div><span>{{ $item->category?->name ?? 'Cartoon' }}</span><h3>{{ $item->title }}</h3><small>{{ $item->published_at?->format('d M Y') }}</small></div></a>@endforeach</div></section>
    @endif
</div>
@endsection
