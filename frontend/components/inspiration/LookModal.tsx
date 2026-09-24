import React, { useEffect, useRef, useState, useCallback } from "react";
import { createPortal } from "react-dom";

import Link from 'next/link';

export interface ProductVariantItem {
  color: string;
  image: string;
}

export interface ProductUsedItem {
  name: string;
  type: string;
  image: string;
  colors?: string[];
  variants?: ProductVariantItem[];
  collection?: string;
  link?: string;
}

export interface LookItem {
  title: string;
  kicker: string;
  room: string;
  image: string;
  productsUsed?: ProductUsedItem[];
}

interface LookModalProps {
  isOpen: boolean;
  look: LookItem | null;
  onClose: () => void;
}

function ProductCard({ p }: { p: ProductUsedItem }) {
  const [selectedVariant, setSelectedVariant] = useState<ProductVariantItem | null>(null);

  const displayImage = selectedVariant ? selectedVariant.image : p.image;

  const ImageContent = (
    <div className="product-card-image-wrap" style={{ width: '100%', height: '180px', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: '#f9f9f9', borderRadius: '4px', overflow: 'hidden' }}>
      <img
        src={displayImage}
        alt={p.name}
        style={{ width: '100%', height: '100%', objectFit: 'cover', padding: '5px' }}
        onError={(e) => {
          (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
        }}
      />
    </div>
  );

  const TitleContent = <h4 className="product-card-title">{p.name}</h4>;

  return (
    <article className="product-card" style={{ display: 'flex', flexDirection: 'column' }}>
      {p.link ? (
        <Link href={p.link} style={{ textDecoration: 'none', color: 'inherit', display: 'block' }}>
          {ImageContent}
        </Link>
      ) : (
        ImageContent
      )}
      
      <div className="product-card-info">
        {p.link ? (
          <Link href={p.link} style={{ textDecoration: 'none', color: 'inherit' }}>
            {TitleContent}
          </Link>
        ) : (
          TitleContent
        )}
        
        <span className="product-card-type">{p.type}</span>

        {p.variants && p.variants.length > 0 ? (
          <div className="product-card-colors" aria-label="Available variants" style={{ display: 'flex', gap: '8px', marginTop: '8px', flexWrap: 'wrap', alignItems: 'center' }}>
            {p.variants.slice(0, 5).map((v, vIdx) => (
              <span
                key={vIdx}
                className={`color-dot ${selectedVariant === v ? 'is-active' : ''}`}
                style={{ 
                  backgroundColor: v.color, 
                  width: '20px', 
                  height: '20px', 
                  borderRadius: '50%',
                  display: 'inline-block',
                  cursor: 'pointer',
                  border: '1px solid rgba(0,0,0,0.1)',
                  outline: selectedVariant === v ? '1.5px solid #111' : 'none', 
                  outlineOffset: '2px',
                  transition: 'outline 0.2s'
                }}
                title={v.color}
                onClick={(e) => {
                  e.preventDefault();
                  e.stopPropagation();
                  setSelectedVariant(v);
                }}
              />
            ))}
            {p.variants.length > 5 && (
              <span style={{ fontSize: '0.75rem', color: '#666', marginLeft: '4px' }}>
                +{p.variants.length - 5}
              </span>
            )}
          </div>
        ) : p.colors && p.colors.length > 0 ? (
          <div className="product-card-colors" aria-label="Available colors" style={{ display: 'flex', gap: '8px', marginTop: '8px', flexWrap: 'wrap', alignItems: 'center' }}>
            {p.colors.slice(0, 5).map((colorHex, cIdx) => (
              <span
                key={cIdx}
                className="color-dot"
                style={{ 
                  backgroundColor: colorHex,
                  width: '20px', 
                  height: '20px', 
                  borderRadius: '50%',
                  display: 'inline-block',
                  border: '1px solid rgba(0,0,0,0.1)'
                }}
                title={colorHex}
              />
            ))}
            {p.colors.length > 5 && (
              <span style={{ fontSize: '0.75rem', color: '#666', marginLeft: '4px' }}>
                +{p.colors.length - 5}
              </span>
            )}
          </div>
        ) : null}

        {p.collection && (
          <span className="product-card-collection">
            {p.collection}
          </span>
        )}
      </div>
    </article>
  );
}

export default function LookModal({ isOpen, look, onClose }: LookModalProps) {
  const trackRef = useRef<HTMLDivElement>(null);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el) return;
    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);
    setCanScrollLeft(scrollLeft > 5);
    setCanScrollRight(scrollLeft < maxScroll - 5);
  }, []);

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && isOpen) {
        onClose();
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, onClose]);

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = "hidden";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [isOpen]);

  useEffect(() => {
    const el = trackRef.current;
    if (el && isOpen) {
      updateScrollState();
      el.addEventListener("scroll", updateScrollState, { passive: true });
    }
    return () => {
      if (el) el.removeEventListener("scroll", updateScrollState);
    };
  }, [isOpen, look, updateScrollState]);

  if (!isOpen || !look || !mounted) return null;

  const productsToRender = look.productsUsed || [];

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const firstCard = el.firstElementChild as HTMLElement;
    const cardWidth = firstCard ? firstCard.getBoundingClientRect().width : 180;
    const gap = 14;
    el.scrollBy({ left: dir * (cardWidth + gap), behavior: "smooth" });
  };

  return createPortal(
    <div
      className="look-modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="look-modal-title"
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div className="modal-card">
        <button
          className="modal-close"
          type="button"
          aria-label="Close lookbook"
          onClick={onClose}
        >
          ×
        </button>
        <img
          className="modal-image"
          src={look.image}
          alt={look.title}
          onError={(e) => {
            (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
          }}
        />
        <div className="modal-content">
          <div className="modal-header-info">
            <h3 id="look-modal-title">{look.title}</h3>
            <p className="modal-kicker">{look.kicker}</p>
          </div>

          <div className="products-slider-section">
            <div className="products-used-label">Products Used</div>

            <div className="products-slider-wrapper">
              {/* Left Arrow Button */}
              {canScrollLeft && (
                <button
                  type="button"
                  className="products-nav-btn prev"
                  onClick={() => scroll(-1)}
                  aria-label="Previous product"
                >
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M15 18l-6-6 6-6" />
                  </svg>
                </button>
              )}

              {/* Scrollable Track */}
              <div ref={trackRef} className="products-track">
                {productsToRender.map((p, idx) => {
                  return <ProductCard key={idx} p={p} />;
                })}
              </div>

              {/* Right Arrow Button */}
              {canScrollRight && (
                <button
                  type="button"
                  className="products-nav-btn next"
                  onClick={() => scroll(1)}
                  aria-label="Next product"
                >
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M9 18l6-6-6-6" />
                  </svg>
                </button>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>,
    document.body
  );
}

