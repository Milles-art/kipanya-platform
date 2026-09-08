@extends('layouts.cartoon')
@section('content')
@php
    $spotlight = $featured ?: $latest->first();
    $artCount = $latest->count();
    $collectionCount = $collections->count();
    $themeCount = $categories->count();
    $hasEarlyAccess = auth()->check() && auth()->user()->is_supporter;
@endphp

<div class="kipanya-profile-page">
    <!-- HERO SECTION - Strong visual entry point -->
    <section class="kipanya-creator-hero">
        <div class="kipanya-creator-hero-art">
            @if($spotlight?->resolved_thumbnail_url)
                <img src="{{ $spotlight->resolved_thumbnail_url }}" alt="{{ $spotlight->title }}" loading="eager" decoding="async">
            @else
                <div class="kipanya-hero-placeholder">K</div>
            @endif
            <div class="kipanya-creator-hero-overlay"></div>
            <span class="kipanya-creator-hero-label">THE WORLD OF KIPANYA</span>
        </div>
        <div class="kipanya-creator-hero-copy">
            <div class="kipanya-creator-avatar">K</div>
            <div class="kipanya-creator-verified"><x-icon name="check-circle" size="14"/> Official cartoon archive</div>
            <h1>Masoud <span>Kipanya</span></h1>
            <p class="kipanya-creator-role">Cartoonist · Broadcaster · Creative storyteller</p>
            <p class="kipanya-creator-intro">A place to experience the ideas, humour and observations behind one of Tanzania's best-known cartoon characters — through the pictures that made people stop, laugh and think.</p>
            <div class="kipanya-creator-actions">
                <a href="#feed" class="kipanya-creator-primary">Explore the work <x-icon name="arrow-right" size="15"/></a>
                <a href="#about" class="kipanya-creator-secondary">About Masoud</a>
            </div>
            <div class="kipanya-creator-stats">
                <span><strong>{{ $artCount }}</strong><small>Latest works</small></span>
                <span><strong>{{ $themeCount }}</strong><small>Themes</small></span>
                <span><strong>{{ $collectionCount }}</strong><small>Collections</small></span>
            </div>
        </div>
    </section>

    <!-- MISSION STATEMENT - Set the context -->
    <section class="kipanya-story-strip">
        <div>
            <span class="public-kicker">The idea behind Kipanya</span>
            <h2>Draw a question.<br><em>Start a conversation.</em></h2>
        </div>
        <p>Kipanya is more than a character. The cartoons use humour, observation and everyday situations to make people look twice — and ask what is really going on.</p>
        <a href="#feed" class="kipanya-text-link">See the latest work <x-icon name="arrow-right" size="14"/></a>
    </section>

    <!-- FEATURED WORK - Highlighted showcase with better emphasis -->
    @if($spotlight)
    <section class="kipanya-feature-showcase">
        <div class="kipanya-feature-copy">
            <div class="kipanya-feature-header">
                <span class="public-kicker">Featured work</span>
                <span class="kipanya-feature-badge">Recommended</span>
            </div>
            <h2>{{ $spotlight->title }}</h2>
            @if($spotlight->caption || $spotlight->description)
                <p>{{ $spotlight->caption ?: $spotlight->description }}</p>
            @endif
            <div class="kipanya-feature-meta">
                <span><x-icon name="calendar" size="13"/> {{ optional($spotlight->published_at)->format('d M Y') ?? 'Kipanya archive' }}</span>
                <span><x-icon name="tag" size="13"/> {{ $spotlight->category?->name ?? 'Cartoon' }}</span>
                @if($spotlight->likes_count)
                <span><x-icon name="heart" size="13"/> {{ number_format($spotlight->likes_count) }} likes</span>
                @endif
            </div>
            <div class="kipanya-feature-actions">
                <a href="{{ route('cartoon.detail', $spotlight) }}" class="kipanya-creator-primary">
                    Open cartoon <x-icon name="arrow-right" size="15"/>
                </a>
                <x-cartoon.share-button 
                    :title="$spotlight->title" 
                    :url="route('cartoon.detail', $spotlight)" 
                    :count="$spotlight->shares_count ?? 0" 
                    :endpoint="route('cartoon.share', $spotlight)"
                />
            </div>
        </div>
        <a href="{{ route('cartoon.detail', $spotlight) }}" class="kipanya-feature-image">
            <img 
                src="{{ $spotlight->resolved_thumbnail_url }}" 
                alt="{{ $spotlight->title }}" 
                loading="lazy"
                decoding="async"
            >
            <span class="kipanya-feature-tag">FEATURED</span>
        </a>
    </section>
    @endif

    <!-- MAIN FEED + SIDEBAR LAYOUT -->
    <div class="kipanya-social-layout" id="feed">
        <main class="kipanya-feed">
            <!-- Feed header with browse option -->
            <div class="kipanya-feed-head">
                <div>
                    <span class="public-kicker">The feed</span>
                    <h2>Latest from Masoud</h2>
                </div>
                <a href="{{ route('discover') }}" class="kipanya-browse-link">
                    Browse archive <x-icon name="arrow-right" size="14"/>
                </a>
            </div>

            <!-- Quick category filter for feed (optional - shows up to 4 most recent categories) -->
            @if($categories->count())
            <nav class="kipanya-feed-filters">
                <a href="#feed" class="kipanya-filter-tag active">All</a>
                @foreach($categories->take(3) as $category)
                <a href="{{ route('discover', ['category' => $category->slug]) }}" class="kipanya-filter-tag">
                    {{ $category->name }}
                </a>
                @endforeach
            </nav>
            @endif

            <!-- Social feed with cartoons -->
            @if($latest->count())
            <div class="kipanya-feed-column">
            @foreach($latest as $cartoon)
                <article class="kipanya-social-post">
                    <!-- Post header with creator info -->
                    <header class="kipanya-post-head">
                        <div class="kipanya-post-author">
                            <span class="kipanya-post-avatar">K</span>
                            <div>
                                <strong>Masoud Kipanya</strong>
                                <small>
                                    <x-icon name="tag" size="12"/> {{ $cartoon->category?->name ?? 'Cartoon' }}
                                    · 
                                    <x-icon name="calendar" size="12"/> {{ optional($cartoon->published_at)->format('d M Y') ?? 'Archive' }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ route('cartoon.detail', $cartoon) }}" class="kipanya-post-more" aria-label="Open {{ $cartoon->title }}">
                            <x-icon name="external" size="16"/>
                        </a>
                    </header>

                    <!-- Cartoon image with better visual treatment -->
                    <a href="{{ route('cartoon.detail', $cartoon) }}" class="kipanya-post-image">
                        @if($cartoon->resolved_thumbnail_url)
                            <img 
                                src="{{ $cartoon->resolved_thumbnail_url }}" 
                                alt="{{ $cartoon->title }}" 
                                loading="lazy"
                                decoding="async"
                            >
                        @else
                            <div class="kipanya-post-image-placeholder">K</div>
                        @endif
                    </a>

                    <!-- Post body with content and interactions -->
                    <div class="kipanya-post-body">
                        <!-- Engagement metrics bar -->
                        <div class="kipanya-post-metrics">
                            <span>
                                <x-icon name="heart" size="14"/>
                                {{ number_format($cartoon->likes_count ?? 0) }}
                            </span>
                            <span>
                                <x-icon name="chat" size="14"/>
                                {{ number_format($cartoon->comments_count ?? 0) }}
                            </span>
                            <span>
                                <x-icon name="share2" size="14"/>
                                {{ number_format($cartoon->shares_count ?? 0) }}
                            </span>
                        </div>

                        <!-- Interaction buttons -->
                        <div class="kipanya-post-actions">
                            <div class="kipanya-post-interactions">
                                <x-cartoon.like-button :cartoon="$cartoon" />
                                <a href="{{ route('cartoon.detail', $cartoon) }}#comments" class="kipanya-comment-action">
                                    <x-icon name="chat" size="17"/>
                                    <span>Comment</span>
                                </a>
                                <x-cartoon.share-button 
                                    :title="$cartoon->title" 
                                    :url="route('cartoon.detail', $cartoon)" 
                                    :count="$cartoon->shares_count ?? 0" 
                                    :endpoint="route('cartoon.share', $cartoon)"
                                />
                            </div>
                            <a href="{{ route('cartoon.detail', $cartoon) }}" class="kipanya-post-open">
                                Open <x-icon name="arrow-right" size="13"/>
                            </a>
                        </div>

                        <!-- Post content -->
                        <div class="kipanya-post-content">
                            <h3>
                                <a href="{{ route('cartoon.detail', $cartoon) }}">
                                    {{ $cartoon->title }}
                                </a>
                            </h3>
                            @if($cartoon->caption || $cartoon->description)
                            <p>{{ $cartoon->caption ?: \Illuminate\Support\Str::limit($cartoon->description, 220) }}</p>
                            @endif
                            <span class="kipanya-post-signature">— Masoud Kipanya</span>
                        </div>

                        <!-- Comment preview and input -->
                        <div class="kipanya-inline-comments" data-comments-box>
                            <div class="kipanya-comment-preview">
                                <span class="kipanya-mini-avatar">
                                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name,0,1)) : 'K' }}
                                </span>
                                <span class="kipanya-comment-preview-text">
                                    {{ $cartoon->comments_count ? $cartoon->comments_count . ' ' . Str::plural('comment', $cartoon->comments_count) : 'Be the first to comment' }}
                                </span>
                            </div>
                            @auth
                            <form class="kipanya-comment-form" data-social-comment data-url="{{ route('cartoon.comment', $cartoon) }}">
                                @csrf
                                <input 
                                    name="body" 
                                    maxlength="2000" 
                                    placeholder="Write a comment…" 
                                    autocomplete="off"
                                >
                                <button type="submit" aria-label="Post comment">
                                    <x-icon name="arrow-right" size="15"/>
                                </button>
                            </form>
                            @else
                            <a href="{{ route('account.login') }}" class="kipanya-comment-login">
                                Log in to join the conversation
                            </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @endforeach
            </div>
            @else
                <!-- Empty state with better messaging -->
                <div class="cartoon-empty cartoon-empty-large">
                    <x-icon name="inbox" size="32"/>
                    <h2>The archive is warming up.</h2>
                    <p>New cartoons will appear here soon. Check back regularly or follow for updates.</p>
                    <a href="{{ route('discover') }}" class="cartoon-btn cartoon-btn-secondary">Browse existing cartoons</a>
                </div>
            @endif
        </main>

        <!-- SIDEBAR - Enhanced with better hierarchy -->
        <aside class="kipanya-profile-sidebar">
            <!-- About the creator -->
            <section class="kipanya-sidebar-card kipanya-about-card" id="about">
                <span class="public-kicker">Meet the creator</span>
                <div class="kipanya-about-mark">K</div>
                <h2>Masoud Kipanya</h2>
                <p>Ally Masoud, known professionally as Masoud Kipanya, is a Tanzanian creative whose work spans cartooning, broadcasting and visual storytelling.</p>
                <p>His signature Kipanya character became a way of asking questions through drawings — turning current life and public conversations into images people can recognise, discuss and remember.</p>
                <div class="kipanya-about-note">
                    <x-icon name="sparkles" size="15"/>
                    <span>The character is the conversation starter.</span>
                </div>
            </section>

            <!-- Daily cartoons with LIVE indicator -->
            @if($daily->count())
            <section class="kipanya-sidebar-card kipanya-daily-card">
                <div class="kipanya-sidebar-head">
                    <div>
                        <span class="public-kicker">Fresh</span>
                        <h3>Daily cartoons</h3>
                    </div>
                    <span class="kipanya-live-pill">
                        <span class="kipanya-live-dot"></span>LIVE
                    </span>
                </div>
                <div class="kipanya-daily-grid">
                    @foreach($daily->take(6) as $story)
                        <a 
                            href="{{ route('cartoon.detail', $story) }}" 
                            title="{{ $story->title }}"
                            class="kipanya-daily-thumbnail"
                        >
                            <img 
                                src="{{ $story->resolved_thumbnail_url }}" 
                                alt="{{ $story->title }}" 
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="kipanya-daily-overlay">{{ $story->category?->name ?? 'New' }}</span>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('discover', ['daily' => 1]) }}" class="kipanya-sidebar-more">
                    View all daily <x-icon name="arrow-right" size="13"/>
                </a>
            </section>
            @endif

            <!-- Collections with improved visibility -->
            @if($collections->count())
            <section class="kipanya-sidebar-card kipanya-collections-card">
                <div class="kipanya-sidebar-head">
                    <div>
                        <span class="public-kicker">Archive</span>
                        <h3>Collections</h3>
                    </div>
                    <a href="{{ route('collections') }}" class="kipanya-sidebar-view-all">
                        View all
                    </a>
                </div>
                <div class="kipanya-collection-list">
                    @foreach($collections->take(4) as $collection)
                        @php($cover=$collection->cartoons->first())
                        <a href="{{ route('collection', $collection) }}" class="kipanya-collection-item">
                            <span class="kipanya-collection-cover">
                                @if($cover?->resolved_thumbnail_url)
                                    <img 
                                        src="{{ $cover->resolved_thumbnail_url }}" 
                                        alt="{{ $collection->name }}"
                                        decoding="async"
                                    >
                                @else
                                    <div class="kipanya-collection-placeholder">K</div>
                                @endif
                            </span>
                            <div class="kipanya-collection-info">
                                <strong>{{ $collection->name }}</strong>
                                <small>{{ $collection->cartoons_count ?? $collection->cartoons->count() }} cartoons</small>
                            </div>
                            <x-icon name="arrow-right" size="13"/>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- Optional: Engagement CTA or stats card -->
            <section class="kipanya-sidebar-card kipanya-engagement-card">
                <span class="public-kicker">Join the conversation</span>
                <h3>Be part of the archive</h3>
                <p>Like, comment and share the cartoons that move you. Your engagement helps Masoud know what resonates.</p>
                <div class="kipanya-engagement-stats">
                    <div>
                        <strong>{{ number_format($latest->sum('likes_count') ?? 0) }}</strong>
                        <small>Total likes</small>
                    </div>
                    <div>
                        <strong>{{ number_format($latest->sum('comments_count') ?? 0) }}</strong>
                        <small>Total comments</small>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- CLOSING CTA - Drive back to archive -->
    <section class="kipanya-archive-cta">
        <div>
            <span class="public-kicker">Keep looking</span>
            <h2>There is always another<br><em>question in the archive.</em></h2>
        </div>
        <a href="{{ route('discover') }}" class="kipanya-creator-primary">
            Explore all cartoons <x-icon name="arrow-right" size="15"/>
        </a>
    </section>
</div>
@endsection