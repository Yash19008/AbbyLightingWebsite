@extends('layout.web', ['theme' => 'light'])
@push('css')
@vite(['resources/scss/project-category.scss'])
@endpush
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
@section("title", "Product Range | Spots, Linears, Tracklights and more | Abby Lighting")
@section("description", "Abby Lighting caters to a broad range of architectural lighting requirements - from spotlights and downlights, to linear profiles, tracklights, and decorative fixtures.")

@section('page-content')
<style>
.tag-display {
    color: #fff; /* default white */
    transition: color 0.3s;
}

.section-link-filter.active .tag-display {
    color: #EBC824 !important; /* active color */
}
.section-link-filter {
    text-decoration: none !important;
}
</style>

<div class="project-category">
    <div class="container-fluid p-0">
        @if(App\Models\Setting::getValue('SUBTAG_BANNER_IMAGE'))
        <img src="{{App\Models\Setting::getValue('SUBTAG_BANNER_IMAGE')}}" alt="" class="img-fluid">
        @else
        <img src="{{asset('storage/uploads/banners/banner_image-sub-tags.jpg')}}" alt="" class="img-fluid">
        @endif
    </div>
    <div id="extra-div-to-scroll">
    </div>
    <section class="container-fluid py-3 p-0 bg-dark text-white sticky-top pb-0">
    <div id="links-section"
         class="links d-flex justify-content-center flex-nowrap"
         style="overflow-x: auto; -webkit-overflow-scrolling: touch; scroll-padding: 0rem;">
         
        <a href="#" id="all-tags-link"
           class="section-link-filter text-uppercase fw-600 text-5b5b5b pb-3"
           style="white-space: nowrap; letter-spacing: 0.1rem; padding: 0 1rem;"
           data-slug="" 
           data-filter="*">
           <span class="tag-display">All</span>
        </a>

        @foreach($tags as $index => $tag)
            @php
                $hasProducts = false;
                if(isset($subTags) && count($subTags) > 0) {
                    foreach($subTags as $subTag) {
                        if (is_iterable($subTag->tags)) {
                            foreach($subTag->tags as $t) {
                                if ($t == $tag->id) {
                                    $hasProducts = true;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            @endphp
            @if($hasProducts)
            <span class="divider text-5b5b5b" style="font-size:1.3rem">|</span>
            <a href="#"
                class="section-link-filter text-uppercase fw-600 tag-link d-flex flex-column align-items-center"
                style="letter-spacing: 0.1rem; white-space: nowrap; padding: 0 1rem;"
                data-slug="{{$tag->slug}}"
                data-filter=".--t{{$tag->id}}">
                <span class="tag-display">{!! str_replace(" ", "&nbsp;", $tag->display_name) !!}</span>
            </a>
            @endif
        @endforeach
    </div>
</section>
            {{--
            <a href="#" class="section-link text-uppercase fw-600 text-5b5b5b u-5b5b5b me-5"
                style="white-space: nowrap;" data-filter=".--corporate-office">Indoor Ceiling Surface</a>
            <a href="#" class="section-link text-uppercase fw-600 text-5b5b5b u-5b5b5b me-5"
                style="white-space: nowrap;" data-filter=".--corporate-office">Indoor Ceiling Recess</a>
            <a href="#" class="section-link text-uppercase fw-600 text-5b5b5b u-5b5b5b" style="white-space: nowrap;"
                data-filter=".--corporate-office">Indoor Ceiling Suspended</a> --}}
        
    @if(@$categories)
    <div id="categories-section" class="text-center">
        <span>Explore:</span>
        @foreach($categories as $index => $category)
        <a href="{{route('category', $category->slug)}}" class="fw-600 text-5b5b5b text-decoration-underline me-3"
            style="white-space: nowrap;letter-spacing:0.1rem">{{$category->title}}</a>
        @endforeach
    </div>
    @endif
    <section>
        <div class="container-fluid stupid-padding mt-5">
            <div class="row gx-4 mt-5 text-center g-0" id="sub-tag-tiles">
                @foreach($subTags as $index => $subTag)
                <div class="element-item col-12 col-md-6 col-lg-3 hover-img @foreach($subTag->tags as $tag) --t{{$tag}} @endforeach">
                    <a class="w-100 img-hover-img" href="{{route('products', $subTag->slug)}}">
                        <img src="{{asset('storage/uploads/sub_tags/'.@$subTag->image)}}" alt="" class="img-fluid w-100 main-img lazy {{ @$subTag->hover_image ? 'main-img-hover-present' : '' }}" loading="lazy"  style="min-width: 150px; min-height: 150px;">
                        @if(@$subTag->hover_image)
                        <img src="{{asset('storage/uploads/sub_tags/'.@$subTag->hover_image)}}" alt="" class="img-fluid w-100 second-img lazy" loading="lazy"  style="min-width: 150px; min-height: 150px;">
                        @endif
                    </a>
                    <br>
                    <a href="{{route('products', $subTag->slug)}}"
                        class="section-text2 fw-500 pt-4">{{$subTag->display_name}}</a>
                </div>
                @endforeach
            </div>
            <div class="mb-5">&nbsp;</div>
        </div>
    </section>
</div>

@push('js')
@vite(['resources/js/app.js'])
@vite(['resources/js/sub-tags.js'])
@endpush
@endsection
