<link rel="stylesheet" href="{{ asset('css/shared-inquiry.css') }}">

<div class="figma-enquiry" id="figmaInquiryModal" style="display: none;" role="presentation">
    <div class="figma-enquiry-card" id="figmaEnquiryCard" role="dialog" aria-modal="true" aria-labelledby="enquiryModalTitle">
        <button type="button" class="figma-enquiry-close" aria-label="Close enquiry form" onclick="closeInquiryModal()">
            &times;
        </button>

        {{-- Thank You Card State (Hidden by default) --}}
        <div id="figmaThankYouCard" class="figma-enquiry-thankyou-card" style="display: none;">
            <div class="figma-enquiry-thankyou-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <h2 id="enquiryThankyouTitle" class="figma-enquiry-thankyou-title">
                Thank You for Your Interest
            </h2>
            <p class="figma-enquiry-thankyou-desc">
                Thank you for reaching out. Our team will get back to you shortly.
            </p>
            <button type="button" class="figma-enquiry-thankyou-btn" onclick="closeInquiryModal()">
                CONTINUE
            </button>
        </div>

        {{-- Form Content State --}}
        <div id="figmaFormContent">
            <h2 id="enquiryModalTitle">Get in Touch</h2>
            <p class="figma-enquiry-lead">We'd love to hear from you.</p>

            <form id="figmaEnquiryForm" class="figma-enquiry-form" novalidate>
                @csrf
                <input type="hidden" name="type" value="general">
                <div class="figma-enquiry-grid">
                    {{-- Name --}}
                    <div class="figma-enquiry-field full" id="field-name">
                        <label for="inquiry-name">Your name*</label>
                        <input id="inquiry-name" name="name" type="text" autocomplete="name" required>
                        <div class="field-error" id="error-name" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>

                    {{-- Phone with Country Code --}}
                    <div class="figma-enquiry-field phone-field-wrap" id="field-phone">
                        <div class="phone-country-select-container" id="phoneCountryContainer">
                            <button type="button" class="phone-country-trigger" id="phoneCountryTrigger" aria-label="Select Country Code" aria-expanded="false">
                                <img src="https://flagcdn.com/w40/in.png" alt="India" class="country-flag-img" id="selectedCountryFlag">
                                <svg class="country-chevron-icon" id="countryChevron" width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="#555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 1L5 5L9 1" />
                                </svg>
                            </button>
                            <div class="phone-country-dropdown" id="phoneCountryDropdown" style="display: none;">
                                <div class="phone-country-search-wrap">
                                    <input type="text" class="phone-country-search-input" id="phoneCountrySearch" placeholder="Search country or code...">
                                </div>
                                <div class="phone-country-list" id="phoneCountryList">
                                    {{-- Populated by JS --}}
                                </div>
                            </div>
                        </div>
                        <label for="inquiry-phone">Enter number</label>
                        <input id="inquiry-phone" name="phone" type="tel" autocomplete="tel" required>
                        <div class="field-error" id="error-phone" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px; width: 100%; flex-basis: 100%;"></div>
                    </div>

                    {{-- Email --}}
                    <div class="figma-enquiry-field" id="field-email">
                        <label for="inquiry-email">Enter Email id*</label>
                        <input id="inquiry-email" name="email" type="email" autocomplete="email" required>
                        <div class="field-error" id="error-email" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>

                    {{-- Select City --}}
                    <div class="figma-enquiry-field" id="field-city">
                        <div class="download-select-wrap" id="citySelectWrap">
                            <button type="button" class="download-select-trigger" id="citySelectTrigger">
                                <span class="download-select-label is-placeholder" id="citySelectLabel">Select City*</span>
                                <svg class="download-select-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="#555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 1L5 5L9 1" />
                                </svg>
                            </button>
                            <input type="hidden" name="city" id="inquiry-city" value="" required>
                            <div class="download-select-menu" id="citySelectMenu" style="display: none;">
                                @php
                                    $inquiryCities = [
                                        'Agra', 'Ahmedabad', 'Amritsar', 'Bhopal', 'Bhubaneswar',
                                        'Chandigarh', 'Chennai', 'Coimbatore', 'Dehradun', 'Delhi',
                                        'Faridabad', 'Ghaziabad', 'Gurugram', 'Guwahati', 'Hyderabad',
                                        'Indore', 'Jaipur', 'Jalandhar', 'Jamshedpur', 'Jodhpur',
                                        'Kanpur', 'Kochi', 'Kolkata', 'Lucknow', 'Ludhiana',
                                        'Madurai', 'Mangaluru', 'Mumbai', 'Mysuru', 'Nagpur',
                                        'Nashik', 'Navi Mumbai', 'Noida', 'Patna', 'Pune',
                                        'Raipur', 'Rajkot', 'Ranchi', 'Surat', 'Thane',
                                        'Thiruvananthapuram', 'Udaipur', 'Vadodara', 'Varanasi',
                                        'Vijayawada', 'Visakhapatnam', 'Other'
                                    ];
                                @endphp
                                @foreach($inquiryCities as $cityName)
                                    <button type="button" class="download-select-option" data-value="{{ $cityName }}">{{ $cityName }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="field-error" id="error-city" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>

                    {{-- Company Name --}}
                    <div class="figma-enquiry-field" id="field-company">
                        <label for="inquiry-company">Enter Company / Firm Name</label>
                        <input id="inquiry-company" name="company" type="text" autocomplete="organization">
                        <div class="field-error" id="error-company" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>

                    {{-- I am a (Role) --}}
                    <div class="figma-enquiry-field full" id="field-role">
                        <div class="download-select-wrap" id="roleSelectWrap">
                            <button type="button" class="download-select-trigger" id="roleSelectTrigger">
                                <span class="download-select-label is-placeholder" id="roleSelectLabel">I am a*</span>
                                <svg class="download-select-arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="#555" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 1L5 5L9 1" />
                                </svg>
                            </button>
                            <input type="hidden" name="role" id="inquiry-role" value="" required>
                            <div class="download-select-menu" id="roleSelectMenu" style="display: none;">
                                <button type="button" class="download-select-option" data-value="Architect">Architect</button>
                                <button type="button" class="download-select-option" data-value="Interior Designer">Interior Designer</button>
                                <button type="button" class="download-select-option" data-value="Lighting Consultant">Lighting Consultant</button>
                                <button type="button" class="download-select-option" data-value="Contractor">Contractor</button>
                                <button type="button" class="download-select-option" data-value="Homeowner">Homeowner</button>
                                <button type="button" class="download-select-option" data-value="Other">Other</button>
                            </div>
                        </div>
                        <div class="field-error" id="error-role" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>

                    {{-- Tell us more (Message) --}}
                    <div class="figma-enquiry-field full message" id="field-message">
                        <label for="inquiry-message">Tell us more (optional)</label>
                        <textarea id="inquiry-message" name="message" rows="3"></textarea>
                        <div class="field-error" id="error-message" style="display: none; color: #e53e3e; font-size: 11px; margin-top: 4px;"></div>
                    </div>
                </div>

                {{-- General error message --}}
                <div id="general-error" style="display: none; color: #e53e3e; font-size: 13px; margin: 10px 0; text-align: center; font-weight: 600;"></div>

                {{-- Captcha --}}
                <div class="figma-captcha-wrapper">
                    <div id="figmaInquiryRecaptcha"></div>
                    <div class="figma-captcha-error" id="recaptcha-error" style="display: none;" role="alert">
                        Please complete Google Captcha verification.
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="figma-enquiry-submit" id="inquirySubmitBtn">
                    <span id="btn-text">SEND ENQUIRY</span>
                    <span id="btn-loading" style="display: none; align-items: center; justify-content: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: figmaSpin 1s linear infinite;">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-linecap="round" opacity="0.3"></circle>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                        SENDING...
                    </span>
                </button>

                <p class="figma-enquiry-consent">
                    By submitting this form, you agree to be contacted by <em>Abby Lighting</em> regarding your enquiry.
                </p>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes figmaSpin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
(function() {
    const COUNTRIES = [
        { code: "IN", name: "India", dialCode: "+91" },
        { code: "AE", name: "United Arab Emirates", dialCode: "+971" },
        { code: "US", name: "United States", dialCode: "+1" },
        { code: "GB", name: "United Kingdom", dialCode: "+44" },
        { code: "SG", name: "Singapore", dialCode: "+65" },
        { code: "SA", name: "Saudi Arabia", dialCode: "+966" },
        { code: "QA", name: "Qatar", dialCode: "+974" },
        { code: "OM", name: "Oman", dialCode: "+968" },
        { code: "KW", name: "Kuwait", dialCode: "+965" },
        { code: "BH", name: "Bahrain", dialCode: "+973" },
        { code: "AU", name: "Australia", dialCode: "+61" },
        { code: "CA", name: "Canada", dialCode: "+1" },
        { code: "DE", name: "Germany", dialCode: "+49" },
        { code: "FR", name: "France", dialCode: "+33" },
        { code: "IT", name: "Italy", dialCode: "+39" },
        { code: "BD", name: "Bangladesh", dialCode: "+880" },
        { code: "LK", name: "Sri Lanka", dialCode: "+94" },
        { code: "NP", name: "Nepal", dialCode: "+977" },
        { code: "MY", name: "Malaysia", dialCode: "+60" },
        { code: "TH", name: "Thailand", dialCode: "+66" },
        { code: "ID", name: "Indonesia", dialCode: "+62" },
        { code: "VN", name: "Vietnam", dialCode: "+84" },
        { code: "PH", name: "Philippines", dialCode: "+63" },
        { code: "NZ", name: "New Zealand", dialCode: "+64" },
        { code: "ZA", name: "South Africa", dialCode: "+27" },
        { code: "EG", name: "Egypt", dialCode: "+20" },
        { code: "KE", name: "Kenya", dialCode: "+254" },
        { code: "NG", name: "Nigeria", dialCode: "+234" },
        { code: "BR", name: "Brazil", dialCode: "+55" },
        { code: "MX", name: "Mexico", dialCode: "+52" },
        { code: "ES", name: "Spain", dialCode: "+34" },
        { code: "NL", name: "Netherlands", dialCode: "+31" },
        { code: "SE", name: "Sweden", dialCode: "+46" },
        { code: "CH", name: "Switzerland", dialCode: "+41" },
        { code: "JP", name: "Japan", dialCode: "+81" },
        { code: "KR", name: "South Korea", dialCode: "+82" },
        { code: "CN", name: "China", dialCode: "+86" }
    ];

    let selectedCountry = COUNTRIES[0];
    let recaptchaWidgetId = null;
    const RECAPTCHA_SITE_KEY = "{{ config('google_captcha.site_key', env('GOOGLE_CAPTCHA_SITE_KEY')) }}";

    // Setup input label transitions
    function initFloatingLabels() {
        const fields = document.querySelectorAll('#figmaInquiryModal .figma-enquiry-field input, #figmaInquiryModal .figma-enquiry-field textarea');
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

    // Populate country list
    function populateCountryList(filter = '') {
        const list = document.getElementById('phoneCountryList');
        if (!list) return;

        const q = filter.toLowerCase().trim();
        const filtered = COUNTRIES.filter(c => 
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
            btn.className = 'phone-country-option' + (c.code === selectedCountry.code ? ' is-selected' : '');
            btn.innerHTML = `
                <img src="https://flagcdn.com/w40/${c.code.toLowerCase()}.png" alt="${c.name}" class="country-flag-img-option">
                <span class="country-name-text">${c.name}</span>
                <span class="country-dial-code">${c.dialCode}</span>
            `;
            btn.addEventListener('click', () => {
                selectedCountry = c;
                document.getElementById('selectedCountryFlag').src = `https://flagcdn.com/w40/${c.code.toLowerCase()}.png`;
                document.getElementById('selectedCountryFlag').alt = c.name;
                document.getElementById('phoneCountryDropdown').style.display = 'none';
                document.getElementById('countryChevron').classList.remove('is-open');
                document.getElementById('inquiry-phone').focus();
            });
            list.appendChild(btn);
        });
    }

    // Setup Custom Select Dropdowns (City & Role)
    function setupCustomSelect(triggerId, menuId, labelId, hiddenInputId) {
        const trigger = document.getElementById(triggerId);
        const menu = document.getElementById(menuId);
        const label = document.getElementById(labelId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const wrap = trigger ? trigger.closest('.download-select-wrap') : null;

        if (!trigger || !menu || !label || !hiddenInput) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = menu.style.display !== 'none';
            // Close other dropdowns
            closeAllDropdowns();
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
                
                menu.querySelectorAll('.download-select-option').forEach(o => o.classList.remove('is-selected'));
                opt.classList.add('is-selected');

                menu.style.display = 'none';
                wrap && wrap.classList.remove('is-open');
                trigger.querySelector('.download-select-arrow')?.classList.remove('is-open');

                // Clear any error
                const err = hiddenInput.closest('.figma-enquiry-field')?.querySelector('.field-error');
                if (err) err.style.display = 'none';
            });
        });
    }

    function closeAllDropdowns() {
        const phoneDropdown = document.getElementById('phoneCountryDropdown');
        if (phoneDropdown) phoneDropdown.style.display = 'none';
        document.getElementById('countryChevron')?.classList.remove('is-open');

        ['citySelectMenu', 'roleSelectMenu'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        document.querySelectorAll('.download-select-wrap').forEach(w => w.classList.remove('is-open'));
        document.querySelectorAll('.download-select-arrow').forEach(a => a.classList.remove('is-open'));
    }

    // Ensure reCAPTCHA
    function ensureRecaptchaLoaded(callback) {
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

    function renderRecaptchaWidget() {
        const target = document.getElementById('figmaInquiryRecaptcha');
        if (!target) return;

        if (recaptchaWidgetId !== null) {
            try {
                grecaptcha.reset(recaptchaWidgetId);
            } catch (err) {}
            return;
        }

        ensureRecaptchaLoaded(() => {
            try {
                target.innerHTML = '';
                recaptchaWidgetId = grecaptcha.render('figmaInquiryRecaptcha', {
                    'sitekey': RECAPTCHA_SITE_KEY,
                    'callback': function() {
                        const err = document.getElementById('recaptcha-error');
                        if (err) err.style.display = 'none';
                    }
                });
            } catch (e) {
                console.warn('reCAPTCHA render error:', e);
            }
        });
    }

    // Open Modal Function
    window.openInquiryModal = function(e) {
        if (e && e.preventDefault) e.preventDefault();
        const modal = document.getElementById('figmaInquiryModal');
        if (!modal) return;

        // Reset state
        document.getElementById('figmaThankYouCard').style.display = 'none';
        document.getElementById('figmaFormContent').style.display = 'block';
        document.getElementById('figmaEnquiryCard').classList.remove('figma-enquiry-card-thankyou');
        
        // Reset form
        const form = document.getElementById('figmaEnquiryForm');
        if (form) form.reset();

        // Reset custom selects
        const cityLabel = document.getElementById('citySelectLabel');
        if (cityLabel) {
            cityLabel.textContent = 'Select City*';
            cityLabel.classList.add('is-placeholder');
        }
        const cityInput = document.getElementById('inquiry-city');
        if (cityInput) cityInput.value = '';

        const roleLabel = document.getElementById('roleSelectLabel');
        if (roleLabel) {
            roleLabel.textContent = 'I am a*';
            roleLabel.classList.add('is-placeholder');
        }
        const roleInput = document.getElementById('inquiry-role');
        if (roleInput) roleInput.value = '';

        // Reset errors
        document.querySelectorAll('#figmaInquiryModal .field-error').forEach(el => el.style.display = 'none');
        const genErr = document.getElementById('general-error');
        if (genErr) genErr.style.display = 'none';
        const capErr = document.getElementById('recaptcha-error');
        if (capErr) capErr.style.display = 'none';

        // Reset button
        const submitBtn = document.getElementById('inquirySubmitBtn');
        if (submitBtn) submitBtn.disabled = false;
        document.getElementById('btn-text').style.display = 'inline';
        document.getElementById('btn-loading').style.display = 'none';

        initFloatingLabels();
        closeAllDropdowns();

        // Show modal and lock body
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Render / reset captcha
        renderRecaptchaWidget();
    };

    // Close Modal Function
    window.closeInquiryModal = function() {
        const modal = document.getElementById('figmaInquiryModal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
        closeAllDropdowns();
    };

    // Form submission
    function handleFormSubmit(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('#figmaInquiryModal .field-error').forEach(el => el.style.display = 'none');
        const genErr = document.getElementById('general-error');
        if (genErr) genErr.style.display = 'none';
        const capErr = document.getElementById('recaptcha-error');
        if (capErr) capErr.style.display = 'none';

        const nameInput = document.getElementById('inquiry-name');
        const phoneInput = document.getElementById('inquiry-phone');
        const emailInput = document.getElementById('inquiry-email');
        const cityInput = document.getElementById('inquiry-city');
        const companyInput = document.getElementById('inquiry-company');
        const roleInput = document.getElementById('inquiry-role');
        const messageInput = document.getElementById('inquiry-message');

        let hasError = false;

        function showError(fieldId, errorMsg) {
            const el = document.getElementById('error-' + fieldId);
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
        if (recaptchaWidgetId !== null && window.grecaptcha) {
            try {
                captchaToken = grecaptcha.getResponse(recaptchaWidgetId);
            } catch (err) {}
        }

        if (!captchaToken) {
            if (capErr) capErr.style.display = 'block';
            hasError = true;
        }

        if (hasError) return;

        // Set Loading state
        const submitBtn = document.getElementById('inquirySubmitBtn');
        submitBtn.disabled = true;
        document.getElementById('btn-text').style.display = 'none';
        document.getElementById('btn-loading').style.display = 'inline-flex';

        const payload = {
            type: 'general',
            name: nameInput.value.trim(),
            phone: `${selectedCountry.dialCode} ${phoneInput.value.trim()}`,
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
                document.getElementById('btn-text').style.display = 'inline';
                document.getElementById('btn-loading').style.display = 'none';

                if (response.status === 422 && data && data.errors) {
                    for (const [key, msgs] of Object.entries(data.errors)) {
                        if (key === 'g-recaptcha-response') {
                            if (capErr) {
                                capErr.textContent = msgs[0];
                                capErr.style.display = 'block';
                            }
                            if (recaptchaWidgetId !== null && window.grecaptcha) {
                                grecaptcha.reset(recaptchaWidgetId);
                            }
                        } else {
                            showError(key, msgs[0]);
                        }
                    }
                } else {
                    if (genErr) {
                        genErr.textContent = (data && data.message) ? data.message : 'Failed to submit enquiry. Please try again.';
                        genErr.style.display = 'block';
                    }
                }
                return;
            }

            // Success state
            document.getElementById('figmaFormContent').style.display = 'none';
            document.getElementById('figmaThankYouCard').style.display = 'block';
            document.getElementById('figmaEnquiryCard').classList.add('figma-enquiry-card-thankyou');
            
            // Reset captcha
            if (recaptchaWidgetId !== null && window.grecaptcha) {
                try { grecaptcha.reset(recaptchaWidgetId); } catch(err) {}
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            document.getElementById('btn-text').style.display = 'inline';
            document.getElementById('btn-loading').style.display = 'none';
            if (genErr) {
                genErr.textContent = 'Network error. Please try again later.';
                genErr.style.display = 'block';
            }
        });
    }

    // Initialize all components
    function init() {
        initFloatingLabels();
        populateCountryList();

        // Phone country dropdown toggle
        const phoneTrigger = document.getElementById('phoneCountryTrigger');
        const phoneDropdown = document.getElementById('phoneCountryDropdown');
        if (phoneTrigger && phoneDropdown) {
            phoneTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = phoneDropdown.style.display !== 'none';
                closeAllDropdowns();
                if (!isOpen) {
                    phoneDropdown.style.display = 'flex';
                    document.getElementById('countryChevron')?.classList.add('is-open');
                    document.getElementById('phoneCountrySearch')?.focus();
                }
            });
        }

        // Phone country search
        const phoneSearch = document.getElementById('phoneCountrySearch');
        if (phoneSearch) {
            phoneSearch.addEventListener('input', (e) => {
                populateCountryList(e.target.value);
            });
            phoneSearch.addEventListener('click', (e) => e.stopPropagation());
        }

        // Setup City and Role selects
        setupCustomSelect('citySelectTrigger', 'citySelectMenu', 'citySelectLabel', 'inquiry-city');
        setupCustomSelect('roleSelectTrigger', 'roleSelectMenu', 'roleSelectLabel', 'inquiry-role');

        // Close dropdowns on document click
        document.addEventListener('click', () => {
            closeAllDropdowns();
        });

        // Close modal on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllDropdowns();
                closeInquiryModal();
            }
        });

        // Close modal on backdrop click
        const modal = document.getElementById('figmaInquiryModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeInquiryModal();
                }
            });
        }

        // Form submit listener
        const form = document.getElementById('figmaEnquiryForm');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
