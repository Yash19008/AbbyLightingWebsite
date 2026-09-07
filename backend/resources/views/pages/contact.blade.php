<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Abby Lighting</title>
    <meta name="description" content="Fill out this form, or reach out to us at frontdesk@abbylighting.com or on +91 9833645212">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    
    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaOnloadCallback&render=explicit" async defer></script>
    
    @vite(['resources/scss/app.scss', 'resources/scss/contact.scss'])
    
    <style>
        /* Add padding to body to account for fixed Next.js header */
        body {
            padding-top: 95px;
            background: #f3f3f1;
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
            <div class="col-12 col-lg-6 col-md-12 p-0">
                @if(App\Models\Setting::getValue('CONTACT_SIDE_IMAGE', false))
                <img src="{{App\Models\Setting::getValue('CONTACT_SIDE_IMAGE')}}" alt="" class="img-fluid">
                @else
                <img src="{{asset('storage/uploads/banners/banner_image-contacts.jpg')}}" alt="" class="img-fluid">
                @endif
            </div>

            <div class="col-12 col-lg-6 col-md-12 md-5 p-3 p-md-5 contact-form">
                <div class="p-0 p-xl-3">
                    <h3 class="contact-section2-title">Say hi, & we will get back to you shortly!</h3>
                    <p class="contact-section-text">Send us an email with your question or concerns. If you would like
                        to discuss your current or potential project with us, please complete the enquiry form provided
                        or contact the Studio directly at +91 9833645212.</p>
                </div>
                <div class="col-12 p-3 form-col">
                    <form id="contact_form" action="{{ route('mail.contact.send') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact1">
                                <input type="text" id="fname" name="full_name" placeholder="Full Name*" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact2">
                                <input type="text" id="cname" name="company" placeholder="Company Name*" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact">
                                <input type="email" id="email" name="email" placeholder="Email*" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact">
                                <input type="text" id="phone" name="phone" placeholder="Phone">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact">
                                <input type="text" id="position" name="position" placeholder="Position">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-12 contact">
                                <input type="text" id="cwebsite" name="website" placeholder="Company Website">
                            </div>
                            <div class="col-12 contact-message">
                                <input type="text" id="message" name="i_message" placeholder="Message">
                            </div>
                        </div>
                        @if (\Session::has('success'))
                        <div class="">
                            {!! \Session::get('success') !!}
                        </div>
                        @endif

                        <div class="col-12 mt-3 submit-div">
                            <div class="col-12 my-5 job-description">
                                <div class="g-recaptcha" id="recaptchaWidgetId">
                                </div>
                                <small class="d-none mb-3 captcha_error" id="captcha_error">Please verify
                                    captcha</small>
                                <button class="d-none section-detail-text" id="contact_form_submit"
                                    type="submit">Submit</button>
                                <a href="javscript:void()"
                                    onclick="event.preventDefault();googleCaptchaForms.submit('contact_form')"
                                    class="section-detail-text">Submit Form</a>
                                <img src="img/icons/right-arrow.svg" width="10">
                            </div>
                        </div>

                        @if (\Session::has('success'))
                        <div class="">
                            {!! \Session::get('success') !!}

                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container-fluid">
        <div class="row">


            <div class="col-12 col-lg-6 col-md-6 md-5 section2 p-3 p-md-5">
                <h3 class="contact-section2-title">Abby Lighting & Switchgear Ltd.</h3>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6 ps-0">
                            <p class="contact-section2-subtitle mb-0">Corporate Office & Studio:</p>
                            <p class="contact-section2-text">802 A, Fortune Terraces, New Link Road, Opp City Mall,
                                Andheri
                                West Mumbai- 400053, India.</p>
                            {{-- <div class="mt-4">
                                <a href="#" class="section-detail-text1">Get Directions</a>
                                <img src="img/icons/right-arrow.svg" alt="" width=10>
                            </div> --}}
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 ps-0">
                            <p class="contact-section2-subtitle mb-0">Factory:</p>
                            <p class="contact-section2-text">70, Genesis Industrial Complex, Phase 1, Kolgaon, Palghar
                                Boisar Road, Palghar 401404, Maharashtra</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6 col-md-6 ps-0">
                            <p class="contact-section2-subtitle mb-0">Technical Support</p>
                            <p class="contact-section2-text">frontdesk@abbylighting.com<br>+91 9833 645 212</p>
                            {{-- <div class="mt-4">
                                <a href="#" class="section-detail-text1">Get Directions</a>
                                <img src="img/icons/right-arrow.svg" alt="" width=10>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6  map-div">
                <div id="map-container-google-1" class="z-depth-1-half map-container" style="height: 440px">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d389515.73676372296!2d72.58127254572507!3d19.11304977269108!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b702244d3419%3A0x8298564494559dae!2sAbby%20Lighting%20%26%20Switchgear%20Ltd!5e0!3m2!1sen!2sin!4v1696337552026!5m2!1sen!2sin"
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
                <p class="section-text2">+91 9833645212</p>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                <h5 class="section3-text1">Overseas</h5>
                <p class="section-text2">info@abbylighting.com</p>
                <p class="section-text2">+91 9833645212</p>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                <h5 class="section3-text1">Support</h5>
                <p class="section-text2">helpdesk@abbylighting.com</p>
                <p class="section-text2">+91 9833645212</p>
            </div>
        </div>
    </div>

</section> --}}

<section>
    <div class="container-fluid section3 p-5">
        <div class="row">
            <div class="col-12 col-lg-4 col-md-3 mb-4 mb-md-0">
                <a href="#" class="section3-detail-text" data-bs-toggle="modal"
                    data-bs-target="#downloadCatalogModal">DOWNLOAD CATALOGUE</a>
                <img src="img/icons/right-arrow.svg" alt="" width=10>
            </div>
            <div class="col-12 col-lg-4 col-md-3 mb-4 mb-md-0">
                <a href="https://www.youtube.com/watch?v=SPgaE-3jxEY" class="section3-detail-text"
                    target="_blank">FACTORY VIDEO</a>
                <img src="img/icons/right-arrow.svg" alt="" width=10>
            </div>
        </div>
    </div>
</section>

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
