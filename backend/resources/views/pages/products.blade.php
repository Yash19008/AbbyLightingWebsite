@extends('layout.web', ['theme' => 'light'])

@push('css')
    @vite(['resources/scss/project-category.scss'])
@endpush


@section('title', "$title | Abby Lighting")
@section('page-content')
    <div class="project-category">
        <style>
            /* Explore Categories Bar (used on category search pages) */
            #categories-section {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
                gap: 12px 32px;
                padding: 38px 24px 34px;
                text-align: center;
                background-color: #ffffff;
                margin-top: 0;
            }

            #categories-section .explore-label {
                font-size: 14.5px;
                font-weight: 700;
                color: #000000;
                letter-spacing: 0.02em;
                margin-right: 4px;
                display: inline-block;
            }

            #categories-section .category-link {
                font-size: 13.5px;
                font-weight: 700;
                color: #000000 !important;
                text-transform: uppercase;
                text-decoration: underline !important;
                text-underline-offset: 3px;
                text-decoration-thickness: 1.5px;
                letter-spacing: 0.06em;
                white-space: nowrap;
                transition: opacity 0.2s ease;
                display: inline-block;
            }

            #categories-section .category-link:hover {
                opacity: 0.6;
                color: #000000 !important;
            }

            /* Container Spacing & Layout */
            .project-category .products-header-section {
                margin-top: 40px !important;
                margin-bottom: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            .project-category .products-grid-section {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }

            .project-category .products-grid-container {
                margin-top: 0 !important;
                padding-top: 10px !important;
                padding-bottom: 60px !important;
            }

            /* Main Title */
            .project-category h1.section-title,
            .project-category h1.section-title-new {
                font-family: inherit !important;
                font-size: clamp(28px, 3.5vw, 42px) !important;
                font-weight: 700 !important;
                color: #000000 !important;
                line-height: 1.15 !important;
                letter-spacing: -0.015em !important;
                text-align: left !important;
                margin-bottom: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }

            .project-category .product-project-links {
                font-size: 14px !important;
                font-weight: 600 !important;
                color: #555555 !important;
                text-decoration: underline !important;
                cursor: pointer !important;
                margin-left: 20px !important;
                vertical-align: middle !important;
                letter-spacing: 0 !important;
            }

            .project-category .product-project-links:hover {
                color: #000000 !important;
            }

            /* Category Filter Links directly below title (All • CATEGORY) */
            .project-category .category-filter-links {
                display: flex !important;
                align-items: center !important;
                flex-wrap: wrap !important;
                gap: 6px 0 !important;
                margin-top: 14px !important;
                margin-bottom: 24px !important;
                padding: 0 !important;
                text-align: left !important;
            }

            .project-category .category-filter-links .section-link-filter {
                font-size: 13px !important;
                font-weight: 700 !important;
                text-decoration: none !important;
                letter-spacing: 0.04em !important;
                color: #555555 !important;
                transition: color 0.2s ease !important;
                line-height: 1.4 !important;
                white-space: nowrap !important;
                display: inline-block !important;
                cursor: pointer !important;
            }

            .project-category .category-filter-links .section-link-filter:hover {
                color: #000000 !important;
            }

            .project-category .category-filter-links .section-link-filter.active {
                color: #000000 !important;
                font-weight: 700 !important;
            }

            .project-category .category-filter-links .filter-bullet {
                color: #666666 !important;
                font-size: 12px !important;
                margin: 0 12px !important;
                user-select: none !important;
                line-height: 1 !important;
                display: inline-block !important;
            }

            /* Product Grid & Cards */
            .project-category #sub-tag-tiles,
            .project-category .products-row {
                margin-left: -14px !important;
                margin-right: -14px !important;
            }

            .project-category .product-card-tile {
                margin-bottom: 44px !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
                padding-left: 14px !important;
                padding-right: 14px !important;
                box-sizing: border-box !important;
            }

            .project-category .product-card-tile.d-none,
            .project-category .product-card-tile.is-filtered-out {
                display: none !important;
            }

            /* Square Image Wrapper */
            .project-category .product-img-wrapper,
            .project-category .img-hover-img.product-img-wrapper {
                display: block !important;
                position: relative !important;
                width: 100% !important;
                height: auto !important;
                max-width: 100% !important;
                max-height: none !important;
                aspect-ratio: 1 / 1 !important;
                margin: 0 !important;
                overflow: hidden !important;
                background-color: #BABDBF !important;
                border-radius: 0 !important;
                text-decoration: none !important;
            }

            .project-category .product-tile-img,
            .project-category .img-hover-img img.product-tile-img {
                width: 100% !important;
                height: 100% !important;
                max-width: 100% !important;
                max-height: 100% !important;
                min-width: 100% !important;
                min-height: 100% !important;
                object-fit: cover !important;
                margin: 0 !important;
                display: block !important;
                transition: opacity 0.35s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            }

            .project-category .img-hover-img .main-img {
                position: relative !important;
                z-index: 1 !important;
                opacity: 1 !important;
                display: block !important;
            }

            .project-category .img-hover-img .second-img {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 2 !important;
                opacity: 0 !important;
                display: block !important;
                pointer-events: none !important;
            }

            .project-category .product-card-tile:hover .img-hover-img .second-img {
                opacity: 1 !important;
            }

            .project-category .product-card-tile:hover .img-hover-img .main-img-hover-present {
                opacity: 0 !important;
            }

            .project-category .product-card-tile:hover .product-tile-img {
                transform: scale(1.025) !important;
            }

            /* Product Title - Left Aligned */
            .project-category .product-tile-title {
                text-align: left !important;
                padding-top: 14px !important;
                padding-bottom: 0px !important;
                margin-top: 0 !important;
                margin-bottom: 2px !important;
                width: 100% !important;
                display: block !important;
                font-family: inherit !important;
                font-size: 16.5px !important;
                font-weight: 700 !important;
                color: #000000 !important;
                line-height: 1.3 !important;
                letter-spacing: -0.01em !important;
            }

            /* Product Variants - Left Aligned */
            .project-category .product-tile-variants {
                text-align: left !important;
                margin-top: 2px !important;
                padding-top: 0 !important;
                margin-bottom: 0 !important;
                font-size: 13.5px !important;
                font-weight: 400 !important;
                line-height: 1.4 !important;
                color: #555555 !important;
                width: 100% !important;
                display: block !important;
                letter-spacing: 0.01em !important;
            }

            .project-category .product-tile-variants a {
                color: #555555 !important;
                text-decoration: none !important;
                font-weight: 400 !important;
                transition: color 0.2s ease, text-decoration 0.2s ease !important;
            }

            .project-category .product-tile-variants a:hover {
                color: #000000 !important;
                text-decoration: underline !important;
            }

            /* Responsive Adjustments */
            @media (max-width: 1199px) {
                .project-category .products-header-section {
                    margin-top: 32px !important;
                }

                .project-category .product-card-tile {
                    margin-bottom: 36px !important;
                    padding-left: 10px !important;
                    padding-right: 10px !important;
                }

                .project-category #sub-tag-tiles,
                .project-category .products-row {
                    margin-left: -10px !important;
                    margin-right: -10px !important;
                }

                .project-category .product-tile-title {
                    font-size: 15.5px !important;
                    padding-top: 12px !important;
                }

                .project-category .product-tile-variants {
                    font-size: 13px !important;
                }
            }

            @media (max-width: 767px) {
                .project-category .stupid-padding {
                    padding-left: 20px !important;
                    padding-right: 20px !important;
                }

                .project-category .products-header-section {
                    margin-top: 24px !important;
                }

                .project-category .products-grid-container {
                    padding-top: 4px !important;
                    padding-bottom: 40px !important;
                }

                .project-category .product-card-tile {
                    margin-bottom: 26px !important;
                    padding-left: 6px !important;
                    padding-right: 6px !important;
                }

                .project-category #sub-tag-tiles,
                .project-category .products-row {
                    margin-left: -6px !important;
                    margin-right: -6px !important;
                }

                .project-category .product-tile-title {
                    font-size: 14.5px !important;
                    padding-top: 9px !important;
                }

                .project-category .product-tile-variants {
                    font-size: 12px !important;
                }

                .project-category .category-filter-links {
                    margin-top: 10px !important;
                    margin-bottom: 18px !important;
                }

                .project-category .category-filter-links .section-link-filter {
                    font-size: 12px !important;
                }

                .project-category .category-filter-links .filter-bullet {
                    margin: 0 8px !important;
                    font-size: 10px !important;
                }
            }
        </style>

        @if (!@$onSearchPage && !empty($banner_images) && count($banner_images) > 0)
            <section class="slider d-sm-block mt-0">
                <div id="carouselExampleControlsWeb" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($banner_images as $index => $slider)
                            <div class="carousel-item container-fluid p-0 {{ $index == 0 ? 'active' : '' }}">
                                <img src="/storage/{{ $slider }}"
                                    onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/{{ $slider }}';}"
                                    alt="" class="img-fluid">
                            </div>
                        @endforeach
                    </div>
                    @if (count($banner_images) > 1)
                        <button class="carousel-control-prev mx-3" type="button"
                            data-bs-target="#carouselExampleControlsWeb" data-bs-slide="prev">
                            <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.971 25.459" class="">
                                <path id="arrow_4L"
                                    d="M0,12.024H0L12.02,0l3.536,3.536L7.069,12.023l8.487,8.487L12.02,24.044Z"
                                    transform="translate(0.707 0.707)"></path>
                            </svg>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next mx-3" type="button"
                            data-bs-target="#carouselExampleControlsWeb" data-bs-slide="next">
                            <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.973 25.459" class="">
                                <path id="arrow_4R"
                                    d="M0,20.509l8.488-8.487L0,3.536,3.536,0,15.557,12.021l0,0h0L3.535,24.044Z"
                                    transform="translate(0.708 0.707)"></path>
                            </svg>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </section>
        @endif

        @if (@$new_categories)
            <div id="categories-section" class="stupid-padding">
                <span class="explore-label">Explore:</span>
                @foreach ($new_categories as $index => $category)
                    <a href="{{ route('category', $category->slug) }}"
                        class="category-link">{{ $category->title }}</a>
                @endforeach
            </div>
        @endif

        <section class="stupid-padding products-header-section">
            <h1 class="section-title {{ @$onSearchPage ? '' : 'section-title-new' }} mb-0 {{ @$categories ? '' : 'pb-0' }}">
                {{ @$title }} @if (!empty($sub_tag_project_images) && count($sub_tag_project_images) > 0)
                    <span id="view-projects" class="product-project-links">View Projects</span>
                @endif
            </h1>
            @if (@$categories)
                <div class="category-filter-links">
                    <a href="#" class="section-link-filter active" data-filter="*">All</a>
                    @foreach ($categories as $index => $category)
                        @php
                            $hasProducts = false;
                            if (isset($products)) {
                                foreach ($products as $product) {
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
                        @if ($hasProducts)
                            <span class="filter-bullet">•</span>
                            <a href="#"
                                class="section-link-filter text-uppercase"
                                data-filter=".--c{{ $category->id }}">{{ $category->title }}</a>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        <section class="products-grid-section">
            <div class="container-fluid stupid-padding special-padding products-grid-container {{ @$onSearchPage ? 'mt-0' : '' }}">
                <div class="row" id="sub-tag-tiles">
                    @foreach ($products as $index => $product)
                        <div
                            class="col-6 col-md-4 col-lg-3 hover-img product-card-tile --c{{ Str::slug(@$product->category->id ?? '') }}">
                            <div class="product-img-wrapper">
                                <img src="{{ asset('storage/uploads/products/' . @$product->featured_image) }}"
                                    onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/products/{{ @$product->featured_image }}';}"
                                    alt="{{ $product->title }}" class="img-fluid product-tile-img">
                            </div>
                            <p class="section-text2 fw-600 product-tile-title">{{ $product->title }}</p>
                            <p class="section-text3 fs-1-1 fw-300 product-tile-variants">
                                @php
                                    foreach ($product->variants as $variant) {
                                        if ($variant->variant_name) {
                                            $variant->variant_name_temp = (float) filter_var(
                                                $variant->variant_name,
                                                FILTER_SANITIZE_NUMBER_FLOAT,
                                                FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND,
                                            );
                                        } else {
                                            $variant->variant_name_temp = 0;
                                        }
                                    }
                                    $product->variants = $product->variants->sortBy('variant_name_temp');
                                @endphp
                                @forelse($product->variants as $index => $variant)
                                    @if ($index != 0)
                                        |
                                    @endif
                                    @if (isset($variant->custom_specsheet))
                                        <a href="{{ asset('storage/uploads/products/' . @$variant->custom_specsheet) }}"
                                            target="_blank" class="text-uppercase">{{ $variant->variant_name }}</a>
                                    @else
                                        <a href="{{ route('product', $variant->slug) }}"
                                            class="text-uppercase">{{ $variant->variant_name }}</a>
                                    @endif
                                @empty
                                    &nbsp;
                                @endforelse
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4"></div>
        </section>

        {{-- YouTube + Catalog Section --}}
        @php
            $extractVid = function ($url) {
                $u = trim($url);
                if (preg_match('~youtu\.be/([A-Za-z0-9_-]{11})~', $u, $m)) {
                    return $m[1];
                }
                if (preg_match('~[?&]v=([A-Za-z0-9_-]{11})~', $u, $m)) {
                    return $m[1];
                }
                if (preg_match('~/((?:embed|shorts))/([A-Za-z0-9_-]{11})~', $u, $m)) {
                    return $m[2];
                }
                return null;
            };

            // Collect all YouTube fields
            $youtubeFields = [
                $subTag->youtube_url ?? '',
                $subTag->youtube_url_link_2 ?? '',
                $subTag->youtube_url_link_3 ?? '',
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
            @if (!empty($subTag->product_catalog))
                <div class="product-catalog-wrapper mb-3">
                    <a href="{{ asset('storage/uploads/product_catalogs/' . $subTag->product_catalog) }}"
                        download="{{ $subTag->product_catalog }}"
                        class="product-catalog-link fs-1-3 fw-600 text-5b5b5b text-decoration-underline me-3"
                        style="white-space: nowrap;letter-spacing:0.1rem">
                        DOWNLOAD PRODUCT CATALOG &gt;
                    </a>
                </div>
            @endif

            {{-- YouTube Videos --}}
            @if (!empty($videoIds))
                <div class="youtube-icon-wrapper">
                    <div class="section-text2 fw-500" style="margin-top: 30px; padding-top: 8px; padding-bottom: 16px;">
                        Product Video:
                    </div>

                    <div class="row gx-5 mt-4 text-center g-0"> {{-- Bootstrap grid --}}
                        @foreach ($videoIds as $vid)
                            <div class="col-12 col-md-6 col-lg-3 d-flex justify-content-center mb-4">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#youtubeModalProducts"
                                    data-video-url="https://www.youtube.com/embed/{{ $vid }}"
                                    class="youtube-thumbnail-link">

                                    <div class="youtube-thumbnail-wrapper">
                                        <img src="https://img.youtube.com/vi/{{ $vid }}/maxresdefault.jpg"
                                            class="youtube-thumbnail img-fluid" alt="YouTube Video Thumbnail">

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
            @if (isset($subTag) && $subTag->linkedSubTags->count())
                <!-- Section Title -->
                <div class="row gx-4 mt-4 text-center g-0" id="related-products-tiles">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center mb-4">
                            <div class="section-text2 fw-700 py-0 cursor-pointer ms-0" style="font-size: 18px; color: #000;">
                                Related products:
                            </div>
                            <div class="flex-fill ms-3">
                                <hr style="border-width: 2px; margin: 0;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subtag Grid -->
                <div class="row products-row">
                    @foreach ($subTag->linkedSubTags as $linked)
                        <div class="col-6 col-md-4 col-lg-3 hover-img product-card-tile">
                            <a class="img-hover-img product-img-wrapper" href="{{ route('products', $linked->slug) }}">
                                <!-- Main image -->
                                <img src="{{ asset('storage/uploads/sub_tags/' . @$linked->image) }}"
                                    onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/sub_tags/{{ @$linked->image }}';}"
                                    alt="{{ $linked->display_name }}"
                                    class="img-fluid main-img lazy product-tile-img {{ @$linked->hover_image ? 'main-img-hover-present' : '' }}"
                                    loading="lazy">

                                <!-- Hover image (only if exists) -->
                                @if (@$linked->hover_image)
                                    <img src="{{ asset('storage/uploads/sub_tags/' . @$linked->hover_image) }}"
                                        onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/sub_tags/{{ @$linked->hover_image }}';}"
                                        alt="{{ $linked->display_name }} hover"
                                        class="img-fluid second-img lazy product-tile-img" loading="lazy">
                                @endif
                            </a>

                            <p class="section-text2 fw-700 product-tile-title">
                                <a href="{{ route('products', $linked->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ $linked->display_name }}
                                </a>
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>


        @if (!empty($sub_tag_project_images) && count($sub_tag_project_images) > 0)
            <section id="sub_tag_project_images_section">
                <div class="container-fluid stupid-padding mt-4">
                    <p id="feature_project_title" class="section-text2 fw-500 py-0 cursor-pointer ms-0">Featured projects:
                    </p>
                    <div class="row gx-5 mt-4 text-center g-0" id="sub-tag-project-tiles">
                        @foreach ($sub_tag_project_images as $index => $slider)
                            <div class="col-12 col-md-6 col-lg-3 "
                                onclick="openProject('{{ route('project-detail', $slider['project_slug'] ?? '') }}')">
                                <div data-content="{{ $slider['project_name'] }}" class="sub-tag-project-img-wrapper">
                                    <img src="{{ asset('storage/uploads/projects/' . trim($slider['project_image_name'])) }}"
                                        alt="" class="img-fluid w-100 sub-tag-project-img-tag">
                                </div>
                                <p class="section-text2 fw-500 pt-0"></p>
                            </div>
                        @endforeach
                    </div>
                    <p id="scrollToTop" class="section-text2 fw-500 pt-0 cursor-pointer product-project-links ms-0">Back
                        to top</p>
                </div>
                <div class="mt-5"></div>
            </section>
        @endif
    </div>
    @include('partials.youtube-modal-products')

@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script>
        function openProject(url) {
            window.location.href = url
        }
    </script>
    @vite(['resources/js/sub-tags.js'])
@endpush
