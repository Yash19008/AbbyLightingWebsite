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

  footer#contact .footer-base > span {
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

  footer#contact .footer-social a,
  footer#contact .footer-social-icon {
    width: 30px !important;
    height: 32px !important;
  }

  footer#contact .footer-social svg {
    width: 27px !important;
    height: 27px !important;
    display: block !important;
  }
}

/* ==================================================================
   TABLET (601px to 900px)
   ================================================================== */
@media (max-width: 900px) and (min-width: 601px) {
  footer#contact {
    padding: 72px 0 110px !important;
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
    width: 112px !important;
    height: 74px !important;
    margin-bottom: 30px !important;
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

  footer#contact .footer-base > span {
    color: #e4e4e4 !important;
    font-size: 14px !important;
  }

  footer#contact .footer-social {
    gap: 25px !important;
    display: flex !important;
    align-items: center !important;
  }
}

/* ==================================================================
   MOBILE (<= 600px)
   ================================================================== */
@media (max-width: 600px) {
  footer#contact {
    padding: 58px 0 112px !important;
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
    gap: 42px 28px !important;
    display: grid !important;
  }

  footer#contact .footer-identity {
    position: static !important;
    grid-column: 1 / 3 !important;
  }

  footer#contact .footer-identity .footer-logo {
    width: 104px !important;
    height: 68px !important;
    margin-bottom: 25px !important;
  }

  footer#contact .footer-identity p {
    max-width: 320px !important;
    font-size: 14px !important;
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
    margin: 54px 0 30px !important;
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

  footer#contact .footer-base > span {
    font-size: 12px !important;
  }

  footer#contact .footer-social {
    gap: 30px !important;
  }

  footer#contact .footer-social a,
  footer#contact .footer-social svg {
    width: 24px !important;
    height: 24px !important;
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
                </a>
                <a href="#" aria-label="LinkedIn">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path>
                        </svg>
                    </span>
                </a>
                <a href="#" aria-label="Facebook">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path>
                        </svg>
                    </span>
                </a>
                <a href="#" aria-label="YouTube">
                    <span class="footer-social-icon footer-icon-desktop">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 576 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path>
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
