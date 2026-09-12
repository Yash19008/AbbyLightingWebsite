"use client";

import { useState, useMemo, useRef, useEffect, useCallback } from "react";
import type { NewArrivalCategory, NewArrivalProduct } from "@/types/new-arrival";

interface NewArrivalsSectionProps {
  categories: NewArrivalCategory[];
}

export default function NewArrivalsSection({ categories }: NewArrivalsSectionProps) {
  const [activeTab, setActiveTab] = useState<string>("Architectural");
  const trackRef = useRef<HTMLDivElement>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(false);

  const GAP = isMobile ? 10 : 16;

  // Derive products for the selected category tab dynamically
  const filteredProducts = useMemo(() => {
    const category = categories.find(
      cat => cat.name.toLowerCase() === activeTab.toLowerCase()
    );
    return category ? category.products : [];
  }, [categories, activeTab]);

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : 280;
    const cardEl = el.firstElementChild as HTMLElement;
    if (!cardEl) return isMobile ? (el.clientWidth - GAP) / 2 : (el.clientWidth - 3 * GAP) / 4;
    return cardEl.getBoundingClientRect().width || (isMobile ? (el.clientWidth - GAP) / 2 : (el.clientWidth - 3 * GAP) / 4);
  }, [isMobile, GAP]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || filteredProducts.length === 0) {
      setCanScrollLeft(false);
      setCanScrollRight(false);
      return;
    }

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);
  }, [filteredProducts.length]);

  useEffect(() => {
    const checkMobile = () => setIsMobile(window.innerWidth <= 768);
    checkMobile();

    const handleResize = () => {
      checkMobile();
      updateScrollState();
    };
    window.addEventListener("resize", handleResize);

    const el = trackRef.current;
    if (el) {
      updateScrollState();
      el.addEventListener("scroll", updateScrollState, { passive: true });
    }

    return () => {
      window.removeEventListener("resize", handleResize);
      if (el) el.removeEventListener("scroll", updateScrollState);
    };
  }, [updateScrollState]);

  // When active category changes, reset scroll to start
  useEffect(() => {
    if (trackRef.current) {
      trackRef.current.scrollLeft = 0;
    }
    // Give DOM a frame to update
    const timeout = setTimeout(() => {
      updateScrollState();
    }, 50);
    return () => clearTimeout(timeout);
  }, [activeTab, updateScrollState]);

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const cardWidth = getCardWidth();
    const scrollDistance = isMobile ? (cardWidth * 2 + GAP * 2) : (cardWidth + GAP);
    el.scrollBy({ left: dir * scrollDistance, behavior: "smooth" });
  };

  const showNav = filteredProducts.length > (isMobile ? 2 : 4);

  const desktopArrow = (isEnabled: boolean): React.CSSProperties => ({
    position: "absolute",
    top: "40%",
    transform: "translateY(-50%)",
    zIndex: 10,
    width: 44,
    height: 60,
    display: !isMobile && showNav ? "grid" : "none",
    placeItems: "center",
    background: "transparent",
    border: "none",
    cursor: isEnabled ? "pointer" : "default",
    fontSize: 36,
    fontWeight: 300,
    color: "#111",
    opacity: isEnabled ? 1 : 0.25,
    pointerEvents: isEnabled ? "auto" : "none",
    transition: "opacity 0.25s ease",
    padding: 0,
    lineHeight: 1,
  });

  return (
    <section className="section" id="arrivals">
      <div className="shell" style={{ width: "100%", boxSizing: "border-box" }}>
        <div className="section-head reveal">
          <h2>New Arrivals</h2>
        </div>
        <div className="product-toolbar">
          <div className="filter-chips" role="tablist" aria-label="New arrival categories">
            {["Architectural", "Decorative", "Outdoor"].map((tabName) => (
              <button 
                key={tabName}
                type="button" 
                role="tab" 
                aria-selected={activeTab === tabName}
                className={activeTab === tabName ? 'active' : ''}
                onClick={() => setActiveTab(tabName)}
              >
                {tabName}
              </button>
            ))}
          </div>
        </div>

        <div style={{ position: "relative", width: "100%" }}>
          {/* Desktop Previous Arrow */}
          {!isMobile && (
            <button
              onClick={() => scroll(-1)}
              aria-label="Previous products"
              style={{ ...desktopArrow(canScrollLeft), left: -50 }}
              disabled={!canScrollLeft}
            >
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
            </button>
          )}

          {/* Slider Container */}
          <div style={{ overflow: "hidden", width: "100%" }}>
            <div
              ref={trackRef}
              className="products-slider-track"
              style={{
                display: "flex",
                gap: GAP,
                overflowX: "scroll",
                scrollSnapType: "x mandatory",
                scrollbarWidth: "none",
                msOverflowStyle: "none",
                WebkitOverflowScrolling: "touch",
                padding: 0,
                margin: 0,
                width: "100%",
                boxSizing: "border-box",
              }}
            >
              {filteredProducts.length > 0 ? (
                filteredProducts.map((product: NewArrivalProduct, index: number) => (
                  <a 
                    key={product.id}
                    className="product reveal is-visible" 
                    style={{
                      flex: isMobile
                        ? `0 0 calc((100% - ${GAP}px) / 2)`
                        : `0 0 calc((100% - 3 * ${GAP}px) / 4)`,
                      minWidth: isMobile
                        ? `calc((100% - ${GAP}px) / 2)`
                        : `calc((100% - 3 * ${GAP}px) / 4)`,
                      maxWidth: isMobile
                        ? `calc((100% - ${GAP}px) / 2)`
                        : `calc((100% - 3 * ${GAP}px) / 4)`,
                      width: isMobile
                        ? `calc((100% - ${GAP}px) / 2)`
                        : `calc((100% - 3 * ${GAP}px) / 4)`,
                      scrollSnapAlign: "start",
                      scrollSnapStop: "always",
                      margin: 0,
                      textDecoration: "none",
                      display: "flex",
                      flexDirection: "column",
                      position: "relative",
                      boxSizing: "border-box",
                      "--i": index,
                    } as any} 
                    href="/#contact"
                  >
                    <div
                      className="photo"
                      style={{
                        position: "relative",
                        width: "100%",
                        height: "auto",
                        aspectRatio: "1/1",
                        borderRadius: isMobile ? "0 28px 0 0" : "0 40px 0 0",
                        overflow: "hidden",
                        backgroundColor: "#eeeeee",
                      }}
                    >
                      {product.image_url ? (
                        <img
                          src={product.image_url}
                          alt={product.name}
                          style={{
                            width: "100%",
                            height: "100%",
                            objectFit: "cover",
                            display: "block",
                            transition: "transform 0.5s ease",
                          }}
                        />
                      ) : (
                        <div style={{ width: "100%", height: "100%", background: "#f0f0f0", display: "flex", alignItems: "center", justifyContent: "center" }}>
                          <span style={{ color: "#999999", fontSize: isMobile ? "12px" : "14px" }}>No image</span>
                        </div>
                      )}
                    </div>
                    <h3
                      style={{
                        margin: isMobile ? "10px 0 2px 0" : "14px 0 4px 0",
                        color: "#111111",
                        fontFamily: "Inter, sans-serif",
                        fontSize: isMobile ? "13px" : "18px",
                        fontWeight: 600,
                        lineHeight: 1.3,
                      }}
                    >
                      {product.name}
                    </h3>
                    <p
                      style={{
                        margin: 0,
                        color: "#888888",
                        fontFamily: "Inter, sans-serif",
                        fontSize: isMobile ? "11px" : "14px",
                        fontWeight: 300,
                        lineHeight: 1.4,
                      }}
                    >
                      {product.parent_category} · {product.category}
                    </p>
                  </a>
                ))
              ) : (
                <div className="products-empty-state" style={{ width: "100%", padding: "40px 0" }}>
                  <p>No products available in this category</p>
                </div>
              )}
            </div>
          </div>

          {/* Desktop Next Arrow */}
          {!isMobile && (
            <button
              onClick={() => scroll(1)}
              aria-label="Next products"
              style={{ ...desktopArrow(canScrollRight), right: -50 }}
              disabled={!canScrollRight}
            >
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M9 18l6-6-6-6"></path>
              </svg>
            </button>
          )}

          {/* Mobile Floating Circular Previous Arrow Button */}
          {isMobile && canScrollLeft && (
            <button
              onClick={() => scroll(-1)}
              aria-label="Previous products"
              style={{
                position: "absolute",
                left: "-12px",
                top: "40%",
                transform: "translateY(-50%)",
                zIndex: 10,
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: "rgba(30, 30, 30, 0.4)",
                border: "1px solid rgba(255, 255, 255, 0.25)",
                color: "#ffffff",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                cursor: "pointer",
                padding: 0,
                boxShadow: "0 2px 8px rgba(0, 0, 0, 0.4)",
              }}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M15 18l-6-6 6-6" />
              </svg>
            </button>
          )}

          {/* Mobile Floating Circular Next Arrow Button */}
          {isMobile && canScrollRight && (
            <button
              onClick={() => scroll(1)}
              aria-label="Next products"
              style={{
                position: "absolute",
                right: "-12px",
                top: "40%",
                transform: "translateY(-50%)",
                zIndex: 10,
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: "rgba(30, 30, 30, 0.4)",
                border: "1px solid rgba(255, 255, 255, 0.25)",
                color: "#ffffff",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                cursor: "pointer",
                padding: 0,
                boxShadow: "0 2px 8px rgba(0, 0, 0, 0.4)",
              }}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M9 18l6-6-6-6" />
              </svg>
            </button>
          )}
        </div>
      </div>
    </section>
  );
}
