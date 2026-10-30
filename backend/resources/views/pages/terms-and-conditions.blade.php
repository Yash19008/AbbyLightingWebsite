<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta_title ?? 'Terms & Conditions | Abby Lighting' }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Terms and conditions for Abby Lighting products and services.' }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Libre+Baskerville:ital@1&family=Manrope:wght@700&family=Poppins:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    {{-- Bootstrap & Existing CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/scss/terms.scss'])

        <style>
        /* Add padding to body to account for fixed Next.js header */
        body {
            padding-top: 80px;
        }
        .policy-page-content {
            max-width: 1400px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
            color: black;
        }
        .policy-page-content h1, .policy-page-content .header {
            color: black;
            font-weight: 500;
            font-size: 39px;
            line-height: 1.25;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 1.5rem;
        }
        .policy-page-content h2, .policy-page-content h3, .policy-page-content .sub-header {
            color: black;
            font-size: 26px;
            line-height: 1.35;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }
        .policy-page-content h4, .policy-page-content h5 {
            color: black;
            font-size: 18px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .policy-page-content p, .policy-page-content .text-content {
            color: black;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            line-height: 1.65;
            margin-top: 1rem !important;
            margin-bottom: 1rem;
            font-weight: 300;
        }
        .policy-page-content ul, .policy-page-content ol {
            color: black;
            padding-left: 2rem;
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .policy-page-content li {
            color: black;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            line-height: 1.65;
            margin-bottom: 0.5rem;
            font-weight: 300;
        }
        .policy-page-content strong, .policy-page-content b {
            font-weight: 600;
        }
        .policy-page-content a {
            color: black;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    {{-- Next.js Style Header --}}
    @include('partials.common.header-nextjs')

    {{-- Header Banner --}}
    @if(!empty($banner_image))
    <div class="container-fluid p-0">
        <img src="{{ asset($banner_image) }}" alt="Terms & Conditions Banner" class="img-fluid w-100">
    </div>
    @endif

    {{-- Dynamic Terms & Conditions Page Content --}}
    <div class="container-fluid p-5 policy-page-content">
        {!! $content !!}
    </div>

    {{-- Include Next.js Footer --}}
    @include('partials.common.footer-nextjs')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

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
