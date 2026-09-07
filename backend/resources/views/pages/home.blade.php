@extends('layout.web', ['theme' => 'dark'])
@push('css')
@vite(['resources/scss/home.scss'])
@endpush
@section('page-content')
@if(Request::is('/'))
    <div class="top-download-bar">
        <a href="#" data-bs-toggle="modal" data-bs-target="#downloadCatalogModal">
            Get our latest Product Catalog
        </a>
    </div>
@endif
<section class="slider d-block d-sm-none">
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($home_sliders_mob as $index => $slider)
            <div class="carousel-item active" onclick="openURL('{{$slider->url}}')">
                <img src="/storage/{{ $slider->path }}" alt="" class="img-fluid">
            </div>
            @endforeach
        </div>
        @if(count($home_sliders_mob) > 1)
            <button class="carousel-control-prev mx-3" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.971 25.459" class="">
                    <path id="arrow_4L" d="M0,12.024H0L12.02,0l3.536,3.536L7.069,12.023l8.487,8.487L12.02,24.044Z" transform="translate(0.707 0.707)"></path>
                </svg>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next mx-3" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <svg fill="#fff" width="16px" height="25px" viewBox="0 0 16.973 25.459" class="">
                    <path id="arrow_4R" d="M0,20.509l8.488-8.487L0,3.536,3.536,0,15.557,12.021l0,0h0L3.535,24.044Z" transform="translate(0.708 0.707)"></path>
                </svg>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>
</section>
<section class="slider d-none d-sm-block">
    <div id="carouselExampleControlsWeb" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($home_sliders_web as $index => $slider)
            <div class="carousel-item {{ $index == 0 ? "active" : "" }} {{ $slider->url != NULL ? "cursor-pointer" : "" }}" onclick="openURL('{{$slider->url}}')">
                <img src="/storage/{{ $slider->path }}" alt="" class="img-fluid">
            </div>
            @endforeach
        </div>
        @if(count($home_sliders_web) > 1)
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
<section class="my-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-6 p-0">
                <a href="#" style="position: relative" data-bs-toggle="modal" data-bs-target="#youtubeModal">
                    <img src="{{ asset('img/icons/youtube.png') }}" style="position: absolute;
                            left: 45%;
                            width: 10%;
                            top: 45%; opacity:0.7;">
                    <img src="{{asset('img/social/manufactoring-facilitities.webp')}}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title">
                        Manufacturing Facility
                    </div>
                    <p class="section-text stupid-text-1">
                        We encourage you to visit our fully integrated ISO certified state-of-the-art factory so that
                        you have full confidence in our strength & competencies.
                    </p>
                    <p class="section-text stupid-text-1">
                        However, if you can’t make time to visit, please view our company video that takes you through
                        our in-house production processes.
                    </p>
                    <p class="section-text stupid-text-2  fw-600">
                        <span class="on-hover">
                            <a href="{{route('page.company')}}" class="section-link text-uppercase  fw-600">Learn
                                more</a>
                            <i aria-hidden="true" class="fas fa-angle-right"></i>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @include('partials.youtube-modal')
</section>
<section class="my-5">
    <div class="container-fluid stupid-padding">
        <div class="row">
            <div class="col-12">
                <div class="section-title pt-0">
                    New Arrivals
                </div>
            </div>
        </div>
        <div class="row mt-4 text-center">
            @foreach($products as $product)
            @foreach($product->variants as $index => $variant)
            @if($index == 0)
            <div class="col-12 col-md-6 col-lg-3">
                @if(isset($variant->custom_specsheet))
                    <a href="{{asset('storage/uploads/products/'.@$variant->custom_specsheet)}}" target="_blank">
                        <img src="{{asset('storage/uploads/products/'.@$product->featured_image)}}" alt="" class="img-fluid" style="width:100%">
                    </a>
                    <p class="section-text2 fw-500 py-4"> <a href="{{asset('storage/uploads/products/'.@$variant->custom_specsheet)}}" target="_blank" class="" style="color:inherit; text-decoration:none;">{{$product->title}}</a></p>
                @else
                    <a href="{{route('product', $variant->slug)}}">
                        <img src="{{asset('storage/uploads/products/'.@$product->featured_image)}}" alt="" class="img-fluid" style="width:100%">
                    </a>
                    <p class="section-text2 fw-500 py-4"> <a href="{{route('product', $variant->slug)}}" class="" style="color:inherit; text-decoration:none;">{{$product->title}}</a></p>
                @endif
            </div>
            @endif
            @endforeach
            @endforeach
        </div>
    </div>
</section>
<section class="my-5">
    <div class="container-fluid stupid-padding-2">
        <div class="row section-grayed g-lg-0">
            <div class="col-12 col-lg-6 align-self-center">
                <div id="product-catalog-sec" class=" stupid-padding-4 ">
                    <div class="section-title text-white">
                        Product Catalog
                    </div>
                    <p class="section-text text-white fw-300">
                        Download your digital copy of our latest catalog/brochures and explore our extensive range of
                        indoor and outdoor luminaires.
                    </p>
                    <div class="section-text">
                        <span class=" on-hover-dark">
                            <a href="#" class="section-link section-link-dark text-uppercase fw-600" data-bs-toggle="modal" data-bs-target="#downloadCatalogModal">Download</a>
                            <i aria-hidden="true" class="fas fa-angle-right"></i>
                        </span>
                    </div>
                </div>

            </div>
            <div class="col-12 col-lg-6 align-self-strech p-0">
                <div class="section-grayed d-flex justify-content-end flex-column">
                    <img src="{{ asset('img/brochure.webp') }}" class="img-fluid brochure-img" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<section class="my-5">
    <div class="container-fluid stupid-padding">
        <div class="row">
            <div class="col-12">
                <div class="d-flex mt-2">
                    <div class="section-title fs-2">
                        Product Range
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtags Grid -->
        <div class="row mt-4 text-center" id="home-sub-tag-tiles">
            @foreach($subtags as $subtag)
                <div class="col-12 col-md-6 col-lg-3 hover-img">
                    <a class="w-100 img-hover-img" href="{{ route('products', $subtag->slug) }}">
                        <!-- Main image -->
                        <img src="{{ asset('storage/uploads/sub_tags/' . @$subtag->image) }}"
                             alt="{{ $subtag->display_name }}"
                             class="img-fluid w-100 main-img lazy {{ @$subtag->hover_image ? 'main-img-hover-present' : '' }}"
                             loading="lazy"
                             style="min-width: 150px; min-height: 150px;">

                        <!-- Hover image (if exists) -->
                        @if(@$subtag->hover_image)
                            <img src="{{ asset('storage/uploads/sub_tags/' . @$subtag->hover_image) }}"
                                 alt="{{ $subtag->display_name }} hover"
                                 class="img-fluid w-100 second-img lazy"
                                 loading="lazy"
                                 style="min-width: 150px; min-height: 150px;">
                        @endif
                    </a>

                    <p class="section-text2 fw-500 py-4">
                        <a href="{{ route('products', $subtag->slug) }}" 
                           style="color: inherit; text-decoration: none;">
                            {{ $subtag->display_name }}
                        </a>
                    </p>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 my-5 text-center">
                <span class="section-text on-hover">
                    <a href="{{ route('sub-tags') }}" class="section-link text-uppercase fw-600">View All</a>
                    <i aria-hidden="true" class="fas fa-angle-right"></i>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="my-5">
    <div class="container-fluid stupid-padding-3">
        <div class="row">
            <div class="col-12">
                <div class="section-title fs-2">
                    Latest Projects
                </div>
            </div>
        </div>
        <div class="row g-lg-4 pt-4">
            @foreach($projects as $project)
            <div class="col-12 col-lg-6 mb-3 mb-sm-0">
                <a href="{{route('project-detail', $project->slug)}}">
                    <img src="{{ @$project->projectImages[0] ? asset('storage/uploads/projects/'.@$project->projectImages[0]->image) : asset('images/default.png') }}" alt="" class="img-fluid">
                </a>
                <a href="{{route('project-detail', $project->slug)}}" class="d-block project-title mt-4 text-decoration-none" style="color: #66635E;">
                    {{$project->name}} - {{$project->location}}
                </a>
                <div class="d-flex my-2">
                    <div class="project-subtitle align-self-center">
                        {{$project->type}}
                    </div>{{--
                    <div class="flex-fill ms-3 align-self-center">
                        <hr style="border-width: 2px">
                    </div> --}}
                </div>
            </div>
            @endforeach
            <div class="col-12 my-5 text-center">
                <span class="section-text on-hover">
                    <a href="{{route('page.projects')}}" class="section-link text-uppercase fw-600">View All</a>
                    <i aria-hidden="true" class="fas fa-angle-right"></i>
                </span>
            </div>
        </div>
    </div>
</section>
<section class="my-5">
    <div class="container">

        <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach(["microsoft","tcs","infosys","barclays","google","kpmg","apollo-pharmacy","lupin","bcg","titan"]
                as $i)
                <div class="swiper-slide">
                    <img src="{{ asset('img/clients/'.$i.'.png')}}" alt="" class="img-fluid">
                </div>
                @endforeach
            </div>
        </div>
    </div>

</section>
@endsection

@push('js')

<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        speed: 400,
        loop: true,
        centeredSlides: true,
        autoPlay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        /*
                    freeMode: {
                        enabled: false,
                        sticky: true,
                    }, */
        slidesPerView: 3,
        spaceBetween: 30,
        autoHeight: true,
        breakpoints: {
            // when window width is >= 320px
            1024: {
                slidesPerView: 5,
                spaceBetween: 20
            },

        }
    });

    function slideNext() {
        swiper.slideNext()
    }
    setInterval(slideNext, 1500);

    const youtubeModal = document.getElementById('youtubeModal')
    youtubeModal.addEventListener('shown.bs.modal', function(event) {
        event.currentTarget.getElementsByTagName("iframe")[0].src = event.currentTarget.getElementsByTagName("iframe")[0].dataset.url
    });
    youtubeModal.addEventListener('hidden.bs.modal', function(event) {
        event.currentTarget.getElementsByTagName("iframe")[0].src = ""
    });

    function openURL(url = null) {
        if (url && url.length > 0) {
            window.open(url, '_blank').focus();
        }
    }
</script>
@endpush
