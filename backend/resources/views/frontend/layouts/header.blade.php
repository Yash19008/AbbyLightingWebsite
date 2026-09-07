{{-- Header with search and navigation --}}
<header class="sitehead" id="mainHeader">
    <div class="wrap">
        <a href="/" class="logo" aria-label="Abby Lighting home">
            <img class="logo-asset" src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
        </a>
        
        <nav aria-label="Primary navigation">
            <ul>
                <li class="has-mega">
                    <a class="link" href="/products">
                        Product<span class="caret"></span>
                    </a>
                    <div class="mega">
                        <div class="mega-panel">
                            <div class="mega-grid">
                                {{-- ARCHITECTURAL - Static --}}
                                <div class="mgroup m-arch">
                                    <div class="mhead">Architectural</div>
                                    <div class="msub">Browse by category</div>
                                    <ul>
                                        <li><a href="/products">Spots &amp; Accents</a></li>
                                        <li><a href="/products">Downlights</a></li>
                                        <li><a href="/products">Profiles</a></li>
                                        <li><a href="/products">Track Lights</a></li>
                                        <li><a href="/products">Washers &amp; Grazers</a></li>
                                    </ul>
                                </div>

                                <div class="msep"></div>

                                {{-- DECORATIVE - Dynamic (only categories with products) --}}
                                <div class="mgroup m-dec">
                                    <div class="mhead">Decorative <span class="mnew">NEW</span></div>
                                    @if(isset($decorativeCategories) && count($decorativeCategories) > 0)
                                        <div class="msub">Browse by category</div>
                                        <ul>
                                            @foreach($decorativeCategories as $category)
                                                <li><a href="/decorative/{{ $category['slug'] }}">{{ $category['name'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{-- Fallback static content if no products/categories exist --}}
                                        <div class="msub">Browse by category</div>
                                        <ul>
                                            <li><a href="/decorative">Chandelier</a></li>
                                            <li><a href="/decorative">Pendant Lights</a></li>
                                            <li><a href="/decorative">Wall Lights</a></li>
                                            <li><a href="/decorative">Floor Lamps</a></li>
                                            <li><a href="/decorative">Table Lamps</a></li>
                                        </ul>
                                    @endif
                                </div>

                                <div class="msep"></div>

                                {{-- OUTDOOR - Single Container with Two Options Side by Side --}}
                                <div class="mgroup m-out">
                                    <div class="outdoor-container">
                                        {{-- Outdoor Option --}}
                                        <a href="/products" class="outdoor-option">
                                            OUTDOOR
                                        </a>
                                        
                                        {{-- Smart Lighting Option --}}
                                        <a href="/abby-smart" class="smart-lighting-option">
                                            SMART LIGHTING
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="has-drop">
                    <a class="link" href="/#projects">
                        Our Work<span class="caret"></span>
                    </a>
                    <div class="drop">
                        <a href="/#projects">Projects</a>
                        <a href="/clients">Clients</a>
                    </div>
                </li>
                <li>
                    <a class="link" href="/#news">Inspiration</a>
                </li>
                <li class="has-drop">
                    <a class="link" href="/#contact">
                        More<span class="caret"></span>
                    </a>
                    <div class="drop">
                        <a href="/about">About Us</a>
                        <a href="/contact">Contact Us</a>
                        <a href="/careers">Careers</a>
                        <a href="/#contact">Catalogues</a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <div class="right">
            <div class="abby-search" id="searchContainer">
                <form class="abby-search-form" role="search" action="/search" method="GET">
                    <span class="abby-search-leading">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="10.7" cy="10.7" r="6.7"></circle>
                            <path d="m16 16 4.5 4.5"></path>
                        </svg>
                    </span>
                    <input 
                        type="search" 
                        name="q"
                        placeholder="Search for products, collections and more" 
                        aria-label="Search Abby Lighting" 
                        autocomplete="off" 
                        id="searchInput"
                    />
                    <button class="abby-search-go" type="submit" aria-label="Submit search" id="searchSubmit">
                        →
                    </button>
                </form>
                <button type="button" class="abby-search-toggle" aria-label="Open search" aria-expanded="false" id="searchToggle">
                    <svg viewBox="0 0 24 24" aria-hidden="true" id="searchIcon">
                        <circle cx="10.7" cy="10.7" r="6.7"></circle>
                        <path d="m16 16 4.5 4.5"></path>
                    </svg>
                    <svg viewBox="0 0 24 24" aria-hidden="true" id="closeIcon" style="display: none;">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <a href="/contact" class="nav-cta">
                <span class="lbl-d">Get in Touch</span>
                <span class="lbl-m">Contact</span>
            </a>
        </div>
    </div>
</header>

{{-- Mobile Halo Navigation --}}
<nav class="halo-nav-wrap halo-visible" aria-label="Mobile sections">
    <div class="halo-nav">
        <span class="halo-nav-shadow" aria-hidden="true"></span>
        <svg class="halo-nav-skin" aria-hidden="true" focusable="false" preserveAspectRatio="none">
            <defs>
                <linearGradient id="haloPlateGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop class="halo-plate-highlight" offset="0"></stop>
                    <stop class="halo-plate-shadow" offset="1"></stop>
                </linearGradient>
                <linearGradient id="haloRimGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop class="halo-rim-strong" offset="0"></stop>
                    <stop class="halo-rim-soft" offset="1"></stop>
                </linearGradient>
            </defs>
            <path class="halo-nav-plate"></path>
        </svg>
        <span class="halo-orb" aria-hidden="true"></span>
        <div class="halo-tabs" role="tablist" aria-label="Page sections">
            <button class="halo-tab" role="tab" type="button" id="halo-tab-home" aria-selected="false" tabindex="0">
                <svg class="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3.5 10.6 12 3.9l8.5 6.7"></path>
                    <path d="M5.7 9.2v9.1a1.6 1.6 0 0 0 1.6 1.6h9.4a1.6 1.6 0 0 0 1.6-1.6V9.2"></path>
                </svg>
                <span class="halo-label">Home</span>
            </button>
            <button class="halo-tab" role="tab" type="button" id="halo-tab-product" aria-selected="false" tabindex="-1">
                <svg class="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 3.4v3.1"></path>
                    <path d="M6.6 13.4a5.4 5.4 0 0 1 10.8 0Z"></path>
                    <path d="M9.7 13.4a2.3 2.3 0 0 0 4.6 0"></path>
                </svg>
                <span class="halo-label">Products</span>
            </button>
            <button class="halo-tab" role="tab" type="button" id="halo-tab-work" aria-selected="false" tabindex="-1">
                <svg class="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3.5 7.1h6.2l2 2h8.8v8.7a1.8 1.8 0 0 1-1.8 1.8H5.3a1.8 1.8 0 0 1-1.8-1.8Z"></path>
                    <path d="M3.5 10h17"></path>
                </svg>
                <span class="halo-label">Our Work</span>
            </button>
            <button class="halo-tab" role="tab" type="button" id="halo-tab-inspiration" aria-selected="false" tabindex="-1">
                <svg class="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9.1 16.3a5 5 0 1 1 5.8 0 1.6 1.6 0 0 0-.6 1.2v.4H9.7v-.4a1.6 1.6 0 0 0-.6-1.2Z"></path>
                    <path d="M10 20.1h4"></path>
                </svg>
                <span class="halo-label">Inspiration</span>
            </button>
            <button class="halo-tab" role="tab" type="button" id="halo-tab-more" aria-selected="false" tabindex="-1">
                <svg class="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4.5 8.2h15"></path>
                    <path d="M4.5 12h15"></path>
                    <path d="M4.5 15.8h15"></path>
                </svg>
                <span class="halo-label">More</span>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<button class="menu-backdrop" aria-label="Close menu" id="menuBackdrop"></button>
<div class="msheet" role="dialog" aria-modal="true" aria-label="Mobile menu" id="mobileMenu">
    <button class="menu-close" aria-label="Close menu" id="menuClose">
        <span aria-hidden="true">×</span>
    </button>
    <div class="m-dash"></div>
    <div class="m-title"></div>
    <div class="mobile-menu-list"></div>
</div>
