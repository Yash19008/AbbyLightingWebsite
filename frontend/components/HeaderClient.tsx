"use client";

import React from "react";

export default function HeaderClient() {
  const [isScrolled, setIsScrolled] = React.useState(false);
  const [isSearchOpen, setIsSearchOpen] = React.useState(false);
  const searchRef = React.useRef<HTMLDivElement>(null);
  const searchInputRef = React.useRef<HTMLInputElement>(null);
  const [architecturalCategories, setArchitecturalCategories] = React.useState<Array<{ id: number; title?: string; name?: string; slug?: string; uri?: string }>>([]);
  const [decorativeCategories, setDecorativeCategories] = React.useState<Array<{ id: number; name: string; slug: string }>>([]);
  const [collections, setCollections] = React.useState<Array<{ id: number; name: string; slug: string }>>([]);

  React.useEffect(() => {
    async function fetchMenuData() {
      try {
        const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
        const [archRes, catRes, colRes] = await Promise.all([
          fetch(`${API_URL}/api/categories`),
          fetch(`${API_URL}/api/decorative-categories`),
          fetch(`${API_URL}/api/collections`)
        ]);

        if (archRes.ok) {
          const archData = await archRes.json();
          if (archData.success && Array.isArray(archData.data)) {
            setArchitecturalCategories(archData.data);
          }
        }

        if (catRes.ok) {
          const catData = await catRes.json();
          if (catData.success && Array.isArray(catData.data)) {
            setDecorativeCategories(catData.data);
          }
        }

        if (colRes.ok) {
          const colData = await colRes.json();
          if (colData.success && Array.isArray(colData.data)) {
            setCollections(colData.data);
          }
        }
      } catch (error) {
        console.error('Error fetching menu data:', error);
      }
    }

    fetchMenuData();
  }, []);

  React.useEffect(() => {
    const isHome = window.location.pathname === '/';
    if (!isHome) {
      setIsScrolled(true);
      return;
    }
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 500);
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll();
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Search toggle functionality
  const handleSearchToggle = () => {
    setIsSearchOpen(!isSearchOpen);
    // Focus input when opening
    if (!isSearchOpen) {
      setTimeout(() => {
        searchInputRef.current?.focus();
      }, 100);
    }
  };

  // Close search when clicking outside
  React.useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (searchRef.current && !searchRef.current.contains(event.target as Node)) {
        setIsSearchOpen(false);
      }
    };

    if (isSearchOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isSearchOpen]);

  // Close search on Escape key
  React.useEffect(() => {
    const handleEscape = (event: KeyboardEvent) => {
      if (event.key === 'Escape' && isSearchOpen) {
        setIsSearchOpen(false);
      }
    };

    document.addEventListener('keydown', handleEscape);

    return () => {
      document.removeEventListener('keydown', handleEscape);
    };
  }, [isSearchOpen]);

  const [activeTab, setActiveTab] = React.useState<"home" | "products" | "work" | "inspiration" | "more">("home");
  const [activeSheet, setActiveSheet] = React.useState<"products" | "work" | "more" | null>(null);
  const [productAccordion, setProductAccordion] = React.useState<"arch" | "dec" | null>("dec");

  React.useEffect(() => {
    if (typeof window !== "undefined") {
      const path = window.location.pathname;
      if (path.startsWith("/inspiration") || path.startsWith("/blogs")) {
        setActiveTab("inspiration");
      } else if (path.startsWith("/projects") || path.startsWith("/clients")) {
        setActiveTab("work");
      } else if (path.startsWith("/decorative") || path.startsWith("/product") || path.startsWith("/collections")) {
        setActiveTab("products");
      }
    }
  }, []);

  React.useEffect(() => {
    const handleScrollTab = () => {
      if (activeSheet || window.location.pathname !== "/") return;
      const scrollY = window.scrollY;
      const newsEl = document.getElementById("news");
      const projectsEl = document.getElementById("projects");
      const worldsEl = document.getElementById("worlds");

      if (newsEl && scrollY >= newsEl.offsetTop - 350) {
        setActiveTab("inspiration");
      } else if (projectsEl && scrollY >= projectsEl.offsetTop - 350) {
        setActiveTab("work");
      } else if (worldsEl && scrollY >= worldsEl.offsetTop - 350) {
        setActiveTab("products");
      } else {
        setActiveTab("home");
      }
    };
    window.addEventListener("scroll", handleScrollTab, { passive: true });
    return () => window.removeEventListener("scroll", handleScrollTab);
  }, [activeSheet]);

  React.useEffect(() => {
    if (activeSheet) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [activeSheet]);

  const handleTabClick = (tabId: "home" | "products" | "work" | "inspiration" | "more") => {
    if (tabId === "home") {
      setActiveTab("home");
      setActiveSheet(null);
      if (window.location.pathname === "/") {
        window.scrollTo({ top: 0, behavior: "smooth" });
      } else {
        window.location.href = "/";
      }
    } else if (tabId === "inspiration") {
      setActiveTab("inspiration");
      setActiveSheet(null);
      window.location.href = "/inspiration";
    } else if (tabId === "products") {
      setActiveTab("products");
      setActiveSheet((prev) => (prev === "products" ? null : "products"));
    } else if (tabId === "work") {
      setActiveTab("work");
      setActiveSheet((prev) => (prev === "work" ? null : "work"));
    } else if (tabId === "more") {
      setActiveTab("more");
      setActiveSheet((prev) => (prev === "more" ? null : "more"));
    }
  };

  const closeSheet = () => {
    setActiveSheet(null);
  };

  return (
    <>
      <header className={`sitehead ${isScrolled ? 'scrolled' : ''}`}>
        <div className="wrap">
          <a href="/" className="logo" aria-label="Abby Lighting home">
            <img className="logo-asset" src="/images/abby-logo.png" alt="Abby Lighting" />
          </a>
          <nav aria-label="Primary navigation">
            <ul>
              <li className="has-mega">
                <a className="link" href="/products">
                  Product<span className="caret"></span>
                </a>
                <div className="mega">
                  <div className="mega-panel">
                    <div className="mega-grid">
                      {/* ARCHITECTURAL - Dynamic */}
                      <div className="mgroup m-arch">
                        <div className="mhead">Architectural</div>
                        <div className="msub">Browse by category</div>
                        <ul>
                          {architecturalCategories.length > 0 ? (
                            architecturalCategories.map((category) => (
                              <li key={category.id}>
                                <a href={category.uri ? category.uri : `/products?category=${category.slug || ''}`}>
                                  {category.title || category.name}
                                </a>
                              </li>
                            ))
                          ) : (
                            <>
                              <li><a href="/products">Spots &amp; Accents</a></li>
                              <li><a href="/products">Downlights</a></li>
                              <li><a href="/products">Profiles</a></li>
                              <li><a href="/products">Track Lights</a></li>
                              <li><a href="/products">Washers &amp; Grazers</a></li>
                            </>
                          )}
                        </ul>
                      </div>

                      <div className="msep"></div>

                      {/* DECORATIVE - Dynamic (Two Columns) */}
                      <div className="mgroup m-dec">
                        <div className="mhead">Decorative <span className="mnew">NEW</span></div>
                        <div className="mcols">
                          {/* Left Column - Browse by Category */}
                          <div>
                            <div className="msub">Browse by category</div>
                            <ul>
                              {decorativeCategories.length > 0 ? (
                                decorativeCategories.map((category) => (
                                  <li key={category.id}>
                                    <a href={`/decorative/${category.slug}`}>{category.name}</a>
                                  </li>
                                ))
                              ) : (
                                <>
                                  <li><a href="/decorative">Chandelier</a></li>
                                  <li><a href="/decorative">Pendant Lights</a></li>
                                  <li><a href="/decorative">Wall Lights</a></li>
                                  <li><a href="/decorative">Floor Lamps</a></li>
                                  <li><a href="/decorative">Table Lamps</a></li>
                                </>
                              )}
                            </ul>
                          </div>
                          
                          <div className="msep sm"></div>
                          
                          {/* Right Column - Browse by Collection */}
                          <div>
                            <div className="msub">Browse by collection</div>
                            <ul>
                              {collections.length > 0 ? (
                                collections.map((col) => (
                                  <li key={col.id}>
                                    <a href={`/collections/${col.slug}`}>{col.name}</a>
                                  </li>
                                ))
                              ) : (
                                <>
                                  <li><a href="/collections/symphony">Symphony</a></li>
                                  <li><a href="/collections/quarry">Quarry</a></li>
                                  <li><a href="/collections/neoma">Neoma</a></li>
                                </>
                              )}
                            </ul>
                          </div>
                        </div>
                      </div>

                       <div className="msep lg"></div>
                        <div className="m-worlds">
                          <a className="mgroup m-out" href="/#worlds"
                            ><span className="mhead">Outdoor</span></a
                          >
                          <div className="msep hz"></div>
                          <a className="mgroup m-smart" href="/#worlds"
                            ><span className="mhead">Smart Lighting</span></a
                          >
                        </div>
                    </div>
                  </div>
                </div>
              </li>
              <li className="has-drop">
                <a className="link" href="/projects">
                  Our Work<span className="caret"></span>
                </a>
                <div className="drop">
                  <a href="/projects">Projects</a>
                  <a href="/clients">Clients</a>
                </div>
              </li>
              <li>
                <a className="link" href="/inspiration">Inspiration</a>
              </li>
              <li className="has-drop">
                <a className="link" href="/company">
                  More<span className="caret"></span>
                </a>
                <div className="drop">
                  <a href="/company">About Us</a>
                  <a href="/contact">Contact Us</a>
                  <a href="/career">Careers</a>
                  <a href="/#contact">Catalogues</a>
                </div>
              </li>
            </ul>
          </nav>
          <div className="right">
            <div ref={searchRef} className={`abby-search ${isSearchOpen ? 'is-open' : ''}`}>
              <form className="abby-search-form" role="search">
                <span className="abby-search-leading">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.7" cy="10.7" r="6.7"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                  </svg>
                </span>
                <input 
                  ref={searchInputRef}
                  type="search" 
                  placeholder="Search for products, collections and more" 
                  aria-label="Search Abby Lighting" 
                  autoComplete="off" 
                  defaultValue="" 
                />
                <button className="abby-search-go" type="submit" aria-label="Submit search" disabled>
                  →
                </button>
              </form>
              <button 
                type="button" 
                className="abby-search-toggle" 
                aria-label={isSearchOpen ? "Close search" : "Open search"}
                aria-expanded={isSearchOpen}
                onClick={handleSearchToggle}
              >
                {isSearchOpen ? (
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
                  </svg>
                ) : (
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.7" cy="10.7" r="6.7"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                  </svg>
                )}
              </button>
            </div>
            <a href="/contact" className="nav-cta">
              <span className="lbl-d">Get in Touch</span>
              <span className="lbl-m">Contact</span>
            </a>
          </div>
        </div>
      </header>

      {/* Floating Bottom Dock (Mobile Only) */}
      <nav className="custom-mobile-dock" aria-label="Mobile bottom navigation">
        <div className="custom-dock-bar">
          {/* 1. Home */}
          <button
            type="button"
            className={`custom-dock-tab ${activeTab === 'home' ? 'is-active' : ''}`}
            onClick={() => handleTabClick('home')}
            aria-label="Home"
          >
            {activeTab === 'home' ? (
              <>
                <div className="custom-dock-notch" aria-hidden="true" />
                <div className="custom-dock-active-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M3 11.5L12 4l9 7.5v7a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 18.5v-7z" />
                  </svg>
                </div>
                <span className="custom-dock-label">Home</span>
              </>
            ) : (
              <div className="custom-dock-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M3 11.5L12 4l9 7.5v7a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 18.5v-7z" />
                </svg>
              </div>
            )}
          </button>

          {/* 2. Products */}
          <button
            type="button"
            className={`custom-dock-tab ${activeTab === 'products' ? 'is-active' : ''}`}
            onClick={() => handleTabClick('products')}
            aria-label="Products"
          >
            {activeTab === 'products' ? (
              <>
                <div className="custom-dock-notch" aria-hidden="true" />
                <div className="custom-dock-active-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="12" y1="3" x2="12" y2="8" />
                    <path d="M5 15a7 7 0 0 1 14 0H5z" />
                    <path d="M10 15a2 2 0 0 0 4 0" />
                  </svg>
                </div>
                <span className="custom-dock-label">Products</span>
              </>
            ) : (
              <div className="custom-dock-icon custom-dock-icon-product">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                  <line x1="12" y1="3" x2="12" y2="8" />
                  <path d="M5 15a7 7 0 0 1 14 0H5z" />
                  <path d="M10 15a2 2 0 0 0 4 0" />
                </svg>
              </div>
            )}
          </button>

          {/* 3. Our works */}
          <button
            type="button"
            className={`custom-dock-tab ${activeTab === 'work' ? 'is-active' : ''}`}
            onClick={() => handleTabClick('work')}
            aria-label="Our works"
          >
            {activeTab === 'work' ? (
              <>
                <div className="custom-dock-notch" aria-hidden="true" />
                <div className="custom-dock-active-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M4 6h4.5l2 2h8a1 1 0 0 1 1 1v1.5H3.5V7a1 1 0 0 1 1-1z" />
                    <path d="M2.5 10.5h18a1 1 0 0 1 1 1.2l-1.3 6.8a1 1 0 0 1-1 .8H4.2a1 1 0 0 1-1-.8L1.8 11.7a1 1 0 0 1 .7-1.2z" />
                  </svg>
                </div>
                <span className="custom-dock-label">Our works</span>
              </>
            ) : (
              <div className="custom-dock-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M4 6h4.5l2 2h8a1 1 0 0 1 1 1v1.5H3.5V7a1 1 0 0 1 1-1z" />
                  <path d="M2.5 10.5h18a1 1 0 0 1 1 1.2l-1.3 6.8a1 1 0 0 1-1 .8H4.2a1 1 0 0 1-1-.8L1.8 11.7a1 1 0 0 1 .7-1.2z" />
                </svg>
              </div>
            )}
          </button>

          {/* 4. Our inspiration */}
          <button
            type="button"
            className={`custom-dock-tab ${activeTab === 'inspiration' ? 'is-active' : ''}`}
            onClick={() => handleTabClick('inspiration')}
            aria-label="Our inspiration"
          >
            {activeTab === 'inspiration' ? (
              <>
                <div className="custom-dock-notch" aria-hidden="true" />
                <div className="custom-dock-active-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M6.5 9.5a5.5 5.5 0 1 1 11 0c0 2.2-1.2 3.8-2.2 5.1-.5.7-.8 1.4-.8 2.4H9.5c0-1-.3-1.7-.8-2.4-1-1.3-2.2-2.9-2.2-5.1z" />
                    <line x1="9" y1="20" x2="15" y2="20" />
                  </svg>
                </div>
                <span className="custom-dock-label">Our inspiration</span>
              </>
            ) : (
              <div className="custom-dock-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M6.5 9.5a5.5 5.5 0 1 1 11 0c0 2.2-1.2 3.8-2.2 5.1-.5.7-.8 1.4-.8 2.4H9.5c0-1-.3-1.7-.8-2.4-1-1.3-2.2-2.9-2.2-5.1z" />
                  <line x1="9" y1="20" x2="15" y2="20" />
                </svg>
              </div>
            )}
          </button>

          {/* 5. More */}
          <button
            type="button"
            className={`custom-dock-tab ${activeTab === 'more' ? 'is-active' : ''}`}
            onClick={() => handleTabClick('more')}
            aria-label="More"
          >
            {activeTab === 'more' ? (
              <>
                <div className="custom-dock-notch" aria-hidden="true" />
                <div className="custom-dock-active-box">
                  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="5" y1="8" x2="19" y2="8" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <line x1="5" y1="16" x2="19" y2="16" />
                  </svg>
                </div>
                <span className="custom-dock-label">More</span>
              </>
            ) : (
              <div className="custom-dock-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
                  <line x1="5" y1="8" x2="19" y2="8" />
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <line x1="5" y1="16" x2="19" y2="16" />
                </svg>
              </div>
            )}
          </button>
        </div>
      </nav>

      {/* Backdrop for Mobile Sheets */}
      <div 
        className={`custom-sheet-backdrop ${activeSheet ? 'is-open' : ''}`}
        onClick={closeSheet}
        aria-hidden="true"
      />

      {/* Fullscreen Mobile Drawer for Products / Works / More */}
      <div className={`pdrop-modal ${activeSheet ? 'is-open' : ''}`} role="dialog" aria-modal="true">
        <div className="pdrop-container">
          {/* Top Header Row with Logo, Search and Contact button */}
          <div className="pdrop-header">
            <a href="/" onClick={closeSheet} className="pdrop-logo">
              <img src="/images/abby-logo.png" alt="Abby Lighting" />
            </a>
            <div className="pdrop-actions">
              <button 
                type="button" 
                className="pdrop-search-btn" 
                onClick={() => { closeSheet(); handleSearchToggle(); }}
                aria-label="Search"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <circle cx="11" cy="11" r="7" />
                  <path d="M21 21l-4.35-4.35" />
                </svg>
              </button>
              <a href="/contact" onClick={closeSheet} className="pdrop-contact-btn">
                CONTACT
              </a>
            </div>
          </div>

          {/* PRODUCTS MENU (Matching Figma Design) */}
          {activeSheet === 'products' && (
            <div className="pdrop-body">
              {/* Title row with (X) close */}
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">PRODUCTS</h2>
                <button 
                  type="button" 
                  className="pdrop-circle-close" 
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>

              {/* 1. Architectural Accordion */}
              <div className={`pdrop-acc ${productAccordion === 'arch' ? 'is-open' : ''}`}>
                <button
                  type="button"
                  className="pdrop-acc-toggle"
                  onClick={() => setProductAccordion(prev => prev === 'arch' ? null : 'arch')}
                >
                  <span className={productAccordion === 'arch' ? 'pdrop-amber-text' : ''}>Architectural</span>
                  <span className="pdrop-caret">
                    {productAccordion === 'arch' ? (
                      <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,7 11,7 5.5,0" />
                      </svg>
                    ) : (
                      <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,0 11,0 5.5,7" />
                      </svg>
                    )}
                  </span>
                </button>
                {productAccordion === 'arch' && (
                  <div className="pdrop-acc-content">
                    <div className="pdrop-section-label">BROWSE BY CATEGORY</div>
                    <ul className="pdrop-list">
                      {architecturalCategories.length > 0 ? (
                        architecturalCategories.map(cat => (
                          <li key={cat.id}>
                            <a 
                              href={cat.uri || `/products?category=${cat.slug || ''}`}
                              onClick={closeSheet}
                            >
                              {cat.title || cat.name}
                            </a>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><a href="/products" onClick={closeSheet}>Spots &amp; Accents</a></li>
                          <li><a href="/products" onClick={closeSheet}>Downlights</a></li>
                          <li><a href="/products" onClick={closeSheet}>Profiles</a></li>
                          <li><a href="/products" onClick={closeSheet}>Track Lights</a></li>
                          <li><a href="/products" onClick={closeSheet}>Washers &amp; Grazers</a></li>
                        </>
                      )}
                    </ul>
                  </div>
                )}
              </div>

              {/* 2. Decorative Accordion */}
              <div className={`pdrop-acc ${productAccordion === 'dec' ? 'is-open' : ''}`}>
                <button
                  type="button"
                  className="pdrop-acc-toggle"
                  onClick={() => setProductAccordion(prev => prev === 'dec' ? null : 'dec')}
                >
                  <span className="pdrop-amber-text">
                    Decorative <span className="pdrop-badge-new">NEW</span>
                  </span>
                  <span className="pdrop-caret">
                    {productAccordion === 'dec' ? (
                      <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,7 11,7 5.5,0" />
                      </svg>
                    ) : (
                      <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,0 11,0 5.5,7" />
                      </svg>
                    )}
                  </span>
                </button>
                {productAccordion === 'dec' && (
                  <div className="pdrop-acc-content">
                    <div className="pdrop-section-label">BROWSE BY CATEGORY</div>
                    <ul className="pdrop-list">
                      {decorativeCategories.length > 0 ? (
                        decorativeCategories.map(cat => (
                          <li key={cat.id}>
                            <a 
                              href={`/decorative/${cat.slug}`}
                              onClick={closeSheet}
                            >
                              {cat.name}
                            </a>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><a href="/decorative" onClick={closeSheet}>Chandelier</a></li>
                          <li><a href="/decorative" onClick={closeSheet}>Pendant Lights</a></li>
                          <li><a href="/decorative" onClick={closeSheet}>Wall Lights</a></li>
                          <li><a href="/decorative" onClick={closeSheet}>Floor Lamps</a></li>
                          <li><a href="/decorative" onClick={closeSheet}>Table Lamps</a></li>
                        </>
                      )}
                    </ul>

                    <div className="pdrop-section-label" style={{ marginTop: '24px' }}>BROWSE BY COLLECTION</div>
                    <ul className="pdrop-list">
                      {collections.length > 0 ? (
                        collections.map(col => (
                          <li key={col.id}>
                            <a 
                              href={`/collections/${col.slug}`}
                              onClick={closeSheet}
                            >
                              {col.name}
                            </a>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><a href="/collections/symphony" onClick={closeSheet}>Symphony</a></li>
                          <li><a href="/collections/quarry" onClick={closeSheet}>Quarry</a></li>
                          <li><a href="/collections/neoma" onClick={closeSheet}>Neoma</a></li>
                        </>
                      )}
                    </ul>
                  </div>
                )}
              </div>

              {/* 3. Outdoor */}
              <div className="pdrop-direct-row">
                <a href="/#worlds" onClick={closeSheet}>
                  Outdoor
                </a>
              </div>

              {/* 4. Smart Lighting */}
              <div className="pdrop-direct-row">
                <a href="/#worlds" onClick={closeSheet}>
                  Smart Lighting
                </a>
              </div>
            </div>
          )}

          {/* OUR WORKS MENU */}
          {activeSheet === 'work' && (
            <div className="pdrop-body">
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">OUR WORKS</h2>
                <button 
                  type="button" 
                  className="pdrop-circle-close" 
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/projects" onClick={closeSheet}>
                  <span>Projects</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/clients" onClick={closeSheet}>
                  <span>Clients</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
            </div>
          )}

          {/* MORE MENU (Matching Figma Design) */}
          {activeSheet === 'more' && (
            <div className="pdrop-body">
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">MORE</h2>
                <button 
                  type="button" 
                  className="pdrop-circle-close" 
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/company" onClick={closeSheet}>
                  <span>About</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/career" onClick={closeSheet}>
                  <span>Careers</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/contact" onClick={closeSheet}>
                  <span>Contact</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/policies" onClick={closeSheet}>
                  <span>Policies</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
