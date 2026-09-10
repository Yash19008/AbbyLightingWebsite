"use client";

import { useRef, useState, useEffect, useCallback } from "react";
import type { LightWorld } from "@/types/light-world";

interface WorldsSectionProps {
  lightWorlds: LightWorld[];
}

export default function WorldsSection({ lightWorlds }: WorldsSectionProps) {
  const trackRef = useRef<HTMLDivElement>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [activeSlide, setActiveSlide] = useState(0);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const [totalDots, setTotalDots] = useState(lightWorlds.length);

  const GAP = isMobile ? 10 : 14;

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : 292;
    const cardEl = el.firstElementChild as HTMLElement;
    if (!cardEl) return isMobile ? ((el.clientWidth - 10) / 2) : 292;
    return cardEl.getBoundingClientRect().width || (isMobile ? ((el.clientWidth - 10) / 2) : 292);
  }, [isMobile]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || lightWorlds.length === 0) return;

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);
    const cardWidth = getCardWidth();
    const itemWidth = cardWidth + GAP;

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);

    const numDots = isMobile
      ? lightWorlds.length
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
  }, [lightWorlds.length, isMobile, GAP, getCardWidth]);

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

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const cardWidth = getCardWidth();
    const scrollDistance = isMobile ? (cardWidth * 2 + GAP * 2) : (cardWidth + GAP);
    el.scrollBy({ left: dir * scrollDistance, behavior: 'smooth' });
  };

  const showNav = lightWorlds.length > (isMobile ? 2 : 3);

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
    <section className="section" id="worlds">
      <div className="shell" style={{ width: '100%', boxSizing: 'border-box' }}>
        <div className="section-head reveal">
          <h2>Four worlds of light</h2>
        </div>

        <div style={{ position: 'relative', width: '100%' }}>
          {/* Desktop Left Arrow */}
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

          <div style={{ overflow: 'hidden', width: '100%' }}>
            <div
              ref={trackRef}
              className="world-grid"
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
              {lightWorlds.map((world, index) => (
                <a
                  key={world.id}
                  className="world"
                  style={{
                    flex: isMobile ? '0 0 calc(50% - 5px)' : `0 0 calc((100% - 3 * ${GAP}px) / 3.5)`,
                    minWidth: isMobile ? 'calc(50% - 5px)' : undefined,
                    maxWidth: isMobile ? 'calc(50% - 5px)' : undefined,
                    width: isMobile ? 'calc(50% - 5px)' : undefined,
                    scrollSnapAlign: 'start',
                    scrollSnapStop: 'always',
                    margin: 0,
                    textDecoration: 'none',
                    display: 'block',
                    position: 'relative',
                    boxSizing: 'border-box',
                    "--i": index
                  } as any}
                  href={world.link || '/#arrivals'}
                >
                  <div
                    className="photo"
                    style={{
                      position: 'relative',
                      width: '100%',
                      aspectRatio: '4/5',
                      borderRadius: 4,
                      overflow: 'hidden',
                      backgroundColor: '#1c1c1c',
                    }}
                  >
                    {world.light_of_image_url && (
                      <img
                        className="world-light world-light-off"
                        src={world.light_of_image_url}
                        alt={`${world.name} switched off`}
                        style={{
                          width: '100%',
                          height: '100%',
                          objectFit: 'cover',
                          display: 'block',
                          transition: 'opacity 0.4s ease',
                        }}
                      />
                    )}
                    {world.light_on_image_url && (
                      <img
                        className="world-light world-light-on"
                        src={world.light_on_image_url}
                        alt={`${world.name} switched on`}
                        style={{
                          position: 'absolute',
                          inset: 0,
                          width: '100%',
                          height: '100%',
                          objectFit: 'cover',
                          opacity: 0,
                          transition: 'opacity 0.4s ease',
                        }}
                      />
                    )}
                  </div>

                  {/* Title and Arrow directly after text */}
                  <h3
                    style={{
                      margin: isMobile ? '0' : '14px 0 0',
                      color: isMobile ? '#ffffff' : '#111111',
                      fontFamily: 'Inter, sans-serif',
                      fontSize: isMobile ? 15 : 22,
                      fontWeight: 600,
                      lineHeight: 1.3,
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: 8,
                      textShadow: isMobile ? '0 1px 4px rgba(0,0,0,0.6)' : 'none',
                    }}
                  >
                    <span>{world.name}</span>
                    {!isMobile && (
                      <svg
                        className="world-arrow"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        style={{
                          display: 'inline-block',
                          flexShrink: 0,
                          transition: 'transform 0.2s ease',
                        }}
                      >
                        <line x1="4" y1="12" x2="20" y2="12" />
                        <polyline points="14 6 20 12 14 18" />
                      </svg>
                    )}
                  </h3>
                </a>
              ))}
            </div>
          </div>

          {/* Desktop Right Arrow */}
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

        {/* Dots indicator (Desktop only) */}
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
      </div>
    </section>
  );
}


