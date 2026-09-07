@extends('layout.web', ['theme' => 'light'])
@push('css')
@vite(['resources/scss/abby-smart.scss'])
<link rel="stylesheet" href="{{ asset('css/smart-scenes.css') }}">
@endpush
@section('title', 'Wireless Smart Lighting | Abby Lighting')
@section('description', 'Explore wireless lighting solutions with Abby Smart, and learn how to integrate it into your
projects')
@section('page-content')
<div class="container-fluid p-0">
    <img src="{{ asset('img/smart-lighting/banner2.jpg') }}" alt="" class="img-fluid">
</div>
<div class="row ms-0">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-4 p-0">
        <div class="text-content mt-5">
            Abby Lighting brings tech expertise to its old art of manufacturing luminaires. Wireless controls are no
            longer the future - they are the present. We've embraced this technology wholeheartedly at our studio, which
            is fully configured
            on Smart Controls.
            <br><br>
            The Abby Studio won an <strong>award from Casambi</strong> - the global leader of wireless controls - for
            the <strong>best Smart Lighting design</strong> in a space.
        </div>
    </div>
    <div class="col-12 col-lg-4 p-0">
        <div class="text-content mt-5">
            Whether for your home or for a commercial space, wireless controls help in 3 main areas:<br><br>
            <strong>- Lower Bills:</strong> Lights are turned on only when required with time-based schedules and
            sensors.<br>
            <strong>- Flexibility:</strong> Configure scenes on the go to control multiple luminaires with a single
            command.<br>
            <strong>- Sustainability:</strong> Save energy and reduce materials with lesser wiring compared to
            traditional automation.<br>
        </div>
    </div>
    <div class="col-12 col-lg-3 p-0">
        <div id="see-how-it-works-wrapper" class="text-content mt-0">
            <strong id="with_break"><a style="color: #4d4d4d" href="#smart-lighting-view-section">SEE HOW<br>IT
                    WORKS</a></strong>
            <strong id="without_break" class="my-5"><a style="color: #4d4d4d" href="#smart-lighting-view-section">SEE
                    HOW IT WORKS</a></strong>
        </div>
    </div>
</div>
<div class="container-fluid p-0">
    <img src="{{ asset('img/smart-lighting/solution.png') }}" alt="" class="img-fluid">
</div>

<div class="container-fluid p-0">
    <div class="row px-md-4 px-2 my-5" id="smart-lighting-view-section">
        <p id="tap-switches-text">With scene programming, one tap does it all—multiple lights adjust instantly to your
            preset dimness and color
            temperature. Effortlessly transform the ambience of any space to suit your mood or purpose. Tap the switches
            below and watch the magic unfold!</p>
        <div class="col-12 pe-md-5 pe-2">
            <div class="screen-img" data-screen="5">
                <img src="{{ asset('img/smart_scenes/1.jpg') }}" alt="smart_screen_1.jpg" class=" img-fluid pe-4">
                <p class="screen-text">&nbsp;&nbsp;</p>
            </div>
            <div class="screen-img d-none" data-screen="4">
                <img src="{{ asset('img/smart_scenes/2.jpg') }}" alt="smart_screen_2.jpg" class=" img-fluid pe-4">
                <p class="screen-text"><strong>Ambient Mode:</strong> Brighten the room with an even, general light,
                    turning on all fixtures at a warm-neutral 3000K, at different
                    levels of brightness to create visual interest.</p>
            </div>
            <div class="screen-img d-none" data-screen="1">
                <img src="{{ asset('img/smart_scenes/3.jpg') }}" alt="smart_screen_3.jpg" class=" img-fluid pe-4">
                <p class="screen-text"><strong>Evening Mode:</strong> Dim, warm lighting with all the luminaires at
                    2700K sets a cozy, welcoming mood.</p>
            </div>
            <div class="screen-img d-none" data-screen="2">
                <img src="{{ asset('img/smart_scenes/4.jpg') }}" alt="smart_screen_4.jpg" class=" img-fluid pe-4">
                <p class="screen-text"><strong>Reading Mode:</strong> The overhead suspended Hoopla is turned on at
                    4000K at medium-high brightness, delivering comfortable,
                    focused illumination while reading in the chair.</p>
            </div>
            <div class="screen-img d-none" data-screen="3">
                <img src="{{ asset('img/smart_scenes/5.jpg') }}" alt="smart_screen_5.jpg" class=" img-fluid pe-4">
                <p class="screen-text"><strong>Art Mode:</strong> Create a gallery-like ambience with only the
                    spotlights focused on the art turned on, echoing the warm hues of the evening sky outside.</p>
            </div>
            <div class="switch-buttons float-end">
                <div class="cursor-start"></div>
                <div class="button-off pt-1"><span>ALL OFF</span></div>
                <div class="button-overlay-group">
                    <div class="overlay-trigger button-overlay d-none" data-id="5"></div>
                    <div class="overlay-trigger button-overlay-4" data-id="4"></div>
                    <div id="targetCursorButton" class="overlay-trigger button-overlay-1" data-id="1"></div>
                    <div class="overlay-trigger button-overlay-2" data-id="2"></div>
                    <div class="overlay-trigger button-overlay-3" data-id="3"></div>
                </div>
                <img src="{{ asset('img/switches/all_off.png') }}" data-target="5" alt="all_off.png" class="img-fluid">
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
    </div>
    <div class="text-spacing"></div>
</div>

<div class="row ms-0">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-4 p-0">
        <div class="text-content mt-5">
            <div class="mb-4 features-text">Features</div>
            Plug your luminaire in, download the app and you are ready to control your lights with a simple Bluetooth
            connection. All products automatically create a wireless mesh network. No need for special wiring, new
            switches, servers, or anything else - it’s as simple as it can get.<br><br>
            Adjust <strong>brightness, colour temperature</strong> and even make use of <strong>Warm Dim
                Lighting</strong> using Smart wireless controls.
        </div>
    </div>
    <div class="col-12 col-lg-4 p-0">
        <div class="text-content mt-5">
            <div class="invisible mb-4 features-text">Features</div>
            Enjoy energy savings and create a human-centric lighting environment using:<br><br>
            <strong>- Motion, Light and Occupancy sensors:</strong> Automatically trigger lights<br>
            <strong>- Time-based programming:</strong> Based on preset schedules, or synced with local
            sunrise-sunset<br>
            <strong>- Scene-setting:</strong> Control multiple luminaires together with custom brightness and colour
            temperature
        </div>
    </div>
    <div class="col-12 col-lg-3 p-0">
    </div>
</div>

<div class="container-fluid p-0 mt-3">
    <img src="{{ asset('img/smart-lighting/flow-3.jpg') }}" alt="" class="img-fluid">
</div>

<div class="row ms-0">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-9 p-0">
        <p id="youtube-heading-line" class="text-content">Watch <strong>Warm Dim lighting</strong> in action - with the
            lights dimming to a
            soft
            amber glow, possible only with the use of Smart controls.</p>

        <div class="videowrapper">
            <iframe src="https://www.youtube.com/embed/S-h4zVb0YXk?si=00cJttNgSpdoeDdl" title="Warm Dim lighting"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>

    </div>
</div>

<div class="row ms-0 mt-5">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-10 p-0">
        <div class="text-content mt-5">
            <div class="mb-4 features-text">Automation with Sensors</div>
            Sensors allow you to unlock power savings by automating the control of lights. Make sure the lights are
            turned on at high brightness only when people are present, or control the light brightness depending on the
            level of daylight entering the space.
        </div>
    </div>
</div>

<div class="row ms-0 mt-5">
    <div class="col-12 col-lg-1 p-0 ">
        <div class="text-content videotext ms-5 ps-2 mob-display">
            <strong>Motion Sensors</strong><br>
            Trigger a light when the motion is detected, and program the light to turn off/dim after
            motion ceases. Ideal to use in display areas, to create an interactive experience while simultaneously
            saving energy.
        </div>
    </div>
    <div class="col-12 col-lg-6 p-0">
        <div class="videowrapper subtiles">
            <iframe src="https://www.youtube.com/embed/N1Ql-DEzxQ4?si=tc8S_pd083EwQUaw" title="Motion Sensors"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
    <div class="col-12 col-lg-5 p-0 relative mob-display-none">
        <div class="text-content videotext ms-5 ps-2">
            <strong>Motion Sensors</strong><br>
            Trigger a light when the motion is detected, and program the light to turn off/dim after
            motion ceases. Ideal to use in display areas, to create an interactive experience while simultaneously
            saving energy.
        </div>
    </div>
</div>


<div class="row ms-0 mt-7">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-5 p-0 relative">
        <div class="text-content videotext ms-5 ps-2">
            <strong>Daylight Sensors</strong><br>
            Make the most of areas blessed with daylight to ensure that lights brighten only when the amount of daylight
            reduces, ensuring a consistent level of illumination throughout the day.
        </div>
    </div>
    <div class="col-12 col-lg-6 p-0">
        <div class="videowrapper subtiles">
            <iframe src="https://www.youtube.com/embed/VJoksubFFKA?si=_1Qw0Ah9cKJfpctV" title="Daylight Sensors"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
</div>


<div class="row ms-0 mt-7">
    <div class="col-12 col-lg-1 p-0">
        <div class="text-content videotext ms-5 ps-2 mob-display">
            <strong>Occupancy Sensors</strong><br>
            A novel new technology that detects human presence even in the absence of motion. Ideal
            for use above a workstation or even in washrooms, where lights are required to be on only when in use and
            can be dimmed down otherwise.
        </div>
    </div>
    <div class="col-12 col-lg-6 p-0">
        <div class="videowrapper subtiles">
            <iframe src="https://www.youtube.com/embed/jXz2_mOUkNM?si=OE388_YjFhaWhaQK" title="Occupancy Sensors"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </div>
    <div class="col-12 col-lg-5 p-0 relative mob-display-none">
        <div class="text-content videotext ms-5 ps-2">
            <strong>Occupancy Sensors</strong><br>
            A novel new technology that detects human presence even in the absence of motion. Ideal
            for use above a workstation or even in washrooms, where lights are required to be on only when in use and
            can be dimmed down otherwise.
        </div>
    </div>
</div>

<div class="row ms-0 mt-5">
    <div class="col-12 col-lg-1 p-0">
    </div>
    <div class="col-12 col-lg-10 p-0">
        <div class="text-content mt-5 wireless-control">
            <div class="mb-4 features-text">Wireless Controls</div>
            Upgrade your luminaires with smart wireless controls. Manage lights, groups, and scenes via the Casambi or
            SmartLife app, configure settings on a remote switch, or use Alexa voice commands for hands-free
            convenience. Experience the future of smart lighting automation today!
        </div>
    </div>
</div>

<div class="container-fluid p-0 mt-5">
    <img src="{{ asset('img/smart-lighting/controls-4.png') }}" alt="" class="img-fluid controls-image">
</div>
<div class="container-fluid p-0">
    <img src="{{ asset('img/smart-lighting/alive-4.jpg') }}" alt="" class="img-fluid">
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


                    var targetImage = document.querySelector('img[data-target="' + toggleable + '"]');
                    if (!targetImage.classList.contains('d-none')) {
                        let overlayImages = document.querySelectorAll('div.overlay-trigger.cursor:not([data-id="5"])');
                        overlayImages.forEach(function(img) {
                            img.classList.add('overlay-effect');
                        });
                        document.querySelector('.button-off').classList.add('overlay-effect');

                        setTimeout(function() {
                            overlayImages.forEach(function(img) {
                                img.classList.remove('overlay-effect');
                            });
                            document.querySelector('.button-off').classList.remove('overlay-effect');
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

                    if (targetImage) {
                        targetImage.classList.remove('d-none');
                        document.querySelector('.button-off').classList.add('on');
                    }
                    var targetScreen = document.querySelector('.screen-img[data-screen="' + toggleable + '"]');
                    if (targetScreen) {
                        targetScreen.classList.remove('d-none');
                        document.querySelector('.button-off').classList.add('on');
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

                if (buttonOff.classList.contains('on')) {
                    targetImageOn.classList.remove('d-none');
                    targetImageOff.classList.add('d-none');
                    document.querySelector('img[data-target="5"]').classList.add('d-none');
                    document.querySelector('img[data-target="4"]').classList.remove('d-none');
                } else {
                    targetImageOn.classList.add('d-none');
                    targetImageOff.classList.remove('d-none');
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
                if(startAnimation == false)
                {
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