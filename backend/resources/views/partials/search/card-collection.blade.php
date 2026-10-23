@php
    $slug = strtolower($item->slug ?: Str::slug($item->name));
    $link = url('/collections/' . $slug);
    $imgFile = $item->cover_image ?: $item->banner_image;
    $imgSrc = $imgFile ? asset('storage/uploads/collections/' . $imgFile) : asset('images/placeholder.svg');
    $excerpt = $item->short_description ?: strip_tags($item->description);
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-collec h-100">
        <a href="{{ $link }}" class="search-card-media-wrap media-collection">
            <span class="search-type-badge badge-collection">Collection</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $item->name }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                <span class="search-card-cat">Signature</span>
            </div>
            <h3 class="search-card-title">
                <a href="{{ $link }}">{{ $item->name }}</a>
            </h3>
            @if($excerpt)
                <p class="search-card-desc d-none d-md-block">{{ Str::limit($excerpt, 75) }}</p>
            @endif
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $link }}" class="search-card-action">
                    <span>Discover</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
