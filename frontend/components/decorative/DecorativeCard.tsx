'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';

interface Variant {
  id: string;
  name: string;
  color: string;
  imageOff: string;
  imageOn: string;
}

interface Product {
  id: string;
  slug: string;
  name: string;
  category: string;
  collection: string;
  isNew: boolean;
  variants: Variant[];
}

interface DecorativeCardProps {
  product: Product;
  order: number;
  filterDelay: number;
  isGlobalLightOn: boolean;
}

export default function DecorativeCard({ product, order, filterDelay, isGlobalLightOn }: DecorativeCardProps) {
  const [activeVariantIndex, setActiveVariantIndex] = useState(0);

  const handleNext = (e: React.MouseEvent) => {
    e.preventDefault();
    setActiveVariantIndex((prev) => (prev + 1) % product.variants.length);
  };

  const handlePrev = (e: React.MouseEvent) => {
    e.preventDefault();
    setActiveVariantIndex((prev) => (prev - 1 + product.variants.length) % product.variants.length);
  };

  const activeVariant = product.variants[activeVariantIndex];

  return (
    <div
      className="decorative-grid-item-motion"
      style={{ '--filter-delay': `${filterDelay}ms` } as React.CSSProperties}
    >
      <article
        className="decorative-card decorative-reveal is-visible"
        style={{ '--card-order': order, '--light-delay': `${order * 50}ms` } as React.CSSProperties}
      >
        <div className={`decorative-card-image ${isGlobalLightOn ? 'is-lit' : ''}`}>
          <Link
            className="decorative-card-main-link"
            href={`/product-detail/${product.slug}`}
            aria-label={`View ${product.name}`}
          >
            <Image
              className="decorative-product-image is-current is-light-off"
              src={activeVariant.imageOff}
              alt=""
              fill
              sizes="(max-width: 600px) 100vw, 33vw"
              aria-hidden="true"
            />
            <Image
              className="decorative-product-image is-current is-light-on"
              src={activeVariant.imageOn}
              alt={`${product.name} in ${activeVariant.name}, light on`}
              fill
              sizes="(max-width: 600px) 100vw, 33vw"
            />
          </Link>

          {product.isNew && <span className="decorative-badge">New</span>}

          {product.variants.length > 1 && (
            <>
              <button
                type="button"
                className="decorative-card-arrow decorative-card-prev"
                aria-label={`Previous ${product.name} image`}
                onClick={handlePrev}
              >
                ‹
              </button>
              <button
                type="button"
                className="decorative-card-arrow decorative-card-next"
                aria-label={`Next ${product.name} image`}
                onClick={handleNext}
              >
                ›
              </button>
              <span className="decorative-gallery-dots">
                {product.variants.map((variant, index) => (
                  <button
                    key={variant.id}
                    type="button"
                    className={index === activeVariantIndex ? 'active' : ''}
                    aria-label={`Show ${product.name} view ${index + 1}`}
                    onClick={(e) => {
                      e.preventDefault();
                      setActiveVariantIndex(index);
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
            {product.variants.map((variant, index) => (
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
                }}
              />
            ))}
          </div>
          <span className="decorative-collection">{product.collection} Collection</span>
        </div>
      </article>
    </div>
  );
}
