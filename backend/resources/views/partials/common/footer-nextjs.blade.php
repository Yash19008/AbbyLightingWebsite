{{-- Next.js Style Footer with Accordion - 1:1 Pixel-Identical Match to Next.js Frontend --}}
@php
  $footerCol1 = $footerCol1 ?? \Illuminate\Support\Facades\Cache::remember('footer_ssr_col_1', 3600, function () {
      try {
          return \App\Models\MenuItem::with('children')
              ->where('location', 'footer_col_1')
              ->whereNull('parent_id')
              ->active()
              ->ordered()
              ->get()
              ->toArray();
      } catch (\Throwable $e) {
          return [];
      }
  });

  $footerCol2 = $footerCol2 ?? \Illuminate\Support\Facades\Cache::remember('footer_ssr_col_2', 3600, function () {
      try {
          return \App\Models\MenuItem::where('location', 'footer_col_2')
              ->active()
              ->ordered()
              ->get()
              ->toArray();
      } catch (\Throwable $e) {
          return [];
      }
  });

  $footerCol3 = $footerCol3 ?? \Illuminate\Support\Facades\Cache::remember('footer_ssr_col_3', 3600, function () {
      try {
          return \App\Models\MenuItem::where('location', 'footer_col_3')
              ->active()
              ->ordered()
              ->get()
              ->toArray();
      } catch (\Throwable $e) {
          return [];
      }
  });
@endphp
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

    footer#contact .footer-links a.footer-mobile-legal,
    footer#contact a.footer-mobile-legal,
    footer#contact .footer-mobile-legal {
        display: none !important;
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

        footer#contact .footer-links a.footer-mobile-legal,
        footer#contact a.footer-mobile-legal,
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
            gap: 24px !important;
            display: flex !important;
        }

        footer#contact .footer-social a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            color: #ffffff !important;
            text-decoration: none !important;
            transition: color 0.2s ease, transform 0.2s ease, opacity 0.2s ease !important;
        }

        footer#contact .footer-social a:hover {
            color: #f6c177 !important;
            transform: translateY(-2px) !important;
            opacity: 1 !important;
        }

        footer#contact .footer-social-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 22px !important;
            height: 22px !important;
            padding: 0 !important;
        }

        footer#contact .footer-social svg {
            width: 20px !important;
            height: 20px !important;
            display: block !important;
            fill: currentColor !important;
            transition: inherit !important;
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

        footer#contact .footer-links a.footer-mobile-legal,
        footer#contact a.footer-mobile-legal,
        footer#contact .footer-mobile-legal {
            display: none !important;
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
            gap: 20px !important;
            display: flex !important;
            align-items: center !important;
        }

        footer#contact .footer-social a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            text-decoration: none !important;
        }

        footer#contact .footer-social-icon {
            width: 22px !important;
            height: 22px !important;
            padding: 0 !important;
        }

        footer#contact .footer-social svg {
            width: 20px !important;
            height: 20px !important;
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

        footer#contact .footer-links a.footer-mobile-legal,
        footer#contact a.footer-mobile-legal,
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
            gap: 20px !important;
            margin-top: 31px !important;
            display: flex !important;
            align-items: center !important;
        }

        footer#contact .footer-social a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            text-decoration: none !important;
        }

        footer#contact .footer-social-icon {
            width: 22px !important;
            height: 22px !important;
            padding: 0 !important;
        }

        footer#contact .footer-social svg {
            width: 20px !important;
            height: 20px !important;
        }
    }
</style>

<footer id="contact">
    <div class="figma-footer">
        <div class="footer-main">
            <div class="footer-identity">
                <img class="footer-logo" src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
                <p>For over three generations, Abby Lighting has been one of India's leading architectural lighting
                    manufacturers, designing and manufacturing premium architectural, decorative and outdoor lighting
                    entirely
                    in-house. From concept and engineering to precision manufacturing and testing, every luminaire is
                    built for
                    consistency, performance and long-term reliability, making Abby Lighting the trusted partner for
                    architects,
                    interior designers and consultants across residential, commercial and hospitality projects.</p>
            </div>

            <nav class="footer-links footer-products" aria-label="Product links">
              @if(!empty($footerCol1) && count($footerCol1) > 0)
                @foreach($footerCol1 as $item)
                  @if(($item['type'] ?? '') === 'group')
                    @php
                      $accId = 'footer-' . \Illuminate\Support\Str::slug($item['title'] ?? 'group');
                    @endphp
                    <div class="footer-accordion" id="{{ $accId }}Accordion">
                      <button class="footer-accordion-toggle" type="button" aria-expanded="false"
                          aria-controls="{{ $accId }}">
                        <span>{{ $item['title'] ?? '' }}</span>
                        <span class="footer-accordion-icon" aria-hidden="true"></span>
                      </button>
                      <div class="footer-accordion-panel" id="{{ $accId }}" aria-hidden="true">
                        @foreach($item['children'] ?? [] as $child)
                          <a href="{{ $child['url'] ?? '/' }}" @if(!empty($child['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $child['title'] ?? '' }}</a>
                        @endforeach
                      </div>
                    </div>
                  @else
                    <a class="footer-inspiration" href="{{ $item['url'] ?? '/inspiration' }}" @if(!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $item['title'] ?? '' }}</a>
                  @endif
                @endforeach
              @else
                <div class="footer-accordion" id="footerProductsAccordion">
                    <button class="footer-accordion-toggle" type="button" aria-expanded="false"
                        aria-controls="footer-products">
                        <span>Products</span>
                        <span class="footer-accordion-icon" aria-hidden="true"></span>
                    </button>
                    <div class="footer-accordion-panel" id="footer-products" aria-hidden="true">
                        <a href="/products">Architectural</a>
                        <a href="/decorative-products">Decorative</a>
                        <a href="/#worlds">Outdoor</a>
                        <a href="/abby-smart">Smart Lighting</a>
                    </div>
                </div>

                <div class="footer-accordion" id="footerWorkAccordion">
                    <button class="footer-accordion-toggle" type="button" aria-expanded="false"
                        aria-controls="footer-our-work">
                        <span>Our Work</span>
                        <span class="footer-accordion-icon" aria-hidden="true"></span>
                    </button>
                    <div class="footer-accordion-panel" id="footer-our-work" aria-hidden="true">
                        <a href="/clients">Clients</a>
                        <a href="/projects">Projects</a>
                    </div>
                </div>

                <a class="footer-inspiration" href="/inspiration">Inspiration</a>
              @endif
            </nav>

            <nav class="footer-links footer-company" aria-label="Company links">
              @if(!empty($footerCol2) && count($footerCol2) > 0)
                @foreach($footerCol2 as $item)
                  <a href="{{ $item['url'] ?? '/' }}" @if(!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $item['title'] ?? '' }}</a>
                @endforeach
              @else
                <a href="/company">About Us</a>
                <a href="/contact">Contact Us</a>
                <a href="/career">Careers</a>
                <a href="/catalogues">Catalogues</a>
                <a href="/fair-events">Fairs &amp; Events</a>
              @endif

              {{-- Mobile-only legal links (shown only on mobile <= 900px, hidden on desktop) --}}
              @if(!empty($footerCol3) && count($footerCol3) > 0)
                @foreach($footerCol3 as $item)
                  <a class="footer-mobile-legal" href="{{ $item['url'] ?? '/' }}" @if(!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $item['title'] ?? '' }}</a>
                @endforeach
              @else
                <a class="footer-mobile-legal" href="/privacy-policy">Privacy Policy</a>
                <a class="footer-mobile-legal" href="/terms-and-conditions">Terms of Use</a>
              @endif
            </nav>

            <nav class="footer-links footer-legal" aria-label="Legal links">
              @if(!empty($footerCol3) && count($footerCol3) > 0)
                @foreach($footerCol3 as $item)
                  <a href="{{ $item['url'] ?? '/' }}" @if(!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $item['title'] ?? '' }}</a>
                @endforeach
              @else
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/terms-and-conditions">Terms of Use</a>
              @endif
            </nav>
        </div>

        <div class="footer-rule"></div>

        <div class="footer-base">
            <span>© 2026 Abby Lighting. All rights reserved.</span>
            <div class="footer-social">
                <a href="https://www.instagram.com/abbylighting/" target="_blank" rel="noopener noreferrer"
                    aria-label="Instagram">
                    <span class="footer-social-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </span>
                </a>
                <a href="https://www.linkedin.com/company/abby-lighting/" target="_blank" rel="noopener noreferrer"
                    aria-label="LinkedIn">
                    <span class="footer-social-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </span>
                </a>
                <a href="https://www.facebook.com/abbylighting/" target="_blank" rel="noopener noreferrer"
                    aria-label="Facebook">
                    <span class="footer-social-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.1v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                        </svg>
                    </span>
                </a>
                <a href="https://www.youtube.com/@abby-lighting" target="_blank" rel="noopener noreferrer"
                    aria-label="YouTube">
                    <span class="footer-social-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
    (function () {
        function initFooterAccordions() {
            const footerEl = document.getElementById('contact');
            if (!footerEl || footerEl.dataset.accordionsInitialized) return;
            footerEl.dataset.accordionsInitialized = 'true';

            footerEl.addEventListener('click', function (e) {
                const toggle = e.target.closest('.footer-accordion-toggle');
                if (!toggle) return;
                e.preventDefault();

                const accordion = toggle.closest('.footer-accordion');
                if (!accordion) return;

                const willOpen = !accordion.classList.contains('is-open') && !accordion.classList.contains('open');
                accordion.classList.toggle('is-open', willOpen);
                accordion.classList.toggle('open', willOpen);
                toggle.setAttribute('aria-expanded', willOpen ? 'true' : 'false');

                const panel = accordion.querySelector('.footer-accordion-panel');
                if (panel) {
                    panel.setAttribute('aria-hidden', willOpen ? 'false' : 'true');
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFooterAccordions);
        } else {
            initFooterAccordions();
        }
    })();
</script>

