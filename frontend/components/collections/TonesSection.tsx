"use client";

import { useRef, useState, useEffect, useCallback } from 'react';
import Image from 'next/image';

const tonesFamilies = [
  {
    image: '/images/symphony/tone-earth.png',
    title: 'The Earths',
    finishes: [
      { color: '#5c2f26', name: 'Coffee Brown' },
      { color: '#b9ad9a', name: 'Pebble Grey' },
      { color: '#b5652f', name: 'Malt Brown' },
      { color: '#80461f', name: 'Milk Chocolate' },
      { color: '#57604e', name: 'Olive Green' },
      { color: '#a6332b', name: 'Brick Red' },
      { color: '#4e2e25', name: 'Chestnut Brown' },
      { color: '#c88566', name: 'Matt Blush' },
      { color: '#bd8643', name: 'Tan Brown' },
    ],
  },
  {
    image: '/images/symphony/tone-metallic.png',
    title: 'The Metallics',
    finishes: [
      { color: '#b5714a', name: 'Metallic Copper' },
      { color: '#1f1f1f', name: 'Matt Black' },
      { color: '#f2f0ea', name: 'Matt White' },
      { color: '#c9a24b', name: 'Metallic Gold' },
    ],
  },
  {
    image: '/images/symphony/tone-neutral.png',
    title: 'The Neutrals',
    finishes: [
      { color: '#23262a', name: 'Charcoal Black' },
      { color: '#5a636b', name: 'Graphite Gray' },
      { color: '#9aa3ab', name: 'Zen Gray' },
      { color: '#f4f3ee', name: 'Arctic White' },
    ],
  },
  {
    image: '/images/symphony/tone-pastel.png',
    title: 'The Pastels',
    finishes: [
      { color: '#704034', name: 'Icy Blue' },
      { color: '#b5652f', name: 'Spring Green' },
      { color: '#57604e', name: 'Matt Blush' },
      { color: '#4e2e25', name: 'Dusky Pink' },
      { color: '#bd8643', name: 'Pebble Grey' },
    ],
  },
];

export default function TonesSection() {
  const gridRef = useRef<HTMLDivElement>(null);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const updateScroll = useCallback(() => {
    const container = gridRef.current;
    if (!container) return;
    const maxScroll = Math.max(0, container.scrollWidth - container.clientWidth);
    setCanScrollRight(container.scrollLeft < maxScroll - 10);
  }, []);

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
        <h2>The colour families</h2>
        <p>A curated palette organised into families — each with its own moodboard and named finishes.</p>
      </div>
      <div className="s-tone-carousel" style={{ position: 'relative' }}>
        <div className="s-tone-grid" ref={gridRef}>
          {tonesFamilies.map((family, index) => (
            <article key={index}>
              <Image
                src={family.image}
                alt={family.title}
                width={600}
                height={400}
                style={{ objectFit: 'cover' }}
              />
              <div>
                <h3>{family.title}</h3>
                <ul>
                  {family.finishes.map((finish, fIndex) => (
                    <li key={fIndex}>
                      <i style={{ background: finish.color }} />
                      {finish.name}
                    </li>
                  ))}
                </ul>
              </div>
            </article>
          ))}
        </div>

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
