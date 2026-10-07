"use client";

import { useState } from "react";
import Link from "next/link";

export default function Footer() {
  const [productsOpen, setProductsOpen] = useState(false);
  const [ourWorkOpen, setOurWorkOpen] = useState(false);

  const toggleProducts = () => {
    setProductsOpen(!productsOpen);
  };

  const toggleOurWork = () => {
    setOurWorkOpen(!ourWorkOpen);
  };

  return (
    <footer id="contact">
      <div className="figma-footer">
        <div className="footer-main">
          <div className="footer-identity">
            <img className="footer-logo" src="/images/abby-logo.png" alt="Abby Lighting" />
            <p>For over three generations, Abby Lighting has been one of India&apos;s leading architectural lighting manufacturers, designing and manufacturing premium architectural and outdoor lighting entirely in-house. From concept and engineering to precision manufacturing and testing, every luminaire is built for consistency, performance and long-term reliability, making Abby Lighting the trusted partner for architects, interior designers and consultants across residential, commercial and hospitality projects.</p>
          </div>
          <nav className="footer-links footer-products" aria-label="Product links">
            <div className={`footer-accordion ${productsOpen ? 'is-open open' : ''}`}>
              <button
                className="footer-accordion-toggle"
                type="button"
                aria-expanded={productsOpen}
                aria-controls="footer-products"
                onClick={toggleProducts}
              >
                <span>Products</span>
                <span className="footer-accordion-icon" aria-hidden="true"></span>
              </button>
              <div className="footer-accordion-panel" id="footer-products" aria-hidden={!productsOpen}>
                <a href="/products">Architectural</a>
                <a href="/products">Outdoor</a>
                <a href="/abby-smart">Smart Lighting</a>
              </div>
            </div>
            <div className={`footer-accordion ${ourWorkOpen ? 'is-open open' : ''}`}>
              <button
                className="footer-accordion-toggle"
                type="button"
                aria-expanded={ourWorkOpen}
                aria-controls="footer-our-work"
                onClick={toggleOurWork}
              >
                <span>Our Work</span>
                <span className="footer-accordion-icon" aria-hidden="true"></span>
              </button>
              <div className="footer-accordion-panel" id="footer-our-work" aria-hidden={!ourWorkOpen}>
                <a href="/clients">Clients</a>
                <a href="/projects">Projects</a>
              </div>
            </div>
            <a className="footer-inspiration" href="/inspiration">Inspiration</a>
          </nav>
          <nav className="footer-links footer-company" aria-label="Company links">
            <a href="/company">About Us</a>
            <a href="/contact">Contact Us</a>
            <a href="/career">Careers</a>
            <Link href="/catalogues">Catalogues</Link>
            <a className="footer-mobile-legal" href="/privacy-policy">Privacy Policy</a>
            <a className="footer-mobile-legal" href="/terms-and-conditions">Terms of Use</a>
            <a href="/fair-events">Fairs &amp; Events</a>
          </nav>
          <nav className="footer-links footer-legal" aria-label="Legal links">
            <a href="/privacy-policy">Privacy Policy</a>
            <a href="/terms-and-conditions">Terms of Use</a>
          </nav>
        </div>
        <div className="footer-rule"></div>
        <div className="footer-base">
          <span>© 2026 Abby Lighting. All rights reserved.</span>
          <div className="footer-social">
            <a href="#" aria-label="Instagram">
              <span className="footer-social-icon">
                <img src="/icons/instagram.png" alt="Instagram" width="27" height="32" className="footer-social-img" />
              </span>
            </a>
            <a href="#" aria-label="LinkedIn">
              <span className="footer-social-icon">
                <img src="/icons/linkedin.png" alt="LinkedIn" width="22" height="22" className="footer-social-img" />
              </span>
            </a>
            <a href="#" aria-label="Facebook">
              <span className="footer-social-icon">
                <img src="/icons/facebook.png" alt="Facebook" width="22" height="22" className="footer-social-img footer-facebook" />
              </span>
            </a>
            <a href="#" aria-label="YouTube">
              <span className="footer-social-icon">
                <img src="/icons/youtube.png" alt="YouTube" width="22" height="22" className="footer-social-img" />
              </span>
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
