<nav class="navbar navbar-expand-xl navbar-dark p-lg-5" style="z-index: 1;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="{{ asset(($theme === 'light') ? 'img/black-logo-1.png' : 'img/logo-light.png') }}"
                alt="Abby Lighting" class="d-none d-lg-block" />
            <img src="{{ asset('img/logo-light.png') }}" alt="Abby Lighting" class="d-block d-lg-none" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu"
            aria-controls="offcanvasMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- <button id="search-button" class="btn btn-link d-flex d-xl-none">
            <img src="{{ asset(($theme === 'light') ? 'img/icons/magnifying-black-glass.svg' : 'img/icons/magnifying-glass.svg' ) }}"
                width="20" class="mx-3">
        </button> --}}
        <form id="search-form-mobile"
            class="ms-xl-5 ps-xl-5 d-flex mb-0 d-xl-none {{$theme === 'light' ? 'search-input-dark' : 'search-input-dark search-input' }}"
            role="search" action="/search">
            <img id="search-icon-mobile"
                src="{{ asset(($theme === 'light') ? 'img/icons/magnifying-black-glass.svg' : 'img/icons/magnifying-glass.svg') }}"
                width="20" class="mx-3">
            <input id="search-input-mobile" class="{{$theme === 'light' ? 'dark-input' : '' }}" type="search"
                placeholder="SEARCH" aria-label="Search" name="q" autocomplete="one-time-code" style="display: none;">
            <button class="d-none btn btn-outline-success" type="submit">Search</button>
        </form>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav  mb-2 mb-lg-0  text-uppercase  align-items-center gap-1 gap-lg-3 gap-xxl-4">
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}" aria-current="page"
                        href="{{route('page.company')}}">About us</a>
                </li>
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}"
                        href="{{route('sub-tags')}}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}"
                        href="{{route('page.abby-smart')}}">Smart Lighting</a>
                </li>
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}"
                        href="{{route('page.projects')}}">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}"
                        href="{{route('page.clients')}}">Clients</a>
                </li>
                <li class="nav-item">
                    <a class="{{$theme === 'light' ? 'dark-nav nav-link' : 'nav-link' }}"
                        href="{{ route('page.contact') }}">Contact</a>
                </li>
            </ul>

            <form
                class="ms-xl-5 d-flex mb-0 {{$theme === 'light' ? 'search-input-dark' : 'search-input-dark search-input' }}"
                role="search" action="/search">
                <img src="{{ asset(($theme === 'light') ? 'img/icons/magnifying-black-glass.svg' : 'img/icons/magnifying-glass.svg') }}"
                    width="20" class="mx-3">
                <input class="{{$theme === 'light' ? 'dark-input' : '' }}" type="search" placeholder="SEARCH"
                    aria-label="Search" name="q" autocomplete="one-time-code">
                <button class="d-none btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
</nav>
<div class="offcanvas offcanvas-start mobile-menu" tabindex="-1" id="offcanvasMenu"
    aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header align-self-end">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <ul class="navbar-nav mb-2 mt-5 text-uppercase  align-items-center gap-4">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{route('page.company')}}">About us</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('sub-tags')}}">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('page.projects')}}">Projects</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('page.clients')}}">Clients</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('page.contact') }}">Contact</a>
        </li>
    </ul>
</div>
