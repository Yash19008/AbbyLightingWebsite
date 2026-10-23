@php
    $pdfUrl = $item->pdf_file ? asset('storage/uploads/catalogues/' . $item->pdf_file) : url('/catalogues');
    $imgSrc = $item->cover_image ? asset('storage/uploads/catalogues/' . $item->cover_image) : asset('images/placeholder.svg');
    $categoryName = $item->category->name ?? 'Catalogue';
    $fileSize = $item->file_size ?: null;
@endphp
<div class="col-6 col-md-4 col-lg-3 mb-3 mb-md-4 search-card-col">
    <div class="search-card search-card-cat h-100">
        <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="search-card-media-wrap media-catalogue">
            <span class="search-type-badge badge-catalogue">Catalogue</span>
            <img src="{{ $imgSrc }}" 
                 alt="{{ $item->title }}" 
                 class="search-card-img" 
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder.svg') }}';">
        </a>
        <div class="search-card-body">
            <div class="search-card-meta">
                <span class="search-card-cat">{{ $categoryName }}</span>
                @if($fileSize)
                    <span class="search-card-size">{{ $fileSize }}</span>
                @endif
            </div>
            <h3 class="search-card-title">
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer">{{ $item->title }}</a>
            </h3>
            @if($item->description)
                <p class="search-card-desc d-none d-md-block">{{ Str::limit(strip_tags($item->description), 75) }}</p>
            @endif
            <div class="search-card-footer mt-auto pt-2 pt-md-3">
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="search-card-action">
                    <span>Download</span>
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
