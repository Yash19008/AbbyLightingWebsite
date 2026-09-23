"use client";

import React, { useEffect, useRef, useState } from "react";
import { DecRelatedProduct } from "@/types/decorative";
import DecorativeCard from "@/components/decorative/DecorativeCard";

interface RelatedFamilyProps {
  products: DecRelatedProduct[];
}

export default function RelatedFamily({
  products,
}: RelatedFamilyProps) {
  const [inView, setInView] = useState(false);
  const [isSettled, setIsSettled] = useState(false);
  const [familyIndex, setFamilyIndex] = useState(0);
  const [perView, setPerView] = useState(3);

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

  useEffect(() => {
    const handleResize = () => {
      setPerView(window.innerWidth <= 600 ? 2 : 3);
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const handleNext = () => {
    setFamilyIndex((prev) => Math.min(prev + 1, products.length - perView));
  };

  const handlePrev = () => {
    setFamilyIndex((prev) => Math.max(prev - 1, 0));
  };

  useEffect(() => {
    if (!trackRef.current) return;
    const cards = trackRef.current.querySelectorAll(".related-card-wrapper");
    if (!cards.length) return;
    const gap = 22; // From CSS
    const step = cards[0].getBoundingClientRect().width + gap;
    trackRef.current.style.transform = `translate3d(${-familyIndex * step}px, 0, 0)`;
  }, [familyIndex, products.length]);

  if (!products || products.length === 0) return null;

  return (
    <section
      className={`related-section ${inView ? "family-inview" : ""} ${
        isSettled ? "family-settled" : ""
      }`}
      ref={sectionRef}
    >
      <h2 className="product-reveal">Related Products</h2>
      <div className="related-carousel">
        <button
          className="related-arrow related-prev"
          aria-label="Previous related products"
          onClick={handlePrev}
          style={{ opacity: familyIndex === 0 ? 0.35 : 1 }}
          disabled={familyIndex === 0}
        >
          ‹
        </button>
        <div className="related-track" ref={trackRef}>
          {products.map((product, i) => (
            <div 
              key={product.id}
              className="product-reveal related-card-wrapper"
              style={{ 
                transitionDelay: isSettled ? "0ms" : `${i * 60}ms`,
                width: 'calc(33.333% - 14.66px)',
                flexShrink: 0
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
          style={{ opacity: familyIndex >= products.length - perView ? 0.35 : 1 }}
          disabled={familyIndex >= products.length - perView}
        >
          ›
        </button>
      </div>
    </section>
  );
}
