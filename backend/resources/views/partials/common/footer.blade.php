@include('partials.download-catalog')
@include('partials.product-enquiry')
<footer>
    <div class="container py-4 pb-4">
        <div class="row">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <hr>
                        <ul>
                            <li>
                                <a href="{{ route('page.company') }}">About us</a>
                            </li>
                            <li>
                                <a href="{{ route('sub-tags') }}">Products</a>
                            </li>
                            <li>
                                <a href="{{ route('page.projects') }}">Projects</a>
                            </li>
                            <li>
                                <a href="{{ route('page.clients') }}">Clients</a>
                            </li>
                            <li>
                                <a href="{{ route('page.contact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-4">
                        <hr>
                        <ul>
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#productEnquiryModal">Product
                                    Enquiries</a>
                            </li>
                            <li>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#downloadCatalogModal">Product
                                    Catalog</a>
                            </li>
                            <li>
                                <a href="{{ route('page.abby-smart') }}">Smart Lighting</a>
                            </li>
                            <li>
                                <a href="{{ route('page.career') }}">Careers</a>

                            </li>
                            <li>
                                <a href="{{ route('page.fair-events') }}">Fairs & Events</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-4">
                        <hr>
                        <ul>
                            <li>
                                <a href="{{ route('page.privacy-policy') }}">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="{{ route('page.terms-and-conditions') }}">Terms of Use </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div id="subscribe-section" class="col-12 col-lg-4 col-xl-6">
                <hr>
                <p class="subscribe font-semibold p-0 m-0">Subscribe and<br>get inspired.</p>
                <div class="row email-section">
                    <div class="col-12 col-md-7 input-section">
                        <p class="input_title">Name</p>
                        <input id="subscribe-name" class="{{ isset($theme) && $theme === 'light' ? 'dark-input' : '' }}" type="name"
                            aria-label="Name" autocomplete="one-time-code">
                    </div>
                    <div class="col-12 col-md-7 mt-3 input-section">
                        <p class="input_title">Email</p>
                        <input id="subscribe-email" class="{{ isset($theme) && $theme === 'light' ? 'dark-input' : '' }}" type="email"
                            aria-label="Email" autocomplete="one-time-code">
                        <p id="subscribe-input-info" class="font-semibold ps-2 m-0 mt-2">Please enter both name and
                            email to sign up.</p>
                    </div>
                    <div id="input-btn-section" class="col-12 col-md-5 pe-0 mt-3">
                        <p class="signup font-semibold p-0 m-0" onclick="signup()">Sign me up</p>
                    </div>

                    <div id="all-set-section" class="col-12" style="display: none;">
                        <p class="all-set font-semibold p-0 m-0">Almost done! Check your inbox for a verification email,
                            then sit back - our newsletter will be on its way.</p>
                    </div>
                </div>

                <ul class="social-links float-start mt-4">
                    <li>
                        <a href="https://www.instagram.com/abbylighting/" target="_blank">
                            <img src="{{ asset('img/social/instagram.svg') }}" alt="Abby Lighting Instagram">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/company/abby-lighting/" target="_blank">
                            <img src="{{ asset('img/social/linkedin-in.svg') }}" alt="Abby Lighting LinkedIn">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/abbylighting/" target="_blank">
                            <img src="{{ asset('img/social/facebook-f.svg') }}" alt="Abby Lighting Faceboook">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/@abby-lighting" target="_blank">
                            <img src="{{ asset('img/social/youtube.svg') }}" alt="Abby Lighting Youtube">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row py-1 py-md-5">
            <div class="copyright col text-center text-uppercase text-white">
                &copy; Abby Lighting All Rights Reserved
            </div>
        </div>
    </div>
</footer>
@push('js')
<script>
    window.onload = function() {

        document.getElementById("productEnquiryModal").addEventListener("hide.bs.modal",() => {
            document.getElementById("product_inquiry").reset();
            document.getElementById("inquiry_msg").classList.add("d-none");
        });

        document.getElementById("downloadCatalogModal").addEventListener("hide.bs.modal",() => {
            document.getElementById("downloadCatalogModalForm").reset();
        });
    }

    window.submitInquiry = function() {
            document.getElementById("productEnquiryFormSubmit").click();

            const formElement = document.getElementById("product_inquiry");
            const enquiryFullName = document.getElementById("enquiryFullName");
            const enquiryEmail = document.getElementById("enquiryEmail");
            const enquiryCompany = document.getElementById("enquiryCompany");
            const formData = new FormData(formElement);


            if(!enquiryFullName.checkValidity() || !enquiryEmail.checkValidity() ||enquiryFullName.value == null || enquiryFullName.value == '' || enquiryEmail.value == null || enquiryEmail.value == '' || enquiryCompany.value == null || enquiryCompany.value == ''){
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
                    body: formData // Convert the data object to a JSON string
                })
                .then(response => {
                    if (!response.ok) {
                        document.getElementById("submitted_msg").classList.add("d-none");
                        document.getElementById("submit_button").classList.remove("d-none");
                        throw new Error('Network response was not ok');
                    }
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                    return response.json(); // Parse the response as JSON
                })
                .then(responseData => {
                    if(responseData.success)
                    document.getElementById("inquiry_msg").classList.remove("d-none");
                    document.getElementById("product_inquiry").reset();
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                })
                .catch(error => {
                    document.getElementById("submitted_msg").classList.add("d-none");
                    document.getElementById("submit_button").classList.remove("d-none");
                    console.error('Error:', error);
                })
            return false;
        }


</script>

<script>
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
                });
                document.querySelectorAll('.captcha_error').forEach(element => {
                    element.classList.remove('d-block');
                });
            }

            const captchaExpired = function() {
                grecaptcha.reset();
            }

            const loadCaptcha = function(recaptchaWidgetId) {
                // check if element named recaptchaWidgetId exists
                if (!document.getElementById(recaptchaWidgetId)) {
                    return;
                }
                // Find parent form element
                const formElement = document.getElementById(recaptchaWidgetId).closest('form');
                // Add form submit event listener
                formElement.addEventListener('submit', function(event) {
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
                const res = true; //grecaptcha.getResponse(index);

                if (!res) {
                    document.querySelectorAll('.captcha_error').forEach(element => {
                        element.classList.add('d-block');
                    });
                    document.querySelectorAll('.captcha_error').forEach(element => {
                        element.classList.remove('d-none');
                    });
                } else {
                    submitForm(formId);
                    // document.getElementById(formId).submit();
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

        function recaptchaOnloadCallback() {
            googleCaptchaForms.init();
        }
        
        function signup() {

            const name = document.getElementById("subscribe-name").value;
            const email = document.getElementById("subscribe-email").value;
            document.getElementById("subscribe-input-info").style.display = 'none';

            if (email && validateEmail(email) && name) {
                fetch("/subscribe-newsletter?email="+ email + '&name=' + name)
                .then(data => {
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
                // document.getElementById("subscribe-email").style.border = "1px solid rgba(255,0,0,0.7)";
                document.getElementById("subscribe-input-info").style.display = 'block';
            }
        }

        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

</script>

@endpush