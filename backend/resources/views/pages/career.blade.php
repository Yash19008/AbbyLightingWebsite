<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | Abby Lighting</title>
    <meta name="description" content="If you love light, become one of us! Join Abby Lighting and develop your career in the lighting sector.">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/scss/app.scss', 'resources/scss/careers.scss'])
    
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

    {{-- Career Page Content --}}

<section class="main-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-6 p-0">
                @if(App\Models\Setting::getValue('CAREER_BANNER_IMAGE', false))
                    <img src="{{App\Models\Setting::getValue('CAREER_BANNER_IMAGE')}}" alt="" class="img-fluid">
                @else
                    <img src="{{asset('storage/uploads/banners/banner_image-career.jpg')}}" alt="" class="img-fluid">
                @endif
            </div>
            <div class="col-12 col-lg-6 md-5 p-3 p-md-5">
                <div class="p-0 p-xl-5">
                    <h3 class="career-section-title pt-5">Work with us</h3>
                    <p class="section-career-text">
                        If you love light, Become one of us!
                    </p>
                    <p class="section-career-text">
                        Illumination is a passion that is shared by all of us who work at Abby Lighting, dedication that is much more than just a way of earning a living. We are always on the lookout for people who will help us to expand this philosophy, professionals who want to develop their career in the lighting sector, driven by an urge to succeed.
                    </p>
                    <p class="section-career-text">Send in your resumes to <span
                            class="career-email bolder"><a href="mailto:careers@abbylighting.com" class="career-email bolder" target="_blank" style="text-decoration:none">careers@abbylighting.com</a></span></p>

                </div>
            </div>
        </div>
    </div>
</section>

<div class="container mt-5">
    <h4 class="career-job-title">JOB OPENINGS</h4>
    <div class="row mb-5">
        @forelse ($openings as $opening)
        <div class="col-12 col-lg-6">
            <div class="section-grayed p-3 me-3">
                <h4 class="career-head-title">{{ $opening->title }} </h4>
                <p class="career-head-location">
                    Job Location: {{ $opening->location }}<br>
                    {{ $opening->short_description }}
                </p>
                <div class="col-12 my-5">
                    <a href="#" class="section-detail-text more-details" data-bs-toggle="modal" data-bs-target="#jobDescriptionModal">More Details</a>
                    <i aria-hidden="true" class="fas fa-angle-right"></i>
                </div>
                <div class="d-none long-description">
                    {!!$opening->description!!}
                </div>
            </div>
        </div>
        @empty
            <p class="section-text text-center">
                Sorry, currently no openings are available.
            </p>
        @endforelse
    </div>
</div>
@include('partials.job-description')

{{-- Include Next.js Footer --}}
@include('partials.common.footer-nextjs')

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Page specific scripts --}}
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

    // Job description modal handler
    document.querySelectorAll(".more-details").forEach(function(element) {
        element.addEventListener('click', function() {
            let desc = this.parentElement.parentElement.querySelector(".long-description").innerHTML;
            document.querySelector("#jobDescriptionModal").querySelector(".modal-body").innerHTML = desc;
        });
    });
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