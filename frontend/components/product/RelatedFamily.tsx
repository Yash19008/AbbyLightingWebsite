"use client";

import React, { useEffect, useRef, useState } from "react";
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
  const [hasMore, setHasMore] = useState(initialProducts.length === 10);
  const [isLoading, setIsLoading] = useState(false);
  
  const [inView, setInView] = useState(false);
  const [isSettled, setIsSettled] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);

  const sectionRef = useRef<HTMLElement>(null);
  const trackRef = useRef<HTMLDivElement>(null);
  const settledTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

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

  const updateScrollState = () => {
    if (!trackRef.current) return;
    const { scrollLeft, scrollWidth, clientWidth } = trackRef.current;
    
    // Check if we need to load more (when within 500px of the end)
    if (scrollWidth - (scrollLeft + clientWidth) < 500 && hasMore && !isLoading) {
      loadMoreProducts();
    }
    
    setCanScrollLeft(scrollLeft > 5);
    setCanScrollRight(scrollLeft < scrollWidth - clientWidth - 5);
  };

  const loadMoreProducts = async () => {
    if (isLoading || !hasMore) return;
    setIsLoading(true);
    try {
      const nextPage = page + 1;
      const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000'}/api/dec-products/${productSlug}/related?page=${nextPage}`);
      if (!res.ok) throw new Error("Failed to fetch related products");
      const data = await res.json();
      
      const newProducts = data.data || [];
      if (newProducts.length > 0) {
        // filter out duplicates just in case
        setProducts(prev => {
          const existingIds = new Set(prev.map(p => p.id));
          const uniqueNew = newProducts.filter((p: DecRelatedProduct) => !existingIds.has(p.id));
          return [...prev, ...uniqueNew];
        });
        setPage(nextPage);
      }
      
      if (!data.next_page_url) {
        setHasMore(false);
      }
    } catch (error) {
      console.error(error);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    updateScrollState();
    window.addEventListener("resize", updateScrollState);
    const el = trackRef.current;
    if (el) {
      el.addEventListener("scroll", updateScrollState, { passive: true });
    }
    return () => {
      window.removeEventListener("resize", updateScrollState);
      if (el) el.removeEventListener("scroll", updateScrollState);
    };
  }, [products]);

  const handleNext = () => {
    const el = trackRef.current;
    if (!el) return;
    const cards = el.querySelectorAll(".related-card-wrapper");
    if (!cards.length) return;
    const step = cards[0].getBoundingClientRect().width + 22; // width + gap
    el.scrollBy({ left: step, behavior: "smooth" });
  };

  const handlePrev = () => {
    const el = trackRef.current;
    if (!el) return;
    const cards = el.querySelectorAll(".related-card-wrapper");
    if (!cards.length) return;
    const step = cards[0].getBoundingClientRect().width + 22; // width + gap
    el.scrollBy({ left: -step, behavior: "smooth" });
  };

  if (!products || products.length === 0) return null;

  return (
    <section
      className={`related-section ${inView ? "family-inview" : ""} ${
        isSettled ? "family-settled" : ""
      }`}
      ref={sectionRef}
    >
      <h2 className="product-reveal" suppressHydrationWarning>Related Products</h2>
      <div className="related-carousel">
        <button
          className="related-arrow related-prev"
          aria-label="Previous related products"
          onClick={handlePrev}
          style={{ opacity: !canScrollLeft ? 0.35 : 1 }}
          disabled={!canScrollLeft}
        >
          ‹
        </button>
        <div 
          className="related-track" 
          ref={trackRef}
          style={{
            display: "flex",
            gap: "22px",
            overflowX: "auto",
            scrollSnapType: "x mandatory",
            scrollbarWidth: "none",
            msOverflowStyle: "none",
            transition: "none",
            transform: "none"
          }}
        >
          {products.map((product, i) => (
            <div 
              key={product.id}
              className="product-reveal related-card-wrapper"
              suppressHydrationWarning
              style={{ 
                transitionDelay: isSettled ? "0ms" : `${i * 60}ms`,
                flex: "0 0 calc(33.333% - 14.66px)",
                scrollSnapAlign: "start",
                scrollSnapStop: "always",
              }}
            >
              <DecorativeCard 
                product={product} 
                order={i} 
                filterDelay={0} 
                isGlobalLightOn={true} 
              />
            </div>
          ))}
        </div>
        <button
          className="related-arrow related-next"
          aria-label="Next related products"
          onClick={handleNext}
          style={{ opacity: !canScrollRight ? 0.35 : 1 }}
          disabled={!canScrollRight}
        >
          ›
        </button>
      </div>
    </section>
  );
}
