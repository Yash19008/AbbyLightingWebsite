<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="p:domain_verify" content="89c6a25a6043881e9264644ec567c5d1"/>
    <meta name="google-site-verification" content="YBiy8ERSg3gepd1XHH2BmnRKXvwzFY9eigmk5lX1Chg" />

    {{-- Favicons --}}
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">

    <title>@yield('title', 'Abby Lighting | Architectural & Decorative Lighting')</title>
    <meta name="description" content="@yield('description', 'Architectural, decorative, outdoor and smart lighting, engineered and manufactured in India.')">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/brands.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    
    {{-- Laravel Vite CSS --}}
    @vite(['resources/scss/app.scss'])
    
    {{-- Next.js Global CSS --}}
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    
    {{-- Google Recaptcha --}}
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaOnloadCallback&render=explicit" async defer></script>
    
    @stack('styles')
</head>
<body class="{{ Request::is('/') ? 'home-page' : '' }}">
    <main>
        {{-- Next.js Style Header --}}
        @include('frontend.layouts.header')
        
        {{-- Page Content --}}
        @yield('content')
        
        {{-- Next.js Style Footer --}}
        @include('frontend.layouts.footer')
    </main>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    
    {{-- Header & Footer Scripts --}}
    <script src="{{ asset('js/header.js') }}"></script>
    <script src="{{ asset('js/footer.js') }}"></script>
    
    {{-- Search Mobile Script --}}
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const searchIconMobile = document.getElementById("search-icon-mobile");
            const searchInputMobile = document.getElementById("search-input-mobile");
            
            if (searchIconMobile && searchInputMobile) {
                searchIconMobile.addEventListener("click", function() {
                    const display = searchInputMobile.style.display;
                    searchInputMobile.style.display = display == 'block' ? 'none' : 'block';
                });
            }
        });
    </script>
    
    {{-- Google Analytics --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-48B9N75V8M"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-48B9N75V8M');
    </script>
    
    @stack('scripts')
</body>
</html>
