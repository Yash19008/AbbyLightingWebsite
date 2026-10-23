@extends('layout.web', ['theme' => 'light'])
@push('css')
    @vite(['resources/scss/project-category.scss'])
@endpush
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
@section('title', 'Product Range | Spots, Linears, Tracklights and more | Abby Lighting')
@section('description',
    'Abby Lighting caters to a broad range of architectural lighting requirements - from spotlights
    and downlights, to linear profiles, tracklights, and decorative fixtures.')

@section('page-content')
    <style>
        .tag-display {
            color: #fff;
            /* default white */
            transition: color 0.3s;
        }

        .section-link-filter.active .tag-display {
            color: #EBC824 !important;
            /* active color */
        }

        .section-link-filter {
            text-decoration: none !important;
        }

        /* Top Explore Categories Bar */
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

        /* Products Grid Layout matching reference design */
        .project-category .sub-tags-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 48px 80px;
            box-sizing: border-box;
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
            margin-bottom: 0 !important;
            width: 100% !important;
            display: block !important;
            font-family: inherit !important;
            font-size: 17px !important;
            font-weight: 700 !important;
            color: #000000 !important;
            line-height: 1.3 !important;
            letter-spacing: -0.01em !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
        }

        .project-category .product-card-tile:hover .product-tile-title {
            color: #000000 !important;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1199px) {
            .project-category .sub-tags-container {
                padding: 0 32px 70px;
            }

            .project-category .product-card-tile {
                margin-bottom: 38px !important;
                padding-left: 11px !important;
                padding-right: 11px !important;
            }

            #categories-section {
                padding: 32px 20px 28px;
                gap: 12px 24px;
            }

            .project-category .product-tile-title {
                font-size: 16px !important;
                padding-top: 12px !important;
            }
        }

        @media (max-width: 767px) {
            .project-category .sub-tags-container {
                padding: 0 16px 50px;
            }

            .project-category .product-card-tile {
                margin-bottom: 26px !important;
                padding-left: 7px !important;
                padding-right: 7px !important;
            }

            #categories-section {
                padding: 22px 14px 22px;
                gap: 8px 16px;
                justify-content: center;
            }

            #categories-section .explore-label {
                font-size: 13.5px;
            }

            #categories-section .category-link {
                font-size: 11.5px;
                letter-spacing: 0.04em;
            }

            .project-category .product-tile-title {
                font-size: 14.5px !important;
                padding-top: 9px !important;
                line-height: 1.25 !important;
            }
        }

        @media (max-width: 400px) {
            .project-category .sub-tags-container {
                padding: 0 12px 40px;
            }

            .project-category .product-card-tile {
                padding-left: 5px !important;
                padding-right: 5px !important;
                margin-bottom: 22px !important;
            }

            .project-category .product-tile-title {
                font-size: 13.5px !important;
                padding-top: 8px !important;
            }
        }
    </style>

    <div class="project-category">
        <div class="container-fluid p-0">
            @if (App\Models\Setting::getValue('SUBTAG_BANNER_IMAGE'))
                <img src="{{ App\Models\Setting::getValue('SUBTAG_BANNER_IMAGE') }}" alt="" class="img-fluid">
            @else
                <img src="{{ asset('storage/uploads/banners/banner_image-sub-tags.jpg') }}" alt="" class="img-fluid">
            @endif
        </div>
        <div id="extra-div-to-scroll">
        </div>
        <section class="container-fluid py-3 p-0 bg-black text-white sticky-top pb-0">
            <div id="links-section" default-data-filter="{{ $default_data_filter ?? '' }}"
                class="links d-flex justify-content-center flex-nowrap"
                style="overflow-x: auto; -webkit-overflow-scrolling: touch; scroll-padding: 0rem;">

                <a href="#" id="all-tags-link"
                    class="section-link-filter text-uppercase fw-600 text-5b5b5b pb-3 {{ empty($default_data_filter) ? 'active' : '' }}"
                    style="white-space: nowrap; letter-spacing: 0.1rem; padding: 0 1rem;" data-slug="" data-filter="*">
                    <span class="tag-display">All</span>
                </a>

                @foreach ($tags as $index => $tag)
                    @php
                        $hasProducts = false;
                        if (isset($subTags) && count($subTags) > 0) {
                            foreach ($subTags as $subTag) {
                                if (is_iterable($subTag->tags)) {
                                    foreach ($subTag->tags as $t) {
                                        if ($t == $tag->id) {
                                            $hasProducts = true;
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    @endphp
                    @if ($hasProducts)
                        <span class="divider text-5b5b5b" style="font-size:1.3rem">|</span>
                        <a href="#"
                            class="section-link-filter text-uppercase fw-600 tag-link d-flex flex-column align-items-center {{ isset($default_data_filter) && $default_data_filter == $tag->id ? 'active' : '' }}"
                            style="letter-spacing: 0.1rem; white-space: nowrap; padding: 0 1rem;"
                            data-slug="{{ $tag->slug }}" data-filter=".--t{{ $tag->id }}">
                            <span class="tag-display">{!! str_replace(' ', '&nbsp;', $tag->display_name) !!}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </section>

        @if (@$categories)
            <div id="categories-section" class="text-center">
                <span class="explore-label">Explore:</span>
                @foreach ($categories as $index => $category)
                    <a href="{{ route('category', $category->slug) }}" class="category-link">{{ $category->title }}</a>
                @endforeach
            </div>
        @endif
        <section>
            <div class="container-fluid sub-tags-container stupid-padding mt-4">
                <div class="row gx-3 gx-md-4 g-0" id="sub-tag-tiles">
                    @foreach ($subTags as $index => $subTag)
                        @php
                            $subTagTagIds = is_array($subTag->tags)
                                ? $subTag->tags
                                : (is_iterable($subTag->tags)
                                    ? iterator_to_array($subTag->tags)
                                    : []);
                            $isMatching = empty($default_data_filter) || in_array($default_data_filter, $subTagTagIds);
                        @endphp
                        <div class="element-item col-6 col-md-4 col-lg-3 hover-img product-card-tile @foreach ($subTagTagIds as $tagId) --t{{ $tagId }} @endforeach {{ $isMatching ? '' : 'd-none is-filtered-out' }}"
                            style="{{ $isMatching ? '' : 'display: none !important;' }}">
                            <a class="img-hover-img product-img-wrapper" href="{{ route('products', $subTag->slug) }}"
                                aria-label="{{ $subTag->display_name }}">
                                <img src="{{ asset('storage/uploads/sub_tags/' . @$subTag->image) }}"
                                    alt="{{ $subTag->display_name }}"
                                    class="img-fluid main-img lazy product-tile-img {{ @$subTag->hover_image ? 'main-img-hover-present' : '' }}"
                                    loading="lazy"
                                    onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/sub_tags/{{ @$subTag->image }}';}">
                                @if (@$subTag->hover_image)
                                    <img src="{{ asset('storage/uploads/sub_tags/' . @$subTag->hover_image) }}"
                                        alt="{{ $subTag->display_name }}"
                                        class="img-fluid second-img lazy product-tile-img" loading="lazy"
                                        onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/sub_tags/{{ @$subTag->hover_image }}';}">
                                @endif
                            </a>
                            <a href="{{ route('products', $subTag->slug) }}"
                                class="section-text2 fw-600 product-tile-title">{{ $subTag->display_name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    @push('js')
        @vite(['resources/js/app.js'])
        @vite(['resources/js/sub-tags.js'])
        <script>
            (function() {
                function applyFilter(filterSelector, slug) {
                    const filterLinks = document.querySelectorAll('.section-link-filter');
                    const tiles = document.querySelectorAll('#sub-tag-tiles .product-card-tile');

                    filterLinks.forEach(function(link) {
                        const linkFilter = link.getAttribute('data-filter');
                        if ((filterSelector === '*' && linkFilter === '*') || (filterSelector !== '*' &&
                                linkFilter === filterSelector)) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    });

                    tiles.forEach(function(tile) {
                        if (filterSelector === '*' || tile.matches(filterSelector)) {
                            tile.classList.remove('d-none', 'is-filtered-out');
                            tile.style.setProperty('display', 'flex', 'important');
                        } else {
                            tile.classList.add('d-none', 'is-filtered-out');
                            tile.style.setProperty('display', 'none', 'important');
                        }
                    });

                    const activeLink = document.querySelector('.section-link-filter.active');
                    if (activeLink) {
                        try {
                            activeLink.scrollIntoView({
                                behavior: 'smooth',
                                inline: 'center',
                                block: 'nearest'
                            });
                        } catch (e) {}
                    }
                }

                function initSubTagFilters() {
                    const linksSection = document.getElementById('links-section');
                    const defaultFilter = linksSection ? linksSection.getAttribute('default-data-filter') : '';
                    if (defaultFilter && defaultFilter.length > 0) {
                        applyFilter('.--t' + defaultFilter, '');
                    }

                    const filterLinks = document.querySelectorAll('.section-link-filter');
                    filterLinks.forEach(function(link) {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            const filter = this.getAttribute('data-filter');
                            const slug = this.getAttribute('data-slug');

                            applyFilter(filter, slug);

                            if (filter === '*') {
                                window.history.pushState({
                                    filter: '*',
                                    slug: ''
                                }, document.title, window.location.origin + '/products');
                            } else if (slug) {
                                window.history.pushState({
                                        filter: filter,
                                        slug: slug
                                    },
                                    document.title,
                                    window.location.origin + '/products/category/' + slug.replace(
                                        /\s+/g, '-').toLowerCase()
                                );
                            }

                            try {
                                const scrollEl = document.querySelector('#extra-div-to-scroll');
                                if (scrollEl) {
                                    scrollEl.scrollIntoView({
                                        behavior: 'smooth'
                                    });
                                }
                            } catch (err) {}
                        });
                    });

                    window.addEventListener('popstate', function(e) {
                        const match = window.location.pathname.match(/\/products\/category\/([^\/]+)/);
                        if (match) {
                            const slug = match[1];
                            const link = Array.from(document.querySelectorAll('.section-link-filter')).find(
                                function(l) {
                                    return (l.getAttribute('data-slug') || '').toLowerCase() === slug
                                        .toLowerCase();
                                });
                            if (link) {
                                applyFilter(link.getAttribute('data-filter'), link.getAttribute('data-slug'));
                                return;
                            }
                        }
                        applyFilter('*', '');
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initSubTagFilters);
                } else {
                    initSubTagFilters();
                }
            })();
        </script>
    @endpush
@endsection
