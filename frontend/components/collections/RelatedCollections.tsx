"use client";

import { useRef, useState, useEffect, useCallback } from 'react';
import Image from 'next/image';
import Link from 'next/link';

interface RelatedCollectionsProps {
  collections: Array<{
    id: number;
    slug: string;
    name: string;
    short_description?: string | null;
    description: string;
    hero_section: {
      background_image: string | null;
      title_prefix: string;
      title_highlight: string;
      description: string;
    } | null;
  }>;
}

export default function RelatedCollections({ collections }: RelatedCollectionsProps) {
  const trackRef = useRef<HTMLDivElement>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [activeSlide, setActiveSlide] = useState(0);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const items = collections || [];
  const [totalDots, setTotalDots] = useState(items.length);

  const GAP = isMobile ? 10 : 24;

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : 500;
    const cardEl = el.firstElementChild as HTMLElement;
    if (!cardEl) return isMobile ? ((el.clientWidth - 10) / 2) : 500;
    return cardEl.getBoundingClientRect().width || (isMobile ? ((el.clientWidth - 10) / 2) : 500);
  }, [isMobile]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || items.length === 0) return;

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);
    const cardWidth = getCardWidth();
    const itemWidth = cardWidth + GAP;

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);

    const numDots = isMobile
      ? Math.max(1, Math.ceil(items.length / 2))
      : Math.max(1, Math.round(maxScroll / itemWidth) + 1);
    setTotalDots(numDots);

    if (maxScroll <= 5) {
      setActiveSlide(0);
    } else if (scrollLeft >= maxScroll - 15) {
      setActiveSlide(numDots - 1);
    } else {
      const idx = Math.round((scrollLeft / maxScroll) * (numDots - 1));
      setActiveSlide(Math.min(Math.max(0, idx), numDots - 1));
    }
  }, [items.length, isMobile, GAP, getCardWidth]);

  useEffect(() => {
    const checkMobile = () => setIsMobile(window.innerWidth <= 768);
    checkMobile();

    const handleResize = () => {
      checkMobile();
      updateScrollState();
    };
    window.addEventListener('resize', handleResize);

    const el = trackRef.current;
    if (el) {
      updateScrollState();
      el.addEventListener('scroll', updateScrollState, { passive: true });
    }

    return () => {
      window.removeEventListener('resize', handleResize);
      if (el) el.removeEventListener('scroll', updateScrollState);
    };
  }, [updateScrollState]);

  if (!collections || collections.length === 0) {
    return null;
  }

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const cardWidth = getCardWidth();
    const scrollDistance = isMobile ? (cardWidth * 2 + GAP * 2) : (cardWidth + GAP);
    el.scrollBy({ left: dir * scrollDistance, behavior: 'smooth' });
  };

  const showNav = items.length > (isMobile ? 2 : 2);

  const desktopArrow = (isEnabled: boolean): React.CSSProperties => ({
    position: 'absolute',
    top: '50%',
    transform: 'translateY(-50%)',
    zIndex: 10,
    width: 44,
    height: 60,
    display: !isMobile && showNav ? 'grid' : 'none',
    placeItems: 'center',
    background: 'transparent',
    border: 'none',
    cursor: isEnabled ? 'pointer' : 'default',
    fontSize: 36,
    fontWeight: 300,
    color: '#111',
    opacity: isEnabled ? 1 : 0.25,
    pointerEvents: isEnabled ? 'auto' : 'none',
    transition: 'opacity 0.25s ease',
    padding: 0,
    lineHeight: 1,
  });

  return (
    <section className="s-section s-related">
      <div className="s-head">
        <h2>Explore other collections</h2>
      </div>

      <div className="s-slider-container" style={{ position: 'relative', display: 'block', width: '100%' }}>
        {/* Desktop Previous Arrow */}
        {!isMobile && (
          <button
            onClick={() => scroll(-1)}
            aria-label="Previous"
            style={{ ...desktopArrow(canScrollLeft), left: -50 }}
            disabled={!canScrollLeft}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
        )}

        {/* Inner clipper */}
        <div style={{ overflow: 'hidden', width: '100%' }}>
          <div
            ref={trackRef}
            style={{
              display: 'flex',
              gap: GAP,
              overflowX: 'scroll',
              scrollSnapType: 'x mandatory',
              scrollbarWidth: 'none',
              msOverflowStyle: 'none',
              WebkitOverflowScrolling: 'touch',
              padding: 0,
              margin: 0,
              width: '100%',
              boxSizing: 'border-box',
            }}
          >
            {items.map((collection) => {
              const imageSrc = collection.hero_section?.background_image || '/images/symphony/look-earth.png';
              const descriptionText = collection.short_description || '';

              return (
                <Link
                  key={collection.id}
                  href={`/collections/${collection.slug}`}
                  style={{
                    flex: isMobile ? '0 0 calc(50% - 5px)' : `0 0 calc((100% - ${GAP}px) / 2)`,
                    minWidth: isMobile ? 'calc(50% - 5px)' : undefined,
                    maxWidth: isMobile ? 'calc(50% - 5px)' : undefined,
                    width: isMobile ? 'calc(50% - 5px)' : undefined,
                    flexShrink: 0,
                    scrollSnapAlign: 'start',
                    scrollSnapStop: 'always',
                    textDecoration: 'none',
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'flex-end',
                    position: 'relative',
                    height: isMobile ? '220px' : '300px',
                    aspectRatio: isMobile ? '4/5' : undefined,
                    color: '#fff',
                    overflow: 'hidden',
                    borderRadius: isMobile ? 4 : 0,
                    padding: isMobile ? '12px 10px' : '0 22px 22px',
                    boxSizing: 'border-box',
                  }}
                >
                  <Image
                    src={imageSrc}
                    alt={collection.name}
                    fill
                    style={{ objectFit: 'cover', position: 'absolute', inset: 0, zIndex: 0 }}
                  />
                  <div style={{
                    position: 'absolute',
                    inset: '30% 0 0',
                    background: 'linear-gradient(transparent, rgba(0,0,0,0.78))',
                    zIndex: 1,
                  }} />
                  <h3 style={{ font: isMobile ? '600 14px Inter' : '600 22px Inter', margin: 0, position: 'relative', zIndex: 2 }}>{collection.name}</h3>
                  {descriptionText && (
                    <p style={{ fontSize: isMobile ? '10px' : '18px', fontWeight: 300, margin: isMobile ? '2px 0 0' : '4px 0 0', position: 'relative', zIndex: 2 }}>{descriptionText}</p>
                  )}
                </Link>
              );
            })}
          </div>
        </div>

        {/* Desktop Next Arrow */}
        {!isMobile && (
          <button
            onClick={() => scroll(1)}
            aria-label="Next"
            style={{ ...desktopArrow(canScrollRight), right: -50 }}
            disabled={!canScrollRight}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
        )}

        {/* Mobile Floating Circular Next Arrow Button */}
        {isMobile && canScrollRight && (
          <button
            onClick={() => scroll(1)}
            aria-label="Next"
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

      {/* Dots Indicator (Desktop only) */}
      {!isMobile && showNav && totalDots > 1 && (
        <div 
          style={{
            display: 'flex',
            justifyContent: 'flex-end',
            gap: 6,
            padding: '24px 0 0',
          }}
        >
          {Array.from({ length: totalDots }).map((_, i) => (
            <button
              key={i}
              onClick={() => {
                const el = trackRef.current;
                if (!el) return;
                const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);
                const cardWidth = getCardWidth();
                const itemWidth = cardWidth + GAP;
                const targetScroll = i === totalDots - 1 ? maxScroll : Math.min(i * itemWidth, maxScroll);
                el.scrollTo({ left: targetScroll, behavior: 'smooth' });
              }}
              aria-label={`Go to slide ${i + 1}`}
              style={{
                width: activeSlide === i ? 30 : 6,
                height: 6,
                borderRadius: 3,
                backgroundColor: activeSlide === i ? '#f6c177' : '#ead8bd',
                border: 'none',
                padding: 0,
                cursor: 'pointer',
                transition: 'all 0.3s cubic-bezier(0.25, 1, 0.5, 1)',
              }}
            />
          ))}
        </div>
      )}
      {/* Dummy element to intercept s-related>div:last-child grid style selector */}
      <div style={{ display: 'none' }} />
    </section>
  );
}

