"use client";
import { API_URL, API_BASE } from '@/lib/config';


import { useState } from "react";
import Link from "next/link";
import type { MenuItem } from "@/lib/api/menu";



export default function Footer({ initialMenuItems = [] }: { initialMenuItems?: MenuItem[] }) {
  const [openAccordions, setOpenAccordions] = useState<Record<number, boolean>>({});

  const toggleAccordion = (id: number) => {
    setOpenAccordions(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  const renderColumnItems = (items: MenuItem[], colClass: string = '') => {
    if (items.length === 0) return null;

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
            {renderColumnItems(initialMenuItems.filter(item => item.location === 'footer_col_1'), "footer-inspiration")}
          </nav>

          <nav className="footer-links footer-company" aria-label="Company links">
            {renderColumnItems(initialMenuItems.filter(item => item.location === 'footer_col_2'))}
          </nav>

          <nav className="footer-links footer-legal" aria-label="Legal links">
            {renderColumnItems(initialMenuItems.filter(item => item.location === 'footer_col_3'))}
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
