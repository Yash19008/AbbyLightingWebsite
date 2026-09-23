import React, { useEffect, useRef, useState, useCallback } from "react";
import { createPortal } from "react-dom";

export interface ProductUsedItem {
  name: string;
  type: string;
  image: string;
  colors?: string[];
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

  const defaultProducts: ProductUsedItem[] = look.productsUsed || [
    {
      name: "Cymbal",
      type: "Pendant Light",
      image: "/images/decorative/cymbal-on.png",
      colors: ["#F5F2EB", "#1C1C1C", "#A04828"],
      collection: "Quarry Collection",
      link: "/#arrivals",
    },
    {
      name: "Dew",
      type: "Pendant Light",
      image: "/images/reference/product-neoma.png",
      colors: ["#F5F2EB", "#1C1C1C", "#A04828"],
      collection: "Quarry Collection",
      link: "/#arrivals",
    },
    {
      name: "Apex",
      type: "Pendant Light",
      image: "/images/decorative/apex-on.png",
      colors: ["#F5F2EB", "#1C1C1C", "#A04828"],
      collection: "Quarry Collection",
      link: "/#arrivals",
    },
    {
      name: "Symphony",
      type: "Linear Light",
      image: "/images/world-decorative-on.png",
      colors: ["#F5F2EB", "#1C1C1C", "#A04828"],
      collection: "Symphony Collection",
      link: "/#arrivals",
    },
  ];

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
                {defaultProducts.map((p, idx) => (
                  <article key={idx} className="product-card">
                    <div className="product-card-image-wrap">
                      <img
                        src={p.image}
                        alt={p.name}
                        onError={(e) => {
                          (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
                        }}
                      />
                    </div>
                    <div className="product-card-info">
                      <h4 className="product-card-title">{p.name}</h4>
                      <span className="product-card-type">{p.type}</span>

                      {p.colors && p.colors.length > 0 && (
                        <div className="product-card-colors" aria-label="Available colors">
                          {p.colors.map((colorHex, cIdx) => (
                            <span
                              key={cIdx}
                              className="color-dot"
                              style={{ backgroundColor: colorHex }}
                            />
                          ))}
                        </div>
                      )}

                      {p.collection && (
                        <span className="product-card-collection">{p.collection}</span>
                      )}
                    </div>
                  </article>
                ))}
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

