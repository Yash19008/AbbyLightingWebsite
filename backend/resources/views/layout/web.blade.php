<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="p:domain_verify" content="89c6a25a6043881e9264644ec567c5d1"/>
    <meta name="google-site-verification" content="YBiy8ERSg3gepd1XHH2BmnRKXvwzFY9eigmk5lX1Chg" />

    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">

    <title>@yield('title', "India's leading manufacturer of LED Lighting Solutions | Abby Lighting")</title>
    <meta name="description" content="@yield('description', "Discover premium LED lighting solutions from Abby Lighting, India's leading manufacturer with complete in-house production.")">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/brands.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaOnloadCallback&render=explicit" async defer>
    </script>

    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @vite(['resources/scss/app.scss'])

    @stack('css')
    
    <style>
        /* Add padding to body to account for fixed Next.js header */
        body {
            padding-top: 95px;
        }
    </style>
</head>
<body class="{{ Request::is('/') ? 'home-page' : '' }}">
    {{-- Next.js Style Header --}}
    @include('partials.common.header-nextjs')
    
    @yield('page-content')
    
    {{-- Next.js Style Footer --}}
    @include('partials.common.footer-nextjs')
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

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

        // Search icon mobile (if exists)
        const searchIconMobile = document.getElementById("search-icon-mobile");
        if (searchIconMobile) {
            searchIconMobile.addEventListener("click", function() {
                const searchInput = document.getElementById("search-input-mobile");
                if (searchInput) {
                    const display = searchInput.style.display;
                    searchInput.style.display = display == 'block' ? 'none' : 'block';
                }
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

    @stack('js')
</body>
</html>
