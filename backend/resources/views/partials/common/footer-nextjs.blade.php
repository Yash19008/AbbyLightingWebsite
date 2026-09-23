{{-- Next.js Style Footer with Accordion - Pixel-Identical to Next.js Frontend --}}
<style>
/* ==================================================================
   NEXTJS FOOTER - EXACT 1:1 STYLES MATCHING FRONTEND
   ================================================================== */

footer#contact {
  color: #ffffff;
  background: #1a1c1d !important;
  font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  position: relative;
  box-sizing: border-box;
  width: 100%;
  margin: 0;
  display: block;
  overflow: hidden;
}

footer#contact * {
  box-sizing: border-box;
}

/* Base accordion styles */
footer#contact .footer-accordion {
  width: 100%;
  margin: 0;
  display: block;
}

footer#contact .footer-accordion-toggle {
  color: #f7f7f7;
  text-align: left;
  cursor: pointer;
  background: transparent;
  border: none;
  outline: none;
  box-shadow: none;
  justify-content: flex-start;
  align-items: center;
  gap: 8px;
  width: 100%;
  min-height: 22px;
  padding: 0;
  font-family: Inter, sans-serif;
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
  display: flex;
  transition: color 0.22s ease;
}

footer#contact .footer-accordion-toggle:hover {
  color: #ffffff;
}

footer#contact .footer-accordion-icon {
  border-bottom: 1.5px solid currentColor;
  border-right: 1.5px solid currentColor;
  flex: none;
  width: 7px;
  height: 7px;
  margin-left: 2px;
  margin-top: -3px;
  transition: transform 0.22s ease;
  transform: rotate(45deg);
  display: inline-block;
}

footer#contact .footer-accordion.open .footer-accordion-icon,
footer#contact .footer-accordion.is-open .footer-accordion-icon {
  transform: rotate(225deg) translate(-2px, -2px);
  margin-top: 2px;
}

footer#contact .footer-accordion-panel {
  opacity: 0;
  max-height: 0;
  padding-left: 10px;
  margin: 0;
  transition: max-height 0.3s ease, opacity 0.2s ease, margin 0.3s ease;
  display: grid;
  overflow: hidden;
}

footer#contact .footer-accordion.open .footer-accordion-panel,
footer#contact .footer-accordion.is-open .footer-accordion-panel {
  opacity: 1;
  max-height: 160px;
  margin: 6px 0 10px;
}

footer#contact .footer-accordion-panel a {
  color: #b6b6b6;
  margin: 0;
  padding: 2px 0;
  font-family: Inter, sans-serif;
  font-weight: 300;
  font-size: 13px;
  line-height: 20px;
  display: block;
  text-decoration: none;
  transition: color 0.2s ease;
}

footer#contact .footer-accordion-panel a:hover {
  color: #ffffff;
}

footer#contact .footer-inspiration {
  color: #f7f7f7;
  font-family: Inter, sans-serif;
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
  display: block;
  text-decoration: none;
  transition: color 0.2s ease;
}

footer#contact .footer-inspiration:hover {
  color: #ffffff;
}

footer#contact .footer-links a {
  color: #f7f7f7;
  font-family: Inter, sans-serif;
  font-weight: 300;
  font-size: 14px;
  line-height: 22px;
  transition: color 0.22s ease;
  display: block;
  text-decoration: none;
}

footer#contact .footer-links a:hover,
footer#contact .footer-links a:focus-visible {
  color: #ffffff;
}

footer#contact .footer-social a {
  color: #ffffff;
  justify-content: center;
  align-items: center;
  transition: color 0.2s ease, transform 0.2s ease;
  display: flex;
  text-decoration: none;
}

footer#contact .footer-social a:hover {
  color: #f6c177;
  transform: translateY(-2px);
}

/* ==================================================================
   DESKTOP (>= 901px) - EXACT NEXT.JS POSITIONING & SIZING
   ================================================================== */
@media (min-width: 901px) {
  footer#contact {
    background: #1a1c1d !important;
    height: 466px;
    padding: 0;
  }

  footer#contact .figma-footer {
    width: 100%;
    max-width: 100%;
    height: 466px;
    margin: 0 auto;
    position: relative;
  }

  footer#contact .footer-main {
    display: block;
    width: 100%;
    height: 100%;
    position: static;
  }

  footer#contact .footer-identity {
    position: absolute;
    top: 57px;
    left: clamp(72px, 9.7vw, 114px);
    display: block;
  }

  footer#contact .footer-identity .footer-logo {
    object-fit: contain;
    object-position: left center;
    width: 131px;
    height: 90px;
    margin: 0;
    display: block;
  }

  footer#contact .footer-identity p {
    color: #ffffff;
    width: 498px;
    max-width: calc(100vw - 780px);
    margin: 26px 0 0;
    font-family: Inter, sans-serif;
    font-weight: 300;
    font-size: 14px;
    line-height: 1.42;
  }

  footer#contact .footer-links {
    padding: 0;
    position: absolute;
    top: 66px;
    display: flex;
    flex-direction: column;
  }

  footer#contact .footer-products {
    width: 158px;
    left: calc(50% + 157px);
  }

  footer#contact .footer-products .footer-accordion + .footer-accordion {
    margin-top: 13px;
  }

  footer#contact .footer-products .footer-inspiration {
    margin: 14px 0 0;
    display: block;
  }

  footer#contact .footer-company {
    left: 75% !important;
  }

  footer#contact .footer-company a {
    margin-bottom: 14px;
  }

  footer#contact .footer-company a:last-child {
    margin-bottom: 0;
  }

  footer#contact .footer-legal {
    right: 115px;
    display: flex;
  }

  footer#contact .footer-legal a {
    margin-bottom: 11px;
  }

  footer#contact .footer-legal a:last-child {
    margin-bottom: 0;
  }

  footer#contact .footer-mobile-legal {
    display: none !important;
  }

  footer#contact .footer-rule {
    background: #ffffff59;
    height: 1px;
    margin: 0;
    border: none;
    position: absolute;
    top: 349px;
    left: clamp(72px, 9.77vw, 114px);
    right: clamp(72px, 9.77vw, 114px);
    display: block;
  }

  footer#contact .footer-base {
    align-items: center;
    justify-content: space-between;
    height: 45px;
    display: flex;
    position: absolute;
    top: 386px;
    left: clamp(90px, 10.95vw, 114px);
    right: clamp(90px, 8.6vw, 114px);
  }

  footer#contact .footer-base > span {
    color: #ffffff;
    font-family: Inter, sans-serif;
    font-weight: 300;
    font-size: 14px;
    line-height: 22px;
  }

  footer#contact .footer-social {
    align-items: center;
    gap: 25px;
    display: flex;
  }

  footer#contact .footer-social a,
  footer#contact .footer-social-icon {
    width: 30px;
    height: 32px;
  }

  footer#contact .footer-social-icon svg {
    width: 25px;
    height: 25px;
    display: block;
  }

  footer#contact .footer-social a:last-child,
  footer#contact .footer-social a:last-child .footer-social-icon {
    width: 37px;
    height: 45px;
  }

  footer#contact .footer-social a:last-child svg {
    width: 29px;
    height: 29px;
  }

  footer#contact .footer-social-icon.footer-icon-desktop {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  footer#contact .footer-social-icon.footer-icon-mobile {
    display: none;
  }
}

/* ==================================================================
   TABLET (601px - 900px)
   ================================================================== */
@media (max-width: 900px) and (min-width: 601px) {
  footer#contact {
    padding: 72px 0 110px;
    height: auto;
  }

  footer#contact .figma-footer {
    width: calc(100% - 48px);
    margin: 0 auto;
    position: static;
  }

  footer#contact .footer-main {
    grid-template-columns: 1.7fr repeat(2, 1fr);
    gap: 34px;
    display: grid;
    position: static;
  }

  footer#contact .footer-identity {
    position: static;
    border-bottom: 1px solid #4D4D4D !important;
    padding-bottom: 28px !important;
    margin-bottom: 6px !important;
  }

  footer#contact .footer-identity .footer-logo {
    width: 120px;
    height: auto;
    max-height: 82px;
    margin: 0 0 25px;
  }

  footer#contact .footer-identity p {
    font-size: 14px;
    color: #e1e1e1;
    max-width: 100%;
    margin: 0;
  }

  footer#contact .footer-links {
    position: static;
    display: flex;
    flex-direction: column;
  }

  footer#contact .footer-products,
  footer#contact .footer-company {
    position: static;
    left: auto !important;
    right: auto;
    width: auto;
  }

  footer#contact .footer-links a {
    margin-bottom: 18px;
    font-size: 14px;
    color: #aaaaaa;
  }

  footer#contact .footer-accordion {
    width: 100%;
    margin-bottom: 18px;
  }

  footer#contact .footer-accordion-toggle {
    color: #aaaaaa;
    font-size: 15px;
  }

  footer#contact .footer-mobile-legal {
    display: block !important;
  }

  footer#contact .footer-legal {
    display: none !important;
  }

  footer#contact .footer-rule {
    position: static;
    background: #5f5f5f;
    height: 1px;
    margin: 60px 0 32px;
    border: none;
    width: 100%;
  }

  footer#contact .footer-base {
    position: static;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
    display: flex;
    height: auto;
  }

  footer#contact .footer-base > span {
    color: #e4e4e4;
    font-size: 14px;
  }

  footer#contact .footer-social {
    gap: 25px;
    display: flex;
    align-items: center;
  }

  footer#contact .footer-social a,
  footer#contact .footer-social-icon {
    width: 28px;
    height: 28px;
  }

  footer#contact .footer-social svg {
    width: 24px;
    height: 24px;
  }

  footer#contact .footer-social-icon.footer-icon-desktop {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  footer#contact .footer-social-icon.footer-icon-mobile {
    display: none;
  }
}

/* ==================================================================
   MOBILE (<= 600px)
   ================================================================== */
@media (max-width: 600px) {
  footer#contact {
    padding: 58px 0 112px;
    height: auto;
  }

  footer#contact .figma-footer {
    width: calc(100% - 40px);
    margin: 0 auto;
    position: static;
    padding: 11px 5px 36px;
  }

  footer#contact .footer-main {
    grid-template-columns: 1fr 1fr;
    gap: 42px 28px;
    display: grid;
    position: static;
  }

  footer#contact .footer-identity {
    grid-column: 1 / -1;
    position: static;
    border-bottom: 1px solid #4D4D4D !important;
    padding-bottom: 28px !important;
    margin-bottom: 6px !important;
  }

  footer#contact .footer-identity .footer-logo {
    width: 104px;
    height: auto;
    max-height: 68px;
    margin-bottom: 25px;
  }

  footer#contact .footer-identity p {
    color: #e1e1e1;
    max-width: none;
    margin: 0;
    font-size: 12px;
    line-height: 1.48;
  }

  footer#contact .footer-links {
    position: static;
    gap: 22px;
    display: flex;
    flex-direction: column;
  }

  footer#contact .footer-products,
  footer#contact .footer-company {
    position: static;
    left: auto !important;
    right: auto;
    width: auto;
  }

  footer#contact .footer-links a {
    color: #aaa;
    font-size: 15px;
  }

  footer#contact .footer-products .footer-accordion {
    width: 100%;
    margin: 0;
  }

  footer#contact .footer-products .footer-accordion-toggle,
  footer#contact .footer-products .footer-inspiration,
  footer#contact .footer-company > a {
    color: #aaa;
    min-height: 0;
    margin: 0 0 18px;
    padding: 0;
    font: 300 15px/1.32 Inter, sans-serif;
  }

  footer#contact .footer-accordion-panel {
    padding-left: 14px;
  }

  footer#contact .footer-accordion.open .footer-accordion-panel,
  footer#contact .footer-accordion.is-open .footer-accordion-panel {
    max-height: 120px;
    margin: 2px 0 8px;
  }

  footer#contact .footer-accordion-panel a {
    margin: 0;
    padding: 4px 0;
    font-size: 13px;
    line-height: 1.35;
  }

  footer#contact .footer-products .footer-accordion.open .footer-accordion-toggle,
  footer#contact .footer-products .footer-accordion.is-open .footer-accordion-toggle {
    margin-bottom: 2px;
  }

  footer#contact .footer-mobile-legal {
    display: block !important;
  }

  footer#contact .footer-legal {
    display: none !important;
  }

  footer#contact .footer-rule {
    position: static;
    background: #5f5f5f;
    height: 1px;
    margin: 44px 0 28px;
    border: none;
    width: 100%;
  }

  footer#contact .footer-base {
    position: static;
    flex-direction: column;
    align-items: flex-start;
    gap: 24px;
    display: flex;
    height: auto;
  }

  footer#contact .footer-base > span {
    color: #e4e4e4;
    font-size: 12px;
  }

  footer#contact .footer-social {
    gap: 26px;
    display: flex;
    align-items: center;
  }

  footer#contact .footer-social a,
  footer#contact .footer-social svg {
    width: 22px;
    height: 22px;
  }

  footer#contact .footer-social-icon.footer-icon-desktop {
    display: none;
  }

  footer#contact .footer-social-icon.footer-icon-mobile {
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>

<footer id="contact">
    <div class="figma-footer">
        <div class="footer-main">
            <div class="footer-identity">
                <img class="footer-logo" src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
                <p>For over three generations, Abby Lighting has been one of India's leading architectural lighting manufacturers, designing and manufacturing premium architectural, decorative and outdoor lighting entirely in-house. From concept and engineering to precision manufacturing and testing, every luminaire is built for consistency, performance and long-term reliability, making Abby Lighting the trusted partner for architects, interior designers and consultants across residential, commercial and hospitality projects.</p>
            </div>
            
            <nav class="footer-links footer-products" aria-label="Product links">
                <div class="footer-accordion" id="footerProductsAccordion">
                    <button 
                        class="footer-accordion-toggle" 
                        type="button" 
                        aria-expanded="false" 
                        aria-controls="footer-products"
                    >
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
                    <button 
                        class="footer-accordion-toggle" 
                        type="button" 
                        aria-expanded="false" 
                        aria-controls="footer-our-work"
                    >
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
                <a class="footer-mobile-legal" href="/privacy-policy">Privacy Policy</a>
                <a class="footer-mobile-legal" href="/terms-and-conditions">Terms of Use</a>
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
                <a href="#" aria-label="Instagram">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                        </svg>
                    </span>
                    <span class="footer-social-icon footer-icon-mobile">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 1.4c4.75 0 8.6 3.85 8.6 8.6s-3.85 8.6-8.6 8.6S3.4 16.75 3.4 12 7.25 3.4 12 3.4zm0 3.8a4.8 4.8 0 100 9.6 4.8 4.8 0 000-9.6zm0 1.4a3.4 3.4 0 110 6.8 3.4 3.4 0 010-6.8z" />
                        </svg>
                    </span>
                </a>
                <a href="#" aria-label="LinkedIn">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path>
                        </svg>
                    </span>
                    <span class="footer-social-icon footer-icon-mobile">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                            <circle cx="5" cy="5" r="1.3" />
                            <rect x="4.2" y="8" width="1.6" height="11" rx="0.3" />
                            <path d="M9.5 8h1.6v1.5c.8-1.1 2-1.7 3.5-1.7 2.3 0 3.8 1.4 3.8 4v7.2h-1.6v-6.9c0-1.7-.8-2.7-2.3-2.7-1.5 0-2.4 1.1-2.4 2.7v6.9H9.5V8z" />
                        </svg>
                    </span>
                </a>
                <a href="#" aria-label="Facebook">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path>
                        </svg>
                    </span>
                    <span class="footer-social-icon footer-icon-mobile">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                            <path d="M14.5 3c-2.4 0-4 1.5-4 4v2.5H8v1.6h2.5V19h1.6v-7.9h3.2v-1.6h-3.2V7c0-1.5.8-2.4 2.4-2.4h1.5V3h-1.5z" />
                        </svg>
                    </span>
                </a>
                <a href="#" aria-label="YouTube">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 576 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path>
                        </svg>
                    </span>
                    <span class="footer-social-icon footer-icon-mobile">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                            <path d="M7 4.5l12.5 7.5L7 19.5z" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Footer Accordions toggle functionality
    const accordionToggles = document.querySelectorAll('.footer-accordion-toggle');
    accordionToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
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
