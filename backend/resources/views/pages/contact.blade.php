<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Abby Lighting</title>
    <meta name="description"
        content="Fill out this form, or reach out to us at frontdesk@abbylighting.com or on +91 7304623220">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shared-inquiry.css') }}">

    @vite(['resources/scss/app.scss', 'resources/scss/contact.scss'])

    <style>
        /* Add padding to body to account for fixed Next.js header */
        body {
            padding-top: 80px;
        }
    </style>
</head>

<body>
    {{-- Next.js Style Header --}}
    @include('partials.common.header-nextjs')

    {{-- Contact Page Content --}}

    <section class="main-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-6 col-md-12 p-0" style="display: flex;">
                    @php
                        $contactSideImage = App\Models\Setting::getValue('CONTACT_SIDE_IMAGE', false);
                    @endphp
                    @if ($contactSideImage && file_exists(public_path($contactSideImage)))
                        <img src="{{ asset($contactSideImage) }}" alt="Abby Lighting Studio" class="img-fluid"
                            style="width: 100%; height: 100%; min-height: 520px; object-fit: cover;">
                    @elseif (file_exists(public_path('img/contact-img.webp')))
                        <img src="{{ asset('img/contact-img.webp') }}" alt="Abby Lighting Studio" class="img-fluid"
                            style="width: 100%; height: 100%; min-height: 520px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/contact-img.jpg') }}" alt="Abby Lighting Studio" class="img-fluid"
                            style="width: 100%; height: 100%; min-height: 520px; object-fit: cover;">
                    @endif
                </div>

                <div class="col-12 col-lg-6 col-md-12 p-4 p-md-5 contact-form">
                    <div class="p-0 mb-4">
                        <h3 class="contact-section2-title">Say hi, & we will get back to you shortly!</h3>
                        <p class="contact-section-text">Send us an email with your question or concerns. If you would
                            like to discuss your current or potential project with us, please complete the enquiry form
                            provided or contact the Studio directly at +91 7304623220.</p>
                    </div>

                    <div class="figma-enquiry figma-enquiry-inline">
                        <div class="figma-enquiry-card">
                            <div id="contactFormContent">
                                <form class="figma-enquiry-form" id="contactPageForm" novalidate>
                                    <div class="figma-enquiry-grid">
                                        {{-- Full Name --}}
                                        <div class="figma-enquiry-field full" id="contact-field-name">
                                            <label for="contact-name">Your name*</label>
                                            <input id="contact-name" name="name" type="text" autocomplete="name"
                                                required>
                                            <div class="field-error" id="contact-error-name"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>

                                        {{-- Phone with Country Code --}}
                                        <div class="figma-enquiry-field phone-field-wrap" id="contact-field-phone">
                                            <div class="phone-country-select-container"
                                                id="contactPhoneCountryContainer">
                                                <button type="button" class="phone-country-trigger"
                                                    id="contactPhoneCountryTrigger" aria-label="Select Country Code"
                                                    aria-expanded="false">
                                                    <img src="https://flagcdn.com/w40/in.png" alt="India"
                                                        class="country-flag-img" id="contactSelectedCountryFlag">
                                                    <svg class="country-chevron-icon" id="contactCountryChevron"
                                                        width="10" height="6" viewBox="0 0 10 6" fill="none"
                                                        stroke="#555" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 1L5 5L9 1" />
                                                    </svg>
                                                </button>
                                                <div class="phone-country-dropdown" id="contactPhoneCountryDropdown"
                                                    style="display: none;">
                                                    <div class="phone-country-search-wrap">
                                                        <input type="text" class="phone-country-search-input"
                                                            id="contactPhoneCountrySearch"
                                                            placeholder="Search country or code...">
                                                    </div>
                                                    <div class="phone-country-list" id="contactPhoneCountryList">
                                                        {{-- Populated by JS --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="contact-phone">Enter number</label>
                                            <input id="contact-phone" name="phone" type="tel" autocomplete="tel"
                                                required>
                                            <div class="field-error" id="contact-error-phone"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px; width: 100%; flex-basis: 100%;">
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="figma-enquiry-field" id="contact-field-email">
                                            <label for="contact-email">Enter Email id*</label>
                                            <input id="contact-email" name="email" type="email"
                                                autocomplete="email" required>
                                            <div class="field-error" id="contact-error-email"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>

                                        {{-- Select City --}}
                                        <div class="figma-enquiry-field" id="contact-field-city">
                                            <div class="download-select-wrap" id="contactCitySelectWrap">
                                                <button type="button" class="download-select-trigger"
                                                    id="contactCitySelectTrigger">
                                                    <span class="download-select-label is-placeholder"
                                                        id="contactCitySelectLabel">Select City*</span>
                                                    <svg class="download-select-arrow" width="10" height="6"
                                                        viewBox="0 0 10 6" fill="none" stroke="#555"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 1L5 5L9 1" />
                                                    </svg>
                                                </button>
                                                <input type="hidden" name="city" id="contact-city"
                                                    value="" required>
                                                <div class="download-select-menu" id="contactCitySelectMenu"
                                                    style="display: none;">
                                                    @php
                                                        $contactCities = [
                                                            'Agra',
                                                            'Ahmedabad',
                                                            'Amritsar',
                                                            'Bhopal',
                                                            'Bhubaneswar',
                                                            'Chandigarh',
                                                            'Chennai',
                                                            'Coimbatore',
                                                            'Dehradun',
                                                            'Delhi',
                                                            'Faridabad',
                                                            'Ghaziabad',
                                                            'Gurugram',
                                                            'Guwahati',
                                                            'Hyderabad',
                                                            'Indore',
                                                            'Jaipur',
                                                            'Jalandhar',
                                                            'Jamshedpur',
                                                            'Jodhpur',
                                                            'Kanpur',
                                                            'Kochi',
                                                            'Kolkata',
                                                            'Lucknow',
                                                            'Ludhiana',
                                                            'Madurai',
                                                            'Mangaluru',
                                                            'Mumbai',
                                                            'Mysuru',
                                                            'Nagpur',
                                                            'Nashik',
                                                            'Navi Mumbai',
                                                            'Noida',
                                                            'Patna',
                                                            'Pune',
                                                            'Raipur',
                                                            'Rajkot',
                                                            'Ranchi',
                                                            'Surat',
                                                            'Thane',
                                                            'Thiruvananthapuram',
                                                            'Udaipur',
                                                            'Vadodara',
                                                            'Varanasi',
                                                            'Vijayawada',
                                                            'Visakhapatnam',
                                                            'Other',
                                                        ];
                                                    @endphp
                                                    @foreach ($contactCities as $cityName)
                                                        <button type="button" class="download-select-option"
                                                            data-value="{{ $cityName }}">{{ $cityName }}</button>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="field-error" id="contact-error-city"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>

                                        {{-- Company Name --}}
                                        <div class="figma-enquiry-field" id="contact-field-company">
                                            <label for="contact-company">Enter Company / Firm Name</label>
                                            <input id="contact-company" name="company" type="text"
                                                autocomplete="organization">
                                            <div class="field-error" id="contact-error-company"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>

                                        {{-- I am a (Role) --}}
                                        <div class="figma-enquiry-field full" id="contact-field-role">
                                            <div class="download-select-wrap" id="contactRoleSelectWrap">
                                                <button type="button" class="download-select-trigger"
                                                    id="contactRoleSelectTrigger">
                                                    <span class="download-select-label is-placeholder"
                                                        id="contactRoleSelectLabel">I am a*</span>
                                                    <svg class="download-select-arrow" width="10" height="6"
                                                        viewBox="0 0 10 6" fill="none" stroke="#555"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M1 1L5 5L9 1" />
                                                    </svg>
                                                </button>
                                                <input type="hidden" name="role" id="contact-role"
                                                    value="" required>
                                                <div class="download-select-menu" id="contactRoleSelectMenu"
                                                    style="display: none;">
                                                    <button type="button" class="download-select-option"
                                                        data-value="Architect">Architect</button>
                                                    <button type="button" class="download-select-option"
                                                        data-value="Interior Designer">Interior Designer</button>
                                                    <button type="button" class="download-select-option"
                                                        data-value="Lighting Consultant">Lighting Consultant</button>
                                                    <button type="button" class="download-select-option"
                                                        data-value="Contractor">Contractor</button>
                                                    <button type="button" class="download-select-option"
                                                        data-value="Homeowner">Homeowner</button>
                                                    <button type="button" class="download-select-option"
                                                        data-value="Other">Other</button>
                                                </div>
                                            </div>
                                            <div class="field-error" id="contact-error-role"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>

                                        {{-- Tell us more (Message) --}}
                                        <div class="figma-enquiry-field full message" id="contact-field-message">
                                            <label for="contact-message">Tell us more (optional)</label>
                                            <textarea id="contact-message" name="message" rows="3"></textarea>
                                            <div class="field-error" id="contact-error-message"
                                                style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- General error message --}}
                                    <div id="contact-general-error"
                                        style="display: none; color: #e53e3e; font-size: 13px; margin: 10px 0; text-align: center; font-weight: 600;">
                                    </div>

                                    {{-- Captcha --}}
                                    <div class="figma-captcha-wrapper">
                                        <div id="contactPageRecaptcha"></div>
                                        <div class="figma-captcha-error" id="contact-recaptcha-error"
                                            style="display: none;" role="alert">
                                            Please complete Google Captcha verification.
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <button type="submit" class="figma-enquiry-submit" id="contactSubmitBtn">
                                        <span id="contact-btn-text">SEND ENQUIRY</span>
                                        <span id="contact-btn-loading"
                                            style="display: none; align-items: center; justify-content: center; gap: 8px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                style="animation: figmaSpin 1s linear infinite;">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="3" stroke-dasharray="32" stroke-linecap="round"
                                                    opacity="0.3"></circle>
                                                <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round"></path>
                                            </svg>
                                            SENDING...
                                        </span>
                                    </button>

                                    <p class="figma-enquiry-consent">
                                        By submitting this form, you agree to be contacted by <em>Abby Lighting</em>
                                        regarding your enquiry.
                                    </p>
                                </form>
                            </div>

                            {{-- Success State --}}
                            <div id="contactThankYouCard"
                                style="display: none; padding: 40px 10px; text-align: center;">
                                <div
                                    style="width: 54px; height: 54px; background: #e8f5e9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#2e7d32" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </div>
                                <h3
                                    style="font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 600; color: #111; margin-bottom: 10px;">
                                    Thank You!</h3>
                                <p
                                    style="font-family: 'Inter', sans-serif; font-size: 14px; color: #666; max-width: 440px; margin: 0 auto 24px; line-height: 1.6;">
                                    We have received your enquiry. A lighting consultant will get back to you shortly.
                                </p>
                                <button type="button" class="figma-enquiry-submit" id="contactResetBtn"
                                    style="max-width: 240px; margin: 0 auto; height: 44px;">
                                    SEND ANOTHER MESSAGE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section>
        <div class="container-fluid pt-5">
            <div class="row">
                <div class="col-12 col-lg-6 col-md-6 md-5 section2">
                    <h3 class="contact-section2-title">Abby Lighting & Switchgear Ltd.</h3>
                    <div class="">
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-6 ps-0">
                                <p class="contact-section2-subtitle mb-0">Corporate Office & Studio:</p>
                                <p class="contact-section2-text">802 A, Fortune Terraces, New Link Road, Opp City Mall,
                                    Andheri
                                    West Mumbai- 400053, India.</p>
                                {{-- <div class="mt-4">
                                    <a href="#" class="section-detail-text1">Get Directions</a>
                                    <img src="img/icons/right-arrow.svg" alt="" width=7>
                                </div> --}}
                            </div>
                            <div class="col-12 col-lg-6 col-md-6 ps-0">
                                <p class="contact-section2-subtitle mb-0">Factory:</p>
                                <p class="contact-section2-text">70, Genesis Industrial Complex, Phase 1, Kolgaon,
                                    Palghar
                                    Boisar Road, Palghar 401404, Maharashtra</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 col-md-6 ps-0">
                                <p class="contact-section2-subtitle mb-0">Technical Support</p>
                                <p class="contact-section2-text">frontdesk@abbylighting.com<br>+91 9833 645 212</p>
                                {{-- <div class="mt-4">
                                    <a href="#" class="section-detail-text1">Get Directions</a>
                                    <img src="img/icons/right-arrow.svg" alt="" width=7>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 col-md-6  map-div">
                    <div id="map-container-google-1" class="z-depth-1-half map-container" style="height: 440px">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d389515.73676372296!2d72.58127254572507!3d19.1130497726978!3m2!1i724!2i768!4f13.1!3m3!1m2!1s0x3be7b702244d3419%3A0x8298564494559dae!2sAbby%20Lighting%20%26%20Switchgear%20Ltd!5e0!3m2!1sen!2sin!4v1696337552026!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

            </div>
    </section>

    {{-- <section>
        <div class="container-fluid section3">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <h5 class="section3-text1">India</h5>
                    <p class="section-text2">frontdesk@abbylighting.com</p>
                    <p class="section-text2">+91 7304623220</p>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <h5 class="section3-text1">Overseas</h5>
                    <p class="section-text2">info@abbylighting.com</p>
                    <p class="section-text2">+91 7304623220</p>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                    <h5 class="section3-text1">Support</h5>
                    <p class="section-text2">helpdesk@abbylighting.com</p>
                    <p class="section-text2">+91 7304623220</p>
                </div>
            </div>
        </div>

    </section> --}}

    <section>
        <div class="container-fluid section3 p-5">
            <div class="row">
                <div class="col-12 col-lg-4 col-md-3 mb-4 mb-md-0">
                    <a href="/catalogues" class="section3-detail-text">DOWNLOAD CATALOGUE</a>
                    <img src="img/icons/right-arrow.svg" alt="" width=7>
                </div>
                <div class="col-12 col-lg-4 col-md-3 mb-4 mb-md-0">
                    <a href="https://www.youtube.com/watch?v=SPgaE-3jxEY" class="section3-detail-text"
                        target="_blank">FACTORY VIDEO</a>
                    <img src="img/icons/right-arrow.svg" alt="" width=7>
                </div>
            </div>
        </div>
    </section>

    {{-- Include Next.js Footer --}}
    @include('partials.common.footer-nextjs')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Contact Page Inquiry Form Controller --}}
    <script>
        const CONTACT_COUNTRIES = [{
                code: "IN",
                name: "India",
                dialCode: "+91"
            },
            {
                code: "US",
                name: "United States",
                dialCode: "+1"
            },
            {
                code: "GB",
                name: "United Kingdom",
                dialCode: "+44"
            },
            {
                code: "AE",
                name: "United Arab Emirates",
                dialCode: "+971"
            },
            {
                code: "SA",
                name: "Saudi Arabia",
                dialCode: "+966"
            },
            {
                code: "QA",
                name: "Qatar",
                dialCode: "+974"
            },
            {
                code: "OM",
                name: "Oman",
                dialCode: "+968"
            },
            {
                code: "KW",
                name: "Kuwait",
                dialCode: "+965"
            },
            {
                code: "BH",
                name: "Bahrain",
                dialCode: "+973"
            },
            {
                code: "SG",
                name: "Singapore",
                dialCode: "+65"
            },
            {
                code: "AU",
                name: "Australia",
                dialCode: "+61"
            },
            {
                code: "CA",
                name: "Canada",
                dialCode: "+1"
            },
            {
                code: "DE",
                name: "Germany",
                dialCode: "+49"
            },
            {
                code: "FR",
                name: "France",
                dialCode: "+33"
            },
            {
                code: "IT",
                name: "Italy",
                dialCode: "+39"
            },
            {
                code: "LK",
                name: "Sri Lanka",
                dialCode: "+94"
            },
            {
                code: "NP",
                name: "Nepal",
                dialCode: "+977"
            },
            {
                code: "MY",
                name: "Malaysia",
                dialCode: "+60"
            },
            {
                code: "TH",
                name: "Thailand",
                dialCode: "+66"
            },
            {
                code: "ID",
                name: "Indonesia",
                dialCode: "+62"
            },
            {
                code: "VN",
                name: "Vietnam",
                dialCode: "+84"
            },
            {
                code: "PH",
                name: "Philippines",
                dialCode: "+63"
            },
            {
                code: "NZ",
                name: "New Zealand",
                dialCode: "+64"
            },
            {
                code: "ZA",
                name: "South Africa",
                dialCode: "+27"
            },
            {
                code: "EG",
                name: "Egypt",
                dialCode: "+20"
            },
            {
                code: "KE",
                name: "Kenya",
                dialCode: "+254"
            },
            {
                code: "NG",
                name: "Nigeria",
                dialCode: "+234"
            },
            {
                code: "BR",
                name: "Brazil",
                dialCode: "+55"
            },
            {
                code: "MX",
                name: "Mexico",
                dialCode: "+52"
            },
            {
                code: "ES",
                name: "Spain",
                dialCode: "+34"
            },
            {
                code: "NL",
                name: "Netherlands",
                dialCode: "+31"
            },
            {
                code: "SE",
                name: "Sweden",
                dialCode: "+46"
            },
            {
                code: "CH",
                name: "Switzerland",
                dialCode: "+41"
            },
            {
                code: "JP",
                name: "Japan",
                dialCode: "+81"
            },
            {
                code: "KR",
                name: "South Korea",
                dialCode: "+82"
            },
            {
                code: "CN",
                name: "China",
                dialCode: "+86"
            }
        ];

        let contactSelectedCountry = CONTACT_COUNTRIES[0];
        let contactRecaptchaWidgetId = null;
        const CONTACT_RECAPTCHA_SITE_KEY = "{{ config('google_captcha.site_key', env('GOOGLE_CAPTCHA_SITE_KEY')) }}";

        function initContactFloatingLabels() {
            const fields = document.querySelectorAll(
                '#contactPageForm .figma-enquiry-field input, #contactPageForm .figma-enquiry-field textarea');
            fields.forEach(field => {
                const wrap = field.closest('.figma-enquiry-field');
                if (!wrap) return;

                const update = () => {
                    if (field.value && field.value.trim().length > 0) {
                        wrap.classList.add('filled');
                    } else {
                        wrap.classList.remove('filled');
                    }
                };

                field.addEventListener('input', update);
                field.addEventListener('focus', () => wrap.classList.add('focused'));
                field.addEventListener('blur', () => {
                    wrap.classList.remove('focused');
                    update();
                });
                update();
            });
        }

        function populateContactCountryList(filter = '') {
            const list = document.getElementById('contactPhoneCountryList');
            if (!list) return;

            const q = filter.toLowerCase().trim();
            const filtered = CONTACT_COUNTRIES.filter(c =>
                !q || c.name.toLowerCase().includes(q) || c.dialCode.includes(q) || c.code.toLowerCase().includes(q)
            );

            list.innerHTML = '';
            if (filtered.length === 0) {
                list.innerHTML = '<div class="phone-country-empty">No country found</div>';
                return;
            }

            filtered.forEach(c => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'phone-country-option' + (c.code === contactSelectedCountry.code ? ' is-selected' :
                    '');
                btn.innerHTML = `
                    <img src="https://flagcdn.com/w40/${c.code.toLowerCase()}.png" alt="${c.name}" class="country-flag-img-option">
                    <span class="country-name-text">${c.name}</span>
                    <span class="country-dial-code">${c.dialCode}</span>
                `;
                btn.addEventListener('click', () => {
                    contactSelectedCountry = c;
                    const flagImg = document.getElementById('contactSelectedCountryFlag');
                    if (flagImg) {
                        flagImg.src = `https://flagcdn.com/w40/${c.code.toLowerCase()}.png`;
                        flagImg.alt = c.name;
                    }
                    const dropdown = document.getElementById('contactPhoneCountryDropdown');
                    if (dropdown) dropdown.style.display = 'none';
                    document.getElementById('contactCountryChevron')?.classList.remove('is-open');
                    document.getElementById('contact-phone')?.focus();
                });
                list.appendChild(btn);
            });
        }

        function setupContactCustomSelect(triggerId, menuId, labelId, hiddenInputId) {
            const trigger = document.getElementById(triggerId);
            const menu = document.getElementById(menuId);
            const label = document.getElementById(labelId);
            const hiddenInput = document.getElementById(hiddenInputId);
            const wrap = trigger ? trigger.closest('.download-select-wrap') : null;

            if (!trigger || !menu || !label || !hiddenInput) return;

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = menu.style.display !== 'none';
                closeAllContactDropdowns();
                if (!isOpen) {
                    menu.style.display = 'flex';
                    wrap && wrap.classList.add('is-open');
                    trigger.querySelector('.download-select-arrow')?.classList.add('is-open');
                }
            });

            menu.querySelectorAll('.download-select-option').forEach(opt => {
                opt.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const val = opt.getAttribute('data-value');
                    hiddenInput.value = val;
                    label.textContent = val;
                    label.classList.remove('is-placeholder');

                    menu.querySelectorAll('.download-select-option').forEach(o => o.classList.remove(
                        'is-selected'));
                    opt.classList.add('is-selected');

                    menu.style.display = 'none';
                    wrap && wrap.classList.remove('is-open');
                    trigger.querySelector('.download-select-arrow')?.classList.remove('is-open');

                    const err = hiddenInput.closest('.figma-enquiry-field')?.querySelector('.field-error');
                    if (err) err.style.display = 'none';
                });
            });
        }

        function closeAllContactDropdowns() {
            const phoneDropdown = document.getElementById('contactPhoneCountryDropdown');
            if (phoneDropdown) phoneDropdown.style.display = 'none';
            document.getElementById('contactCountryChevron')?.classList.remove('is-open');

            ['contactCitySelectMenu', 'contactRoleSelectMenu'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.style.display = 'none';
            });
            document.querySelectorAll('#contactPageForm .download-select-wrap').forEach(w => w.classList.remove('is-open'));
            document.querySelectorAll('#contactPageForm .download-select-arrow').forEach(a => a.classList.remove(
            'is-open'));
        }

        function ensureContactRecaptchaLoaded(callback) {
            if (window.grecaptcha && window.grecaptcha.render) {
                callback();
                return;
            }

            const scriptId = 'google-recaptcha-inquiry-script';
            if (!document.getElementById(scriptId)) {
                const script = document.createElement('script');
                script.id = scriptId;
                script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
                script.async = true;
                script.defer = true;
                script.onload = () => {
                    if (window.grecaptcha && window.grecaptcha.ready) {
                        window.grecaptcha.ready(callback);
                    } else {
                        setTimeout(callback, 500);
                    }
                };
                document.head.appendChild(script);
            } else {
                const check = setInterval(() => {
                    if (window.grecaptcha && window.grecaptcha.render) {
                        clearInterval(check);
                        callback();
                    }
                }, 200);
            }
        }

        function renderContactRecaptcha() {
            const target = document.getElementById('contactPageRecaptcha');
            if (!target) return;

            if (contactRecaptchaWidgetId !== null) {
                try {
                    grecaptcha.reset(contactRecaptchaWidgetId);
                } catch (err) {}
                return;
            }

            ensureContactRecaptchaLoaded(() => {
                try {
                    target.innerHTML = '';
                    contactRecaptchaWidgetId = grecaptcha.render('contactPageRecaptcha', {
                        'sitekey': CONTACT_RECAPTCHA_SITE_KEY,
                        'callback': function() {
                            const err = document.getElementById('contact-recaptcha-error');
                            if (err) err.style.display = 'none';
                        }
                    });
                } catch (e) {
                    console.warn('Contact reCAPTCHA render error:', e);
                }
            });
        }

        function handleContactFormSubmit(e) {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('#contactPageForm .field-error').forEach(el => el.style.display = 'none');
            const genErr = document.getElementById('contact-general-error');
            if (genErr) genErr.style.display = 'none';
            const capErr = document.getElementById('contact-recaptcha-error');
            if (capErr) capErr.style.display = 'none';

            const nameInput = document.getElementById('contact-name');
            const phoneInput = document.getElementById('contact-phone');
            const emailInput = document.getElementById('contact-email');
            const cityInput = document.getElementById('contact-city');
            const companyInput = document.getElementById('contact-company');
            const roleInput = document.getElementById('contact-role');
            const messageInput = document.getElementById('contact-message');

            let hasError = false;

            function showError(fieldId, errorMsg) {
                const el = document.getElementById('contact-error-' + fieldId);
                if (el) {
                    el.textContent = errorMsg;
                    el.style.display = 'block';
                }
                hasError = true;
            }

            if (!nameInput.value.trim()) {
                showError('name', 'The name field is required.');
            }

            if (!phoneInput.value.trim()) {
                showError('phone', 'The phone field is required.');
            }

            const emailVal = emailInput.value.trim();
            if (!emailVal) {
                showError('email', 'The email field is required.');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
                showError('email', 'Please enter a valid email address.');
            }

            if (!cityInput.value.trim()) {
                showError('city', 'The city field is required.');
            }

            if (!roleInput.value.trim()) {
                showError('role', 'The role field is required.');
            }

            let captchaToken = '';
            if (contactRecaptchaWidgetId !== null && window.grecaptcha) {
                try {
                    captchaToken = grecaptcha.getResponse(contactRecaptchaWidgetId);
                } catch (err) {}
            }

            if (!captchaToken) {
                if (capErr) capErr.style.display = 'block';
                hasError = true;
            }

            if (hasError) return;

            // Set Loading state
            const submitBtn = document.getElementById('contactSubmitBtn');
            submitBtn.disabled = true;
            document.getElementById('contact-btn-text').style.display = 'none';
            document.getElementById('contact-btn-loading').style.display = 'inline-flex';

            const payload = {
                type: 'general',
                name: nameInput.value.trim(),
                phone: `${contactSelectedCountry.dialCode} ${phoneInput.value.trim()}`,
                email: emailVal,
                city: cityInput.value.trim(),
                company: companyInput.value.trim() || null,
                role: roleInput.value.trim(),
                message: messageInput.value.trim() || null,
                'g-recaptcha-response': captchaToken
            };

            fetch('/api/inquiries', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                .then(async response => {
                    const data = await response.json().catch(() => null);
                    if (!response.ok) {
                        submitBtn.disabled = false;
                        document.getElementById('contact-btn-text').style.display = 'inline';
                        document.getElementById('contact-btn-loading').style.display = 'none';

                        if (response.status === 422 && data && data.errors) {
                            for (const [key, msgs] of Object.entries(data.errors)) {
                                if (key === 'g-recaptcha-response') {
                                    if (capErr) {
                                        capErr.textContent = msgs[0];
                                        capErr.style.display = 'block';
                                    }
                                    if (contactRecaptchaWidgetId !== null && window.grecaptcha) {
                                        grecaptcha.reset(contactRecaptchaWidgetId);
                                    }
                                } else {
                                    showError(key, msgs[0]);
                                }
                            }
                        } else {
                            if (genErr) {
                                genErr.textContent = (data && data.message) ? data.message :
                                    'Failed to submit enquiry. Please try again.';
                                genErr.style.display = 'block';
                            }
                        }
                        return;
                    }

                    // Success state
                    document.getElementById('contactFormContent').style.display = 'none';
                    document.getElementById('contactThankYouCard').style.display = 'block';

                    // Reset captcha
                    if (contactRecaptchaWidgetId !== null && window.grecaptcha) {
                        try {
                            grecaptcha.reset(contactRecaptchaWidgetId);
                        } catch (err) {}
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    document.getElementById('contact-btn-text').style.display = 'inline';
                    document.getElementById('contact-btn-loading').style.display = 'none';
                    if (genErr) {
                        genErr.textContent = 'Network error. Please try again later.';
                        genErr.style.display = 'block';
                    }
                });
        }

        function resetContactForm() {
            const form = document.getElementById('contactPageForm');
            if (form) form.reset();

            const cityLabel = document.getElementById('contactCitySelectLabel');
            if (cityLabel) {
                cityLabel.textContent = 'Select City*';
                cityLabel.classList.add('is-placeholder');
            }
            const cityInput = document.getElementById('contact-city');
            if (cityInput) cityInput.value = '';

            const roleLabel = document.getElementById('contactRoleSelectLabel');
            if (roleLabel) {
                roleLabel.textContent = 'I am a*';
                roleLabel.classList.add('is-placeholder');
            }
            const roleInput = document.getElementById('contact-role');
            if (roleInput) roleInput.value = '';

            document.querySelectorAll('#contactPageForm .download-select-option').forEach(o => o.classList.remove(
                'is-selected'));
            document.querySelectorAll('#contactPageForm .field-error').forEach(el => el.style.display = 'none');
            const genErr = document.getElementById('contact-general-error');
            if (genErr) genErr.style.display = 'none';
            const capErr = document.getElementById('contact-recaptcha-error');
            if (capErr) capErr.style.display = 'none';

            const submitBtn = document.getElementById('contactSubmitBtn');
            if (submitBtn) submitBtn.disabled = false;
            document.getElementById('contact-btn-text').style.display = 'inline';
            document.getElementById('contact-btn-loading').style.display = 'none';

            document.getElementById('contactThankYouCard').style.display = 'none';
            document.getElementById('contactFormContent').style.display = 'block';

            if (contactRecaptchaWidgetId !== null && window.grecaptcha) {
                try {
                    grecaptcha.reset(contactRecaptchaWidgetId);
                } catch (err) {}
            }

            initContactFloatingLabels();
            closeAllContactDropdowns();
        }

        document.addEventListener('DOMContentLoaded', function() {
            initContactFloatingLabels();
            populateContactCountryList();

            // Phone country dropdown trigger
            const phoneTrigger = document.getElementById('contactPhoneCountryTrigger');
            const phoneDropdown = document.getElementById('contactPhoneCountryDropdown');
            if (phoneTrigger && phoneDropdown) {
                phoneTrigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = phoneDropdown.style.display !== 'none';
                    closeAllContactDropdowns();
                    if (!isOpen) {
                        phoneDropdown.style.display = 'flex';
                        document.getElementById('contactCountryChevron')?.classList.add('is-open');
                        document.getElementById('contactPhoneCountrySearch')?.focus();
                    }
                });
            }

            // Phone country search
            const phoneSearch = document.getElementById('contactPhoneCountrySearch');
            if (phoneSearch) {
                phoneSearch.addEventListener('input', (e) => {
                    populateContactCountryList(e.target.value);
                });
                phoneSearch.addEventListener('click', (e) => e.stopPropagation());
            }

            // Custom selects
            setupContactCustomSelect('contactCitySelectTrigger', 'contactCitySelectMenu', 'contactCitySelectLabel',
                'contact-city');
            setupContactCustomSelect('contactRoleSelectTrigger', 'contactRoleSelectMenu', 'contactRoleSelectLabel',
                'contact-role');

            // Form submit
            const form = document.getElementById('contactPageForm');
            if (form) {
                form.addEventListener('submit', handleContactFormSubmit);
            }

            // Reset button
            const resetBtn = document.getElementById('contactResetBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', resetContactForm);
            }

            // Document click to close dropdowns
            document.addEventListener('click', () => {
                closeAllContactDropdowns();
            });

            // Render reCAPTCHA
            renderContactRecaptcha();
        });

        // reCAPTCHA Callback fallback
        window.recaptchaOnloadCallback = function() {
            renderContactRecaptcha();
        };

        // Newsletter Signup
        function signup() {
            const name = document.getElementById("subscribe-name").value;
            const email = document.getElementById("subscribe-email").value;
            document.getElementById("subscribe-input-info").style.display = 'none';

            if (email && validateEmail(email) && name) {
                fetch("/subscribe-newsletter?email=" + email + '&name=' + name)
                    .then(data => {
                        console.log('Subscribed successfully');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                document.getElementById("all-set-section").style.display = 'block';
                document.getElementById("input-btn-section").style.display = 'none';
                document.querySelectorAll('.input-section').forEach(function(el) {
                    el.style.display = 'none';
                });
            } else {
                document.getElementById("subscribe-input-info").style.display = 'block';
            }
        }

        // Email Validation
        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };
    </script>

    {{-- Google Analytics --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-48B9N75V8M"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-48B9N75V8M');
    </script>

</body>

</html>
