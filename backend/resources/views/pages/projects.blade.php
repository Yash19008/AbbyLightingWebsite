<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects across industries | Abby Lighting</title>
    <meta name="description"
        content="Lighting solutions for every project - from high-end Retail and Hospitality to Residences and Office spaces">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/scss/projects.scss'])

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

    {{-- Projects Page Content --}}
    <div class="project-page">
        <div class="container-fluid projects-header-container">
            <h1 class="section-title">{{ @$title }}</h1>
        </div>

            {{-- Sticky dark bar (same as Products) --}}
            <section class="container-fluid py-3 p-0 bg-black text-white sticky-top pb-0">
                <div id="links-section" class="links d-flex flex-nowrap"
                    style="overflow-x: auto; -webkit-overflow-scrolling: touch; scroll-padding: 2rem;"
                    default-data-filter="{{ $default_data_filter }}">

                    {{-- All --}}
                    <a href="#" id="all-projects-link" class="section-link-filter text-uppercase fw-600 pb-3"
                        style="white-space: nowrap; letter-spacing: 0.1rem; margin: 0 2rem;" data-slug=""
                        data-filter="*">
                        <span class="tag-display">All</span>
                    </a>

                    @php
                        $projectTypes = [
                            'retail' => ['filter' => '.--retail', 'display' => 'Retail'],
                            'office-spaces' => ['filter' => '.--office-spaces', 'display' => 'Office&nbsp;Spaces'],
                            'hospitality' => ['filter' => '.--hospitality', 'display' => 'Hospitality'],
                            'residential' => ['filter' => '.--residential', 'display' => 'Residential'],
                            'education' => ['filter' => '.--education', 'display' => 'Education'],
                            'public-spaces' => ['filter' => '.--public-spaces', 'display' => 'Public&nbsp;Spaces'],
                        ];
                    @endphp
                    @foreach ($projectTypes as $slug => $data)
                        @php
                            $hasProjects = false;
                            if (isset($projects)) {
                                foreach ($projects as $p) {
                                    if (
                                        isset($p->typeSlug) &&
                                        (trim($p->typeSlug) == trim(str_replace('.', '', $data['filter'])) ||
                                            trim($p->typeSlug) == trim($slug))
                                    ) {
                                        $hasProjects = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        @if ($hasProjects)
                            <span class="divider text-5b5b5b" style="font-size:1.3rem">|</span>
                            <a href="#" class="section-link-filter text-uppercase fw-600 pb-3"
                                style="white-space: nowrap; letter-spacing: 0.1rem; margin: 0 2rem;"
                                data-slug="{{ $slug }}" data-filter="{{ $data['filter'] }}">
                                <span class="tag-display">{!! $data['display'] !!}</span>
                            </a>
                        @endif
                    @endforeach

                </div>
            </section>

            {{-- Styling for Projects Grid and Typography --}}
            <style>
                /* Tag display in #links-section */
                .tag-display {
                    color: #fff;
                    transition: color 0.3s;
                    display: inline-block;
                }

                .section-link-filter {
                    text-decoration: none !important;
                    border: none !important;
                }

                .section-link-filter:hover {
                    text-decoration: none !important;
                }

                .section-link-filter.active .tag-display {
                    color: #EBC824 !important;
                }

                /* Responsive behavior for links-section */
                @media (min-width: 992px) {
                    #links-section {
                        justify-content: center;
                        overflow-x: visible;
                    }
                }

                @media (max-width: 991.98px) {
                    #links-section {
                        justify-content: flex-start;
                        padding-left: 1rem;
                        padding-right: 1rem;
                    }
                }

                /* Container Spacing */
                .project-page .projects-header-container,
                .project-page .projects-main-container {
                    max-width: 1440px;
                    margin: 0 auto;
                    padding-left: 48px;
                    padding-right: 48px;
                    box-sizing: border-box;
                }

                .project-page .section-title {
                    font-family: inherit !important;
                    font-size: clamp(28px, 3.5vw, 42px) !important;
                    font-weight: 700 !important;
                    color: #000000 !important;
                    line-height: 1.2 !important;
                    letter-spacing: -0.015em !important;
                    padding-top: 36px !important;
                    padding-bottom: 24px !important;
                    margin: 0 !important;
                    text-align: left !important;
                }

                .project-page .projects-main-container {
                    padding-top: 36px;
                    padding-bottom: 80px;
                }

                /* Masonry Grid */
                .project-page #project-tiles {
                    margin-left: -12px !important;
                    margin-right: -12px !important;
                    position: relative;
                }

                .project-page .masonry-sizer,
                .project-page .masonry-item.col-span-1 {
                    width: 33.333333% !important;
                }

                .project-page .masonry-item.col-span-2 {
                    width: 66.666667% !important;
                }

                .project-page .masonry-item.col-span-3 {
                    width: 100% !important;
                }

                .project-page .masonry-item {
                    float: left;
                    box-sizing: border-box !important;
                    padding-left: 12px !important;
                    padding-right: 12px !important;
                    margin-bottom: 38px !important;
                    display: flex;
                    flex-direction: column;
                }

                /* Image Link & Image */
                .project-page .project-img-link {
                    display: block;
                    width: 100%;
                    overflow: hidden;
                    text-decoration: none !important;
                }

                .project-page .project-img {
                    display: block;
                    width: 100%;
                    height: auto;
                    object-fit: cover;
                    margin: 0 !important;
                    padding: 0 !important;
                    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                }

                .project-page .masonry-item:hover .project-img {
                    transform: scale(1.02);
                }

                /* Project Title */
                .project-page .project-title-link {
                    display: block;
                    text-decoration: none !important;
                }

                .project-page .project-title {
                    font-family: inherit !important;
                    font-size: 15.5px !important;
                    font-weight: 700 !important;
                    color: #000000 !important;
                    line-height: 1.35 !important;
                    letter-spacing: -0.01em !important;
                    margin-top: 12px !important;
                    margin-bottom: 3px !important;
                    padding: 0 !important;
                    text-decoration: none !important;
                    transition: color 0.2s ease;
                }

                .project-page .masonry-item:hover .project-title {
                    color: #000000 !important;
                }

                /* Project Subtitle (Type) */
                .project-page .project-subtitle {
                    font-family: inherit !important;
                    font-size: 13px !important;
                    font-weight: 400 !important;
                    color: #666666 !important;
                    line-height: 1.35 !important;
                    letter-spacing: 0.01em !important;
                    margin-top: 0 !important;
                    margin-bottom: 0 !important;
                    padding: 0 !important;
                }

                /* Responsive Adjustments */
                @media (max-width: 1199px) {
                    .project-page .projects-header-container,
                    .project-page .projects-main-container {
                        padding-left: 32px;
                        padding-right: 32px;
                    }
                    .project-page .projects-main-container {
                        padding-top: 28px;
                        padding-bottom: 60px;
                    }
                }

                @media (max-width: 991px) and (min-width: 576px) {
                    .project-page .masonry-sizer,
                    .project-page .masonry-item.col-span-1 {
                        width: 50% !important;
                    }
                    .project-page .masonry-item.col-span-2,
                    .project-page .masonry-item.col-span-3 {
                        width: 100% !important;
                    }
                    .project-page .masonry-item {
                        padding-left: 10px !important;
                        padding-right: 10px !important;
                        margin-bottom: 30px !important;
                    }
                    .project-page #project-tiles {
                        margin-left: -10px !important;
                        margin-right: -10px !important;
                    }
                    .project-page .project-title {
                        font-size: 14.5px !important;
                        margin-top: 10px !important;
                    }
                    .project-page .project-subtitle {
                        font-size: 12.5px !important;
                    }
                }

                @media (max-width: 575px) {
                    .project-page .projects-header-container,
                    .project-page .projects-main-container {
                        padding-left: 16px;
                        padding-right: 16px;
                    }
                    .project-page .projects-main-container {
                        padding-top: 20px;
                        padding-bottom: 40px;
                    }
                    .project-page .section-title {
                        font-size: 26px !important;
                        padding-top: 24px !important;
                        padding-bottom: 16px !important;
                    }
                    .project-page .masonry-sizer,
                    .project-page .masonry-item.col-span-1,
                    .project-page .masonry-item.col-span-2,
                    .project-page .masonry-item.col-span-3 {
                        width: 100% !important;
                    }
                    .project-page .masonry-item {
                        padding-left: 0 !important;
                        padding-right: 0 !important;
                        margin-bottom: 26px !important;
                    }
                    .project-page #project-tiles {
                        margin-left: 0 !important;
                        margin-right: 0 !important;
                    }
                    .project-page .project-title {
                        font-size: 14px !important;
                        margin-top: 8px !important;
                    }
                    .project-page .project-subtitle {
                        font-size: 12px !important;
                    }
                }
            </style>

            <div class="container-fluid projects-main-container">
                <div class="row" id="project-tiles">
                    <div class="masonry-sizer"></div>
                    @foreach ($projects as $index => $project)
                        <div
                            class="masonry-item {{ $project->block_column == 2 ? 'col-span-2' : ($project->block_column == 3 ? 'col-span-3' : 'col-span-1') }} {{ $project->typeSlug }}">
                            <a href="{{ route('project-detail', $project->slug) }}" class="project-img-link">
                                <img src="{{ @$project->projectImages[0] ? asset('storage/uploads/projects/' . @$project->projectImages[0]->image) : asset('images/default.png') }}"
                                    onerror="if(!this.dataset.fallbackTried){this.dataset.fallbackTried='1';this.src='https://abbylighting.com/storage/uploads/projects/{{ @$project->projectImages[0]->image }}';}"
                                    onload="if(window.msnryInstance){window.msnryInstance.layout();}"
                                    alt="{{ $project->name }} - {{ $project->location }}"
                                    class="project-img">
                            </a>
                            <a href="{{ route('project-detail', $project->slug) }}" class="project-title-link">
                                <div class="project-title">{{ $project->name }} - {{ $project->location }}</div>
                            </a>
                            <div class="project-subtitle">{{ $project->type }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-5"> </div>
            </div>
        </div>

    {{-- Include Next.js Footer --}}
    @include('partials.common.footer-nextjs')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/js/app.js'])
    @vite(['resources/js/projects.js'])

    {{-- Footer scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal reset handlers
            const productEnquiryModal = document.getElementById("productEnquiryModal");
            const downloadCatalogModal = document.getElementById("downloadCatalogModal");

            if (productEnquiryModal) {
                productEnquiryModal.addEventListener("hide.bs.modal", () => {
                    const form = document.getElementById("product_inquiry");
                    if (form) form.reset();
                    const msg = document.getElementById("inquiry_msg");
                    if (msg) msg.classList.add("d-none");
                });
            }

            if (downloadCatalogModal) {
                downloadCatalogModal.addEventListener("hide.bs.modal", () => {
                    const form = document.getElementById("downloadCatalogModalForm");
                    if (form) form.reset();
                });
            }
        });

        // Product Inquiry Form
        window.submitInquiry = function() {
            document.getElementById("productEnquiryFormSubmit").click();

            const formElement = document.getElementById("product_inquiry");
            const enquiryFullName = document.getElementById("enquiryFullName");
            const enquiryEmail = document.getElementById("enquiryEmail");
            const enquiryCompany = document.getElementById("enquiryCompany");
            const formData = new FormData(formElement);

            if (!enquiryFullName.checkValidity() || !enquiryEmail.checkValidity() ||
                enquiryFullName.value == null || enquiryFullName.value == '' ||
                enquiryEmail.value == null || enquiryEmail.value == '' ||
                enquiryCompany.value == null || enquiryCompany.value == '') {
                return;
            }

            document.getElementById('enquiryInvalidForm').value = "Valid";
            document.getElementById("submitted_msg").classList.remove("d-none");
            document.getElementById("submit_button").classList.add("d-none");

            fetch('/product-inquiry', {
                    method: 'POST',
                    headers: {
                        '_token': "{{ csrf_token() }}",
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        document.getElementById("submitted_msg").classList.add("d-none");
                        document.getElementById("submit_button").classList.remove("d-none");
                        throw new Error('Network response was not ok');
                    }
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                    return response.json();
                })
                .then(responseData => {
                    if (responseData.success) {
                        document.getElementById("inquiry_msg").classList.remove("d-none");
                    }
                    document.getElementById("product_inquiry").reset();
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                })
                .catch(error => {
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                    console.error('Error:', error);
                });
            return false;
        }

        // Google reCAPTCHA Forms Handler
        const googleCaptchaForms = (function() {
            const captchaIndex = {
                contact_form: -1,
                product_inquiry: -1,
                downloadCatalogModalForm: -1,
            }
            let currentIndex = -1;

            const init = function() {
                loadCaptcha('recaptchaWidgetId');
                loadCaptcha('productInquiriesWidget');
                loadCaptcha('productCatalogWidget');
            }

            const captchaVerified = function() {
                document.querySelectorAll('.captcha_error').forEach(element => {
                    element.classList.add('d-none');
                    element.classList.remove('d-block');
                });
            }

            const captchaExpired = function() {
                grecaptcha.reset();
            }

            const loadCaptcha = function(recaptchaWidgetId) {
                if (!document.getElementById(recaptchaWidgetId)) {
                    return;
                }
                const formElement = document.getElementById(recaptchaWidgetId).closest('form');
                formElement.addEventListener('submit', function(event) {
                    event.preventDefault();
                    return false;
                });
                grecaptcha.render(recaptchaWidgetId, {
                    'sitekey': "{{ config('google_captcha.site_key', env('GOOGLE_CAPTCHA_SITE_KEY')) }}",
                    'callback': captchaVerified,
                    'expired-callback': captchaExpired
                });

                captchaIndex[formElement.id] = ++currentIndex;
            }

            const submit = function(formId) {
                const index = captchaIndex[formId];
                const res = true;

                if (!res) {
                    document.querySelectorAll('.captcha_error').forEach(element => {
                        element.classList.add('d-block');
                        element.classList.remove('d-none');
                    });
                } else {
                    submitForm(formId);
                }
            }

            const submitForm = function(formId) {
                if (formId === 'product_inquiry') {
                    submitInquiry();
                    return;
                }
                if (formId === 'downloadCatalogModalForm') {
                    document.getElementById('catalogFormSubmit').click();
                    return;
                }
                if (formId === 'contact_form') {
                    document.getElementById('contact_form_submit').click();
                    return;
                }
            }

            return {
                init: init,
                submit: submit,
            }
        })();

        // reCAPTCHA Callback
        function recaptchaOnloadCallback() {
            googleCaptchaForms.init();
        }

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
