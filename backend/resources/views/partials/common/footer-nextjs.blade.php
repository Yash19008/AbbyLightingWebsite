{{-- Next.js Style Footer with Accordion - 1:1 Pixel-Identical Match to Next.js Frontend --}}
<style>
  /* ==================================================================
   NEXTJS FOOTER - EXACT 1:1 DESKTOP & MOBILE STYLES
   ================================================================== */
  footer#contact {
    color: #fff !important;
    background: #050505 !important;
    font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    position: relative !important;
    box-sizing: border-box !important;
    width: 100% !important;
    margin: 0 !important;
    display: block !important;
    overflow: hidden !important;
  }

  footer#contact * {
    box-sizing: border-box !important;
  }

  /* Base accordion styles (for Mobile / Tablet) */
  footer#contact .footer-accordion {
    width: 100%;
    margin: 0;
    display: block;
  }

  footer#contact .footer-accordion-toggle {
    background: transparent !important;
    border: none !important;
    outline: none !important;
    padding: 0 !important;
    margin: 0 0 14px !important;
    color: #B6B6B6 !important;
    font: 300 14px / 1.4 Inter, sans-serif !important;
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    cursor: pointer !important;
    text-align: left !important;
    width: auto !important;
    white-space: nowrap !important;
    transition: color 0.22s ease;
  }

  footer#contact .footer-accordion-toggle:hover {
    color: #fff !important;
  }

  footer#contact .footer-accordion-icon {
    border-bottom: 1.5px solid currentColor;
    border-right: 1.5px solid currentColor;
    flex: none;
    width: 7px;
    height: 7px;
    transition: transform 0.22s ease;
    transform: rotate(45deg);
    display: inline-block;
  }

  footer#contact .footer-accordion.open .footer-accordion-icon,
  footer#contact .footer-accordion.is-open .footer-accordion-icon {
    transform: rotate(225deg);
  }

  footer#contact .footer-accordion-panel {
    opacity: 0;
    max-height: 0;

    margin: 0;
    transition: max-height 0.3s ease, opacity 0.2s ease, margin 0.3s ease;
    display: grid;
    overflow: hidden;
  }

  .footer-accordion-panel a {
    color: #b6b6b6 !important;
    font-size: 13px !important;
    /* margin-bottom: 0 !important; */
  }

  .footer-links a {
    color: #B6B6B6 !important;
    font: 300 14px / 1.4 Inter, sans-serif;
    display: block !important;
    text-decoration: none !important;
    white-space: nowrap !important;
  }

  footer#contact .footer-accordion.open .footer-accordion-panel,
  footer#contact .footer-accordion.is-open .footer-accordion-panel {
    opacity: 1;
    max-height: 240px;
    display: flex !important;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 14px;
  }

  .footer-identity .footer-logo {
    object-fit: contain !important;
    object-position: left center !important;
    width: 120px !important;
    height: auto !important;
    margin: 0 0 24px !important;
  }

  footer#contact .footer-links a {
    color: #B6B6B6 !important;
    margin: 0 0 14px !important;
    font: 300 14px / 1.4 Inter, sans-serif !important;
    display: block !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    transition: color 0.22s ease !important;
  }

  footer#contact .footer-links .footer-accordion-panel a,
  footer#contact .footer-accordion-panel a {
    color: #b6b6b6 !important;
    font: 300 13px/1.4 Inter, sans-serif !important;
    font-size: 13px !important;
    margin-bottom: 0 !important;
    display: block !important;
    text-decoration: none !important;
  }

  footer#contact .footer-links .footer-accordion-panel a:hover,
  footer#contact .footer-accordion-panel a:hover {
    color: #fff !important;
  }

  footer#contact .footer-links a:hover,
  footer#contact .footer-links a:focus-visible {
    color: #fff !important;
  }

  /* ==================================================================
   DESKTOP (>= 901px) - NATIVE NEXT.JS GRID & FLEXBOX FLOW
   ================================================================== */
  @media (min-width: 901px) {
    footer#contact {
      background: #050505 !important;
      height: auto !important;
      padding: 57px 0 36px !important;
    }

    footer#contact .figma-footer {
      width: 100% !important;
      max-width: 100% !important;
      height: auto !important;
      box-sizing: border-box !important;
      padding: 0 6.45vw !important;
      position: relative !important;
    }

    footer#contact .footer-main {
      display: grid !important;
      grid-template-columns: 1.8fr auto auto auto !important;
      grid-column-gap: clamp(28px, 5vw, 80px) !important;
      column-gap: clamp(28px, 5vw, 80px) !important;
      grid-row-gap: 48px !important;
      row-gap: 48px !important;
      align-items: start !important;
      width: 100% !important;
      position: static !important;
    }

    footer#contact .footer-identity {
      position: static !important;
      display: block !important;
    }

    footer#contact .footer-identity .footer-logo {
      object-fit: contain !important;
      object-position: left center !important;
      width: 120px !important;
      height: auto !important;
      margin: 0 0 24px !important;
    }

    footer#contact .footer-identity p {
      color: #e1e1e1 !important;
      width: 100% !important;
      max-width: 440px !important;
      margin: 0 !important;
      font: 300 14px / 1.5 Inter, sans-serif !important;
    }

    footer#contact .footer-links {
      padding: 0 !important;
      position: static !important;
      display: flex !important;
      flex-direction: column !important;
      width: auto !important;
    }

    footer#contact .footer-products {
      width: auto !important;
      min-width: 110px;
    }


    footer#contact .footer-products .footer-accordion-toggle {
      margin-bottom: 7px !important;
    }

    /* footer#contact .footer-products .footer-inspiration {
    margin: 14px 0 0 !important;
    display: block !important;
  } */

    footer#contact .footer-company {
      width: auto !important;
    }

    footer#contact .footer-company a {
      margin-bottom: 14px !important;
    }

    footer#contact .footer-company a:last-child {
      margin-bottom: 0 !important;
    }

    footer#contact .footer-legal {
      width: auto !important;
      display: flex !important;
      flex-direction: column !important;
    }

    footer#contact .footer-legal a {
      margin-bottom: 11px !important;
    }

    footer#contact .footer-legal a:last-child {
      margin-bottom: 0 !important;
    }

    footer#contact .footer-mobile-legal {
      display: none !important;
    }

    footer#contact .footer-rule {
      background: #ffffff59 !important;
      height: 1px !important;
      margin: 48px 0 24px !important;
      border: none !important;
      position: static !important;
      width: 100% !important;
      display: block !important;
    }

    footer#contact .footer-base {
      align-items: center !important;
      justify-content: space-between !important;
      height: auto !important;
      display: flex !important;
      position: static !important;
      width: 100% !important;
      margin: 0 !important;
    }

    footer#contact .footer-base>span {
      color: #ffffff !important;
      font-family: Inter, sans-serif !important;
      font-weight: 300 !important;
      font-size: 14px !important;
      line-height: 22px !important;
    }

    footer#contact .footer-social {
      align-items: center !important;
      gap: 25px !important;
      display: flex !important;
    }

    footer#contact .footer-social a {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 30px !important;
      height: 32px !important;
      text-decoration: none !important;
    }

    footer#contact .footer-social-icon {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 24px !important;
      height: 24px !important;
      padding: 0 !important;
    }

    footer#contact .footer-social-img {
      width: 27px;
      height: 32px;
      object-fit: contain;
      display: block;
      transition: transform 0.2s ease, opacity 0.2s ease;
    }

    footer#contact .footer-facebook {
      width: 22px !important;
      height: 37px !important;
    }

    footer#contact .footer-youtube {
      width: 32px !important;
      height: 38px !important;
    }

    footer#contact .footer-social a:hover .footer-social-img {
      opacity: 0.8;
      transform: scale(1.08);
    }
  }

  /* ==================================================================
   TABLET (601px to 900px)
   ================================================================== */
  @media (max-width: 900px) and (min-width: 601px) {
    footer#contact {
      padding: 48px 0 40px !important;
      height: auto !important;
    }

    footer#contact .figma-footer {
      width: 100% !important;
      max-width: 100% !important;
      height: auto !important;
      box-sizing: border-box !important;
      padding: 0 6.45vw !important;
      position: relative !important;
    }

    footer#contact .footer-main {
      display: grid !important;
      grid-template-columns: 1.8fr auto auto auto !important;
      grid-column-gap: clamp(28px, 5vw, 80px) !important;
      column-gap: clamp(28px, 5vw, 80px) !important;
      grid-row-gap: 48px !important;
      row-gap: 48px !important;
      align-items: start !important;
      width: 100% !important;
      position: static !important;

    }

    footer#contact .footer-identity {
      position: static !important;
    }

    footer#contact .footer-identity .footer-logo {
      width: 110px !important;
      height: 74px !important;
      margin-bottom: 20px !important;
    }

    footer#contact .footer-identity p {
      color: #e1e1e1 !important;
      width: 100% !important;
      max-width: none !important;
      margin: 0 !important;
      font-size: 14px !important;
      line-height: 1.42 !important;
    }

    footer#contact .footer-links {
      position: static !important;
      gap: 0 !important;
      display: flex !important;
      flex-direction: column !important;
      width: auto !important;
    }

    footer#contact .footer-products,
    footer#contact .footer-company {
      position: static !important;
      left: auto !important;
      right: auto !important;
      width: auto !important;
    }

    footer#contact .footer-links a {
      color: #aaa !important;
      font-size: 14px !important;
      margin-bottom: 18px !important;
    }

    footer#contact .footer-mobile-legal {
      display: block !important;
    }



    footer#contact .footer-rule {
      position: static !important;
      background: #5f5f5f !important;
      height: 1px !important;
      margin: 90px 0 30px !important;
      border: none !important;
      width: 100% !important;
    }

    footer#contact .footer-base {
      position: static !important;
      flex-direction: row !important;
      align-items: center !important;
      justify-content: space-between !important;
      gap: 25px !important;
      display: flex !important;
      height: auto !important;
    }

    footer#contact .footer-base>span {
      color: #e4e4e4 !important;
      font-size: 14px !important;
    }

    footer#contact .footer-social {
      gap: 24px !important;
      display: flex !important;
      align-items: center !important;
    }

    footer#contact .footer-social a {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: auto !important;
      height: auto !important;
      text-decoration: none !important;
    }

    footer#contact .footer-social-icon {
      width: 24px !important;
      height: 24px !important;
      padding: 0 !important;
    }

    footer#contact .footer-social-img {
      width: 22px !important;
      height: 22px !important;
    }
  }

  /* ==================================================================
   MOBILE (<= 600px)
   ================================================================== */
  @media (max-width: 600px) {
    footer#contact {
      padding: 40px 0 31px !important;
      height: auto !important;
    }

    footer#contact .figma-footer {
      width: 100% !important;
      max-width: 100% !important;
      height: auto !important;
      box-sizing: border-box !important;
      padding: 0 6.45vw !important;
      position: relative !important;
    }

    footer#contact .footer-main {
      position: static !important;
      grid-template-columns: 1fr 1fr !important;
      gap: 32px 20px !important;
      display: grid !important;
    }

    footer#contact .footer-identity {
      position: static !important;
      grid-column: 1 / 3 !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;

      margin-top: 16px !important;
    }

    footer#contact .footer-identity .footer-logo {
      width: 104px !important;
      height: 68px !important;
      margin-bottom: 25px !important;
    }

    footer#contact .footer-identity p {
      max-width: 100% !important;
      font-size: 13px !important;
      line-height: 1.5 !important;
      margin-bottom: 24px;
      font-weight: 300 !important;
    }

    footer#contact .footer-links {
      position: static !important;
      width: auto !important;
    }

    footer#contact .footer-links:nth-of-type(3) {
      grid-column: 1 / 3 !important;
    }

    footer#contact .footer-mobile-legal {
      display: block !important;
    }

    footer#contact .footer-legal {
      display: none !important;
    }

    footer#contact .footer-rule {
      position: static !important;
      margin: 32px 0 24px !important;
      background: #5f5f5f !important;
      height: 1px !important;
      width: 100% !important;
    }

    footer#contact .footer-base {
      position: static !important;
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 27px !important;
      height: auto !important;
    }

    footer#contact .footer-base>span {
      font-size: 12px !important;
    }

    footer#contact .footer-social {
      gap: 24px !important;
      margin-top: 31px !important;
      display: flex !important;
      align-items: center !important;
    }

    footer#contact .footer-social a {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: auto !important;
      height: auto !important;
      text-decoration: none !important;
    }

    footer#contact .footer-social-icon {
      width: 24px !important;
      height: 24px !important;
      padding: 0 !important;
    }

    footer#contact .footer-social-img {
      width: 22px !important;
      height: 22px !important;
    }
  }
</style>

<footer id="contact">
  <div class="figma-footer">
    <div class="footer-main">
      <div class="footer-identity">
        <img class="footer-logo" src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
        <p>For over three generations, Abby Lighting has been one of India's leading architectural lighting
          manufacturers, designing and manufacturing premium architectural, decorative and outdoor lighting entirely
          in-house. From concept and engineering to precision manufacturing and testing, every luminaire is built for
          consistency, performance and long-term reliability, making Abby Lighting the trusted partner for architects,
          interior designers and consultants across residential, commercial and hospitality projects.</p>
      </div>

      <nav class="footer-links footer-products" aria-label="Product links">
        <div class="footer-accordion" id="footerProductsAccordion">
          <button class="footer-accordion-toggle" type="button" aria-expanded="false" aria-controls="footer-products">
            <span>Products</span>
            <span class="footer-accordion-icon" aria-hidden="true"></span>
          </button>
          <div class="footer-accordion-panel" id="footer-products" aria-hidden="true">
            <a href="/products">Architectural</a>
            <a href="/decorative-products">Decorative</a>
            <a href="/products">Outdoor</a>
            <a href="/abby-smart">Smart Lighting</a>
          </div>
        </div>

        <div class="footer-accordion" id="footerWorkAccordion">
          <button class="footer-accordion-toggle" type="button" aria-expanded="false" aria-controls="footer-our-work">
            <span>Our Work</span>
            <span class="footer-accordion-icon" aria-hidden="true"></span>
          </button>
          <div class="footer-accordion-panel" id="footer-our-work" aria-hidden="true">
            <a href="/clients">Clients</a>
            <a href="/projects">Projects</a>
          </div>
        </div>

        <a class="footer-inspiration" href="/inspiration">Inspiration</a>
      </nav>

      <nav class="footer-links footer-company" aria-label="Company links">
        <a href="/company">About Us</a>
        <a href="/contact">Contact Us</a>
        <a href="/career">Careers</a>
        <a href="/catalog-download-user-form">Catalogues</a>


        <a href="/fair-events">Fairs &amp; Events</a>
      </nav>

      <nav class="footer-links footer-legal" aria-label="Legal links">
        <a href="/privacy-policy">Privacy Policy</a>
        <a href="/terms-and-conditions">Terms of Use</a>
      </nav>
    </div>

    <div class="footer-rule"></div>

    <div class="footer-base">
      <span>© 2026 Abby Lighting. All rights reserved.</span>
      <div class="footer-social">
        <a href="https://www.instagram.com/abbylighting/" target="_blank" rel="noopener noreferrer"
          aria-label="Instagram">
          <span class="footer-social-icon">
            <img src="{{ asset('icons/instagram.png') }}" alt="Instagram" width="27" height="32"
              class="footer-social-img" />
          </span>
        </a>
        <a href="https://www.linkedin.com/company/abby-lighting/" target="_blank" rel="noopener noreferrer"
          aria-label="LinkedIn">
          <span class="footer-social-icon">
            <img src="{{ asset('icons/linkedin.png') }}" alt="LinkedIn" width="22" height="22"
              class="footer-social-img" />
          </span>
        </a>
        <a href="https://www.facebook.com/abbylighting/" target="_blank" rel="noopener noreferrer"
          aria-label="Facebook">
          <span class="footer-social-icon">
            <img src="{{ asset('icons/facebook.png') }}" alt="Facebook" width="22" height="22"
              class="footer-social-img footer-facebook" />
          </span>
        </a>
        <a href="https://www.youtube.com/@abby-lighting" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
          <span class="footer-social-icon">
            <img src="{{ asset('icons/youtube.png') }}" alt="YouTube" width="22" height="22"
              class="footer-social-img footer-youtube" />
          </span>
        </a>
      </div>
    </div>
  </div>
</footer>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Footer Accordions toggle functionality
    const accordionToggles = document.querySelectorAll('.footer-accordion-toggle');
    accordionToggles.forEach(function (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        const accordion = this.closest('.footer-accordion');
        if (!accordion) return;
        const isOpen = accordion.classList.contains('is-open') || accordion.classList.contains('open');
        const panel = accordion.querySelector('.footer-accordion-panel');

        if (isOpen) {
          accordion.classList.remove('is-open', 'open');
          this.setAttribute('aria-expanded', 'false');
          if (panel) panel.setAttribute('aria-hidden', 'true');
        } else {
          accordion.classList.add('is-open', 'open');
          this.setAttribute('aria-expanded', 'true');
          if (panel) panel.setAttribute('aria-hidden', 'false');
        }
      });
    });
  });
</script>

{{-- Include modals that are needed for footer links --}}
@include('partials.download-catalog')
@include('partials.product-enquiry')