'use client';

import React, { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import '@/styles/decorative-products.css';

interface Variant {
  id: string | number;
  name: string;
  color: string;
  imageOff: string;
  imageOn: string;
}

interface Gallery {
  id: string | number;
  image: string;
}

export interface Product {
  id: string | number;
  slug: string;
  name: string;
  category: string;
  collection: string;
  isNew: boolean;
  variants: Variant[];
  galleries?: Gallery[];
}

interface DecorativeCardProps {
  product: Product;
  order: number;
  filterDelay: number;
  isGlobalLightOn: boolean;
  hideNewBadge?: boolean;
}

export default function DecorativeCard({ product, order, filterDelay, isGlobalLightOn, hideNewBadge }: DecorativeCardProps) {
  const [activeVariantIndex, setActiveVariantIndex] = useState(0);
  const [activeImageIndex, setActiveImageIndex] = useState(0);
  const maxSwatches = 4;

  const touchStartXRef = useRef<number | null>(null);
  const touchStartYRef = useRef<number | null>(null);
  const isSwipingRef = useRef<boolean>(false);

  const displayGalleries = (product.galleries || []).slice(0, 4);
  const totalImages = 1 + displayGalleries.length;

  useEffect(() => {
    setActiveImageIndex(0);
  }, [isGlobalLightOn]);

  const handleNext = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (activeImageIndex < totalImages - 1) {
      setActiveImageIndex((prev) => prev + 1);
    }
  };

  const handlePrev = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (activeImageIndex > 0) {
      setActiveImageIndex((prev) => prev - 1);
    }
  };

  const handleTouchStart = (e: React.TouchEvent | React.MouseEvent) => {
    const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
    const clientY = 'touches' in e ? e.touches[0].clientY : e.clientY;
    touchStartXRef.current = clientX;
    touchStartYRef.current = clientY;
    isSwipingRef.current = false;
  };

  const handleTouchEnd = (e: React.TouchEvent | React.MouseEvent) => {
    if (touchStartXRef.current === null || touchStartYRef.current === null) return;

    const clientX = 'changedTouches' in e ? e.changedTouches[0].clientX : e.clientX;
    const clientY = 'changedTouches' in e ? e.changedTouches[0].clientY : e.clientY;

    const diffX = touchStartXRef.current - clientX;
    const diffY = touchStartYRef.current - clientY;

    touchStartXRef.current = null;
    touchStartYRef.current = null;

    // Ignore if vertical scrolling is dominant
    if (Math.abs(diffY) > Math.abs(diffX)) return;

    // Horizontal swipe threshold (30px)
    if (Math.abs(diffX) > 30) {
      isSwipingRef.current = true;
      if (diffX > 30) {
        // Swiped Left -> Next image
        if (activeImageIndex < totalImages - 1) {
          setActiveImageIndex((prev) => prev + 1);
        }
      } else if (diffX < -30) {
        // Swiped Right -> Previous image
        if (activeImageIndex > 0) {
          setActiveImageIndex((prev) => prev - 1);
        }
      }
    }
  };

  const handleLinkClick = (e: React.MouseEvent) => {
    if (isSwipingRef.current) {
      e.preventDefault();
      isSwipingRef.current = false;
    }
  };

  const activeVariant = product.variants && product.variants.length > 0 
    ? product.variants[activeVariantIndex] 
    : null;

  const firstVariant = product.variants && product.variants.length > 0 
    ? product.variants[0] 
    : null;

  const displayImageOff = activeVariant?.imageOff || firstVariant?.imageOff;
  const displayImageOn = activeVariant?.imageOn || firstVariant?.imageOn;

  return (
    <div
      className="decorative-grid-item-motion"
      style={{ '--filter-delay': `${filterDelay}ms` } as React.CSSProperties}
    >
      <article
        className="decorative-card decorative-reveal is-visible"
        suppressHydrationWarning
        style={{ '--card-order': order, '--light-delay': `${order * 50}ms` } as React.CSSProperties}
      >
        <div 
          className={`decorative-card-image ${isGlobalLightOn ? 'is-lit' : 'is-unlit'}`}
          onTouchStart={handleTouchStart}
          onTouchEnd={handleTouchEnd}
          onMouseDown={handleTouchStart}
          onMouseUp={handleTouchEnd}
          style={{ touchAction: 'pan-y', userSelect: 'none' }}
        >
          <Link
            className="decorative-card-main-link"
            href={`/product-detail/${product.slug}`}
            aria-label={`View ${product.name}`}
            onClick={handleLinkClick}
          >
            {activeImageIndex === 0 ? (
              <>
                {displayImageOff && (
                  <Image
                    className="decorative-product-image is-current is-light-off"
                    src={displayImageOff}
                    alt=""
                    fill
                    sizes="(max-width: 600px) 100vw, 33vw"
                    aria-hidden="true"
                    style={{ objectFit: 'cover' }}
                    draggable={false}
                  />
                )}
                {displayImageOn && (
                  <Image
                    className="decorative-product-image is-current is-light-on"
                    src={displayImageOn}
                    alt={`${product.name} in ${activeVariant?.name || 'default'}, light on`}
                    fill
                    sizes="(max-width: 600px) 100vw, 33vw"
                    style={{ objectFit: 'cover' }}
                    draggable={false}
                  />
                )}
              </>
            ) : (
              displayGalleries[activeImageIndex - 1]?.image && (
                <Image
                  className="decorative-product-image is-current is-light-off is-light-on"
                  src={displayGalleries[activeImageIndex - 1].image}
                  alt={`${product.name} gallery image`}
                  fill
                  sizes="(max-width: 600px) 100vw, 33vw"
                  style={{ objectFit: 'cover' }}
                  draggable={false}
                />
              )
            )}
          </Link>

          {!hideNewBadge && product.isNew && <span className="decorative-badge">New</span>}

          {totalImages > 1 && (
            <>
              {activeImageIndex > 0 && (
                <button
                  type="button"
                  className="decorative-card-arrow decorative-card-prev"
                  aria-label={`Previous ${product.name} image`}
                  onClick={handlePrev}
                >
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                  </svg>
                </button>
              )}
              {activeImageIndex < totalImages - 1 && (
                <button
                  type="button"
                  className="decorative-card-arrow decorative-card-next"
                  aria-label={`Next ${product.name} image`}
                  onClick={handleNext}
                >
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M9 18l6-6-6-6" />
                  </svg>
                </button>
              )}
              <span className="decorative-gallery-dots">
                {[0, ...displayGalleries.map((_, i) => i + 1)].map((dotIndex) => (
                  <button
                    key={dotIndex}
                    type="button"
                    className={dotIndex === activeImageIndex ? 'active' : ''}
                    aria-label={`Show ${product.name} view ${dotIndex + 1}`}
                    onClick={(e) => {
                      e.preventDefault();
                      e.stopPropagation();
                      setActiveImageIndex(dotIndex);
                    }}
                  />
                ))}
              </span>
            </>
          )}
        </div>

        <div className="decorative-card-copy">
          <h3>{product.name}</h3>
          <p>{product.category}</p>
          <div className="decorative-swatches" aria-label={`${product.name} finishes`}>
            {product.variants.slice(0, maxSwatches).map((variant, index) => (
              <button
                key={variant.id}
                type="button"
                className={index === activeVariantIndex ? 'active' : ''}
                style={{ background: variant.color }}
                aria-label={`Select ${variant.name}`}
                title={variant.name}
                onClick={(e) => {
                  e.preventDefault();
                  e.stopPropagation();
                  setActiveVariantIndex(index);
                  setActiveImageIndex(0);
                }}
              />
            ))}
            {product.variants.length > maxSwatches && (
              <span 
                className="decorative-swatch-more" 
                style={{ fontSize: '11px', color: 'var(--decorative-muted)', display: 'flex', alignItems: 'center', marginLeft: '2px' }}
              >
                +{product.variants.length - maxSwatches}
              </span>
            )}
          </div>
          <span className="decorative-collection">{product.collection}</span>
        </div>
      </article>
    </div>
  );
}
