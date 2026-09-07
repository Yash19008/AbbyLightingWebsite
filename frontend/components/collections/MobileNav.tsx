"use client";

import { useState, useEffect, useRef, useLayoutEffect } from 'react';
import Link from 'next/link';

const menus: Record<string, { title: string; links: [string, string][] }> = {
  product: {
    title: 'Products',
    links: [
      ['Architectural', '/#worlds'],
      ['Decorative', '/decorative'],
      ['Outdoor', '/#worlds'],
      ['Smart Lighting', '/#worlds'],
    ],
  },
  work: {
    title: 'Our Work',
    links: [
      ['Projects', '/projects'],
      ['Clients', '/clients'],
    ],
  },
  inspiration: {
    title: 'Inspiration',
    links: [
      ['Ideas, Stories & Inspiration', '/inspiration'],
      ['Catalogues', '/catalog-download-user-form'],
    ],
  },
  more: {
    title: 'More',
    links: [
      ['About Us', '/#contact'],
      ['Contact Us', '/#contact'],
      ['Careers', '/#contact'],
      ['Fairs & Events', '/#contact'],
    ],
  },
};

const icons: Record<string, React.ReactNode> = {
  home: (
    <>
      <path d="M3.5 10.6 12 3.9l8.5 6.7" />
      <path d="M5.7 9.2v9.1a1.6 1.6 0 0 0 1.6 1.6h9.4a1.6 1.6 0 0 0 1.6-1.6V9.2" />
    </>
  ),
  product: (
    <>
      <path d="M12 3.4v3.1" />
      <path d="M6.6 13.4a5.4 5.4 0 0 1 10.8 0Z" />
      <path d="M9.7 13.4a2.3 2.3 0 0 0 4.6 0" />
    </>
  ),
  work: (
    <>
      <path d="M3.5 7.1h6.2l2 2h8.8v8.7a1.8 1.8 0 0 1-1.8 1.8H5.3a1.8 1.8 0 0 1-1.8-1.8Z" />
      <path d="M3.5 10h17" />
    </>
  ),
  inspiration: (
    <>
      <path d="M9.1 16.3a5 5 0 1 1 5.8 0 1.6 1.6 0 0 0-.6 1.2v.4H9.7v-.4a1.6 1.6 0 0 0-.6-1.2Z" />
      <path d="M10 20.1h4" />
    </>
  ),
  more: (
    <>
      <path d="M4.5 8.2h15" />
      <path d="M4.5 12h15" />
      <path d="M4.5 15.8h15" />
    </>
  ),
};

const tabs = [
  ['home', 'Home'],
  ['product', 'Products'],
  ['work', 'Our Work'],
  ['inspiration', 'Inspiration'],
  ['more', 'More'],
];

export default function MobileNav() {
  const [menu, setMenu] = useState<string | null>(null);
  const [visible, setVisible] = useState(true);
  const [plate, setPlate] = useState({ width: 390, height: 76 });
  const dockRef = useRef<HTMLDivElement>(null);

  useLayoutEffect(() => {
    if (!dockRef.current) return;
    const measure = () => {
      if (dockRef.current) {
        setPlate({
          width: Math.round(dockRef.current.getBoundingClientRect().width),
          height: Math.round(dockRef.current.getBoundingClientRect().height),
        });
      }
    };
    measure();
    const observer = new ResizeObserver(measure);
    observer.observe(dockRef.current);
    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    let previous = window.scrollY;
    const onScroll = () => {
      const next = window.scrollY;
      setVisible(next < previous || next < 40);
      previous = next;
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    document.body.style.overflowY = menu ? 'hidden' : 'auto';
    return () => {
      document.body.style.overflowY = 'auto';
    };
  }, [menu]);

  useEffect(() => {
    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') setMenu(null);
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, []);

  const handleTabClick = (key: string) => {
    if (key === 'home') {
      window.location.assign('/');
    } else {
      setMenu((current) => (current === key ? null : key));
    }
  };

  const d = `M6 0H${plate.width - 6}Q${plate.width} 0 ${plate.width} 6V${plate.height - 6}Q${plate.width} ${plate.height} ${plate.width - 6} ${plate.height}H6Q0 ${plate.height} 0 ${plate.height - 6}V6Q0 0 6 0Z`;

  return (
    <>
      <nav
        className={`halo-nav-wrap ${visible || menu ? 'halo-visible' : 'halo-hidden'}`}
        aria-label="Mobile sections"
      >
        <div ref={dockRef} className="halo-nav is-ready is-idle">
          <span className="halo-nav-shadow" aria-hidden="true" />
          <svg
            className="halo-nav-skin"
            aria-hidden="true"
            focusable="false"
            preserveAspectRatio="none"
            viewBox={`0 0 ${plate.width} ${plate.height}`}
          >
            <defs>
              <linearGradient id="internalHaloPlateGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-plate-highlight" offset="0" />
                <stop className="halo-plate-shadow" offset="1" />
              </linearGradient>
              <linearGradient id="internalHaloRimGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-rim-strong" offset="0" />
                <stop className="halo-rim-soft" offset="1" />
              </linearGradient>
            </defs>
            <path className="halo-nav-plate" d={d} />
          </svg>
          <span className="halo-orb" aria-hidden="true" />
          <div className="halo-tabs" role="tablist" aria-label="Page sections">
            {tabs.map(([key, label], index) => (
              <button
                key={key}
                className="halo-tab"
                role="tab"
                type="button"
                id={`halo-tab-${key}`}
                aria-selected={menu === key}
                tabIndex={index === 0 ? 0 : -1}
                onClick={() => handleTabClick(key)}
              >
                <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                  {icons[key]}
                </svg>
                <span className="halo-label">{label}</span>
              </button>
            ))}
          </div>
        </div>
      </nav>

      <button
        className={`menu-backdrop ${menu ? 'open' : ''}`}
        aria-label="Close menu"
        onClick={() => setMenu(null)}
      />

      <div
        className={`msheet ${menu ? 'open' : ''}`}
        role="dialog"
        aria-modal="true"
        aria-label="Mobile menu"
      >
        <button className="menu-close" aria-label="Close menu" onClick={() => setMenu(null)}>
          <span aria-hidden="true">×</span>
        </button>
        <div className="m-dash" />
        <div className="m-title">{menu ? menus[menu]?.title : ''}</div>
        <div className="mobile-menu-list">
          {menu && (
            <div className="mobile-menu-group">
              {menus[menu]?.links.map(([label, href]) => (
                <Link key={label} href={href}>
                  {label}
                  <span>→</span>
                </Link>
              ))}
            </div>
          )}
        </div>
      </div>
    </>
  );
}
