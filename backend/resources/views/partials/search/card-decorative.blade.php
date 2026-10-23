@php
    $link = url('/decorative-lights/' . $item->slug);
    $mainColor = $item->colors->first();
    $imgFile = $mainColor->main_image ?? ($item->featured_image ?? null);
    $imgSrc = $imgFile ? asset('storage/uploads/decorative/' . $imgFile) : asset('images/placeholder.svg');
    $categoryName = $item->category->name ?? 'Decorative';
    $collectionName = $item->collection->name ?? null;
    $colorsCount = $item->colors->count();
    $excerpt = $item->short_description ?: strip_tags($item->description);
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-dec h-100">
        <a href="{{ $link }}" class="search-card-media-wrap">
            <span class="search-type-badge badge-decorative">Decorative</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $item->name }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                <span class="search-card-cat">{{ $categoryName }}</span>
                @if($collectionName)
                    <span class="search-card-subcat d-none d-sm-inline-block">{{ $collectionName }}</span>
                @endif
            </div>
            <h3 class="search-card-title">
                <a href="{{ $link }}">{{ $item->name }}</a>
            </h3>
            @if($excerpt)
                <p class="search-card-desc d-none d-md-block">{{ Str::limit($excerpt, 75) }}</p>
            @endif
            @if($colorsCount > 1)
                <div class="search-card-finishes">
                    <span class="search-finish-text">{{ $colorsCount }} {{ Str::plural('Finish', $colorsCount) }}</span>
                </div>
            @endif
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $link }}" class="search-card-action">
                    <span>Explore Design</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
