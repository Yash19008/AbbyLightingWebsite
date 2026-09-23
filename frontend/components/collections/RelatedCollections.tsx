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
  const [isTablet, setIsTablet] = useState(false);
  const [activeSlide, setActiveSlide] = useState(0);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const items = collections || [];
  const [totalDots, setTotalDots] = useState(items.length);

  const GAP = isMobile ? 12 : isTablet ? 20 : 24;

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return 500;
    const cardEl = el.firstElementChild as HTMLElement;
    if (!cardEl) return (el.clientWidth - GAP) / 2;
    return cardEl.getBoundingClientRect().width || (el.clientWidth - GAP) / 2;
  }, [GAP]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || items.length === 0) return;

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);
    const cardWidth = getCardWidth();
    const itemWidth = cardWidth + GAP;

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);

    const numDots = Math.max(1, Math.round(maxScroll / itemWidth) + 1);
    setTotalDots(numDots);

    if (maxScroll <= 5) {
      setActiveSlide(0);
    } else if (scrollLeft >= maxScroll - 15) {
      setActiveSlide(numDots - 1);
    } else {
      const idx = Math.round((scrollLeft / maxScroll) * (numDots - 1));
      setActiveSlide(Math.min(Math.max(0, idx), numDots - 1));
    }
  }, [items.length, GAP, getCardWidth]);

  useEffect(() => {
    const checkViewport = () => {
      const w = window.innerWidth;
      setIsMobile(w <= 600);
      setIsTablet(w > 600 && w <= 1024);
    };
    checkViewport();

    const handleResize = () => {
      checkViewport();
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
    const scrollDistance = cardWidth + GAP;
    el.scrollBy({ left: dir * scrollDistance, behavior: 'smooth' });
  };

  const showNav = items.length > 1;

  const cardFlex = `0 0 calc((100% - ${GAP}px) / 2)`;
  const cardDim = `calc((100% - ${GAP}px) / 2)`;

  return (
    <section className="s-section s-related">
      <div className="s-head">
        <h2>Explore other collections</h2>
      </div>

      <div className="s-carousel">
        <button
          className="s-carousel-arrow s-carousel-prev"
          type="button"
          aria-label="Previous rooms"
          onClick={() => scroll(-1)}
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M15 18l-6-6 6-6"></path>
          </svg>
        </button>

        {/* Inner clipper */}
        <div style={{ overflow: 'hidden', width: '100%' }}>
          <div
            ref={trackRef}
            className="s-scroll"
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
                    flex: cardFlex,
                    minWidth: cardDim,
                    maxWidth: cardDim,
                    width: cardDim,
                    flexShrink: 0,
                    scrollSnapAlign: 'start',
                    scrollSnapStop: 'always',
                    textDecoration: 'none',
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'flex-end',
                    position: 'relative',
                    height: isMobile ? '220px' : isTablet ? '260px' : '300px',
                    color: '#fff',
                    overflow: 'hidden',
                    borderRadius: 0,
                    padding: isTablet ? '0 16px 16px' : '0 22px 22px',
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
                  <h3 style={{ font: isTablet ? '600 18px Inter' : '600 22px Inter', margin: 0, position: 'relative', zIndex: 2 }}>{collection.name}</h3>
                  {descriptionText && (
                    <p style={{ fontSize: isTablet ? '14px' : '18px', fontWeight: 300, margin: '4px 0 0', position: 'relative', zIndex: 2 }}>{descriptionText}</p>
                  )}
                </Link>
              );
            })}
          </div>
        </div>

        <button
          className="s-carousel-arrow s-carousel-next"
          type="button"
          aria-label="Next rooms"
          onClick={() => scroll(1)}
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 18l6-6-6-6"></path>
          </svg>
        </button>
      </div>

      {/* Dots Indicator */}
      {!isMobile && showNav && totalDots > 1 && (
        <div 
          style={{
            display: 'flex',
            justifyContent: 'flex-end',
            gap: 6,
            padding: '20px 0 0',
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

