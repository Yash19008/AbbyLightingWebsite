"use client";

import React from "react";

import Link from "next/link";


export default function Header() {
  const [isSearchOpen, setIsSearchOpen] = React.useState(false);
  const searchRef = React.useRef<HTMLDivElement>(null);
  const searchInputRef = React.useRef<HTMLInputElement>(null);



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



  return (
    <>
      <header className="sitehead ">
        <div className="wrap">
          <Link href="/" className="logo" aria-label="Abby Lighting home">
            <img className="logo-asset" src="/images/abby-logo.png" alt="Abby Lighting" />
          </Link>
          <nav aria-label="Primary navigation">
            <ul>
              <li className="has-mega">
                <Link className="link" href="/#worlds">
                  Product<span className="caret"></span>
                </Link>
                <div className="mega">
                  <div className="mega-panel">
                    <div className="mega-grid">
                      <div className="mgroup m-arch ">
                        <div className="mhead">Architectural</div>
                        <div className="msub">Browse by category</div>
                        <ul>
                          <li><Link href="/#arrivals">Spots &amp; Accents</Link></li>
                          <li><Link href="/#arrivals">Downlights</Link></li>
                          <li><Link href="/#arrivals">Profiles</Link></li>
                          <li><Link href="/#arrivals">Track Lights</Link></li>
                          <li><Link href="/#arrivals">Washers &amp; Grazers</Link></li>
                        </ul>
                      </div>
                      <div className="msep"></div>
                      <div className="mgroup m-dec">
                        <div className="mhead">Decorative <span className="mnew">NEW</span></div>
                        <div className="mcols">
                          <div>
                            <div className="msub">Browse by category</div>
                            <ul>
                              <li><Link href="/decorative-products?category=chandelier">Chandelier</Link></li>
                              <li><Link href="/decorative-products?category=pendant-lights">Pendant Lights</Link></li>
                              <li><Link href="/decorative-products?category=wall-lights">Wall Lights</Link></li>
                              <li><Link href="/decorative-products?category=floor-lamps">Floor Lamps</Link></li>
                              <li><Link href="/decorative-products?category=table-lamps">Table Lamps</Link></li>
                            </ul>
                          </div>
                          <div className="msep sm"></div>
                          <div>
                            <div className="msub">Browse by collection</div>
                            <ul>
                              <li><Link href="/collections/symphony">Symphony</Link></li>
                              <li><Link href="/collections/quarry">Quarry</Link></li>
                              <li><Link href="/collections/neoma">Neoma</Link></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div className="msep lg"></div>
                      <div className="mgroup m-out">
                        <div className="mhead">Outdoor</div>
                        <div className="msub">Browse by category</div>
                        <ul>
                          <li><Link href="/#arrivals">Wall Lights</Link></li>
                          <li><Link href="/#arrivals">Path Lights</Link></li>
                          <li><Link href="/#arrivals">Bollards</Link></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li className="has-drop">
                <Link className="link" href="/projects">
                  Our Work<span className="caret"></span>
                </Link>
                <div className="drop">
                  <Link href="/projects">Projects</Link>
                  <Link href="/clients">Clients</Link>
                </div>
              </li>
              <li>
                <Link className="link" href="/inspiration">Inspiration</Link>
              </li>
              <li className="has-drop">
                <Link className="link" href="/company">
                  More<span className="caret"></span>
                </Link>
                <div className="drop">
                  <Link href="/company">About Us</Link>
                  <Link href="/contact">Contact Us</Link>
                  <Link href="/career">Careers</Link>
                  <Link href="/catalogues">Catalogues</Link>
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
            <Link href="/contact" className="nav-cta">
              <span className="lbl-d">Get in Touch</span>
              <span className="lbl-m">Contact</span>
            </Link>
          </div>
        </div>
      </header>

      <nav className="halo-nav-wrap halo-visible" aria-label="Mobile sections">
        <div className="halo-nav">
          <span className="halo-nav-shadow" aria-hidden="true"></span>
          <svg className="halo-nav-skin" aria-hidden="true" focusable="false" preserveAspectRatio="none">
            <defs>
              <linearGradient id="haloPlateGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-plate-highlight" offset="0"></stop>
                <stop className="halo-plate-shadow" offset="1"></stop>
              </linearGradient>
              <linearGradient id="haloRimGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-rim-strong" offset="0"></stop>
                <stop className="halo-rim-soft" offset="1"></stop>
              </linearGradient>
            </defs>
            <path className="halo-nav-plate"></path>
          </svg>
          <span className="halo-orb" aria-hidden="true"></span>
          <div className="halo-tabs" role="tablist" aria-label="Page sections">
            <button className="halo-tab" role="tab" type="button" id="halo-tab-home" aria-selected="false" tabIndex={0}>
              <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3.5 10.6 12 3.9l8.5 6.7"></path>
                <path d="M5.7 9.2v9.1a1.6 1.6 0 0 0 1.6 1.6h9.4a1.6 1.6 0 0 0 1.6-1.6V9.2"></path>
              </svg>
              <span className="halo-label">Home</span>
            </button>
            <button className="halo-tab" role="tab" type="button" id="halo-tab-product" aria-selected="false" tabIndex={-1}>
              <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3.4v3.1"></path>
                <path d="M6.6 13.4a5.4 5.4 0 0 1 10.8 0Z"></path>
                <path d="M9.7 13.4a2.3 2.3 0 0 0 4.6 0"></path>
              </svg>
              <span className="halo-label">Products</span>
            </button>
            <button className="halo-tab" role="tab" type="button" id="halo-tab-work" aria-selected="false" tabIndex={-1}>
              <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3.5 7.1h6.2l2 2h8.8v8.7a1.8 1.8 0 0 1-1.8 1.8H5.3a1.8 1.8 0 0 1-1.8-1.8Z"></path>
                <path d="M3.5 10h17"></path>
              </svg>
              <span className="halo-label">Our Work</span>
            </button>
            <button className="halo-tab" role="tab" type="button" id="halo-tab-inspiration" aria-selected="false" tabIndex={-1}>
              <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M9.1 16.3a5 5 0 1 1 5.8 0 1.6 1.6 0 0 0-.6 1.2v.4H9.7v-.4a1.6 1.6 0 0 0-.6-1.2Z"></path>
                <path d="M10 20.1h4"></path>
              </svg>
              <span className="halo-label">Inspiration</span>
            </button>
            <button className="halo-tab" role="tab" type="button" id="halo-tab-more" aria-selected="false" tabIndex={-1}>
              <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4.5 8.2h15"></path>
                <path d="M4.5 12h15"></path>
                <path d="M4.5 15.8h15"></path>
              </svg>
              <span className="halo-label">More</span>
            </button>
          </div>
        </div>
      </nav>

      <button className="menu-backdrop " aria-label="Close menu"></button>
      <div className="msheet  " role="dialog" aria-modal="true" aria-label="Mobile menu">
        <button className="menu-close" aria-label="Close menu">
          <span aria-hidden="true">×</span>
        </button>
        <div className="m-dash"></div>
        <div className="m-title"></div>
        <div className="mobile-menu-list"></div>
      </div>
    </>
  );
}
