"use client";

import React, { useEffect, useRef, useState } from "react";
import { DecRelatedProduct } from "@/types/decorative";

interface RelatedFamilyProps {
  familyName: string;
  products: DecRelatedProduct[];
}

export default function RelatedFamily({
  familyName,
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
    const cards = trackRef.current.querySelectorAll("a");
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
      <h2 className="product-reveal">The {familyName} Family</h2>
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
            <a
              key={product.id}
              className="product-reveal"
              style={{ transitionDelay: isSettled ? "0ms" : `${i * 60}ms` }}
              href={`/product-detail/${product.slug}`}
            >
              <img 
                src={product.featured_image ? `${process.env.NEXT_PUBLIC_BACKEND_URL || "http://localhost:8000"}/storage/${product.featured_image}` : "/images/symphony-iv-figma-live/family-v.png"} 
                alt={product.name} 
              />
              <h3>{product.name}</h3>
              <span>{product.category?.name || "Product"}</span>
            </a>
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
