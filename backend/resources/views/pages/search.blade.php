@extends('layout.web', ['theme' => 'light'])

@section('title', "Search Results for \"" . ($q ?? '') . "\" | Abby Lighting")
@section('description', "Explore Abby Lighting search results for " . ($q ?? '') . " across architectural lighting, decorative fixtures, collections, projects, and catalogues.")

@push('css')
<style>
    /* Search Page Full Responsiveness & Luxury Styling */
    .search-page {
        background-color: #fafafa;
        min-height: 80vh;
        padding-bottom: 5rem;
        overflow-x: hidden;
        width: 100%;
    }

    /* Hero Banner */
    .search-hero {
        background: #000000;
        color: #ffffff;
        padding: 3.5rem 1.25rem 3rem;
        position: relative;
        overflow: hidden;
    }
    .search-hero::after {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 70%);
        pointer-events: none;
    }
    .search-hero-inner {
        max-width: 960px;
        margin: 0 auto;
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .search-hero-eyebrow {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.22rem;
        color: #999999;
        margin-bottom: 0.65rem;
        display: inline-block;
        font-weight: 500;
    }
    .search-hero-title {
        font-size: 2.25rem;
        font-weight: 300;
        letter-spacing: -0.02em;
        margin-bottom: 1.35rem;
        color: #ffffff;
        line-height: 1.25;
    }
    .search-hero-title strong {
        font-weight: 600;
        color: #ffffff;
    }

    /* In-Hero Search Form */
    .search-hero-form {
        max-width: 680px;
        width: 100%;
        margin: 0 auto 1.25rem;
        position: relative;
        display: flex;
        align-items: center;
        background: #141414;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 9999px;
        padding: 0.35rem 0.5rem 0.35rem 1.25rem;
        transition: all 0.25s ease;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }
    .search-hero-form:focus-within {
        border-color: #ffffff;
        background: #1a1a1a;
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    }
    .search-hero-icon {
        color: #888888;
        display: flex;
        align-items: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }
    .search-hero-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: #ffffff;
        font-size: 0.98rem;
        font-weight: 400;
        min-width: 0;
    }
    .search-hero-input::placeholder {
        color: #777777;
    }
    .search-hero-btn {
        background: #ffffff;
        color: #000000;
        border: none;
        border-radius: 9999px;
        padding: 0.6rem 1.35rem;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.04rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-shrink: 0;
    }
    .search-hero-btn:hover {
        background: #e5e5e5;
        transform: translateX(2px);
    }
    .search-hero-summary {
        font-size: 0.88rem;
        color: #aaaaaa;
        letter-spacing: 0.02rem;
    }
    .search-hero-summary span.highlight-count {
        color: #ffffff;
        font-weight: 600;
        border-bottom: 1px solid rgba(255,255,255,0.4);
        padding-bottom: 1px;
    }

    /* Category Nav Filter Bar (Responsive: sticky on desktop, relative on mobile) */
    .search-nav-bar {
        background: #ffffff;
        border-bottom: 1px solid #e5e5e5;
        position: sticky;
        top: 82px;
        z-index: 99;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .search-nav-container {
        max-width: 1360px;
        margin: 0 auto;
        padding: 0.6rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        white-space: nowrap;
    }
    .search-nav-container::-webkit-scrollbar {
        display: none;
    }
    .search-nav-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1.1rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #444444;
        background: #f1f1f1;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        flex-shrink: 0;
    }
    .search-nav-pill:hover {
        background: #e5e5e5;
        color: #000000;
    }
    .search-nav-pill.active {
        background: #000000;
        color: #ffffff;
    }
    .search-nav-pill .pill-badge {
        font-size: 0.72rem;
        padding: 0.15rem 0.45rem;
        border-radius: 9999px;
        background: rgba(0,0,0,0.08);
        color: inherit;
        font-weight: 600;
    }
    .search-nav-pill.active .pill-badge {
        background: rgba(255,255,255,0.25);
        color: #ffffff;
    }

    /* Content Wrapper */
    .search-content-wrap {
        max-width: 1360px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem 0;
    }

    /* Category Section */
    .search-category-section {
        margin-bottom: 3.5rem;
        scroll-margin-top: 140px;
    }
    .search-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.6rem;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .search-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .search-section-title {
        font-size: 1.35rem;
        font-weight: 600;
        letter-spacing: -0.01em;
        color: #111111;
        margin: 0;
    }
    .search-section-badge {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06rem;
        background: #000000;
        color: #ffffff;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
    }
    .search-section-count-hint {
        font-size: 0.82rem;
        color: #777777;
    }

    /* Cards Styling */
    .search-card {
        background: #ffffff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .search-card:hover {
        transform: translateY(-4px);
        border-color: #000000;
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .search-card-media-wrap {
        position: relative;
        display: block;
        width: 100%;
        height: 200px;
        background: #f7f7f7;
        overflow: hidden;
    }
    .search-card-media-wrap.media-project,
    .search-card-media-wrap.media-blog,
    .search-card-media-wrap.media-collection {
        height: 190px;
    }
    .search-card-media-wrap.media-catalogue {
        height: 230px;
        background: #f0f0f0;
    }
    .search-card-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 0.9rem;
        transition: transform 0.4s ease;
    }
    .media-project .search-card-img,
    .media-blog .search-card-img,
    .media-collection .search-card-img {
        object-fit: cover;
        padding: 0;
    }
    .media-catalogue .search-card-img {
        object-fit: contain;
        padding: 1.1rem;
    }
    .search-card:hover .search-card-img {
        transform: scale(1.04);
    }
    .search-type-badge {
        position: absolute;
        top: 0.65rem;
        left: 0.65rem;
        font-size: 0.62rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06rem;
        background: rgba(0,0,0,0.85);
        color: #ffffff;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        backdrop-filter: blur(4px);
        z-index: 2;
    }
    .badge-decorative { background: #252525; }
    .badge-collection { background: #4a3e35; }
    .badge-project { background: #1e3a5f; }
    .badge-catalogue { background: #592525; }
    .badge-blog { background: #1f4e38; }

    .search-card-body {
        padding: 1.1rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .search-card-meta {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        color: #777777;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }
    .search-card-cat {
        text-transform: uppercase;
        letter-spacing: 0.05rem;
        font-weight: 600;
        color: #888888;
        font-size: 0.7rem;
    }
    .search-card-subcat {
        background: #f0f0f0;
        padding: 0.1rem 0.35rem;
        border-radius: 3px;
        font-size: 0.68rem;
    }
    .search-card-count, .search-card-date, .search-card-size {
        margin-left: auto;
        color: #999999;
        font-size: 0.7rem;
    }
    .search-card-loc {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        color: #666666;
    }
    .search-card-title {
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.35;
        margin: 0 0 0.4rem 0;
        color: #111111;
        word-break: break-word;
    }
    .search-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .search-card-title a:hover {
        color: #000000;
        text-decoration: underline;
    }
    .search-card-desc {
        font-size: 0.78rem;
        color: #666666;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    .search-card-finishes {
        font-size: 0.72rem;
        color: #555555;
        margin-bottom: 0.4rem;
    }
    .search-card-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #111111;
        text-decoration: none;
        transition: gap 0.2s ease, color 0.2s ease;
    }
    .search-card-action:hover {
        color: #000000;
        gap: 0.55rem;
    }

    /* View More Button */
    .search-load-more-wrap {
        text-align: center;
        margin-top: 1.75rem;
        padding-top: 0.5rem;
    }
    .btn-search-load-more {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: transparent;
        color: #000000;
        border: 1.5px solid #000000;
        border-radius: 9999px;
        padding: 0.7rem 2rem;
        font-size: 0.84rem;
        font-weight: 600;
        letter-spacing: 0.04rem;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        min-width: 200px;
    }
    .btn-search-load-more:hover {
        background: #000000;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }
    .btn-search-load-more .spinner {
        display: none;
        width: 15px;
        height: 15px;
        border: 2px solid rgba(0,0,0,0.25);
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    .btn-search-load-more.loading .spinner {
        display: inline-block;
    }
    .btn-search-load-more.loading .btn-text,
    .btn-search-load-more.loading svg {
        opacity: 0.7;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Empty Zero State */
    .search-empty-state {
        text-align: center;
        padding: 4rem 1.25rem;
        max-width: 650px;
        margin: 0 auto;
    }
    .search-empty-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #777777;
    }
    .search-empty-title {
        font-size: 1.6rem;
        font-weight: 500;
        margin-bottom: 0.65rem;
        color: #111111;
    }
    .search-empty-text {
        color: #666666;
        font-size: 0.92rem;
        line-height: 1.5;
        margin-bottom: 1.75rem;
    }
    .search-suggestions-label {
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.12rem;
        color: #888888;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    .search-suggestions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
    .search-suggestion-chip {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        background: #ffffff;
        border: 1px solid #dcdcdc;
        border-radius: 9999px;
        color: #222222;
        font-size: 0.82rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .search-suggestion-chip:hover {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }

    /* Fade-in for newly loaded cards */
    @keyframes cardFadeIn {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .search-card-col.animate-in {
        animation: cardFadeIn 0.35s ease forwards;
    }

    /* ==================================================================
       MOBILE & TABLET RESPONSIVE OVERRIDES
       ================================================================== */
    @media (max-width: 768px) {
        .search-hero {
            padding: 2rem 1rem 1.6rem;
        }
        .search-hero-eyebrow {
            font-size: 0.65rem;
            letter-spacing: 0.18rem;
            margin-bottom: 0.45rem;
        }
        .search-hero-title {
            font-size: 1.45rem;
            margin-bottom: 1rem;
        }
        .search-hero-form {
            padding: 0.25rem 0.35rem 0.25rem 0.9rem;
            margin-bottom: 1rem;
        }
        .search-hero-icon {
            margin-right: 0.5rem;
        }
        .search-hero-icon svg {
            width: 17px;
            height: 17px;
        }
        .search-hero-input {
            font-size: 0.88rem;
        }
        .search-hero-btn {
            padding: 0.45rem 0.95rem;
            font-size: 0.78rem;
        }
        .search-hero-btn span {
            display: inline-block;
        }
        .search-hero-summary {
            font-size: 0.8rem;
        }

        /* Nav Bar: make relative on mobile so it doesn't obstruct screen while scrolling */
        .search-nav-bar {
            position: relative;
            top: auto;
            box-shadow: none;
            background: #ffffff;
            border-bottom: 1px solid #eeeeee;
        }
        .search-nav-container {
            padding: 0.5rem 0.75rem;
            gap: 0.4rem;
        }
        .search-nav-pill {
            padding: 0.38rem 0.85rem;
            font-size: 0.75rem;
        }
        .search-nav-pill .pill-badge {
            font-size: 0.68rem;
            padding: 0.1rem 0.4rem;
        }

        /* Content wrap */
        .search-content-wrap {
            padding: 1.25rem 0.75rem 0;
        }
        .search-category-section {
            margin-bottom: 2.5rem;
            scroll-margin-top: 20px;
        }
        .search-section-header {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
        }
        .search-section-title {
            font-size: 1.15rem;
        }
        .search-section-badge {
            font-size: 0.68rem;
            padding: 0.15rem 0.5rem;
        }
        .search-section-count-hint {
            font-size: 0.75rem;
        }

        /* Responsive Card Dimensions */
        .search-card-media-wrap {
            height: 135px;
        }
        .search-card-media-wrap.media-project,
        .search-card-media-wrap.media-blog,
        .search-card-media-wrap.media-collection {
            height: 125px;
        }
        .search-card-media-wrap.media-catalogue {
            height: 155px;
        }
        .search-card-img {
            padding: 0.5rem;
        }
        .media-project .search-card-img,
        .media-blog .search-card-img,
        .media-collection .search-card-img {
            padding: 0;
        }
        .media-catalogue .search-card-img {
            padding: 0.65rem;
        }
        .search-type-badge {
            top: 0.45rem;
            left: 0.45rem;
            font-size: 0.55rem;
            padding: 0.12rem 0.4rem;
        }
        .search-card-body {
            padding: 0.7rem 0.65rem;
        }
        .search-card-meta {
            font-size: 0.68rem;
            margin-bottom: 0.25rem;
        }
        .search-card-cat {
            font-size: 0.65rem;
        }
        .search-card-title {
            font-size: 0.88rem;
            line-height: 1.3;
            margin-bottom: 0.25rem;
        }
        .search-card-desc {
            font-size: 0.74rem;
            line-height: 1.35;
            margin-bottom: 0.35rem;
        }
        .search-card-finishes {
            font-size: 0.68rem;
            margin-bottom: 0.3rem;
        }
        .search-card-action {
            font-size: 0.72rem;
            gap: 0.25rem;
        }
        .search-card-action svg {
            width: 13px;
            height: 13px;
        }

        /* View more button */
        .btn-search-load-more {
            width: 100%;
            max-width: 300px;
            padding: 0.65rem 1.25rem;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 360px) {
        .search-hero-title {
            font-size: 1.25rem;
        }
        .search-hero-btn span {
            display: none;
        }
        .search-hero-btn {
            padding: 0.5rem 0.7rem;
        }
        .search-card-media-wrap {
            height: 115px;
        }
    }
</style>
@endpush

@section('page-content')
<div class="search-page">
    {{-- Search Hero Header --}}
    <section class="search-hero">
        <div class="search-hero-inner">
            <span class="search-hero-eyebrow">Search Catalog &amp; Resources</span>
            <h1 class="search-hero-title">
                @if(!empty($q))
                    Results for <strong>"{{ $q }}"</strong>
                @else
                    Discover Abby Lighting
                @endif
            </h1>

            {{-- Integrated Search Form --}}
            <form action="{{ route('search') }}" method="GET" class="search-hero-form" role="search">
                <span class="search-hero-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </span>
                <input type="search" 
                       name="q" 
                       value="{{ $q ?? '' }}" 
                       class="search-hero-input" 
                       placeholder="Search products, collections, projects, catalogues..." 
                       aria-label="Search" 
                       autocomplete="off"
                       autofocus>
                <button type="submit" class="search-hero-btn" aria-label="Search">
                    <span>Search</span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            @if(!empty($q))
                <div class="search-hero-summary">
                    Found <span class="highlight-count">{{ $totalResults }}</span> matching {{ Str::plural('result', $totalResults) }}
                    across {{ count(array_filter($categories, fn($c) => $c['total'] > 0)) }} categories
                </div>
            @endif
        </div>
    </section>

    @if($totalResults > 0)
        {{-- Category Quick-Filter Navigation --}}
        <nav class="search-nav-bar" aria-label="Search categories">
            <div class="search-nav-container">
                <a href="#all" class="search-nav-pill active" data-target="all">
                    <span>All</span>
                    <span class="pill-badge">{{ $totalResults }}</span>
                </a>
                @foreach($categories as $key => $cat)
                    @if($cat['total'] > 0)
                        <a href="#cat-{{ $key }}" class="search-nav-pill" data-target="cat-{{ $key }}">
                            <span>{{ $cat['name'] }}</span>
                            <span class="pill-badge">{{ $cat['total'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </nav>

        {{-- Categorized Sections Container --}}
        <div class="search-content-wrap">
            @foreach($categories as $key => $cat)
                @if($cat['total'] > 0)
                    <section class="search-category-section" id="cat-{{ $key }}" data-category="{{ $key }}">
                        <div class="search-section-header">
                            <div class="search-section-title-wrap">
                                <h2 class="search-section-title">{{ $cat['name'] }}</h2>
                                <span class="search-section-badge">{{ $cat['total'] }}</span>
                            </div>
                            <span class="search-section-count-hint">
                                Showing <span id="shown-count-{{ $key }}">{{ $cat['items']->count() }}</span> of {{ $cat['total'] }}
                            </span>
                        </div>

                        {{-- Cards Grid (Responsive 2-col on mobile, 3-col on tablet, 4-col on desktop) --}}
                        <div class="row g-2 g-md-3 g-lg-4 search-cards-grid" id="grid-{{ $key }}">
                            @foreach($cat['items'] as $item)
                                @include($cat['card_view'], ['item' => $item])
                            @endforeach
                        </div>

                        {{-- View More Button (if more than 8 exist) --}}
                        @if($cat['has_more'])
                            <div class="search-load-more-wrap" id="wrap-load-more-{{ $key }}">
                                <button type="button" 
                                        class="btn-search-load-more" 
                                        id="btn-load-more-{{ $key }}"
                                        data-category="{{ $key }}"
                                        data-page="2"
                                        data-query="{{ $q }}"
                                        data-total="{{ $cat['total'] }}"
                                        data-loaded="{{ $cat['items']->count() }}"
                                        data-remaining="{{ $cat['remaining'] }}"
                                        aria-label="View more {{ $cat['name'] }}">
                                    <span class="spinner" aria-hidden="true"></span>
                                    <span class="btn-text">View More ({{ $cat['remaining'] }} remaining)</span>
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2">
                                        <path d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </section>
                @endif
            @endforeach
        </div>
    @else
        {{-- Empty / Zero Results State --}}
        <div class="search-empty-state">
            <div class="search-empty-icon">
                <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                    <path d="M8 11h6"></path>
                </svg>
            </div>
            <h2 class="search-empty-title">No matches found</h2>
            <p class="search-empty-text">
                @if(!empty($q))
                    We couldn't find any products, collections, projects, catalogues or articles matching "<strong>{{ $q }}</strong>".
                @else
                    Enter a keyword above to search across all Abby Lighting products, collections, case studies, and resources.
                @endif
            </p>

            <div class="search-suggestions-label">Try searching for:</div>
            <div class="search-suggestions-list">
                <a href="{{ route('search', ['q' => 'Downlight']) }}" class="search-suggestion-chip">Downlights</a>
                <a href="{{ route('search', ['q' => 'Pendant']) }}" class="search-suggestion-chip">Pendants</a>
                <a href="{{ route('search', ['q' => 'Track lights']) }}" class="search-suggestion-chip">Track Lights</a>
                <a href="{{ route('search', ['q' => 'Quarry']) }}" class="search-suggestion-chip">Quarry Collection</a>
                <a href="{{ route('search', ['q' => 'Symphony']) }}" class="search-suggestion-chip">Symphony</a>
                <a href="{{ route('search', ['q' => 'Enso']) }}" class="search-suggestion-chip">Enso</a>
                <a href="{{ route('search', ['q' => 'Profiles']) }}" class="search-suggestion-chip">Profiles</a>
                <a href="{{ route('search', ['q' => 'Mumbai']) }}" class="search-suggestion-chip">Mumbai Projects</a>
            </div>
        </div>
    @endif
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Smooth Navigation Scroll & Active Pill Switching
    const navPills = document.querySelectorAll('.search-nav-pill');
    navPills.forEach(pill => {
        pill.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            navPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            if (targetId === 'all') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                const targetElem = document.getElementById(targetId);
                if (targetElem) {
                    targetElem.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // 2. AJAX Dynamic "View More" for each Category
    const loadMoreButtons = document.querySelectorAll('.btn-search-load-more');
    loadMoreButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.classList.contains('loading')) return;

            const category = this.getAttribute('data-category');
            const page = parseInt(this.getAttribute('data-page') || '2', 10);
            const query = this.getAttribute('data-query') || '';
            const total = parseInt(this.getAttribute('data-total') || '0', 10);
            const loadedSoFar = parseInt(this.getAttribute('data-loaded') || '0', 10);

            const grid = document.getElementById('grid-' + category);
            const shownCountElem = document.getElementById('shown-count-' + category);
            const wrap = document.getElementById('wrap-load-more-' + category);
            const btnText = this.querySelector('.btn-text');

            // Set loading state
            this.classList.add('loading');
            this.disabled = true;
            if (btnText) btnText.textContent = 'Loading...';

            const url = new URL('{{ route("search") }}', window.location.origin);
            url.searchParams.set('q', query);
            url.searchParams.set('category', category);
            url.searchParams.set('page', page);
            url.searchParams.set('action', 'load_more');

            fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                this.classList.remove('loading');
                this.disabled = false;

                if (data.success && data.html) {
                    // Create a temporary container to extract elements and add animation class
                    const temp = document.createElement('div');
                    temp.innerHTML = data.html;
                    const newCards = Array.from(temp.children);

                    newCards.forEach(card => {
                        card.classList.add('animate-in');
                        grid.appendChild(card);
                    });

                    // Update counts
                    const newLoaded = loadedSoFar + data.items_count;
                    this.setAttribute('data-loaded', newLoaded);
                    this.setAttribute('data-page', data.next_page);
                    this.setAttribute('data-remaining', data.remaining);

                    if (shownCountElem) {
                        shownCountElem.textContent = newLoaded;
                    }

                    // Check if more remaining
                    if (!data.has_more || data.remaining <= 0) {
                        if (wrap) {
                            wrap.style.transition = 'opacity 0.3s ease';
                            wrap.style.opacity = '0';
                            setTimeout(() => wrap.remove(), 300);
                        }
                    } else {
                        if (btnText) {
                            btnText.textContent = `View More (${data.remaining} remaining)`;
                        }
                    }
                } else {
                    if (wrap) wrap.remove();
                }
            })
            .catch(err => {
                console.error('Load more failed:', err);
                this.classList.remove('loading');
                this.disabled = false;
                const rem = this.getAttribute('data-remaining') || '';
                if (btnText) btnText.textContent = rem ? `View More (${rem} remaining)` : 'View More';
            });
        });
    });
});
</script>
@endpush
@endsection
