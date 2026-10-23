"use client";
import { API_URL, API_BASE } from '@/lib/config';
import { useState, useEffect } from "react";
import Link from "next/link";
import type { MenuItem } from "@/lib/api/menu";

export default function Footer({ initialMenuItems = [] }: { initialMenuItems?: MenuItem[] }) {
  const [menuItems, setMenuItems] = useState<MenuItem[]>(initialMenuItems);
  const [openAccordions, setOpenAccordions] = useState<Record<number, boolean>>({});

  // Client-side fetch just like HeaderClient
  useEffect(() => {
    async function fetchFooterMenu() {
      try {
        const res = await fetch(`${API_URL}/menu-items`, { cache: 'no-store' });
        if (res.ok) {
          const data = await res.json();
          if (data.success && Array.isArray(data.data) && data.data.length > 0) {
            setMenuItems(data.data);
          }
        }
      } catch (error) {
        console.error('Error fetching footer menu items:', error);
      }
    }

    fetchFooterMenu();
  }, []);

  // Update if initialMenuItems prop changes
  useEffect(() => {
    if (initialMenuItems && initialMenuItems.length > 0) {
      setMenuItems(initialMenuItems);
    }
  }, [initialMenuItems]);

  const toggleAccordion = (id: number) => {
    setOpenAccordions(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  const renderColumnItems = (items: MenuItem[], fallbackContent: React.ReactNode, colClass: string = '') => {
    if (!items || items.length === 0) return fallbackContent;

    return items.map(item => {
      if (item.type === 'group') {
        const isOpen = !!openAccordions[item.id];
        return (
          <div key={item.id} className={`footer-accordion ${isOpen ? 'is-open open' : ''}`}>
            <button
              className="footer-accordion-toggle"
              type="button"
              aria-expanded={isOpen}
              onClick={() => toggleAccordion(item.id)}
            >
              <span>{item.title}</span>
              <span className="footer-accordion-icon" aria-hidden="true"></span>
            </button>
            <div className="footer-accordion-panel" aria-hidden={!isOpen}>
              {item.children?.map(child => (
                <Link key={child.id} href={child.url || '/'}>{child.title}</Link>
              ))}
            </div>
          </div>
        );
      } else {
        return (
          <Link 
            key={item.id} 
            className={colClass || (item.title === 'Privacy Policy' || item.title === 'Terms of Use' ? 'footer-mobile-legal' : '')} 
            href={item.url || '/'}
          >
            {item.title}
          </Link>
        );
      }
    });
  };

  const col1Items = menuItems.filter(item => item.location === 'footer_col_1');
  const col2Items = menuItems.filter(item => item.location === 'footer_col_2');
  const col3Items = menuItems.filter(item => item.location === 'footer_col_3');

  const defaultCol1 = (
    <>
      <div className={`footer-accordion ${openAccordions[-1] ? 'is-open open' : ''}`}>
        <button
          className="footer-accordion-toggle"
          type="button"
          aria-expanded={!!openAccordions[-1]}
          onClick={() => toggleAccordion(-1)}
        >
          <span>Products</span>
          <span className="footer-accordion-icon" aria-hidden="true"></span>
        </button>
        <div className="footer-accordion-panel" aria-hidden={!openAccordions[-1]}>
          <Link href="/products">Architectural</Link>
          <Link href="/decorative-lights">Decorative</Link>
          <Link href="/#worlds">Outdoor</Link>
          <Link href="/abby-smart">Smart Lighting</Link>
        </div>
      </div>
      <div className={`footer-accordion ${openAccordions[-2] ? 'is-open open' : ''}`}>
        <button
          className="footer-accordion-toggle"
          type="button"
          aria-expanded={!!openAccordions[-2]}
          onClick={() => toggleAccordion(-2)}
        >
          <span>Our Work</span>
          <span className="footer-accordion-icon" aria-hidden="true"></span>
        </button>
        <div className="footer-accordion-panel" aria-hidden={!openAccordions[-2]}>
          <Link href="/clients">Clients</Link>
          <Link href="/projects">Projects</Link>
        </div>
      </div>
      <Link className="footer-inspiration" href="/inspiration">Inspiration</Link>
    </>
  );

  const defaultCol2 = (
    <>
      <Link href="/company">About Us</Link>
      <Link href="/contact">Contact Us</Link>
      <Link href="/career">Careers</Link>
      <Link href="/catalogues">Catalogues</Link>
      <Link className="footer-mobile-legal" href="/privacy-policy">Privacy Policy</Link>
      <Link className="footer-mobile-legal" href="/terms-and-conditions">Terms of Use</Link>
      <Link href="/fair-events">Fairs &amp; Events</Link>
    </>
  );

  const defaultCol3 = (
    <>
      <Link href="/privacy-policy">Privacy Policy</Link>
      <Link href="/terms-and-conditions">Terms of Use</Link>
    </>
  );

  return (
    <footer id="contact">
      <div className="figma-footer">
        <div className="footer-main">
          <div className="footer-identity">
            <img className="footer-logo" src="/images/abby-logo.png" alt="Abby Lighting" />
            <p>For over three generations, Abby Lighting has been one of India&apos;s leading architectural lighting manufacturers, designing and manufacturing premium architectural and outdoor lighting entirely in-house. From concept and engineering to precision manufacturing and testing, every luminaire is built for consistency, performance and long-term reliability, making Abby Lighting the trusted partner for architects, interior designers and consultants across residential, commercial and hospitality projects.</p>
          </div>
          
          <nav className="footer-links footer-products" aria-label="Product links">
            {renderColumnItems(col1Items, defaultCol1, "footer-inspiration")}
          </nav>

          <nav className="footer-links footer-company" aria-label="Company links">
            {renderColumnItems(col2Items, defaultCol2)}
          </nav>

          <nav className="footer-links footer-legal" aria-label="Legal links">
            {renderColumnItems(col3Items, defaultCol3)}
          </nav>
        </div>
        <div className="footer-rule"></div>
        <div className="footer-base">
          <span>© 2026 Abby Lighting. All rights reserved.</span>
          <div className="footer-social">
            <a href="https://www.instagram.com/abbylighting/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
              <span className="footer-social-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
              </span>
            </a>
            <a href="https://www.linkedin.com/company/abby-lighting/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
              <span className="footer-social-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                  <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                </svg>
              </span>
            </a>
            <a href="https://www.facebook.com/abbylighting/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
              <span className="footer-social-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                  <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.1v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                </svg>
              </span>
            </a>
            <a href="https://www.youtube.com/@abby-lighting" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
              <span className="footer-social-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                </svg>
              </span>
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
