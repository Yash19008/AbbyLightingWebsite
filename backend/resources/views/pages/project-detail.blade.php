@extends('layout.web', ['theme' => 'light'])
<link rel="stylesheet" type="text/css" href="{{asset('css/viewer.min.css')}}">
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="{{  asset('js/viewer.min.js').'?v='.config('custom_config.css_js_version')  }}"></script>

@section("title", "$project->name | Abby Lighting")
@section('page-content')
<div class="px-lg-5 project-page">
    <div class="container-fluid">
        <h1 class="section-title p-0 p-0 project-page-header-title">{{@$project->name}}<span class="project-product-links d-none d-md-inline">View products</span></h1>
        <div id="project_description">{!!@$project->description!!}</div>
        <div class="project-title">{{@$project->location}}</div>
        <p class="project-product-links mobile-link-btn d-block d-md-none mt-2 mb-0">View products</p>
        <div class="row mt-5" id="sub-tag-tiles">
            @php $currentIndex = 0; @endphp
            @foreach($project->projectImages as $projectImage)

            @php $currentIndex = $currentIndex + 1; @endphp

            <li class="col-12 col-lg-6 col-xl-4 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </li>

            {{-- @if($currentIndex == 2)
            <div class="col-12 col-lg-6 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 3)
            <div class="col-12 col-lg-6 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 4)
            <div class="col-12 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 5)
            <div class="col-12 col-lg-6 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 6)
            <div class="col-12 col-lg-6 mt-4">
                <img src="{{asset('storage/uploads/projects/'.$projectImage['image'])}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @php if ($currentIndex == 6) $currentIndex = 0; @endphp --}}
            @endforeach
        </div>
        <div id="projects_used_title" class="row mt-5">
            <div class="col-12 col-lg-12">
                <div class="d-flex mt-2">
                    <div class="section-title fs-2 pb-2">
                        Products used
                    </div>
                    <div class="flex-fill ms-3 align-self-center">
                        <hr style="border-width: 2px">
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-12 col-xl-12">
                <div class="row">
                    @foreach($project->projectSubTags as $projectSubTag)
                    @if($projectSubTag->sub_tag)
                    <div class="col-12 col-md-6 col-lg-2 text-center">
                        <a href="{{route('products', $projectSubTag->sub_tag->slug)}}"
                            class="section-text2 fw-500"><img src="{{asset('storage/uploads/sub_tags/'.@$projectSubTag->sub_tag->image)}}"
                            alt="" class="img-fluid w-100"></a>
                        <a href="{{route('products', $projectSubTag->sub_tag->slug)}}"
                            class="section-text2 fw-500">{{$projectSubTag->sub_tag->display_name}}</a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

        </div>
        <div class="row mb-3">
            <div class="col">
                <p class="section-text stupid-text-2  fw-600">
                    @if($prev != null)
                    <span class="on-hover">
                        <i aria-hidden="true" class="fas fa-angle-left"></i>
                        <a href="{{route('project-detail', $prev)}}"
                            class="section-link text-uppercase  fw-600">Previous Project</a>
                    </span>
                    @endif
                </p>
            </div>
            <div class="col text-end">
                <p class="section-text stupid-text-2  fw-600">
                    @if($next != null)
                    <span class="on-hover">
                        <a href="{{route('project-detail', $next)}}" class="section-link text-uppercase  fw-600">Next
                            Project</a>
                        <i aria-hidden="true" class="fas fa-angle-right"></i>
                    </span>
                    @endif
                </p>
            </div>
        </div>
        <div class="row my-5"></div>
    </div>
</div>
@endsection
@push('css')
@vite(['resources/scss/projects.scss'])
@endpush
@push('js')
@vite(['resources/js/app.js'])
@vite(['resources/js/sub-tags.js'])
@vite(['resources/js/project-details.js'])
@endpush
