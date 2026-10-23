{{-- Next.js Style Header with Mega Dropdown --}}
<link rel="stylesheet" href="{{ asset('css/header.css') }}">

@php
    $archCategories =
        $archCategories ??
        \Illuminate\Support\Facades\Cache::remember('header_ssr_arch_categories', 3600, function () {
            try {
                return \App\Models\Tag::where('is_active', 'yes')
                    ->where('is_featured', 1)
                    ->orderBy('id', 'ASC')
                    ->get(['id', 'name', 'display_name', 'slug'])
                    ->map(function ($tag) {
                        $slug = $tag->slug ?? '';
                        $title = $tag->display_name ?: $tag->name;
                        return [
                            'id' => $tag->id,
                            'title' => $title,
                            'name' => $title,
                            'slug' => $slug,
                            'url' => '/products/category/' . $slug,
                        ];
                    })
                    ->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });

    $decCategories =
        $decCategories ??
        \Illuminate\Support\Facades\Cache::remember('header_ssr_dec_categories', 3600, function () {
            try {
                return \App\Models\Decorative\DecCategory::where('is_featured', 1)
                    ->orderBy('name', 'asc')
                    ->get(['id', 'name', 'slug'])
                    ->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });

    $collections =
        $collections ??
        \Illuminate\Support\Facades\Cache::remember('header_ssr_collections', 3600, function () {
            try {
                return \App\Models\Collection::where('is_active', true)
                    ->ordered()
                    ->get(['id', 'name', 'slug'])
                    ->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });

    $headerMegaItems =
        $headerMegaItems ??
        \Illuminate\Support\Facades\Cache::remember('header_ssr_mega_items', 3600, function () {
            try {
                return \App\Models\MenuItem::where('location', 'header_mega')->active()->ordered()->get()->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });
@endphp

<div class="main-box">
    <header class="sitehead" style="background: #000000 !important; background-color: #000000 !important;">
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
                            <div class="mega-panel"
                                style="background: #000000 !important; background-color: #000000 !important;">
                                <div class="mega-grid">
                                    <div class="mgroup m-arch">
                                        <a href="/products" class="mhead">Architectural</a>
                                        <div class="msub">Browse by category</div>
                                        <ul id="bladeArchitecturalCategories">
                                            @forelse($archCategories as $cat)
                                                <li><a
                                                        href="{{ $cat['url'] ?? '/products/category/' . ($cat['slug'] ?? '') }}">{{ $cat['title'] ?? $cat['name'] }}</a>
                                                </li>
                                            @empty
                                                <li><a href="/products/category/spots-&-accents">Spots &amp; Accents</a>
                                                </li>
                                                <li><a href="/products/category/downlights">Downlights</a></li>
                                                <li><a href="/products/category/profiles">Profiles</a></li>
                                                <li><a href="/products/category/track-lights">Track Lights</a></li>
                                                <li><a href="/products/category/washers-grazers">Washers &amp;
                                                        Grazers</a></li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="msep"></div>
                                    <div class="mgroup m-dec">
                                        <a href="/decorative-lights" class="mhead">
                                            Decorative <span class="mnew">NEW</span>
                                        </a>
                                        <div class="mcols">
                                            <div>
                                                <div class="msub">Browse by category</div>
                                                <ul id="bladeDecCategories">
                                                    @forelse($decCategories as $cat)
                                                        <li><a
                                                                href="/decorative-lights?category={{ urlencode($cat['slug'] ?? $cat['name']) }}">{{ $cat['name'] }}</a>
                                                        </li>
                                                    @empty
                                                        <li><a
                                                                href="/decorative-lights?category=chandelier">Chandelier</a>
                                                        </li>
                                                        <li><a href="/decorative-lights?category=pendant-lights">Pendant
                                                                Lights</a></li>
                                                        <li><a href="/decorative-lights?category=wall-lights">Wall
                                                                Lights</a></li>
                                                        <li><a href="/decorative-lights?category=floor-lamps">Floor
                                                                Lamps</a></li>
                                                        <li><a href="/decorative-lights?category=table-lamps">Table
                                                                Lamps</a></li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            <div class="msep sm"></div>
                                            <div>
                                                <div class="msub">Browse by collection</div>
                                                <ul id="bladeDecCollections">
                                                    @forelse($collections as $col)
                                                        <li><a
                                                                href="/collections/{{ urlencode(strtolower($col['slug'] ?? $col['name'])) }}">{{ $col['name'] }}</a>
                                                        </li>
                                                    @empty
                                                        <li><a href="/collections/symphony">Symphony</a></li>
                                                        <li><a href="/collections/quarry">Quarry</a></li>
                                                        <li><a href="/collections/neoma">Neoma</a></li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="msep lg"></div>
                                    <div class="m-worlds">
                                        @forelse($headerMegaItems as $index => $item)
                                            @if ($index > 0)
                                                <div class="msep hz"></div>
                                            @endif
                                            <a class="mgroup {{ \Illuminate\Support\Str::contains(strtolower($item['title'] ?? ''), 'smart') ? 'm-smart' : 'm-out' }}"
                                                href="{{ $item['url'] ?? '/#worlds' }}"
                                                @if (!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>
                                                <span class="mhead">{{ $item['title'] ?? '' }}</span>
                                            </a>
                                        @empty
                                            <a class="mgroup m-out" href="/#worlds">
                                                <span class="mhead">Outdoor</span>
                                            </a>
                                            <div class="msep hz"></div>
                                            <a class="mgroup m-smart" href="/#worlds">
                                                <span class="mhead">Smart Lighting</span>
                                            </a>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="has-drop">
                        <a class="link" href="/projects">
                            Our Work<span class="caret"></span>
                        </a>
                        <div class="drop"
                            style="background: #000000 !important; background-color: #000000 !important;">
                            <a href="/projects">Projects</a>
                            <a href="/clients">Clients</a>
                        </div>
                    </li>
                    <li>
                        <a class="link" href="/inspiration">Inspiration</a>
                    </li>
                    <li class="has-drop">
                        <a class="link" href="/company">
                            More<span class="caret"></span>
                        </a>
                        <div class="drop"
                            style="background: #000000 !important; background-color: #000000 !important;">
                            <a href="/company">About Us</a>
                            <a href="/contact">Contact Us</a>
                            <a href="/career">Careers</a>
                            <a href="/catalogues">Catalogues</a>
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
                        <input type="search" name="q" placeholder="Search for products, collections and more"
                            aria-label="Search Abby Lighting" autocomplete="off" id="searchInput" />
                        <button class="abby-search-go" type="submit" aria-label="Submit search" disabled>→</button>
                    </form>
                    <button type="button" class="abby-search-toggle" aria-label="Open search" aria-expanded="false"
                        id="searchToggle">
                        <svg viewBox="0 0 24 24" aria-hidden="true" id="searchIcon">
                            <circle cx="10.7" cy="10.7" r="6.7"></circle>
                            <path d="m16 16 4.5 4.5"></path>
                        </svg>
                        <svg viewBox="0 0 24 24" aria-hidden="true" id="closeIcon">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <a href="javascript:void(0)" class="nav-cta" onclick="openInquiryModal(event)" role="button"
                    aria-label="Get in Touch">
                    <span class="lbl-d">Get in Touch</span>
                    <span class="lbl-m">Contact Us</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Floating Bottom Dock (Mobile & Tablet Floating SVG Halo Dock - Exact Next.js Copy) -->
    <nav class="halo-nav-wrap halo-hidden" id="mobileDock" aria-label="Mobile bottom navigation">
        <div class="halo-nav is-ready" id="dockNav">
            <span class="halo-nav-shadow" aria-hidden="true"></span>
            <svg class="halo-nav-skin" id="dockSvg" aria-hidden="true" focusable="false"
                preserveAspectRatio="none">
                <defs>
                    <linearGradient id="haloPlateGradient" x1="0" y1="0" x2="0"
                        y2="1">
                        <stop class="halo-plate-highlight" offset="0"></stop>
                        <stop class="halo-plate-shadow" offset="1"></stop>
                    </linearGradient>
                    <linearGradient id="haloRimGradient" x1="0" y1="0" x2="0"
                        y2="1">
                        <stop class="halo-rim-strong" offset="0"></stop>
                        <stop class="halo-rim-soft" offset="1"></stop>
                    </linearGradient>
                    <linearGradient id="internalHaloPlateGradient" x1="0" y1="0" x2="0"
                        y2="1">
                        <stop class="halo-plate-highlight" offset="0"></stop>
                        <stop class="halo-plate-shadow" offset="1"></stop>
                    </linearGradient>
                    <linearGradient id="internalHaloRimGradient" x1="0" y1="0" x2="0"
                        y2="1">
                        <stop class="halo-rim-strong" offset="0"></stop>
                        <stop class="halo-rim-soft" offset="1"></stop>
                    </linearGradient>
                </defs>
                <path class="halo-nav-plate" id="dockPlate"></path>
            </svg>
            <span class="halo-orb" id="dockOrb" aria-hidden="true"></span>
            <div class="halo-tabs" role="tablist" aria-label="Page sections">
                <button class="halo-tab halo-tab-btn" role="tab" type="button" id="halo-tab-home"
                    data-tab="home" aria-label="Home">
                    <img class="halo-icon halo-dock-img" src="{{ asset('images/mobile-dock/home.png') }}"
                        alt="Home" aria-hidden="true" />
                    <span class="halo-label">Home</span>
                </button>
                <button class="halo-tab halo-tab-btn" role="tab" type="button" id="halo-tab-products"
                    data-tab="products" aria-label="Products">
                    <img class="halo-icon halo-dock-img" src="{{ asset('images/mobile-dock/products.png') }}"
                        alt="Products" aria-hidden="true" />
                    <span class="halo-label">Products</span>
                </button>
                <button class="halo-tab halo-tab-btn" role="tab" type="button" id="halo-tab-work"
                    data-tab="work" aria-label="Our Work">
                    <img class="halo-icon halo-dock-img" src="{{ asset('images/mobile-dock/our-work.png') }}"
                        alt="Our Work" aria-hidden="true" />
                    <span class="halo-label">Our Work</span>
                </button>
                <button class="halo-tab halo-tab-btn" role="tab" type="button" id="halo-tab-inspiration"
                    data-tab="inspiration" aria-label="Inspiration">
                    <img class="halo-icon halo-dock-img" src="{{ asset('images/mobile-dock/inspiration.png') }}"
                        alt="Inspiration" aria-hidden="true" />
                    <span class="halo-label">Inspiration</span>
                </button>
                <button class="halo-tab halo-tab-btn" role="tab" type="button" id="halo-tab-more"
                    data-tab="more" aria-label="More">
                    <img class="halo-icon halo-dock-img" src="{{ asset('images/mobile-dock/more.png') }}"
                        alt="More" aria-hidden="true" />
                    <span class="halo-label">More</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Backdrop for Mobile Drawers -->
    <div class="custom-sheet-backdrop" id="sheetBackdrop" aria-hidden="true"></div>

    <!-- Fullscreen Mobile Drawer Modal -->
    <div class="pdrop-modal" id="sheetModal" role="dialog" aria-modal="true">
        <div class="pdrop-container">
            <div class="pdrop-header">
                <a href="/" class="pdrop-logo">
                    <img src="{{ asset('images/abby-logo.png') }}" alt="Abby Lighting" />
                </a>
                <div class="pdrop-actions">
                    <button type="button" class="pdrop-search-btn" id="modalSearchBtn" aria-label="Search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="M21 21l-4.35-4.35" />
                        </svg>
                    </button>
                    <a href="/contact" class="pdrop-contact-btn">Contact Us</a>
                </div>
            </div>

            <!-- PRODUCTS MENU -->
            <div class="pdrop-body" id="sheetProducts" style="display: none;">
                <div class="pdrop-title-row">
                    <h2 class="pdrop-title">PRODUCTS</h2>
                    <button type="button" class="pdrop-circle-close sheet-close-btn" aria-label="Close menu">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="pdrop-acc" id="modalArchAccordion">
                    <div class="pdrop-acc-toggle">
                        <a href="/products" class="pdrop-acc-heading pdrop-amber-text">Architectural</a>
                        <button type="button" class="pdrop-caret-btn" id="modalArchToggle"
                            aria-label="Toggle Architectural categories">
                            <span class="pdrop-caret">
                                <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                                    <polygon points="0,0 11,0 5.5,7" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div class="pdrop-acc-content" id="modalArchContent" style="display: none;">
                        <div class="pdrop-section-label">BROWSE BY CATEGORY</div>
                        <ul class="pdrop-list" id="modalArchList">
                            @forelse($archCategories as $cat)
                                <li><a
                                        href="{{ $cat['url'] ?? '/products/category/' . ($cat['slug'] ?? '') }}">{{ $cat['title'] ?? $cat['name'] }}</a>
                                </li>
                            @empty
                                <li><a href="/products/category/spots-&-accents">Spots &amp; Accents</a></li>
                                <li><a href="/products/category/downlights">Downlights</a></li>
                                <li><a href="/products/category/profiles">Profiles</a></li>
                                <li><a href="/products/category/track-lights">Track Lights</a></li>
                                <li><a href="/products/category/washers-grazers">Washers &amp; Grazers</a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="pdrop-acc" id="modalDecAccordion">
                    <div class="pdrop-acc-toggle">
                        <a href="/decorative-lights" class="pdrop-acc-heading">
                            <span>Decorative <span class="mnew"
                                    style="margin-left: 6px; font-size: 0.65rem; padding: 1px 5px; border-radius: 3px;">NEW</span></span>
                        </a>
                        <button type="button" class="pdrop-caret-btn" id="modalDecToggle"
                            aria-label="Toggle Decorative categories">
                            <span class="pdrop-caret">
                                <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                                    <polygon points="0,0 11,0 5.5,7" />
                                </svg>
                            </span>
                        </button>
                    </div>
                    <div class="pdrop-acc-content" id="modalDecContent" style="display: none;">
                        <div class="pdrop-section-label">BROWSE BY CATEGORY</div>
                        <ul class="pdrop-list" id="modalDecCatList">
                            @forelse($decCategories as $cat)
                                <li><a
                                        href="/decorative-lights?category={{ urlencode($cat['slug'] ?? $cat['name']) }}">{{ $cat['name'] }}</a>
                                </li>
                            @empty
                                <li><a href="/decorative-lights?category=chandelier">Chandelier</a></li>
                                <li><a href="/decorative-lights?category=pendant-lights">Pendant Lights</a></li>
                                <li><a href="/decorative-lights?category=wall-lights">Wall Lights</a></li>
                                <li><a href="/decorative-lights?category=floor-lamps">Floor Lamps</a></li>
                                <li><a href="/decorative-lights?category=table-lamps">Table Lamps</a></li>
                            @endforelse
                        </ul>
                        <div class="pdrop-section-label" style="margin-top: 18px;">BROWSE BY COLLECTION</div>
                        <ul class="pdrop-list" id="modalDecColList">
                            @forelse($collections as $col)
                                <li><a
                                        href="/collections/{{ urlencode(strtolower($col['slug'] ?? $col['name'])) }}">{{ $col['name'] }}</a>
                                </li>
                            @empty
                                <li><a href="/collections/symphony">Symphony</a></li>
                                <li><a href="/collections/quarry">Quarry</a></li>
                                <li><a href="/collections/neoma">Neoma</a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                @forelse($headerMegaItems as $item)
                    <div class="pdrop-direct-row">
                        <a href="{{ $item['url'] ?? '/#worlds' }}"
                            @if (!empty($item['open_in_new_tab'])) target="_blank" rel="noopener noreferrer" @endif>{{ $item['title'] ?? '' }}</a>
                    </div>
                @empty
                    <div class="pdrop-direct-row">
                        <a href="/#worlds">Outdoor</a>
                    </div>
                    <div class="pdrop-direct-row">
                        <a href="/#worlds">Smart Lighting</a>
                    </div>
                @endforelse
            </div>

            <!-- OUR WORKS MENU -->
            <div class="pdrop-body" id="sheetWork" style="display: none;">
                <div class="pdrop-title-row">
                    <h2 class="pdrop-title">OUR WORKS</h2>
                    <button type="button" class="pdrop-circle-close sheet-close-btn" aria-label="Close menu">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/projects">
                        <span>Projects</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/clients">
                        <span>Clients</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- MORE MENU -->
            <div class="pdrop-body" id="sheetMore" style="display: none;">
                <div class="pdrop-title-row">
                    <h2 class="pdrop-title">MORE</h2>
                    <button type="button" class="pdrop-circle-close sheet-close-btn" aria-label="Close menu">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/company">
                        <span>About</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/career">
                        <span>Careers</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/contact">
                        <span>Contact</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
                <div class="pdrop-arrow-row">
                    <a href="/privacy-policy">
                        <span>Policies</span>
                        <svg class="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="20" y2="12" />
                            <polyline points="14 6 20 12 14 18" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function initHeader() {
            // 1. Mobile Floating SVG Halo Dock & Sheets (Mobile/Tablet only)
            const mobileDock = document.getElementById('mobileDock');
            const navEl = document.getElementById('dockNav');
            const svgEl = document.getElementById('dockSvg');
            const plateEl = document.getElementById('dockPlate');
            const orbEl = document.getElementById('dockOrb');
            const tabs = Array.from(document.querySelectorAll('.halo-tab-btn'));

            const sheetBackdrop = document.getElementById('sheetBackdrop');
            const sheetModal = document.getElementById('sheetModal');
            const sheetProducts = document.getElementById('sheetProducts');
            const sheetWork = document.getElementById('sheetWork');
            const sheetMore = document.getElementById('sheetMore');
            const closeBtns = document.querySelectorAll('.sheet-close-btn');

            function getRouteTab(path) {
                if (path === '/' || path === '') return 'home';
                if (path.includes('/products') || path.includes('/decorative') || path.includes('/collections') ||
                    path.includes('/catalogues')) return 'products';
                if (path.includes('/projects') || path.includes('/clients')) return 'work';
                if (path.includes('/inspiration') || path.includes('/blogs') || path.includes('/news'))
                    return 'inspiration';
                if (path.includes('/company') || path.includes('/contact') || path.includes('/about') || path
                    .includes('/career') || path.includes('/policies') || path.includes('/privacy')) return 'more';
                return 'home';
            }

            const currentPath = window.location.pathname;
            let activeTab = getRouteTab(currentPath);
            let activeSheet = null;
            let dockScrollTimeout = null;
            const TAB_INDEX_MAP = {
                home: 0,
                products: 1,
                work: 2,
                inspiration: 3,
                more: 4
            };

            function showDock() {
                if (!mobileDock) return;
                mobileDock.classList.remove('halo-hidden');
                mobileDock.classList.add('halo-visible');
            }

            function hideDock() {
                if (!mobileDock || activeSheet !== null) return;
                mobileDock.classList.remove('halo-visible');
                mobileDock.classList.add('halo-hidden');
            }

            function scheduleDockHide(delay = 1500) {
                if (dockScrollTimeout) clearTimeout(dockScrollTimeout);
                showDock();
                dockScrollTimeout = setTimeout(() => {
                    hideDock();
                }, delay);
            }

            // Initial page entrance animation for mobile dock
            if (window.innerWidth <= 900) {
                setTimeout(() => {
                    scheduleDockHide(2000);
                }, 150);
            }

            // Pointer & Touch interactions on mobile dock
            if (mobileDock) {
                mobileDock.addEventListener('pointerenter', () => {
                    if (dockScrollTimeout) clearTimeout(dockScrollTimeout);
                    showDock();
                });
                mobileDock.addEventListener('pointerleave', () => {
                    if (activeSheet !== null) return;
                    scheduleDockHide(1500);
                });
                mobileDock.addEventListener('touchstart', () => {
                    if (dockScrollTimeout) clearTimeout(dockScrollTimeout);
                    showDock();
                }, {
                    passive: true
                });

                window.addEventListener('scroll', () => {
                    if (window.innerWidth <= 900) scheduleDockHide(1800);
                }, {
                    passive: true
                });

                window.addEventListener('touchmove', () => {
                    if (window.innerWidth <= 900) scheduleDockHide(1800);
                }, {
                    passive: true
                });
            }

            function openSheet(sheetName) {
                activeSheet = sheetName;
                document.body.style.overflow = 'hidden';
                if (dockScrollTimeout) clearTimeout(dockScrollTimeout);
                showDock();
                if (sheetBackdrop) sheetBackdrop.classList.add('is-open');
                if (sheetModal) sheetModal.classList.add('is-open');
                if (sheetProducts) sheetProducts.style.display = (sheetName === 'products') ? 'block' : 'none';
                if (sheetWork) sheetWork.style.display = (sheetName === 'work') ? 'block' : 'none';
                if (sheetMore) sheetMore.style.display = (sheetName === 'more') ? 'block' : 'none';
            }

            function closeAllSheets() {
                activeSheet = null;
                document.body.style.overflow = '';
                if (sheetBackdrop) sheetBackdrop.classList.remove('is-open');
                if (sheetModal) sheetModal.classList.remove('is-open');
                if (sheetProducts) sheetProducts.style.display = 'none';
                if (sheetWork) sheetWork.style.display = 'none';
                if (sheetMore) sheetMore.style.display = 'none';
                if (window.innerWidth <= 900) {
                    scheduleDockHide(1500);
                }
            }

            if (sheetBackdrop) sheetBackdrop.addEventListener('click', closeAllSheets);
            closeBtns.forEach(btn => btn.addEventListener('click', closeAllSheets));
            if (sheetModal) {
                sheetModal.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link) {
                        link.blur();
                        closeAllSheets();
                    }
                });
            }

            // Halo Navigation Physics (Mobile Only)
            if (navEl && svgEl && plateEl && orbEl && tabs.length) {
                const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const clamp = (v, min, max) => Math.min(max, Math.max(min, v));
                const dist = (a, b, c) => Math.sqrt(Math.max((a + b) ** 2 - (a - c) ** 2, 1));
                const ease = (t) => t * t * (3 - 2 * t);

                const m = {
                    width: 0,
                    height: 0,
                    radius: 6,
                    beadDiameter: 56,
                    beadRadius: 34,
                    shoulderRadius: 12,
                    beadY: 0,
                    slots: [],
                    span: 80,
                };

                let currX = 0;
                let targetX = 0;
                let velX = 0;
                let currentSlotIndex = -1;
                let isPointerDragging = false;
                let animId = 0;
                let lastTime = 0;
                let resizeTimer = 0;
                let activePointerId = null;
                let dragStartX = 0;

                const measure = () => {
                    if (window.innerWidth > 900) return false;
                    const rect = navEl.getBoundingClientRect();
                    const w = Math.round(rect.width);
                    const h = Math.round(rect.height);
                    if (w < 40 || h < 30) return false;

                    m.slots = tabs.map((tab) => {
                        const tRect = tab.getBoundingClientRect();
                        return tRect.left - rect.left + tRect.width / 2;
                    });
                    m.span = m.slots.length > 1 ? m.slots[1] - m.slots[0] : w;
                    m.width = w;
                    m.height = h;
                    m.radius = clamp(h * 0.08, 5, 7);
                    m.beadY = 0;

                    let d = Math.min(h * 0.68, m.span * 0.78);
                    const s = m.slots[0] - m.radius - 6;

                    for (let i = 0; i < 3; i++) {
                        const e = dist(d * 0.22, d / 2 + 6, m.beadY);
                        if (e <= s) break;
                        d *= s / e;
                    }

                    m.beadDiameter = Math.max(Math.round(d), 30);
                    m.shoulderRadius = m.beadDiameter * 0.22;
                    m.beadRadius = m.beadDiameter / 2 + 6;
                    m.beadY = m.beadRadius * 0.2;

                    svgEl.setAttribute('viewBox', `0 0 ${w} ${h}`);
                    navEl.style.setProperty('--halo-radius', `${m.radius.toFixed(1)}px`);
                    navEl.style.setProperty('--halo-size', `${m.beadDiameter}px`);
                    navEl.style.setProperty('--halo-y', `${m.beadY}px`);
                    navEl.style.setProperty('--halo-rise', `${(h / 2 - m.beadY).toFixed(1)}px`);
                    return true;
                };

                const buildIdlePath = () => {
                    const {
                        width: w,
                        height: h,
                        radius: r
                    } = m;
                    const str = (v) => v.toFixed(2);
                    return `M0 ${str(r)}A${str(r)} ${str(r)} 0 0 1 ${str(r)} 0L${str(w - r)} 0A${str(r)} ${str(r)} 0 0 1 ${str(w)} ${str(r)}L${str(w)} ${str(h - r)}A${str(r)} ${str(r)} 0 0 1 ${str(w - r)} ${str(h)}L${str(r)} ${str(h)}A${str(r)} ${str(r)} 0 0 1 0 ${str(h - r)}Z`;
                };

                const buildActivePath = (x, sLeft, sRight) => {
                    const {
                        width: w,
                        height: h,
                        radius: r,
                        beadRadius: br,
                        beadY: by
                    } = m;
                    const c = x - br;
                    const l = x + br;
                    const u = by - br;
                    const d = clamp(r + 2, 7, br * 0.34);
                    const f = clamp(c - sLeft, r, w - r);
                    const p = clamp(l + sRight, r, w - r);
                    const str = (v) => v.toFixed(2);

                    return `M0 ${str(r)}A${str(r)} ${str(r)} 0 0 1 ${str(r)} 0L${str(f)} 0C${str(c - sLeft * 0.42)} 0 ${str(c)} 0 ${str(c)} ${str(-d)}L${str(c)} ${str(u + d)}Q${str(c)} ${str(u)} ${str(c + d)} ${str(u)}L${str(l - d)} ${str(u)}Q${str(l)} ${str(u)} ${str(l)} ${str(u + d)}L${str(l)} ${str(-d)}C${str(l)} 0 ${str(l + sRight * 0.42)} 0 ${str(p)} 0L${str(w - r)} 0A${str(r)} ${str(r)} 0 0 1 ${str(w)} ${str(r)}L${str(w)} ${str(h - r)}A${str(r)} ${str(r)} 0 0 1 ${str(w - r)} ${str(h)}L${str(r)} ${str(h)}A${str(r)} ${str(r)} 0 0 1 0 ${str(h - r)}Z`;
                };

                const setIdle = () => {
                    plateEl.setAttribute('d', buildIdlePath());
                    navEl.classList.add('is-idle');
                    tabs.forEach((tab, i) => {
                        tab.setAttribute('aria-selected', 'false');
                        tab.tabIndex = i === 0 ? 0 : -1;
                        tab.style.setProperty('--halo-active', '0');
                    });
                };

                const renderState = () => {
                    const normV = clamp(velX / 1100, -1, 1) * (isPointerDragging ? 0.5 : 1);
                    const absV = Math.abs(normV);
                    const sLeft = clamp(m.shoulderRadius * (1 + 0.06 * absV + 0.4 * normV), m.shoulderRadius *
                        0.55, m.shoulderRadius * 2.1);
                    const sRight = clamp(m.shoulderRadius * (1 + 0.06 * absV - 0.4 * normV), m.shoulderRadius *
                        0.55, m.shoulderRadius * 2.1);

                    plateEl.setAttribute('d', buildActivePath(currX, sLeft, sRight));

                    const scaleS = 1 + 0.07 * absV;
                    orbEl.style.transform =
                        `translate3d(${currX.toFixed(2)}px,0,0) scale(${scaleS.toFixed(3)},${(1 / scaleS).toFixed(3)})`;

                    tabs.forEach((tab, i) => {
                        const activeRatio = ease(clamp(1 - Math.abs(currX - m.slots[i]) / (m.span *
                            0.55), 0, 1));
                        tab.style.setProperty('--halo-active', activeRatio.toFixed(3));
                    });
                };

                const loopStep = (now) => {
                    animId = 0;
                    const dt = Math.min((now - lastTime) / 1000, 1 / 30);
                    lastTime = now;

                    const stiffness = isPointerDragging ? 900 : 142;
                    const damping = isPointerDragging ? 52 : 19.3;

                    let remaining = dt;
                    while (remaining > 0) {
                        const step = Math.min(remaining, 1 / 240);
                        velX += (-stiffness * (currX - targetX) - damping * velX) * step;
                        currX += velX * step;
                        remaining -= step;
                    }

                    renderState();

                    if (Math.abs(currX - targetX) > 0.05 || Math.abs(velX) > 0.6 || isPointerDragging) {
                        triggerPhysicsLoop();
                    } else {
                        currX = targetX;
                        velX = 0;
                        renderState();
                    }
                };

                const triggerPhysicsLoop = () => {
                    if (!animId) {
                        lastTime = performance.now();
                        animId = requestAnimationFrame(loopStep);
                    }
                };

                const animateToSlot = (pos) => {
                    targetX = pos;
                    if (prefersReducedMotion() && !isPointerDragging) {
                        currX = targetX;
                        velX = 0;
                        renderState();
                        return;
                    }
                    triggerPhysicsLoop();
                };

                const selectTab = (idx, options = {}) => {
                    const {
                        focus = false, animate = true
                    } = options;
                    if (idx == null || idx < 0 || !m.slots.length) {
                        currentSlotIndex = -1;
                        setIdle();
                        return;
                    }
                    const slotIdx = (idx + tabs.length) % tabs.length;
                    const isSame = currentSlotIndex === slotIdx;
                    currentSlotIndex = slotIdx;
                    navEl.classList.remove('is-idle');

                    tabs.forEach((tab, i) => {
                        tab.setAttribute('aria-selected', String(i === currentSlotIndex));
                        tab.tabIndex = i === currentSlotIndex ? 0 : -1;
                    });

                    if (focus) tabs[currentSlotIndex].focus();

                    if (animate) {
                        animateToSlot(m.slots[currentSlotIndex]);
                    } else if (!isSame || Math.abs(currX - m.slots[currentSlotIndex]) > 0.05) {
                        currX = targetX = m.slots[currentSlotIndex];
                        velX = 0;
                        renderState();
                    }
                };

                const handlePointerDown = (e) => {
                    if (e.button !== 0 && e.pointerType === 'mouse') return;
                    activePointerId = e.pointerId;
                    dragStartX = e.clientX;
                };

                const handlePointerMove = (e) => {
                    if (e.pointerId !== activePointerId) return;
                    if (!isPointerDragging && Math.abs(e.clientX - dragStartX) < 14) return;

                    if (!isPointerDragging) {
                        isPointerDragging = true;
                        navEl.classList.remove('is-idle');
                        navEl.classList.add('is-dragging');
                        if (navEl.setPointerCapture) {
                            try {
                                navEl.setPointerCapture(activePointerId);
                            } catch (err) {}
                        }
                    }

                    e.preventDefault();
                    const rect = navEl.getBoundingClientRect();
                    targetX = clamp(e.clientX - rect.left, m.slots[0], m.slots[m.slots.length - 1]);
                    triggerPhysicsLoop();
                };

                const handlePointerUp = (e) => {
                    if (e.pointerId !== activePointerId) return;
                    activePointerId = null;

                    if (!isPointerDragging) return;
                    isPointerDragging = false;
                    navEl.classList.remove('is-dragging');

                    let bestIndex = 0;
                    let minDistance = Infinity;
                    m.slots.forEach((slotPos, i) => {
                        const distance = Math.abs(targetX - slotPos);
                        if (distance < minDistance) {
                            minDistance = distance;
                            bestIndex = i;
                        }
                    });

                    onTabSelected(bestIndex);
                };

                navEl.addEventListener('pointerdown', handlePointerDown);
                navEl.addEventListener('pointermove', handlePointerMove);
                navEl.addEventListener('pointerup', handlePointerUp);
                navEl.addEventListener('pointercancel', handlePointerUp);

                function onTabSelected(idx) {
                    const tabBtn = tabs[idx];
                    if (!tabBtn) return;
                    const tabId = tabBtn.getAttribute('data-tab');
                    activeTab = tabId;
                    selectTab(idx, {
                        animate: true
                    });

                    if (tabId === 'home') {
                        closeAllSheets();
                        if (window.location.pathname === '/') {
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            window.location.href = '/';
                        }
                    } else if (tabId === 'inspiration') {
                        closeAllSheets();
                        window.location.href = '/inspiration';
                    } else if (tabId === 'products' || tabId === 'work' || tabId === 'more') {
                        if (activeSheet === tabId) {
                            closeAllSheets();
                        } else {
                            openSheet(tabId);
                        }
                    }
                }

                tabs.forEach((tab, idx) => {
                    tab.addEventListener('click', (e) => {
                        e.preventDefault();
                        onTabSelected(idx);
                    });
                });

                // Safe measurement without infinite polling
                const updateLayout = (animate = false) => {
                    if (!measure()) return;
                    const initialIdx = TAB_INDEX_MAP[activeTab] ?? -1;
                    if (initialIdx < 0) {
                        currX = targetX = velX = 0;
                        setIdle();
                    } else {
                        selectTab(initialIdx, {
                            animate
                        });
                    }
                    navEl.classList.add('is-ready');
                };

                if (window.innerWidth <= 900) {
                    updateLayout(false);
                }

                window.addEventListener('load', () => {
                    if (window.innerWidth <= 900) updateLayout(false);
                });

                if (window.ResizeObserver) {
                    const ro = new ResizeObserver(() => {
                        cancelAnimationFrame(resizeTimer);
                        resizeTimer = requestAnimationFrame(() => {
                            if (window.innerWidth <= 900) updateLayout(false);
                        });
                    });
                    ro.observe(navEl);
                }

                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(() => {
                        if (window.innerWidth <= 900) updateLayout(false);
                    });
                }
            }

            // 2. Modal Drawer Accordion Toggles
            const modalArchToggle = document.getElementById('modalArchToggle');
            const modalArchAccordion = document.getElementById('modalArchAccordion');
            if (modalArchToggle && modalArchAccordion) {
                modalArchToggle.addEventListener('click', function() {
                    const isOpen = modalArchAccordion.classList.toggle('is-open');
                    const content = document.getElementById('modalArchContent');
                    if (content) content.style.display = isOpen ? 'block' : 'none';
                    const caret = modalArchToggle.querySelector('.pdrop-caret');
                    if (caret) caret.style.transform = isOpen ? 'rotate(180deg)' : 'none';
                    const text = modalArchAccordion.querySelector('.pdrop-acc-heading');
                    if (text) text.classList.toggle('pdrop-amber-text', isOpen);
                });
            }

            const modalDecToggle = document.getElementById('modalDecToggle');
            const modalDecAccordion = document.getElementById('modalDecAccordion');
            if (modalDecToggle && modalDecAccordion) {
                modalDecToggle.addEventListener('click', function() {
                    const isOpen = modalDecAccordion.classList.toggle('is-open');
                    const content = document.getElementById('modalDecContent');
                    if (content) content.style.display = isOpen ? 'block' : 'none';
                    const caret = modalDecToggle.querySelector('.pdrop-caret');
                    if (caret) caret.style.transform = isOpen ? 'rotate(180deg)' : 'none';
                    const text = modalDecAccordion.querySelector('.pdrop-acc-heading');
                    if (text) text.classList.toggle('pdrop-amber-text', isOpen);
                });
            }

            // 3. Modal Search Trigger
            const modalSearchBtn = document.getElementById('modalSearchBtn');
            if (modalSearchBtn) {
                modalSearchBtn.addEventListener('click', function() {
                    closeAllSheets();
                    const searchToggle = document.getElementById('searchToggle');
                    if (searchToggle) searchToggle.click();
                });
            }

            // 4. Header Search Toggle & Submit Logic
            const searchToggle = document.getElementById('searchToggle');
            const searchContainer = document.getElementById('searchContainer');
            const searchInput = document.getElementById('searchInput');

            if (searchToggle && searchContainer) {
                searchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = searchContainer.classList.toggle('is-open');
                    searchToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    searchToggle.setAttribute('aria-label', isOpen ? 'Close search' : 'Open search');
                    if (isOpen && searchInput) {
                        setTimeout(() => searchInput.focus(), 120);
                    }
                });

                document.addEventListener('pointerdown', function(e) {
                    if (!searchContainer.contains(e.target)) {
                        if (searchContainer.classList.contains('is-open')) {
                            searchContainer.classList.remove('is-open');
                            searchToggle.setAttribute('aria-expanded', 'false');
                            searchToggle.setAttribute('aria-label', 'Open search');
                        }
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && searchContainer.classList.contains('is-open')) {
                        searchContainer.classList.remove('is-open');
                        searchToggle.setAttribute('aria-expanded', 'false');
                        searchToggle.setAttribute('aria-label', 'Open search');
                    }
                });

                const searchForm = searchContainer.querySelector('.abby-search-form');
                if (searchForm && searchInput) {
                    searchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const q = searchInput.value.trim();
                        if (q) {
                            window.location.href = '/search?q=' + encodeURIComponent(q);
                        }
                    });
                    searchInput.addEventListener('input', function() {
                        const goBtn = searchForm.querySelector('.abby-search-go');
                        if (goBtn) {
                            goBtn.disabled = !this.value.trim();
                        }
                    });
                }
            }

            // 5. Throttled Scroll Handler (Header Background & Mobile Dock)
            const siteHeader = document.querySelector('header.sitehead');
            const isHomePage = window.location.pathname === '/' || window.location.pathname === '';

            function handleHeaderScroll() {
                if (!siteHeader) return;
                const isScrolled = !isHomePage || window.scrollY > 20;
                if (siteHeader.classList.contains('scrolled') !== isScrolled) {
                    siteHeader.classList.toggle('scrolled', isScrolled);
                }
            }

            handleHeaderScroll();

            let isScrollTicking = false;
            window.addEventListener('scroll', function() {
                if (!isScrollTicking) {
                    requestAnimationFrame(function() {
                        handleHeaderScroll();
                        if (window.innerWidth <= 900) {
                            scheduleDockHide(1500);
                        }
                        isScrollTicking = false;
                    });
                    isScrollTicking = true;
                }
            }, {
                passive: true
            });

            // 6. Desktop Navigation Auto-Close on Click & Focus Cleanup
            const desktopNav = document.querySelector('.sitehead nav');
            if (desktopNav) {
                desktopNav.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    // Immediately blur clicked element and current focus
                    link.blur();
                    if (document.activeElement && typeof document.activeElement.blur === 'function') {
                        document.activeElement.blur();
                    }

                    // Add menu-force-closed to parent dropdown so it closes immediately
                    const parentDropdown = link.closest('.has-mega, .has-drop');
                    if (parentDropdown) {
                        parentDropdown.classList.add('menu-force-closed');
                        setTimeout(() => {
                            parentDropdown.classList.remove('menu-force-closed');
                        }, 350);
                    }
                });

                desktopNav.querySelectorAll('.has-mega, .has-drop').forEach(item => {
                    item.addEventListener('mouseleave', function() {
                        this.classList.remove('menu-force-closed');
                    });
                    item.addEventListener('pointerleave', function() {
                        this.classList.remove('menu-force-closed');
                    });
                });

                // Click outside navigation immediately blurs any lingering focus
                document.addEventListener('pointerdown', function(e) {
                    if (desktopNav && !desktopNav.contains(e.target)) {
                        if (document.activeElement && desktopNav.contains(document.activeElement)) {
                            document.activeElement.blur();
                        }
                    }
                });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHeader);
        } else {
            initHeader();
        }
    })();
</script>

@include('partials.common.inquiry-modal')
