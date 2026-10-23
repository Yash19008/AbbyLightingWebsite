@php
    $link = route('project-detail', $item->slug);
    $firstImg = $item->projectImages->first();
    $imgFile = $firstImg ? $firstImg->image : null;
    $imgSrc = $imgFile ? asset('storage/uploads/projects/' . $imgFile) : asset('images/placeholder.svg');
    $location = $item->location;
    $type = $item->type;
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-proj h-100">
        <a href="{{ $link }}" class="search-card-media-wrap media-project">
            <span class="search-type-badge badge-project">Project</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $item->name }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                @if($type)
                    <span class="search-card-cat">{{ $type }}</span>
                @endif
                @if($location)
                    <span class="search-card-loc">
                        <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-8-7.5-8-12a8 8 0 1 1 16 0c0 4.5-8 12-8 12z"/><circle cx="12" cy="9" r="3"/></svg>
                        {{ $location }}
                    </span>
                @endif
            </div>
            <h3 class="search-card-title">
                <a href="{{ $link }}">{{ $item->name }}</a>
            </h3>
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $link }}" class="search-card-action">
                    <span>View Project</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
