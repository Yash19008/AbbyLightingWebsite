@extends('layout.web', ['theme' => 'light'])
@section('page-content')
<div class="px-lg-5 project-page">
    <div class="container-fluid">
        <h1 class="section-title pb-0">{{$event->name}}</h1>
        <div id="project_description">{!!@$event->description!!}</div>
        <div class="project-title">{{$event->location}}</div>
        <div class="row" id="project-tiles">
            <div class="masonry-sizer col-4"></div>
            @php $currentIndex = 0; @endphp
            @foreach($event->eventImages as $index => $eventImage)
            @php $currentIndex = $currentIndex + 1; @endphp
            @if($currentIndex == 1)
            <div class="masonry-item col-12 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 2)
            <div class="masonry-item col-12 col-xl-8 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 3)
            <div class="masonry-item col-12 col-xl-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 4)
            <div class="masonry-item col-12 col-lg-8 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 5)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 6)
            <div class="masonry-item col-12 col-lg-4 col-xl-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 7)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 8)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 9)
            <div class="masonry-item col-12 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 10)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 11)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @if($currentIndex == 12)
            <div class="masonry-item col-12 col-lg-4 mt-4">
                <img src="{{asset('storage/uploads/events/'.@$eventImage->image)}}" alt="" class="img-fluid w-100">
            </div>
            @endif
            @php if ($currentIndex == 12) $currentIndex = 0; @endphp
            @endforeach
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
@vite(['resources/js/projects.js'])
@endpush()