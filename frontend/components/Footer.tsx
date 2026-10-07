"use client";

import { useState, useEffect } from "react";
import Link from "next/link";

interface MenuItem {
  id: number;
  title: string;
  url: string;
  location: string;
  type: string;
  children?: MenuItem[];
}

export default function Footer() {
  const [menuItems, setMenuItems] = useState<MenuItem[]>([]);
  const [openAccordions, setOpenAccordions] = useState<Record<number, boolean>>({});

  useEffect(() => {
    async function fetchMenuItems() {
      try {
        const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
        const res = await fetch(`${API_URL}/api/menu-items`);
        if (res.ok) {
          const data = await res.json();
          if (data.success && Array.isArray(data.data)) {
            setMenuItems(data.data);
          }
        }
      } catch (error) {
        console.error('Error fetching footer menu items:', error);
      }
    }
    fetchMenuItems();
  }, []);

  const toggleAccordion = (id: number) => {
    setOpenAccordions(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  const renderColumnItems = (items: MenuItem[], fallbackContent: React.ReactNode, colClass: string = '') => {
    if (items.length === 0) return fallbackContent;

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
                <Link key={child.id} href={child.url}>{child.title}</Link>
              ))}
            </div>
          </div>
        );
      } else {
        return (
          <Link 
            key={item.id} 
            className={colClass || (item.title === 'Privacy Policy' || item.title === 'Terms of Use' ? 'footer-mobile-legal' : '')} 
            href={item.url}
          >
            {item.title}
          </Link>
        );
      }
    });
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
            {renderColumnItems(menuItems.filter(item => item.location === 'footer_col_1'), (
              <>
                <div className="footer-accordion">
                  <button className="footer-accordion-toggle" type="button">
                    <span>Products</span>
                    <span className="footer-accordion-icon" aria-hidden="true"></span>
                  </button>
                  <div className="footer-accordion-panel">
                    <a href="/products">Architectural</a>
                    <a href="/products">Outdoor</a>
                    <a href="/abby-smart">Smart Lighting</a>
                  </div>
                </div>
                <div className="footer-accordion">
                  <button className="footer-accordion-toggle" type="button">
                    <span>Our Work</span>
                    <span className="footer-accordion-icon" aria-hidden="true"></span>
                  </button>
                  <div className="footer-accordion-panel">
                    <a href="/clients">Clients</a>
                    <a href="/projects">Projects</a>
                  </div>
                </div>
                <a className="footer-inspiration" href="/inspiration">Inspiration</a>
              </>
            ), "footer-inspiration")}
          </nav>

          <nav className="footer-links footer-company" aria-label="Company links">
            {renderColumnItems(menuItems.filter(item => item.location === 'footer_col_2'), (
              <>
                <a href="/company">About Us</a>
                <a href="/contact">Contact Us</a>
                <a href="/career">Careers</a>
                <Link href="/catalogues">Catalogues</Link>
                <a className="footer-mobile-legal" href="/privacy-policy">Privacy Policy</a>
                <a className="footer-mobile-legal" href="/terms-and-conditions">Terms of Use</a>
                <a href="/fair-events">Fairs &amp; Events</a>
              </>
            ))}
          </nav>

          <nav className="footer-links footer-legal" aria-label="Legal links">
            {renderColumnItems(menuItems.filter(item => item.location === 'footer_col_3'), (
              <>
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/terms-and-conditions">Terms of Use</a>
              </>
            ))}
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
                <img src="/icons/youtube.png" alt="YouTube" width="22" height="22" className="footer-social-img footer-youtube" />
              </span>
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
