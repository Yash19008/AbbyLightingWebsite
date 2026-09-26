'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';

interface Variant {
  id: string;
  name: string;
  color: string;
  imageOff: string;
  imageOn: string;
}

interface Gallery {
  id: string;
  image: string;
}

export interface Product {
  id: string;
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

  const totalImages = 1 + (product.galleries?.length || 0);

  useEffect(() => {
    setActiveImageIndex(0);
  }, [isGlobalLightOn]);

  const handleNext = (e: React.MouseEvent) => {
    e.preventDefault();
    if (activeImageIndex < totalImages - 1) {
      setActiveImageIndex((prev) => prev + 1);
    }
  };

  const handlePrev = (e: React.MouseEvent) => {
    e.preventDefault();
    if (activeImageIndex > 0) {
      setActiveImageIndex((prev) => prev - 1);
    }
  };

  const activeVariant = product.variants && product.variants.length > 0 
    ? product.variants[activeVariantIndex] 
    : null;

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
        <div className={`decorative-card-image ${isGlobalLightOn ? 'is-lit' : 'is-unlit'}`}>
          <Link
            className="decorative-card-main-link"
            href={`/product-detail/${product.slug}`}
            aria-label={`View ${product.name}`}
          >
            {activeImageIndex === 0 ? (
              <>
                {activeVariant?.imageOff && (
                  <Image
                    className="decorative-product-image is-current is-light-off"
                    src={activeVariant.imageOff}
                    alt=""
                    fill
                    sizes="(max-width: 600px) 100vw, 33vw"
                    aria-hidden="true"
                    style={{ objectFit: 'cover' }}
                  />
                )}
                {activeVariant?.imageOn && (
                  <Image
                    className="decorative-product-image is-current is-light-on"
                    src={activeVariant.imageOn}
                    alt={`${product.name} in ${activeVariant?.name || 'default'}, light on`}
                    fill
                    sizes="(max-width: 600px) 100vw, 33vw"
                    style={{ objectFit: 'cover' }}
                  />
                )}
              </>
            ) : (
              product.galleries?.[activeImageIndex - 1]?.image && (
                <Image
                  className="decorative-product-image is-current is-light-off is-light-on"
                  src={product.galleries[activeImageIndex - 1].image}
                  alt={`${product.name} gallery image`}
                  fill
                  sizes="(max-width: 600px) 100vw, 33vw"
                  style={{ objectFit: 'cover' }}
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
                {/* 1 dot for variant, plus dots for galleries */}
                {[0, ...(product.galleries || []).map((_, i) => i + 1)].map((dotIndex) => (
                  <button
                    key={dotIndex}
                    type="button"
                    className={dotIndex === activeImageIndex ? 'active' : ''}
                    aria-label={`Show ${product.name} view ${dotIndex + 1}`}
                    onClick={(e) => {
                      e.preventDefault();
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
            {product.variants.slice(0, 8).map((variant, index) => (
              <button
                key={variant.id}
                type="button"
                className={index === activeVariantIndex ? 'active' : ''}
                style={{ background: variant.color }}
                aria-label={`Select ${variant.name}`}
                title={variant.name}
                onClick={(e) => {
                  e.preventDefault();
                  setActiveVariantIndex(index);
                  setActiveImageIndex(0); // Snap back to main image
                }}
              />
            ))}
            {product.variants.length > 8 && (
              <span 
                className="decorative-swatch-more" 
                style={{ fontSize: '11px', color: 'var(--decorative-muted)', display: 'flex', alignItems: 'center', marginLeft: '2px' }}
              >
                +{product.variants.length - 8}
              </span>
            )}
          </div>
          <span className="decorative-collection">{product.collection}</span>
        </div>
      </article>
    </div>
  );
}
