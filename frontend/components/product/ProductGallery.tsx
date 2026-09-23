"use client";

import React, { useRef, useState, useEffect } from "react";
import Image from "next/image";
import Lightbox from "./Lightbox";

interface ProductGalleryProps {
  galleryImages: string[];
  stageImage: string; // Not strictly needed anymore since galleryImages[0] is deduplicated stageImage, but kept for prop compatibility
}

export default function ProductGallery({
  galleryImages,
}: ProductGalleryProps) {
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);
  const [activeIndex, setActiveIndex] = useState(0); 

  const trackRef = useRef<HTMLDivElement>(null);
  const carouselRef = useRef<HTMLDivElement>(null);

  const firstGalleryImage = galleryImages[0];

  // When galleryImages changes significantly (e.g. colour variant changed), reset to first image
  useEffect(() => {
    setActiveIndex(0);
  }, [firstGalleryImage]);

  // Scroll active item into view smoothly
  useEffect(() => {
    if (!trackRef.current) return;
    const activeBtn = trackRef.current.children[activeIndex] as HTMLElement;
    if (activeBtn) {
      activeBtn.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "center" });
    }
  }, [activeIndex]);

  const handleNext = () => {
    if (galleryImages.length === 0) return;
    setActiveIndex(prev => (prev + 1) % galleryImages.length);
  };

  const handlePrev = () => {
    if (galleryImages.length === 0) return;
    setActiveIndex(prev => (prev - 1 + galleryImages.length) % galleryImages.length);
  };

  const currentStageImage = galleryImages[activeIndex] || "";

  return (
    <div className="product-gallery product-reveal">
      <div className="thumb-carousel" ref={carouselRef}>
        <button
          className="gallery-arrow gallery-prev"
          aria-label="Previous product images"
          onClick={handlePrev}
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M15 18l-6-6 6-6" />
          </svg>
        </button>
        
        <div className="product-thumbs" ref={trackRef}>
          {galleryImages.map((src, idx) => (
            <button
              key={idx}
              className={`gallery-thumb-wrapper ${idx === activeIndex ? "active" : ""}`}
              onClick={() => setActiveIndex(idx)}
            >
              <Image 
                src={src} 
                alt={`Thumbnail ${idx + 1}`} 
                fill
                sizes="84px"
                className="gallery-thumb-image"
              />
            </button>
          ))}
        </div>

        <button
          className="gallery-arrow gallery-next"
          aria-label="Next product images"
          onClick={handleNext}
        >
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 18l6-6-6-6" />
          </svg>
        </button>
      </div>
      
        <button
          className="product-stage symphony-stage gallery-stage-wrapper"
          onClick={() => setIsLightboxOpen(true)}
        >
          <Image
            src={currentStageImage}
            alt="Product stage"
            fill
            priority
            sizes="(max-width: 960px) 100vw, 50vw"
            className="gallery-stage-image"
          />
          <span className="zoom-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </span>
      </button>

      <Lightbox
        isOpen={isLightboxOpen}
        images={galleryImages}
        currentIndex={activeIndex}
        onClose={() => setIsLightboxOpen(false)}
        onNext={handleNext}
        onPrev={handlePrev}
      />
    </div>
  );
}
