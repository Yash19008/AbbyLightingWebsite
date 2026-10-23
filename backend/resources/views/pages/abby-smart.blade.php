@extends('layout.web', ['theme' => 'light'])
@push('css')
    @vite(['resources/scss/abby-smart.scss'])
    <link rel="stylesheet" href="{{ asset('css/smart-scenes.css') }}">
    <style>
        .smart-banner-wrapper {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            line-height: 0 !important;
            overflow: hidden !important;
            background-color: #E0DFDD;
        }

        .smart-banner-img {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            display: block !important;
            object-fit: cover !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        .smart-intro-section {
            background-color: #E0DFDD;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .smart-intro-container {
            width: 100%;
            max-width: 1360px;
            margin: 0 auto;
            padding: clamp(32px, 4.5vw, 60px) clamp(24px, 5.5vw, 84px) clamp(24px, 3.5vw, 40px);
            box-sizing: border-box;
        }

        .smart-intro-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: clamp(32px, 5vw, 80px);
            row-gap: 24px;
            align-items: start;
        }

        .smart-intro-col {
            display: flex;
            flex-direction: column;
        }

        .smart-intro-text {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(14px, 1vw, 15.5px);
            line-height: 1.62;
            color: #222222;
            margin: 0 0 22px 0;
            letter-spacing: -0.005em;
        }

        .smart-intro-text:last-child {
            margin-bottom: 0;
        }

        .smart-intro-text strong,
        .smart-intro-point strong {
            font-weight: 700;
            color: #111111;
        }

        .smart-intro-lead {
            margin: 0 0 16px 0;
        }

        .smart-intro-points {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .smart-intro-point {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(14px, 1vw, 15.5px);
            line-height: 1.62;
            color: #222222;
            margin: 0;
            letter-spacing: -0.005em;
        }

        .smart-scene-section {
            width: 100%;
            /* max-width: 1360px; */
            margin: clamp(24px, 3.5vw, 44px) auto 0;
            padding: 0 20px;
            box-sizing: border-box;
        }

        #tap-switches-text {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(12.5px, 0.9vw, 14px);
            width: 100%;
            margin-bottom: clamp(12px, 1.5vw, 18px);
            line-height: 1.55;
            text-align: left;
            color: #555555;
            letter-spacing: -0.005em;
        }

        .screen-caption[data-caption="5"],
        .screen-caption[data-caption="5"] .screen-text {
            display: none !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .smart-features-section {
            width: 100%;
            background-color: #ffffff;
            margin-top: clamp(28px, 3.5vw, 44px);
            margin-bottom: clamp(40px, 5vw, 72px);
        }

        .smart-features-container {
            width: 100%;
            max-width: 1360px;
            margin: 0 auto;
            padding: 0 clamp(20px, 4vw, 64px);
            box-sizing: border-box;
        }

        .smart-features-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(32px, 2.8vw, 42px);
            font-weight: 700;
            color: #000000;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin: 0 0 clamp(24px, 2.5vw, 36px) 0;
            text-align: left;
        }

        .smart-features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: clamp(36px, 6vw, 92px);
            row-gap: 24px;
            align-items: start;
        }

        .smart-features-col {
            display: flex;
            flex-direction: column;
        }

        .smart-features-text {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(14px, 1vw, 15.5px);
            line-height: 1.62;
            color: #222222;
            margin: 0 0 24px 0;
            letter-spacing: -0.005em;
        }

        .smart-features-text:last-child {
            margin-bottom: 0;
        }

        .smart-features-lead {
            margin: 0 0 16px 0;
        }

        .smart-features-points {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .smart-features-point {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(14px, 1vw, 15.5px);
            line-height: 1.62;
            color: #222222;
            margin: 0;
            letter-spacing: -0.005em;
        }

        /* ==========================================================================
                                   Automation with Sensors Section
                                   ========================================================================== */
        .smart-sensors-section {
            width: 100%;
            background-color: #ffffff;
            margin-top: clamp(48px, 6vw, 76px);
            margin-bottom: clamp(48px, 6vw, 76px);
        }

        .smart-sensors-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(32px, 2.8vw, 42px);
            font-weight: 700;
            color: #000000;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin: 0 0 clamp(16px, 1.8vw, 24px) 0;
            text-align: left;
        }

        .smart-sensors-intro {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(14px, 1.05vw, 15.5px);
            line-height: 1.62;
            color: #222222;
            margin: 0 0 clamp(36px, 4.5vw, 56px) 0;
            letter-spacing: -0.005em;
        }

        .smart-sensor-rows {
            display: flex;
            flex-direction: column;
            gap: clamp(44px, 5.5vw, 72px);
        }

        .smart-sensor-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: clamp(36px, 6vw, 84px);
            row-gap: 24px;
            align-items: center;
        }

        .smart-sensor-video-wrap {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background-color: #000000;
            border-radius: 3px;
        }

        .smart-sensor-video-wrap iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .smart-sensor-text-col {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .smart-sensor-item-title {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(22px, 1.8vw, 28px);
            font-weight: 700;
            color: #000000;
            letter-spacing: -0.015em;
            line-height: 1.25;
            margin: 0 0 clamp(12px, 1.2vw, 18px) 0;
        }

        .smart-sensor-item-desc {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: clamp(16px, 1.5vw, 17.5px);
            line-height: 1.65;
            color: #222222;
            margin: 0;
            letter-spacing: -0.005em;
            max-width: 520px;
        }

        @media (max-width: 860px) {

            .smart-intro-grid,
            .smart-features-grid,
            .smart-sensor-row {
                grid-template-columns: 1fr;
                row-gap: 20px;
            }

            .smart-intro-container,
            .smart-features-container,
            .smart-scene-section {
                padding: 28px 20px 20px;
            }

            .smart-sensor-row.smart-sensor-reverse .smart-sensor-video-col {
                order: 1;
            }

            .smart-sensor-row.smart-sensor-reverse .smart-sensor-text-col {
                order: 2;
            }

            .smart-sensor-rows {
                gap: 40px;
            }
        }
    </style>
@endpush
@section('title', 'Wireless Smart Lighting | Abby Lighting')
@section('description',
    'Explore wireless lighting solutions with Abby Smart, and learn how to integrate it into your
    projects')
@section('page-content')

    {{-- Full-Width Responsive Header Banner --}}
    <div class="smart-banner-wrapper">
        <img src="{{ asset('img/smart-lighting/banner2.jpg') }}" alt="Connect with Abby Smart - Wireless Smart Lighting"
            class="smart-banner-img">
    </div>

    {{-- Section 2: Intro Text & Solutions Graphic --}}
    <div class="container-fluid p-0 smart-intro-section" style="background-color: #E0DFDD">
        <div class="smart-intro-container">
            <div class="smart-intro-grid">
                <div class="smart-intro-col">
                    <p class="smart-intro-text">
                        Abby Lighting brings tech expertise to its old art of manufacturing luminaires. Wireless controls
                        are no longer the future - they are the present. We've embraced this technology wholeheartedly at
                        our studio, which is fully configured on Smart Controls.
                    </p>
                    <p class="smart-intro-text">
                        The Abby Studio won an <strong>award from Casambi</strong> - the global leader of wireless controls
                        - for the
                    </p>
                </div>
                <div class="smart-intro-col">
                    <p class="smart-intro-text smart-intro-lead">
                        Whether for your home or for a commercial space, wireless controls help in 3 main areas:
                    </p>
                    <div class="smart-intro-points">
                        <p class="smart-intro-point">
                            - <strong>Lower Bills:</strong> Lights are turned on only when required with time-based
                            schedules and sensors.
                        </p>
                        <p class="smart-intro-point">
                            - <strong>Flexibility:</strong> Configure scenes on the go to control multiple luminaires with a
                            single command.
                        </p>
                        <p class="smart-intro-point">
                            - <strong>Sustainability:</strong> Save energy and reduce materials with lesser wiring compared
                            to traditional automation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{ asset('img/smart-lighting/solution.png') }}" alt="Abby Smart Intelligent Solutions"
            class="img-fluid w-100 d-block">
    </div>
    <div class="container-fluid p-0">
        <div class="smart-scene-section" id="smart-lighting-view-section">
            <p id="tap-switches-text">With scene programming, one tap does it all—multiple lights adjust instantly to your
                preset dimness and color temperature. Effortlessly transform the ambience of any space to suit your mood or
                purpose. Tap the switches below and watch the magic unfold!</p>
            <div class="col-12 scene-container p-0">
                <div class="scene-stage position-relative">
                    <div class="screen-img" data-screen="5">
                        <img src="{{ asset('img/smart_scenes/1.jpg') }}" alt="smart_screen_1.jpg"
                            class="img-fluid w-100 d-block">
                    </div>
                    <div class="screen-img d-none" data-screen="4">
                        <img src="{{ asset('img/smart_scenes/2.jpg') }}" alt="smart_screen_2.jpg"
                            class="img-fluid w-100 d-block">
                    </div>
                    <div class="screen-img d-none" data-screen="1">
                        <img src="{{ asset('img/smart_scenes/3.jpg') }}" alt="smart_screen_3.jpg"
                            class="img-fluid w-100 d-block">
                    </div>
                    <div class="screen-img d-none" data-screen="2">
                        <img src="{{ asset('img/smart_scenes/4.jpg') }}" alt="smart_screen_4.jpg"
                            class="img-fluid w-100 d-block">
                    </div>
                    <div class="screen-img d-none" data-screen="3">
                        <img src="{{ asset('img/smart_scenes/5.jpg') }}" alt="smart_screen_5.jpg"
                            class="img-fluid w-100 d-block">
                    </div>

                    <div class="switch-buttons">
                        <div class="cursor-start"></div>
                        <div class="button-off pt-1"><span>ALL OFF</span></div>
                        <div class="button-overlay-group">
                            <div class="overlay-trigger button-overlay d-none" data-id="5"></div>
                            <div class="overlay-trigger button-overlay-4" data-id="4"></div>
                            <div id="targetCursorButton" class="overlay-trigger button-overlay-1" data-id="1"></div>
                            <div class="overlay-trigger button-overlay-2" data-id="2"></div>
                            <div class="overlay-trigger button-overlay-3" data-id="3"></div>
                        </div>
                        <img src="{{ asset('img/switches/all_off.png') }}" data-target="5" alt="all_off.png"
                            class="img-fluid">
                        <img src="{{ asset('img/switches/bottom_right.png') }}" data-target="4" alt="bottom_right.png"
                            class="img-fluid d-none">
                        <img src="{{ asset('img/switches/top_left.png') }}" data-target="1" alt="top_left.png"
                            class="img-fluid d-none">
                        <img src="{{ asset('img/switches/top_right.png') }}" data-target="2" alt="top_right.png"
                            class="img-fluid d-none">
                        <img src="{{ asset('img/switches/bottom_left.png') }}" data-target="3" alt="bottom_left.png"
                            class="img-fluid d-none">
                    </div>
                </div>

                {{-- Scene Text Captions in normal flow below image stage --}}
                <div class="scene-captions-wrapper">
                    <div class="screen-caption" data-caption="5">
                        <p class="screen-text">&nbsp;</p>
                    </div>
                    <div class="screen-caption d-none" data-caption="4">
                        <p class="screen-text"><strong>Ambient Mode:</strong> Brighten the room with an even, general light,
                            turning on all fixtures at a warm-neutral 3000K, at different
                            levels of brightness to create visual interest.</p>
                    </div>
                    <div class="screen-caption d-none" data-caption="1">
                        <p class="screen-text"><strong>Evening Mode:</strong> Dim, warm lighting with all the luminaires at
                            2700K sets a cozy, welcoming mood.</p>
                    </div>
                    <div class="screen-caption d-none" data-caption="2">
                        <p class="screen-text"><strong>Reading Mode:</strong> The overhead suspended Hoopla is turned on at
                            4000K at medium-high brightness, delivering comfortable,
                            focused illumination while reading in the chair.</p>
                    </div>
                    <div class="screen-caption d-none" data-caption="3">
                        <p class="screen-text"><strong>Art Mode:</strong> Create a gallery-like ambience with only the
                            spotlights focused on the art turned on, echoing the warm hues of the evening sky outside.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Features Section --}}
    <div class="smart-features-section">
        <div class="smart-features-container">
            <h2 class="smart-features-title">Features</h2>
            <div class="smart-features-grid">
                <div class="smart-features-col">
                    <p class="smart-features-text">
                        Plug your luminaire in, download the app and you are ready to control your lights with a simple
                        Bluetooth connection. All products automatically create a wireless mesh network. No need for special
                        wiring, new switches, servers, or anything else - it’s as simple as it can get.
                    </p>
                    <p class="smart-features-text">
                        Adjust brightness, colour temperature and even make use of Warm Dim Lighting using Smart wireless
                        controls.
                    </p>
                </div>
                <div class="smart-features-col">
                    <p class="smart-features-text smart-features-lead">
                        Enjoy energy savings and create a human-centric lighting environment using:
                    </p>
                    <div class="smart-features-points">
                        <p class="smart-features-point">
                            - Motion, Light and Occupancy sensors: Automatically trigger lights
                        </p>
                        <p class="smart-features-point">
                            - Time-based programming: Based on preset schedules, or synced with local sunrise-sunset
                        </p>
                        <p class="smart-features-point">
                            - Scene-setting: Control multiple luminaires together with custom brightness and colour
                            temperature
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-0 mt-4 mt-lg-5">
        <img src="{{ asset('img/smart-lighting/flow-3.jpg') }}" alt="" class="img-fluid w-100 d-block">
    </div>


    <div class="container">
        <div class="p-0">
            <div class="row ms-0">

                <div class="col-12 col-lg-12 p-0">
                    <p id="youtube-heading-line" class="text-content">Watch <strong>Warm Dim lighting</strong>
                        in action - with the
                        lights dimming to a
                        soft
                        amber glow, possible only with the use of Smart controls.</p>

                    <div class="videowrapper">
                        <iframe src="https://www.youtube.com/embed/S-h4zVb0YXk?si=00cJttNgSpdoeDdl"
                            title="Warm Dim lighting" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>

                </div>
            </div>

            {{-- Automation with Sensors Section --}}
            <div class="smart-sensors-section">
                <h2 class="smart-sensors-title">Automation with Sensors</h2>
                <p class="smart-sensors-intro">
                    Sensors allow you to unlock power savings by automating the control of lights. Make sure the lights are
                    turned on at high brightness only when people are present, or control the light brightness depending on
                    the level of daylight entering the space.
                </p>

                <div class="smart-sensor-rows">
                    {{-- Row 1: Motion Sensors (Video Left, Text Right) --}}
                    <div class="smart-sensor-row">
                        <div class="smart-sensor-video-col">
                            <div class="smart-sensor-video-wrap">
                                <iframe src="https://www.youtube.com/embed/N1Ql-DEzxQ4?si=tc8S_pd083EwQUaw"
                                    title="Motion Sensors" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="smart-sensor-text-col">
                            <h3 class="smart-sensor-item-title">Motion Sensors</h3>
                            <p class="smart-sensor-item-desc">
                                Trigger a light when the motion is detected, and program the light to turn off/dim after
                                motion ceases. Ideal to use in display areas, to create an interactive experience while
                                simultaneously saving energy.
                            </p>
                        </div>
                    </div>

                    {{-- Row 2: Daylight Sensors (Text Left, Video Right) --}}
                    <div class="smart-sensor-row smart-sensor-reverse">
                        <div class="smart-sensor-text-col">
                            <h3 class="smart-sensor-item-title">Daylight Sensors</h3>
                            <p class="smart-sensor-item-desc">
                                Make the most of areas blessed with daylight to ensure that lights brighten only when the
                                amount of daylight reduces, ensuring a consistent level of illumination throughout the day.
                            </p>
                        </div>
                        <div class="smart-sensor-video-col">
                            <div class="smart-sensor-video-wrap">
                                <iframe src="https://www.youtube.com/embed/VJoksubFFKA?si=_1Qw0Ah9cKJfpctV"
                                    title="Daylight Sensors" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>

                    {{-- Row 3: Occupancy Sensors (Video Left, Text Right) --}}
                    <div class="smart-sensor-row">
                        <div class="smart-sensor-video-col">
                            <div class="smart-sensor-video-wrap">
                                <iframe src="https://www.youtube.com/embed/jXz2_mOUkNM?si=OE388_YjFhaWhaQK"
                                    title="Occupancy Sensors" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="smart-sensor-text-col">
                            <h3 class="smart-sensor-item-title">Occupancy Sensors</h3>
                            <p class="smart-sensor-item-desc">
                                A novel new technology that detects human presence even in the absence of motion. Ideal for
                                use above a workstation or even in washrooms, where lights are required to be on only when
                                in use and can be dimmed down otherwise.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row ms-0 mt-5">

                <div class="col-12 col-lg-7 p-0">
                    <div class="text-content wireless-control">
                        <h3 class="smart-sensor-item-title">Wireless Controls</h3>
                        <p class="smart-sensor-item-desc">
                            Upgrade your luminaires with smart wireless controls. Manage lights, groups, and scenes
                            via the Casambi or
                            SmartLife app, configure settings on a remote switch, or use Alexa voice commands for
                            hands-free
                            convenience. Experience the future of smart lighting automation today!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-0 mt-4 mt-lg-5">
        <img src="{{ asset('img/smart-lighting/controls-4.png') }}" alt="" class="img-fluid w-100 d-block">
    </div>
    <div class="p-0">
        <img src="{{ asset('img/smart-lighting/alive-4.jpg') }}" alt="" class="img-fluid w-100 d-block">
    </div>

@endsection

@push('js')
    <script>
        var startAnimation = true;

        document.addEventListener("DOMContentLoaded", function() {
            var triggers = document.querySelectorAll('.overlay-trigger');

            triggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();

                    startAnimation = false;
                    var toggleable = this.getAttribute('data-id');
                    var triggerButtons = document.querySelectorAll('.overlay-trigger');
                    triggerButtons.forEach(function(button) {
                        button.classList.add('cursor');
                    });
                    this.classList.remove('cursor');


                    var targetImage = document.querySelector('img[data-target="' + toggleable +
                        '"]');
                    if (!targetImage.classList.contains('d-none')) {
                        let overlayImages = document.querySelectorAll(
                            'div.overlay-trigger.cursor:not([data-id="5"])');
                        overlayImages.forEach(function(img) {
                            img.classList.add('overlay-effect');
                        });
                        document.querySelector('.button-off').classList.add('overlay-effect');

                        setTimeout(function() {
                            overlayImages.forEach(function(img) {
                                img.classList.remove('overlay-effect');
                            });
                            document.querySelector('.button-off').classList.remove(
                                'overlay-effect');
                        }, 400);
                    }

                    var images = document.querySelectorAll('.switch-buttons img');
                    images.forEach(function(img) {
                        img.classList.add('d-none');
                    });

                    var screens = document.querySelectorAll('.screen-img');
                    screens.forEach(function(img) {
                        img.classList.add('d-none');
                    });
                    var captions = document.querySelectorAll('.screen-caption');
                    captions.forEach(function(cap) {
                        cap.classList.add('d-none');
                    });

                    if (targetImage) {
                        targetImage.classList.remove('d-none');
                        document.querySelector('.button-off').classList.add('on');
                    }
                    var targetScreen = document.querySelector('.screen-img[data-screen="' +
                        toggleable + '"]');
                    if (targetScreen) {
                        targetScreen.classList.remove('d-none');
                        document.querySelector('.button-off').classList.add('on');
                    }
                    var targetCaption = document.querySelector('.screen-caption[data-caption="' +
                        toggleable + '"]');
                    if (targetCaption) {
                        targetCaption.classList.remove('d-none');
                    }
                });
            });


            // TOGGLE BUTTON JS
            var buttonOff = document.querySelector('.button-off');
            var targetImageOn = document.querySelector('.screen-img[data-screen="4"]');
            var targetImageOff = document.querySelector('.screen-img[data-screen="5"]');

            buttonOff.addEventListener('click', function() {
                this.classList.toggle('on');
                startAnimation = false;
                var screens = document.querySelectorAll('.screen-img');
                screens.forEach(function(img) {
                    img.classList.add('d-none');
                });
                var captions = document.querySelectorAll('.screen-caption');
                captions.forEach(function(cap) {
                    cap.classList.add('d-none');
                });

                if (buttonOff.classList.contains('on')) {
                    targetImageOn.classList.remove('d-none');
                    targetImageOff.classList.add('d-none');
                    var targetCapOn = document.querySelector('.screen-caption[data-caption="4"]');
                    if (targetCapOn) targetCapOn.classList.remove('d-none');
                    document.querySelector('img[data-target="5"]').classList.add('d-none');
                    document.querySelector('img[data-target="4"]').classList.remove('d-none');
                } else {
                    targetImageOn.classList.add('d-none');
                    targetImageOff.classList.remove('d-none');
                    var targetCapOff = document.querySelector('.screen-caption[data-caption="5"]');
                    if (targetCapOff) targetCapOff.classList.remove('d-none');
                    document.querySelector('img[data-target="5"]').classList.remove('d-none');
                    document.querySelector('img[data-target="4"]').classList.add('d-none');
                }

                document.querySelector('img[data-target="3"]').classList.add('d-none');
                document.querySelector('img[data-target="2"]').classList.add('d-none');
                document.querySelector('img[data-target="1"]').classList.add('d-none');

            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const cursor = document.querySelector('.cursor-start');
            const button = document.querySelector('#targetCursorButton');

            const animateCursor = () => {
                if (startAnimation == false) {
                    cursor.style.display = 'none';
                    return true;
                }
                cursor.style.display = 'block';
                cursor.style.transition = 'none';
                cursor.style.marginLeft = '-80px';
                cursor.style.marginTop = '-80px';

                setTimeout(() => {
                    cursor.style.transition = 'margin-left 2s, margin-top 2s';
                    const buttonRect = button.getBoundingClientRect();

                    cursor.style.marginLeft = `40px`;
                    cursor.style.marginTop = `40px`;

                    setTimeout(() => {
                        cursor.style.transform = 'scale(1.2)';

                        setTimeout(() => {
                            cursor.style.transform = 'scale(1)';

                            setTimeout(() => {
                                cursor.style.display = 'none';
                                animateCursor();
                            }, 500);

                        }, 200);
                    }, 2000);
                }, 100);
            };

            animateCursor();
        });
    </script>
@endpush
