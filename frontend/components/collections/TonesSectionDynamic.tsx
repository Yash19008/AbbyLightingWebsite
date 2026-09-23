"use client";

import { useRef, useState, useEffect, useCallback } from 'react';
import Image from 'next/image';
import { TonesSection as TonesSectionType } from '@/types/collection';

interface TonesSectionProps {
  tonesSection: TonesSectionType;
}

// Helper function to capitalize first letter of each word
const capitalizeFirstLetter = (str: string): string => {
  return str
    .split(' ')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(' ');
};

export default function TonesSectionDynamic({ tonesSection }: TonesSectionProps) {
  const gridRef = useRef<HTMLDivElement>(null);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const updateScroll = useCallback(() => {
    const container = gridRef.current;
    if (!container) return;
    const maxScroll = Math.max(0, container.scrollWidth - container.clientWidth);
    setCanScrollLeft(container.scrollLeft > 10);
    setCanScrollRight(container.scrollLeft < maxScroll - 10);
  }, []);

  const scrollPrev = () => {
    if (!gridRef.current) return;
    const container = gridRef.current;
    const cards = Array.from(container.querySelectorAll('article'));
    const current = cards.findIndex(
      (card) => Math.abs(card.getBoundingClientRect().left - container.getBoundingClientRect().left) < card.getBoundingClientRect().width / 2
    );
    const prevIndex = Math.max(current - 1, 0);
    const target = cards[prevIndex] as HTMLElement;
    
    if (target) {
      container.scrollTo({
        left: target.offsetLeft,
        behavior: 'smooth',
      });
    }
  };

  const scrollNext = () => {
    if (!gridRef.current) return;
    const container = gridRef.current;
    const cards = Array.from(container.querySelectorAll('article'));
    const current = cards.findIndex(
      (card) => Math.abs(card.getBoundingClientRect().left - container.getBoundingClientRect().left) < card.getBoundingClientRect().width / 2
    );
    const nextIndex = Math.min(current + 1, cards.length - 1);
    const target = cards[nextIndex] as HTMLElement;
    
    if (target) {
      container.scrollTo({
        left: target.offsetLeft,
        behavior: 'smooth',
      });
    }
  };

  useEffect(() => {
    const container = gridRef.current;
    if (!container) return;

    updateScroll();
    container.addEventListener('scroll', updateScroll, { passive: true });
    window.addEventListener('resize', updateScroll);

    return () => {
      container.removeEventListener('scroll', updateScroll);
      window.removeEventListener('resize', updateScroll);
    };
  }, [updateScroll]);

  return (
    <section className="s-section s-tones">
      <div className="s-head">
        <h2>{tonesSection.title}</h2>
        <p>{tonesSection.subtitle}</p>
      </div>
      <div style={{ position: 'relative', width: '100%' }}>
        <div className="s-tone-grid" ref={gridRef}>
          {tonesSection.families.map((family) => (
            <article key={family.id}>
              <Image
                src={family.image}
                alt={family.title}
                width={300}
                height={250}
                style={{ objectFit: 'cover' }}
              />
              <div>
                <h3>{family.title}</h3>
                <ul>
                  {family.colors.map((color) => (
                    <li key={color.id}>
                      <i
                        style={{
                          backgroundColor: color.css_value,
                          border: color.css_value === '#ffffff' || color.css_value.toLowerCase() === '#fff' ? '1px solid #ccc' : 'none',
                        }}
                      />
                      {capitalizeFirstLetter(color.name)}
                    </li>
                  ))}
                </ul>
              </div>
            </article>
          ))}
        </div>

        {/* Mobile Floating Circular Previous Arrow Button */}
        {canScrollLeft && (
          <button
            type="button"
            onClick={scrollPrev}
            aria-label="Previous colour family"
            style={{
              position: 'absolute',
              left: '-12px',
              top: '50%',
              transform: 'translateY(-50%)',
              zIndex: 10,
              width: 32,
              height: 32,
              borderRadius: '50%',
              background: 'rgba(30, 30, 30, 0.4)',
              border: '1px solid rgba(255, 255, 255, 0.25)',
              color: '#ffffff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              padding: 0,
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.4)',
            }}
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6" />
            </svg>
          </button>
        )}

        {/* Mobile Floating Circular Next Arrow Button */}
        {canScrollRight && (
          <button
            type="button"
            onClick={scrollNext}
            aria-label="Next colour family"
            style={{
              position: 'absolute',
              right: '-12px',
              top: '50%',
              transform: 'translateY(-50%)',
              zIndex: 10,
              width: 32,
              height: 32,
              borderRadius: '50%',
              background: 'rgba(30, 30, 30, 0.4)',
              border: '1px solid rgba(255, 255, 255, 0.25)',
              color: '#ffffff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              padding: 0,
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.4)',
            }}
          >
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6" />
            </svg>
          </button>
        )}
      </div>
    </section>
  );
}

