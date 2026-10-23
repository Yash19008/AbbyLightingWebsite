@php
    $link = url('/products/' . $item->slug);
    $imgFile = $item->image ?: ($item->hover_image ?: null);
    $imgSrc = $imgFile ? asset('storage/uploads/sub_tags/' . $imgFile) : asset('images/placeholder.svg');
    $firstTag = $item->subTagMappings->first()?->tag;
    $categoryName = $firstTag ? ($firstTag->display_name ?: $firstTag->name) : 'Architectural';
    $title = $item->display_name ?: $item->name;
    $modelsCount = \App\Models\ProductMaster::whereRaw('FIND_IN_SET(' . $item->id . ', sub_tag_ids)')->where('is_active', 'yes')->count();
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-arch h-100">
        <a href="{{ $link }}" class="search-card-media-wrap">
            <span class="search-type-badge">Architectural</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $title }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                <span class="search-card-cat">{{ $categoryName }}</span>
                @if($modelsCount > 0)
                    <span class="search-card-count">{{ $modelsCount }} {{ Str::plural('Model', $modelsCount) }}</span>
                @endif
            </div>
            <h3 class="search-card-title">
                <a href="{{ $link }}">{{ $title }}</a>
            </h3>
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $link }}" class="search-card-action">
                    <span>View Product</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
