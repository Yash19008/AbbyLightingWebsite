<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Abby Lighting</title>
    <meta name="description" content="With three generations of expertise, we've emerged as one of India's top LED lighting players. Explore our Studio, our Machines, and our Quality Commitment.">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/scss/app.scss', 'resources/scss/company.scss'])
    
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

    {{-- Company Page Content --}}
<div class="container-fluid p-0 mt-5">
    <div class="row">
        <div class="col-12">
            <img src="{{asset('img/about-us/Artboard1.png')}}" alt="" class="img-fluid w-100">
        </div>
    </div>
</div>
<section>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title custom-title-color custom-underline">
                        Our History
                    </div>
                    <p class="section-text stupid-text-1">
                        Designing and producing lighting systems has been in our veins for the third generation running. As the lighting industry has evolved, so has Abby - to emerge as one of India’s top manufacturers of LED lighting fixtures. Today, with our 85,000+ sq ft design and manufacturing facility, we provide tailored architectural lighting solutions across sectors.
                    </p>

                </div>
            </div>
            <div class="col-12 col-lg-6 p-0">
                <a href="#" style="position: relative">
                    <img src="{{asset('img/about-us/Artboard2.png')}}" alt="" class="img-fluid">
                </a>
            </div>

        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-6 p-0 order-2 order-lg-1">
                <a href="#" style="position: relative">
                    <img src="{{asset('img/about-us/Artboard3.png')}}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center order-1 order-lg-2">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title custom-title-color custom-underline">
                        The Abby Studio
                    </div>
                    <p class="section-text stupid-text-1">
                        Established in 2018, Abby Studio is a one-of-a-kind experience centre and office space that doubles up as an event venue after hours. The lighting in the entire Studio is configured wirelessly, to serve as a tangible showcase of how smart lighting can transform spaces.
                    </p>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title custom-title-color custom-underline">
                        The Design Lab
                    </div>
                    <p class="section-text stupid-text-1">
                        Innovation is the essence of our heritage. We make luminaires that add character and ambience to spaces. Whatever the individual style, we have something for everyone. Being in full control of the manufacturing process end-to-end, we’re able to carry out customised fixtures and promptly respond to changes in designs, wherever required. </p>

                </div>
            </div>
            <div class="col-12 col-lg-6 p-0">
                <a href="#" style="position: relative">
                    <img src="{{asset('img/about-us/Artboard4.png')}}" alt="" class="img-fluid">
                </a>
            </div>

        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-6 p-0 order-2 order-lg-1">
                <a href="#" style="position: relative">
                    <img src="{{asset('img/about-us/Artboard5.png')}}" alt="" class="img-fluid">
                </a>
            </div>
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center order-1 order-lg-2">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title custom-title-color custom-underline">
                        The Quality
                    </div>
                    <p class="section-text stupid-text-1">
                        Our state-of-the-art facility is equipped with the finest of machines across the entire manufacturing process (SMT Lines, Pressure Die Casting, Laser Cutting, Powder Coating, and various other CNC operations). Supported by our ISO 9001:2015 certification, we uphold uncompromising standards of quality at every step, with a highly skilled workforce to bolster us. </p>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-6 px-3 ps-lg-5 pe-lg-0 align-self-center">
                <div class="p-0 p-md-3 px-lg-0 px-xl-5">
                    <div class="section-title custom-title-color custom-underline">
                        Sustainability
                    </div>
                    <p class="section-text stupid-text-1">
                        While we prioritise design and customer satisfaction, we place equal emphasis on sustainability. Recognizing the urgent need to protect our environment, all our machinery is energy-efficient and undergoes regular checks. Our internal operations are continuously monitored and optimised to minimise environmental impact. As pioneers in LED manufacturing, we take pride in converting many customers to LED lighting, thereby reducing the overall carbon footprint. </p>

                </div>
            </div>
            <div class="col-12 col-lg-6 p-0">
                <a href="#" style="position: relative">
                    <img src="{{asset('img/about-us/Artboard6.png')}}" alt="" class="img-fluid">
                </a>
            </div>

        </div>
    </div>

</section>
<div class="container-fluid p-0 mt-5">
    <div class="row">
        <div class="col-12">
            <img src="{{asset('img/about-us/Artboard7.png')}}" alt="" class="img-fluid w-100">
        </div>
    </div>
</div>

<div class="mb-5">&nbsp;</div>

{{-- Include Next.js Footer --}}
@include('partials.common.footer-nextjs')

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Footer scripts - Combined to avoid window.onload conflicts --}}
<script>
// Wait for DOM to be ready
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