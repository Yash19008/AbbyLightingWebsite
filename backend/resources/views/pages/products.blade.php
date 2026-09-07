@extends('layout.web', ['theme' => 'light'])

@push('css')
@vite(['resources/scss/project-category.scss'])
@endpush


@section("title", "$title | Abby Lighting")
@section('page-content')
<div class="project-category">
    @if(!@$onSearchPage)
    {{-- <div class="container-fluid p-0">
        <img src="{{asset('storage/uploads/sub_tags/'.@$subTag->banner_image)}}" alt="" class="img-fluid">
    </div> --}}
    
    <section class="slider d-sm-block mt-0">
        <div id="carouselExampleControlsWeb" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banner_images as $index => $slider)
                <div class="carousel-item container-fluid p-0 {{ $index == 0 ? "active" : "" }}">
                    <img src="/storage/{{ $slider }}" alt="" class="img-fluid">
                </div>
                @endforeach
            </div>
            @if(count($banner_images) > 1)
                <button class="carousel-control-prev mx-3" type="button" data-bs-target="#carouselExampleControlsWeb" data-bs-slide="prev">
                    <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.971 25.459" class="">
                        <path id="arrow_4L" d="M0,12.024H0L12.02,0l3.536,3.536L7.069,12.023l8.487,8.487L12.02,24.044Z" transform="translate(0.707 0.707)"></path>
                    </svg>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next mx-3" type="button" data-bs-target="#carouselExampleControlsWeb" data-bs-slide="next">
                    <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.973 25.459" class="">
                        <path id="arrow_4R" d="M0,20.509l8.488-8.487L0,3.536,3.536,0,15.557,12.021l0,0h0L3.535,24.044Z" transform="translate(0.708 0.707)"></path>
                    </svg>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>
    </section>

    @endif

    @if(@$new_categories)
    <div id="categories-section" class="stupid-padding">
        <span>Explore:</span>
        @foreach($new_categories as $index => $category)
        <a href="{{route('category', $category->slug)}}" class="fw-600 text-5b5b5b text-decoration-underline me-3"
            style="white-space: nowrap;letter-spacing:0.1rem">{{$category->title}}</a>
        @endforeach
    </div>
    @endif
    
    <section class="stupid-padding {{ @$onSearchPage ? 'mt-0' : 'stupid-padding-new'}}">
        <h1 class="section-title {{ @$onSearchPage ? '' : 'section-title-new'}} mb-0 {{ @$categories ? '' : 'pb-0'}}">
            {{@$title}} @if(!empty($sub_tag_project_images) && count($sub_tag_project_images) > 0)<span id="view-projects" class="product-project-links">View Projects</span>@endif
        </h1>        
        @if(@$categories)
        <div class="links">
            <a href="#" class="section-link-filter fs-1-3 fw-600 text-5b5b5b text-decoration-underline me-3"
                style="white-space: nowrap;letter-spacing:0.1rem" data-filter="*">All</a>
            @foreach($categories as $index => $category)
                @php
                    $hasProducts = false;
                    if(isset($products)) {
                        foreach($products as $product) {
                            if (isset($product->category) && $product->category->id == $category->id) {
                                $hasProducts = true;
                                break;
                            } elseif (isset($product->category_id) && $product->category_id == $category->id) {
                                $hasProducts = true;
                                break;
                            }
                        }
                    }
                @endphp
                @if($hasProducts)
                <a href="#" class="section-link-filter fs-1-3 fw-600 text-5b5b5b text-decoration-underline me-3" style="white-space: nowrap;letter-spacing:0.1rem"
                    data-filter=".--c{{$category->id}}">{{$category->title}}</a>
                @endif
            @endforeach
        </div>
        @endif
    </section>
    <section>   
        <div class="container-fluid stupid-padding special-padding {{ @$onSearchPage ? 'mt-0' : ''}}">
            <div class="row gx-4 mt-4 text-center g-0" id="sub-tag-tiles">
                @foreach($products as $index => $product)
                <div class="col-12 col-md-6 col-lg-3 hover-img --c{{Str::slug($product->category->id)}}">
                    <img src="{{asset('storage/uploads/products/'.@$product->featured_image)}}" alt="" class="img-fluid w-100">
                    <p class="section-text2 fw-500 pt-4">{{$product->title}}</p>
                    <p class="section-text3 fs-1-1 fw-300 mt-n5 pt-2 mb-5">
                        @php 
                            foreach($product->variants as $variant){
                                if ($variant->variant_name) {
                                    $variant->variant_name_temp = (float) filter_var($variant->variant_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                                } else {
                                    $variant->variant_name_temp = 0;
                                }
                            }
                            $product->variants = $product->variants->sortBy('variant_name_temp'); 
                        @endphp
                        @forelse($product->variants as $index => $variant)
                        @if($index != 0)|@endif
                        @if(isset($variant->custom_specsheet))
                        <a href="{{asset('storage/uploads/products/'.@$variant->custom_specsheet)}}" target="_blank" class="text-uppercase">{{$variant->variant_name}}</a>
                        @else
                        <a href="{{route('product', $variant->slug)}}" class="text-uppercase">{{$variant->variant_name}}</a>
                        @endif
                        @empty
                        &nbsp;
                        @endforelse
                    </p>                                                            
                </div>
                @endforeach
            </div>
        </div>
        <div class="mt-5"></div>        
    </section>

    {{-- YouTube + Catalog Section --}}
    @php
        $extractVid = function($url) {
            $u = trim($url);
            if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $u, $m)) return $m[1];
            if (preg_match('~[?&]v=([A-Za-z0-9_-]{11})~', $u, $m)) return $m[1];
            if (preg_match('~/((?:embed|shorts))/([A-Za-z0-9_-]{11})~', $u, $m)) return $m[2];
            return null;
        };

        // Collect all YouTube fields
        $youtubeFields = [
            $subTag->youtube_url ?? '',
            $subTag->youtube_url_link_2 ?? '',
            $subTag->youtube_url_link_3 ?? ''
        ];

        // Extract valid video IDs
        $videoIds = [];
        foreach ($youtubeFields as $field) {
            $vid = $extractVid($field);
            if ($vid && strlen($vid) === 11) {
                $videoIds[] = $vid;
            }
        }
    @endphp

    <section class="stupid-padding product-extra-section">
        {{-- Product Catalog --}}
        @if(!empty($subTag->product_catalog))
            <div class="product-catalog-wrapper mb-3">
                <a href="{{ asset('storage/uploads/product_catalogs/'.$subTag->product_catalog) }}" 
                download="{{ $subTag->product_catalog }}"
                class="product-catalog-link fs-1-3 fw-600 text-5b5b5b text-decoration-underline me-3"
                style="white-space: nowrap;letter-spacing:0.1rem" >
                DOWNLOAD PRODUCT CATALOG &gt;
                </a>   
            </div>
        @endif

        {{-- YouTube Videos --}}
        @if(!empty($videoIds))
            <div class="youtube-icon-wrapper">
                <div class="section-text2 fw-500" style="margin-top: 30px; padding-top: 8px; padding-bottom: 16px;">
                    Product Video:
                </div>

                <div class="row gx-5 mt-4 text-center g-0"> {{-- Bootstrap grid --}}
                    @foreach($videoIds as $vid)
                        <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center mb-4">
                            <a href="#"
                                data-bs-toggle="modal"
                                data-bs-target="#youtubeModalProducts"
                                data-video-url="https://www.youtube.com/embed/{{ $vid }}"
                                class="youtube-thumbnail-link">
                                
                                <div class="youtube-thumbnail-wrapper">
                                    <img src="https://img.youtube.com/vi/{{ $vid }}/maxresdefault.jpg" 
                                        class="youtube-thumbnail img-fluid" 
                                        alt="YouTube Video Thumbnail">
                                    
                                    <div class="youtube-play-button"></div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        @endif

        <style>
            section.product-extra-section {
                padding-top: 10px !important;
                margin-top: 0 !important;
            }

            .product-catalog-link {
                font-weight: 1000;
                font-size: 22px;
                white-space: nowrap;
            }                
            .product-catalog-wrapper {
                margin-bottom: 20px;
            }

            .youtube-icon-wrapper {
                margin-top: 5px;
                margin-bottom: 30px;
            }

            .youtube-thumbnail-link {
                display: inline-block;
                max-width: 100%;
            }

            .youtube-thumbnail {
                display: block;
                width: 100%;
                max-width: 100%;
                height: auto;
                object-fit: cover;
                border-radius: 0px;
            }

            .youtube-thumbnail-wrapper {
                position: relative;
                display: inline-block;
            }

            .youtube-play-button {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 48px;
                height: 34px;
                background-color: red;
                border-radius: 8px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .youtube-play-button::before {
                content: "";
                display: block;
                width: 0;
                height: 0;
                border-left: 14px solid white;
                border-top: 8px solid transparent;
                border-bottom: 8px solid transparent;
                margin-left: 3px;
            }


            @media (max-width: 768px) {
                .product-catalog-link {
                    font-size: 16px;
                }
                .youtube-thumbnail {
                    max-width: 100%;
                }
            }

            @media (max-width: 480px) {
                .product-catalog-link {
                    font-size: 14px;
                }
                .youtube-thumbnail {
                    max-width: 100%;
                }
            }
        </style>
    </section>


   <section class="container-fluid stupid-padding special-padding product-extra-section">
        @if(isset($subTag) && $subTag->linkedSubTags->count())
            <!-- Section Title -->
            <div class="row gx-4 mt-4 text-center g-0" id="related-products-tiles">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="section-text2 fw-500 py-0 cursor-pointer ms-0" style="margin-top: 5px">
                            Related products:
                        </div>
                        <div class="flex-fill ms-3">
                            <hr style="border-width: 2px; margin: 0;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtag Grid -->
            <div class="row gx-4 mt-2 text-center g-0">
                @foreach($subTag->linkedSubTags as $linked)
                    <div class="col-12 col-md-6 col-lg-3 hover-img">
                        <a class="w-100 img-hover-img" href="{{ route('products', $linked->slug) }}">
                            <!-- Main image -->
                            <img src="{{ asset('storage/uploads/sub_tags/' . @$linked->image) }}"
                                alt="{{ $linked->display_name }}"
                                class="img-fluid w-100 main-img lazy {{ @$linked->hover_image ? 'main-img-hover-present' : '' }}"
                                loading="lazy"
                                style="min-width: 150px; min-height: 150px;">

                            <!-- Hover image (only if exists) -->
                            @if(@$linked->hover_image)
                                <img src="{{ asset('storage/uploads/sub_tags/' . @$linked->hover_image) }}"
                                    alt="{{ $linked->display_name }} hover"
                                    class="img-fluid w-100 second-img lazy"
                                    loading="lazy"
                                    style="min-width: 150px; min-height: 150px;">
                            @endif
                        </a>

                        <p class="section-text2 fw-500 pt-4 mb-5">
                            <a href="{{ route('products', $linked->slug) }}">
                                {{ $linked->display_name }}
                            </a>
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>


    @if(!empty($sub_tag_project_images) && count($sub_tag_project_images) > 0)
    <section id="sub_tag_project_images_section">
        <div class="container-fluid stupid-padding mt-4">
            <p id="feature_project_title" class="section-text2 fw-500 py-0 cursor-pointer ms-0">Featured projects:</p>
            <div class="row gx-5 mt-4 text-center g-0" id="sub-tag-tiles">
                @foreach($sub_tag_project_images as $index => $slider)
                <div class="col-12 col-md-6 col-lg-3 " onclick="openProject('{{route('project-detail', $slider['project_slug'] ?? '')}}')">
                    <div data-content="{{$slider['project_name']}}" class="sub-tag-project-img-wrapper">
                        <img src="{{asset('storage/uploads/projects/'.trim($slider['project_image_name']))}}" alt="" class="img-fluid w-100 sub-tag-project-img-tag">
                    </div>
                    <p class="section-text2 fw-500 pt-0"></p>
                </div>
                @endforeach
            </div>
            <p id="scrollToTop" class="section-text2 fw-500 pt-0 cursor-pointer product-project-links ms-0">Back to top</p>
        </div>
        <div class="mt-5"></div>
    </section>
    @endif
    {{-- <div class="container-fluid stupid-padding mb-5" style="position: relative;">
        <div class="swiper-button-next"></div>
            <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($sub_tag_project_images as $index => $slider)
                <div  data-content="{{$slider['project_name']}}" class="swiper-slide">
                    <img src="{{asset('storage/uploads/projects/'.$slider['project_image_name'])}}" alt="" class="img-fluid" style="height: 405px; width: 405px;">
                </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-button-prev"></div>
    </div> --}}

</div>
@include('partials.youtube-modal-products')

@endsection
@push('js')
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
    // const swiper = new Swiper('.swiper', {
    //     loop: true,
    //     slidesPerView: 1,
    //     spaceBetween: 0,
    //     breakpoints: {
    //         // when window width is >= 320px
    //         413: {
    //             slidesPerView: "auto",
    //             spaceBetween: 50,
    //         },

    //     },
    //     navigation: {
    //         nextEl: ".swiper-button-next",
    //         prevEl: ".swiper-button-prev",
    //     },
    // });

    function openProject(url) {
        window.location.href = url
    }
    
</script>
@vite(['resources/js/sub-tags.js'])
@endpush
