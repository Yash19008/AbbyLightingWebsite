<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects across industries | Abby Lighting</title>
    <meta name="description" content="Lighting solutions for every project - from high-end Retail and Hospitality to Residences and Office spaces">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/scss/app.scss', 'resources/scss/projects.scss'])
    
    <style>
        /* Add padding to body to account for fixed Next.js header */
        body {
            padding-top: 95px;
        }
    </style>
</head>
<body>
    {{-- Next.js Style Header --}}
    @include('partials.common.header-nextjs')

    {{-- Projects Page Content --}}
<div class="px-lg-5 project-page">
    <div class="container-fluid">
        <h1 class="section-title">{{ @$title }}</h1>

        {{-- Sticky dark bar (same as Products) --}}
        <section class="container-fluid py-3 p-0 bg-dark text-white sticky-top pb-0">
            <div id="links-section"
                class="links d-flex flex-nowrap"
                style="overflow-x: auto; -webkit-overflow-scrolling: touch; scroll-padding: 2rem;"
                default-data-filter="{{ $default_data_filter }}">

                        {{-- All --}}
                        <a href="#"
                        id="all-projects-link"
                        class="section-link-filter text-uppercase fw-600 pb-3"
                        style="white-space: nowrap; letter-spacing: 0.1rem; margin: 0 2rem;"
                        data-slug=""
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
                        @foreach($projectTypes as $slug => $data)
                            @php
                                $hasProjects = false;
                                if(isset($projects)) {
                                    foreach($projects as $p) {
                                        if(isset($p->typeSlug) && (trim($p->typeSlug) == trim(str_replace('.', '', $data['filter'])) || trim($p->typeSlug) == trim($slug))) {
                                            $hasProjects = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if($hasProjects)
                            <span class="divider text-5b5b5b" style="font-size:1.3rem">|</span>
                            <a href="#"
                            class="section-link-filter text-uppercase fw-600 pb-3"
                            style="white-space: nowrap; letter-spacing: 0.1rem; margin: 0 2rem;"
                            data-slug="{{ $slug }}"
                            data-filter="{{ $data['filter'] }}">
                                <span class="tag-display">{!! $data['display'] !!}</span>
                            </a>
                            @endif
                        @endforeach            
                           
                </div>
            </section> 

            {{-- Styling identical to Products --}}
            <style>
                .tag-display {
                    color: #fff;
                    transition: color 0.3s;
                    display: inline-block;
                }
                .section-link-filter { text-decoration: none !important; border: none !important; }
                .section-link-filter:hover { text-decoration: none !important; }
                .section-link-filter.active .tag-display { color: #EBC824 !important; }

                /* Responsive behavior */
                @media (min-width: 992px) { /* lg and up */
                    #links-section {
                        justify-content: center;  /* Center on desktop */
                        overflow-x: visible;      /* Disable scroll on desktop */
                    }
                }

                @media (max-width: 991.98px) { /* below lg */
                    #links-section {
                        justify-content: flex-start; /* Left-align so scroll works */
                        padding-left: 1rem;          /* small breathing room for ALL */
                        padding-right: 1rem;
                    }
                }
            </style>


        <div class="row mt-5" id="project-tiles">
            <div class="masonry-sizer col-4"></div>
            @php $currentIndex = 0; @endphp
            @foreach($projects as $index => $project)

            @php $currentIndex = $currentIndex + 1; @endphp
            {{-- @if($currentIndex == 1) --}}
            <div class="masonry-item col-12 col-xl-{{ $project->block_column*4 }} mt-4 {{$project->typeSlug}}">
                <a href="{{route('project-detail', $project->slug)}}">
                    <img src="{{ @$project->projectImages[0] ? asset('storage/uploads/projects/'.@$project->projectImages[0]->image) : asset('images/default.png') }}"
                        class="img-fluid w-100 mt-3 mb-3">
                </a>
                <a href="{{route('project-detail', $project->slug)}}"
                    class="project-title text-decoration-none fw-500 pt-1">
                    {{$project->name}} - {{$project->location}}
                </a>
                <div class="project-subtitle fw-500">{{$project->type}}</div>
            </div>
           {{--  @endif --}}
           
            @endforeach
        </div>
        <div class="row mt-5"> </div>
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

    if(!enquiryFullName.checkValidity() || !enquiryEmail.checkValidity() || 
       enquiryFullName.value == null || enquiryFullName.value == '' || 
       enquiryEmail.value == null || enquiryEmail.value == '' || 
       enquiryCompany.value == null || enquiryCompany.value == ''){
        return;
    }

    document.getElementById('enquiryInvalidForm').value = "Valid";
    document.getElementById("submitted_msg").classList.remove("d-none");
    document.getElementById("submit_button").classList.add("d-none");
    
    fetch('/product-inquiry', {
        method: 'POST',
        headers: {
            '_token' : "{{ csrf_token() }}",
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
        if(responseData.success) {
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
const googleCaptchaForms = (function () {
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
            'sitekey': '6Lf9pMgpAAAAALMPOL0I0ZDi9he4KKDCRcepVuBY',
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

    const submitForm = function (formId) {
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
        fetch("/subscribe-newsletter?email="+ email + '&name=' + name)
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
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-48B9N75V8M');
</script>

</body>
</html>