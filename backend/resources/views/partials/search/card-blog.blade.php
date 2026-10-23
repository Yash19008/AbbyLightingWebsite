@php
    $link = url('/blogs/' . $item->slug);
    $imgSrc = $item->featured_image ? asset('storage/uploads/blogs/' . $item->featured_image) : asset('images/placeholder.svg');
    $categoryName = $item->category->name ?? 'Story & Inspiration';
    $date = optional($item->published_at)->format('M d, Y');
    $excerpt = $item->dek ?: strip_tags($item->content);
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-blog h-100">
        <a href="{{ $link }}" class="search-card-media-wrap media-blog">
            <span class="search-type-badge badge-blog">Story</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $item->title }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                <span class="search-card-cat">{{ $categoryName }}</span>
                @if($date)
                    <span class="search-card-date d-none d-sm-inline-block">{{ $date }}</span>
                @endif
            </div>
            <h3 class="search-card-title">
                <a href="{{ $link }}">{{ $item->title }}</a>
            </h3>
            @if($excerpt)
                <p class="search-card-desc d-none d-md-block">{{ Str::limit($excerpt, 75) }}</p>
            @endif
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $link }}" class="search-card-action">
                    <span>Read Story</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
