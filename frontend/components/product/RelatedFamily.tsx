"use client";
import { API_URL, API_BASE } from '@/lib/config';


import React, { useEffect, useRef, useState, useCallback } from "react";
import { DecRelatedProduct } from "@/types/decorative";
import DecorativeCard from "@/components/decorative/DecorativeCard";

interface RelatedFamilyProps {
  products: DecRelatedProduct[];
  productSlug: string;
}

export default function RelatedFamily({
  products: initialProducts,
  productSlug,
}: RelatedFamilyProps) {
  const [products, setProducts] = useState<DecRelatedProduct[]>(initialProducts);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(initialProducts.length >= 6);
  const [isLoading, setIsLoading] = useState(false);

  const [inView, setInView] = useState(false);
  const [isSettled, setIsSettled] = useState(false);
  const [isMobile, setIsMobile] = useState(false);
  const [isTablet, setIsTablet] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const sectionRef = useRef<HTMLElement>(null);
  const trackRef = useRef<HTMLDivElement>(null);
  const settledTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  const GAP = isMobile ? 12 : 18;

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          setInView(true);
          settledTimerRef.current = setTimeout(() => setIsSettled(true), 900);
          observer.disconnect();
        }
      },
      { rootMargin: "0px 0px -100px 0px", threshold: 0.08 }
    );
    if (sectionRef.current) observer.observe(sectionRef.current);
    return () => {
      observer.disconnect();
      if (settledTimerRef.current) clearTimeout(settledTimerRef.current);
    };
  }, []);

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : isTablet ? 250 : 280;
    const cardEl = el.firstElementChild as HTMLElement;
    if (cardEl) {
      const w = cardEl.getBoundingClientRect().width;
      if (w > 0) return w;
    }
    return isMobile
      ? (el.clientWidth - GAP) / 2
      : isTablet
        ? (el.clientWidth - 2 * GAP) / 3
        : (el.clientWidth - 3 * GAP) / 4;
  }, [isMobile, isTablet, GAP]);

  const loadMoreProducts = useCallback(async () => {
    if (isLoading || !hasMore) return;
    setIsLoading(true);
    try {
      const nextPage = page + 1;
      
      const cleanApiUrl = API_BASE.endsWith('/api') ? API_BASE : `${API_BASE.replace(/\/$/, '')}/api`;
      const res = await fetch(`${cleanApiUrl}/dec-products/${productSlug}/related?page=${nextPage}`);
      if (!res.ok) throw new Error("Failed to fetch related products");
      const json = await res.json();
      
      const paginator = json.success ? json.data : json;
      const newProducts = paginator.data || [];
      
      if (newProducts.length > 0) {
        setProducts(prev => {
          const existingIds = new Set(prev.map(p => p.id));
          const uniqueNew = newProducts.filter((p: DecRelatedProduct) => !existingIds.has(p.id));
          return [...prev, ...uniqueNew];
        });
        setPage(nextPage);
      }

      if (!paginator.next_page_url) {
        setHasMore(false);
      }
    } catch (error) {
      console.error(error);
    } finally {
      setIsLoading(false);
    }
  }, [isLoading, hasMore, page, productSlug]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || products.length === 0) return;

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);

    if (el.scrollWidth - (scrollLeft + el.clientWidth) < 500 && hasMore && !isLoading) {
      loadMoreProducts();
    }

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);
  }, [products.length, hasMore, isLoading, loadMoreProducts]);

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

  const scroll = (dir: 1 | -1) => {
    const el = trackRef.current;
    if (!el) return;
    const cardWidth = getCardWidth();
    const step = isMobile ? (cardWidth + GAP) * 2 : cardWidth + GAP;
    el.scrollBy({ left: dir * step, behavior: "smooth" });
  };

  if (!products || products.length === 0) return null;

  const desktopArrow = (isEnabled: boolean): React.CSSProperties => ({
    position: "absolute",
    top: "40%",
    transform: "translateY(-50%)",
    zIndex: 10,
    width: 44,
    height: 60,
    display: !isMobile ? "grid" : "none",
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

  const cardFlex = isMobile
    ? `0 0 calc((100% - ${GAP}px) / 2)`
    : isTablet
      ? `0 0 calc((100% - 2 * ${GAP}px) / 3)`
      : `0 0 calc((100% - 3 * ${GAP}px) / 4)`;

  const cardDim = isMobile
    ? `calc((100% - ${GAP}px) / 2)`
    : isTablet
      ? `calc((100% - 2 * ${GAP}px) / 3)`
      : `calc((100% - 3 * ${GAP}px) / 4)`;

  return (
    <section className="section" id="related-products" ref={sectionRef}>
      <div className="shell" style={{ width: "100%", boxSizing: "border-box" }}>
        <div className="section-head reveal" suppressHydrationWarning>
          <h2>Related Products</h2>
        </div>

        <div style={{ position: "relative", width: "100%" }}>
          {/* Desktop Previous Arrow */}
          {!isMobile && (
            <button
              onClick={() => scroll(-1)}
              aria-label="Previous related products"
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
              {products.map((product, index) => (
                <div
                  key={product.id}
                  suppressHydrationWarning
                  style={{
                    flex: cardFlex,
                    minWidth: cardDim,
                    maxWidth: cardDim,
                    width: cardDim,
                    scrollSnapAlign: "start",
                    scrollSnapStop: "always",
                    margin: 0,
                    boxSizing: "border-box",
                  }}
                >
                  <DecorativeCard
                    product={product}
                    order={index}
                    filterDelay={0}
                    isGlobalLightOn={true}
                  />
                </div>
              ))}
            </div>
          </div>

          {/* Desktop Next Arrow */}
          {!isMobile && (
            <button
              onClick={() => scroll(1)}
              aria-label="Next related products"
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
              aria-label="Previous related products"
              style={{
                position: "absolute",
                left: "-15px",
                top: "31%",
                transform: "translateY(-50%)",
                zIndex: 10,
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: "rgba(30, 30, 30, 0.6)",
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
              aria-label="Next related products"
              style={{
                position: "absolute",
                right: "-15px",
                top: "31%",
                transform: "translateY(-50%)",
                zIndex: 10,
                width: 32,
                height: 32,
                borderRadius: "50%",
                background: "rgba(30, 30, 30, 0.6)",
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
