"use client";

import { useRef, useState, useEffect, useCallback } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { useHorizontalCarousel } from '@/lib/hooks/useHorizontalCarousel';

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
  const items = collections || [];

  const {
    trackRef,
    isMobile,
    isTablet,
    canScrollLeft,
    canScrollRight,
    scrollLeft,
    scrollRight,
    handleScroll,
    gap,
  } = useHorizontalCarousel({
    itemCount: items.length,
    gap: { mobile: 12, tablet: 20, desktop: 24 },
    desktopCardsVisible: 2,
    tabletCardsVisible: 2,
    mobileCardsVisible: 2,
  });

  if (!collections || collections.length === 0) {
    return null;
  }

  const cardDim = `calc((100% - ${gap}px) / 2)`;

  return (
    <section className="s-section s-related">
      <div className="s-head">
        <h2>Explore other collections</h2>
      </div>

      <div className="s-carousel">
        {canScrollLeft && (
          <button
            className="s-carousel-arrow s-carousel-prev"
            type="button"
            aria-label="Previous collections"
            onClick={scrollLeft}
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
        )}

        <div style={{ overflow: 'hidden', width: '100%' }}>
          <div
            ref={trackRef}
            onScroll={handleScroll}
            className="s-scroll"
            style={{
              display: 'flex',
              gap: gap,
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
                    flexGrow: 0,
                    flexShrink: 0,
                    flexBasis: cardDim,
                    minWidth: cardDim,
                    maxWidth: cardDim,
                    width: cardDim,
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
                  <h3 style={{ font: isTablet ? '600 18px Inter' : '600 22px Inter', margin: 0, position: 'relative', zIndex: 2 }}>
                    {collection.name}
                  </h3>
                  {descriptionText && (
                    <p style={{ fontSize: isTablet ? '14px' : '18px', fontWeight: 300, margin: '4px 0 0', position: 'relative', zIndex: 2 }}>
                      {descriptionText}
                    </p>
                  )}
                </Link>
              );
            })}
          </div>
        </div>

        {canScrollRight && (
          <button
            className="s-carousel-arrow s-carousel-next"
            type="button"
            aria-label="Next collections"
            onClick={scrollRight}
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
        )}
      </div>

      {/* Dummy element to intercept s-related>div:last-child grid style selector */}
      <div style={{ display: 'none' }} />
    </section>
  );
}
