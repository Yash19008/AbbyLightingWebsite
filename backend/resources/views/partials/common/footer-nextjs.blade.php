{{-- Next.js Style Footer with Accordion - Pixel-Identical to Next.js Frontend --}}
<style>
/* ==================================================================
   NEXTJS FOOTER - PIXEL IDENTICAL STYLES
   ================================================================== */

footer#contact {
  color: #ffffff !important;
  background: #000000 !important;
  background-color: #000000 !important;
  padding: 86px 0 46px !important;
  font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
  position: relative !important;
  box-sizing: border-box !important;
  width: 100% !important;
  height: auto !important;
  min-height: auto !important;
  max-height: none !important;
  overflow: visible !important;
  margin: 0 !important;
  display: block !important;
}

footer#contact * {
  box-sizing: border-box !important;
}

footer#contact .figma-footer {
  width: min(1240px, 100% - 64px) !important;
  margin: 0 auto !important;
  box-sizing: border-box !important;
  position: relative !important;
  padding: 0 !important;
  height: auto !important;
  overflow: visible !important;
}

footer#contact .footer-main {
  grid-template-columns: 2.45fr 0.85fr 1.2fr 0.85fr !important;
  align-items: start !important;
  gap: 68px !important;
  display: grid !important;
  width: 100% !important;
  height: auto !important;
  overflow: visible !important;
}

footer#contact .footer-identity {
  display: block !important;
  height: auto !important;
  overflow: visible !important;
}

footer#contact .footer-identity .footer-logo {
  object-fit: contain !important;
  object-position: left center !important;
  width: 112px !important;
  height: auto !important;
  max-height: 74px !important;
  margin: 0 0 40px !important;
  display: block !important;
}

footer#contact .footer-identity p {
  color: #e1e1e1 !important;
  width: min(330px, 100%) !important;
  margin: 0 !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 16px !important;
  line-height: 1.42 !important;
  letter-spacing: normal !important;
  overflow: visible !important;
  height: auto !important;
}

footer#contact .footer-links {
  padding-top: 3px !important;
  display: flex !important;
  flex-direction: column !important;
  height: auto !important;
}

footer#contact .footer-links a {
  color: #aaaaaa !important;
  margin: 0 0 23px !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 16px !important;
  line-height: 1.32 !important;
  transition: color 0.22s ease !important;
  display: block !important;
  text-decoration: none !important;
  width: max-content !important;
}

footer#contact .footer-links a:hover,
footer#contact .footer-links a:focus-visible {
  color: #ffffff !important;
}

/* Accordion Component */
footer#contact .footer-accordion {
  width: 100% !important;
  margin-bottom: 23px !important;
  display: block !important;
}

footer#contact .footer-accordion-toggle {
  color: #ffffff !important;
  text-align: left !important;
  cursor: pointer !important;
  background: transparent !important;
  border: none !important;
  outline: none !important;
  box-shadow: none !important;
  justify-content: flex-start !important;
  align-items: center !important;
  gap: 8px !important;
  width: 100% !important;
  min-height: 22px !important;
  padding: 0 !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 16px !important;
  line-height: 1.32 !important;
  display: flex !important;
  transition: color 0.22s ease !important;
}

footer#contact .footer-accordion-toggle:hover {
  color: #ffffff !important;
}

footer#contact .footer-accordion-icon {
  border-bottom: 1.5px solid currentColor !important;
  border-right: 1.5px solid currentColor !important;
  flex: none !important;
  width: 7px !important;
  height: 7px !important;
  margin-left: 2px !important;
  margin-top: -3px !important;
  transition: transform 0.22s ease !important;
  transform: rotate(45deg) !important;
  display: inline-block !important;
}

footer#contact .footer-accordion.open .footer-accordion-icon,
footer#contact .footer-accordion.is-open .footer-accordion-icon {
  transform: rotate(225deg) translate(-2px, -2px) !important;
  margin-top: 2px !important;
}

footer#contact .footer-accordion-panel {
  opacity: 0 !important;
  max-height: 0 !important;
  padding-left: 10px !important;
  margin: 0 !important;
  transition: max-height 0.3s ease, opacity 0.2s ease, margin 0.3s ease !important;
  display: grid !important;
  overflow: hidden !important;
}

footer#contact .footer-accordion.open .footer-accordion-panel,
footer#contact .footer-accordion.is-open .footer-accordion-panel {
  opacity: 1 !important;
  max-height: 140px !important;
  margin: 10px 0 14px !important;
}

footer#contact .footer-accordion-panel a {
  color: #b6b6b6 !important;
  margin: 0 0 10px !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 14px !important;
  line-height: 1.35 !important;
  display: block !important;
}

footer#contact .footer-accordion-panel a:last-child {
  margin-bottom: 0 !important;
}

footer#contact .footer-accordion-panel a:hover {
  color: #ffffff !important;
}

footer#contact .footer-inspiration {
  color: #aaaaaa !important;
  margin: 0 0 23px !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 16px !important;
  line-height: 1.32 !important;
  display: block !important;
  text-decoration: none !important;
}

footer#contact .footer-inspiration:hover {
  color: #ffffff !important;
}

/* Hide mobile legal links on desktop (> 900px) */
footer#contact .footer-mobile-legal,
footer#contact .footer-links a.footer-mobile-legal {
  display: none !important;
}

footer#contact .footer-legal {
  display: flex !important;
}

footer#contact .footer-rule {
  background: #5f5f5f !important;
  height: 1px !important;
  margin: 80px 0 42px !important;
  border: none !important;
  display: block !important;
  width: 100% !important;
}

footer#contact .footer-base {
  justify-content: space-between !important;
  align-items: center !important;
  gap: 32px !important;
  display: flex !important;
  width: 100% !important;
}

footer#contact .footer-base > span {
  color: #e4e4e4 !important;
  font-family: Inter, sans-serif !important;
  font-weight: 300 !important;
  font-size: 15px !important;
  line-height: 1.4 !important;
}

footer#contact .footer-social {
  align-items: center !important;
  gap: 36px !important;
  display: flex !important;
}

footer#contact .footer-social a {
  color: #ffffff !important;
  justify-content: center !important;
  align-items: center !important;
  width: 28px !important;
  height: 28px !important;
  transition: color 0.2s ease, transform 0.2s ease !important;
  display: flex !important;
  text-decoration: none !important;
}

footer#contact .footer-social a:hover {
  color: #f6c177 !important;
  transform: translateY(-2px) !important;
}

footer#contact .footer-social svg {
  width: 24px !important;
  height: 24px !important;
  display: block !important;
}

footer#contact .footer-social-icon.footer-icon-desktop {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

footer#contact .footer-social-icon.footer-icon-mobile {
  display: none !important;
}

/* ==================================================================
   RESPONSIVE: TABLET (< 901px)
   ================================================================== */
@media (max-width: 900px) {
  footer#contact {
    padding: 72px 0 110px !important;
  }

  footer#contact .figma-footer {
    width: calc(100% - 48px) !important;
  }

  footer#contact .footer-main {
    grid-template-columns: 1.7fr repeat(2, 1fr) !important;
    gap: 34px !important;
  }

  footer#contact .footer-rule {
    margin: 60px 0 32px !important;
  }

  footer#contact .footer-identity p {
    font-size: 14px !important;
  }

  footer#contact .footer-links a {
    margin-bottom: 18px !important;
    font-size: 14px !important;
  }

  footer#contact .footer-social {
    gap: 25px !important;
  }

  footer#contact .footer-accordion {
    width: 100% !important;
    margin-bottom: 18px !important;
  }

  footer#contact .footer-accordion-toggle {
    color: #aaaaaa !important;
    font-size: 15px !important;
  }

  /* Show mobile legal links inside company column */
  footer#contact .footer-mobile-legal,
  footer#contact .footer-links a.footer-mobile-legal {
    display: block !important;
  }

  /* Hide separate legal column on mobile/tablet */
  footer#contact .footer-legal {
    display: none !important;
  }
}

/* ==================================================================
   RESPONSIVE: MOBILE (< 601px)
   ================================================================== */
@media (max-width: 600px) {
  footer#contact {
    padding: 58px 0 112px !important;
  }

  footer#contact .figma-footer {
    width: calc(100% - 40px) !important;
  }

  footer#contact .footer-main {
    grid-template-columns: 1fr 1fr !important;
    gap: 42px 28px !important;
  }

  footer#contact .footer-identity {
    grid-column: 1 / 3 !important;
  }

  footer#contact .footer-identity .footer-logo {
    width: 104px !important;
    height: auto !important;
    max-height: 68px !important;
    margin-bottom: 25px !important;
  }

  footer#contact .footer-identity p {
    max-width: 100% !important;
  }

  footer#contact .footer-rule {
    margin: 44px 0 28px !important;
  }

  footer#contact .footer-base {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 24px !important;
  }

  footer#contact .footer-base > span {
    font-size: 12px !important;
  }

  footer#contact .footer-social {
    gap: 26px !important;
  }

  footer#contact .footer-social a,
  footer#contact .footer-social svg {
    width: 22px !important;
    height: 22px !important;
  }

  footer#contact .footer-social-icon.footer-icon-desktop {
    display: none !important;
  }

  footer#contact .footer-social-icon.footer-icon-mobile {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
  }
}
</style>

<footer id="contact">
    <div class="figma-footer">
        <div class="footer-main">
            <div class="footer-identity">
                <img class="footer-logo" src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
                <p>For over three generations, Abby Lighting has been one of India's leading architectural lighting manufacturers, designing and manufacturing premium architectural and outdoor lighting entirely in-house. From concept and engineering to precision manufacturing and testing, every luminaire is built for consistency, performance and long-term reliability, making Abby Lighting the trusted partner for architects, interior designers and consultants across residential, commercial and hospitality projects.</p>
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

