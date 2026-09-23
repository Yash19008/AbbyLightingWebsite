"use client";

import { useRef, useState, useEffect, useCallback } from 'react';
import Image from 'next/image';
import { CompositionsSection as CompositionsSectionType } from '@/types/collection';
import LookModal, { LookItem } from '@/components/inspiration/LookModal';
import '@/styles/inspiration.css';

interface Props {
  compositionsSection: CompositionsSectionType;
}

export default function CompositionsSectionDynamic({ compositionsSection }: Props) {
  const trackRef = useRef<HTMLDivElement>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [isTablet, setIsTablet] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);
  const [activeSlide, setActiveSlide] = useState(0);
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const [activeLook, setActiveLook] = useState<LookItem | null>(null);

  const items = compositionsSection.items || [];
  const [totalDots, setTotalDots] = useState(items.length);

  const GAP = isMobile ? 10 : 14;

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : isTablet ? 280 : 358;
    const cardEl = el.firstElementChild as HTMLElement;
    if (!cardEl) return isMobile ? ((el.clientWidth - 10) / 2) : isTablet ? ((el.clientWidth - 2 * GAP) / 2.45) : 358;
    return cardEl.getBoundingClientRect().width || (isMobile ? ((el.clientWidth - 10) / 2) : isTablet ? ((el.clientWidth - 2 * GAP) / 2.45) : 358);
  }, [isMobile, isTablet, GAP]);

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
    const checkViewport = () => {
      const w = window.innerWidth;
      setIsMobile(w <= 700);
      setIsTablet(w > 700 && w <= 1024);
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

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const cardWidth = getCardWidth();
    const scrollDistance = isMobile ? (cardWidth * 2 + GAP * 2) : (cardWidth + GAP);
    el.scrollBy({ left: dir * scrollDistance, behavior: 'smooth' });
  };

  const showNav = items.length > (isMobile ? 2 : isTablet ? 2 : 3);

  const desktopArrow = (isEnabled: boolean): React.CSSProperties => ({
    position: 'absolute',
    top: '50%',
    transform: 'translateY(-50%)',
    zIndex: 10,
    width: 44,
    height: 60,
    display: !isMobile && !isTablet && showNav ? 'grid' : 'none',
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

  const cardFlex = isMobile
    ? '0 0 calc(50% - 5px)'
    : isTablet
    ? `0 0 calc((100% - 2 * ${GAP}px) / 2.45)`
    : `0 0 calc((100% - 3 * ${GAP}px) / 3.5)`;

  const cardDim = isMobile
    ? 'calc(50% - 5px)'
    : isTablet
    ? `calc((100% - 2 * ${GAP}px) / 2.45)`
    : undefined;

  return (
    <section className="s-section s-inspire">
      {/* Heading */}
      <div className="s-head">
        <h2>{compositionsSection.title}</h2>
        <p>{compositionsSection.subtitle}</p>
      </div>

      <div style={{ position: 'relative', width: '100%' }}>
        {/* Desktop Previous Arrow */}
        {!isMobile && !isTablet && (
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
            {items.map((comp, index) => (
              <article
                key={comp.id}
                className={`s-look ${activeIndex === index ? 'is-active' : ''}`}
                tabIndex={0}
                onClick={() => {
                  setActiveIndex(activeIndex === index ? null : index);
                  setActiveLook({
                    title: comp.title || 'Composition',
                    kicker: comp.kicker || comp.category || 'Symphony Composition',
                    room: comp.category ? comp.category.toLowerCase() : 'living',
                    image: comp.image,
                  });
                }}
                style={{
                  cursor: 'pointer',
                  flex: cardFlex,
                  minWidth: cardDim,
                  maxWidth: cardDim,
                  width: cardDim,
                  scrollSnapAlign: 'start',
                  scrollSnapStop: 'always',
                  margin: 0,
                  boxSizing: 'border-box',
                  borderRadius: isMobile ? 4 : 0,
                  overflow: 'hidden',
                  position: 'relative',
                  aspectRatio: isMobile ? '4/5' : undefined,
                  height: isMobile ? 'auto' : 429,
                }}
              >
                <Image
                  src={comp.image}
                  alt="Symphony composition"
                  fill
                  style={{ objectFit: 'cover' }}
                />
                <div style={{
                  position: 'absolute',
                  inset: '25% 0 0',
                  background: 'linear-gradient(transparent, rgba(0,0,0,0.82))',
                }} />
                <div style={{ position: 'absolute', zIndex: 2, left: isMobile ? 12 : 28, right: isMobile ? 12 : 24, bottom: isMobile ? 12 : 25 }}>
                  <p style={{ font: isMobile ? '500 italic 12px Inter' : '500 italic 18px Inter', margin: '0 0 4px' }}>{comp.kicker}</p>
                  <span style={{ fontSize: isMobile ? 10 : 13 }}>{comp.title} {comp.category ? `· ${comp.category}` : ''}</span>
                </div>
              </article>
            ))}
          </div>
        </div>

        {/* Desktop Next Arrow */}
        {!isMobile && !isTablet && (
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

        {/* Mobile & Tablet Floating Circular Previous Arrow Button */}
        {(isMobile || isTablet) && canScrollLeft && (
          <button
            onClick={() => scroll(-1)}
            aria-label="Previous"
            style={{
              position: 'absolute',
              left: isMobile ? '-10px' : '-14px',
              top: '50%',
              transform: 'translateY(-50%)',
              zIndex: 10,
              width: isMobile ? 32 : 38,
              height: isMobile ? 32 : 38,
              borderRadius: '50%',
              background: 'rgba(30, 30, 30, 0.65)',
              backdropFilter: 'blur(4px)',
              WebkitBackdropFilter: 'blur(4px)',
              border: '1px solid rgba(255, 255, 255, 0.3)',
              color: '#ffffff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              padding: 0,
              boxShadow: '0 2px 10px rgba(0, 0, 0, 0.4)',
            }}
          >
            <svg width={isMobile ? "14" : "18"} height={isMobile ? "14" : "18"} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6" />
            </svg>
          </button>
        )}

        {/* Mobile & Tablet Floating Circular Next Arrow Button */}
        {(isMobile || isTablet) && canScrollRight && (
          <button
            onClick={() => scroll(1)}
            aria-label="Next"
            style={{
              position: 'absolute',
              right: isMobile ? '-10px' : '-14px',
              top: '50%',
              transform: 'translateY(-50%)',
              zIndex: 10,
              width: isMobile ? 32 : 38,
              height: isMobile ? 32 : 38,
              borderRadius: '50%',
              background: 'rgba(30, 30, 30, 0.65)',
              backdropFilter: 'blur(4px)',
              WebkitBackdropFilter: 'blur(4px)',
              border: '1px solid rgba(255, 255, 255, 0.3)',
              color: '#ffffff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              padding: 0,
              boxShadow: '0 2px 10px rgba(0, 0, 0, 0.4)',
            }}
          >
            <svg width={isMobile ? "14" : "18"} height={isMobile ? "14" : "18"} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
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

      <LookModal
        isOpen={Boolean(activeLook)}
        look={activeLook}
        onClose={() => setActiveLook(null)}
      />
    </section>
  );
}

