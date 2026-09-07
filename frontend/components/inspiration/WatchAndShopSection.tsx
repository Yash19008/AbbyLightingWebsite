"use client";

import React, { useState, useRef, useEffect, useCallback } from "react";
import ReelModal from "./ReelModal";
import { getWatchAndShops } from "@/lib/api/watch-and-shop";
import type { WatchAndShopItem } from "@/types/watch-and-shop";

const FALLBACK_REELS: WatchAndShopItem[] = [
  {
    id: 1,
    title: "Abby Lighting Studio Reel 1",
    thumbnail: "https://www.figma.com/api/mcp/asset/1584a78c-a8d3-4b65-88ed-735f41b1ee02.png",
    video_type: "url",
    video_url: "https://www.figma.com/api/mcp/asset/1584a78c-a8d3-4b65-88ed-735f41b1ee02.png",
    product_name: "Neoma Downlight",
    product_link: "/decorative-products",
    display_order: 1,
  },
  {
    id: 2,
    title: "Abby Lighting Studio Reel 2",
    thumbnail: "https://www.figma.com/api/mcp/asset/4d2b9404-c6f7-4695-b1e4-1d4762b138d6.png",
    video_type: "url",
    video_url: "https://www.figma.com/api/mcp/asset/4d2b9404-c6f7-4695-b1e4-1d4762b138d6.png",
    product_name: "Architectural Spot",
    product_link: "/decorative-products",
    display_order: 2,
  },
  {
    id: 3,
    title: "Abby Lighting Studio Reel 3",
    thumbnail: "https://www.figma.com/api/mcp/asset/38dd565e-cac0-4d41-a059-200a3bb71b5e.png",
    video_type: "url",
    video_url: "https://www.figma.com/api/mcp/asset/38dd565e-cac0-4d41-a059-200a3bb71b5e.png",
    product_name: "Linear Track System",
    product_link: "/decorative-products",
    display_order: 3,
  },
  {
    id: 4,
    title: "Abby Lighting Studio Reel 4",
    thumbnail: "https://www.figma.com/api/mcp/asset/323d7393-323c-43a9-a513-718188307d7e.png",
    video_type: "url",
    video_url: "https://www.figma.com/api/mcp/asset/323d7393-323c-43a9-a513-718188307d7e.png",
    product_name: "Atmospheric Pendant",
    product_link: "/decorative-products",
    display_order: 4,
  },
  {
    id: 5,
    title: "Abby Lighting Studio Reel 5",
    thumbnail: "/images/reference/product-neoma.png",
    video_type: "url",
    video_url: "/images/reference/product-neoma.png",
    product_name: "Neoma Series",
    product_link: "/decorative-products",
    display_order: 5,
  },
  {
    id: 6,
    title: "Abby Lighting Studio Reel 6",
    thumbnail: "/images/figma-update/manufacturing.png",
    video_type: "url",
    video_url: "/images/figma-update/manufacturing.png",
    product_name: "Craftsmanship & Engineering",
    product_link: "/decorative-products",
    display_order: 6,
  },
  {
    id: 7,
    title: "Abby Lighting Studio Reel 7",
    thumbnail: "/images/reference/project-atlas.png",
    video_type: "url",
    video_url: "/images/reference/project-atlas.png",
    product_name: "Project Atlas Installation",
    product_link: "/decorative-products",
    display_order: 7,
  },
  {
    id: 8,
    title: "Abby Lighting Studio Reel 8",
    thumbnail: "/images/figma-update/catalogue.png",
    video_type: "url",
    video_url: "/images/figma-update/catalogue.png",
    product_name: "Catalogue Showcase",
    product_link: "/decorative-products",
    display_order: 8,
  },
];

export default function WatchAndShopSection() {
  const trackRef = useRef<HTMLDivElement>(null);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);
  const [reels, setReels] = useState<WatchAndShopItem[]>(FALLBACK_REELS);
  const [selectedReel, setSelectedReel] = useState<WatchAndShopItem | null>(null);

  useEffect(() => {
    async function loadReels() {
      try {
        const data = await getWatchAndShops();
        if (data && data.length > 0) {
          setReels(data);
        }
      } catch (err) {
        console.warn("Using fallback reels:", err);
      }
    }
    loadReels();
  }, []);

  const checkScroll = useCallback(() => {
    if (!trackRef.current) return;
    const { scrollLeft, scrollWidth, clientWidth } = trackRef.current;
    setCanScrollLeft(scrollLeft > 5);
    setCanScrollRight(scrollLeft < scrollWidth - clientWidth - 5);
  }, []);

  useEffect(() => {
    checkScroll();
    const track = trackRef.current;
    if (track) {
      track.addEventListener("scroll", checkScroll, { passive: true });
      window.addEventListener("resize", checkScroll);
      return () => {
        track.removeEventListener("scroll", checkScroll);
        window.removeEventListener("resize", checkScroll);
      };
    }
  }, [checkScroll, reels]);

  const handleSlide = (direction: "left" | "right") => {
    if (!trackRef.current) return;
    const cardEl = trackRef.current.firstElementChild as HTMLElement;
    const scrollAmount = cardEl ? cardEl.getBoundingClientRect().width + 14 : trackRef.current.clientWidth * 0.8;
    trackRef.current.scrollBy({
      left: direction === "left" ? -scrollAmount : scrollAmount,
      behavior: "smooth",
    });
  };

  return (
    <section className="reels-section">
      <div className="insp-shell">
        <h2 className="section-title">Watch &amp; shop</h2>
        <p className="section-deck">
          Play our latest Instagram reels right here — tap a reel to watch without leaving the site.
        </p>
        <div className="reel-track-wrap">
          {/* Desktop Left Arrow */}
          <button
            type="button"
            className="slide-arrow prev"
            aria-label="Previous reel"
            onClick={() => handleSlide("left")}
            disabled={!canScrollLeft}
          >
            ‹
          </button>

          {/* Mobile Floating Previous Arrow */}
          {canScrollLeft && (
            <button
              type="button"
              className="reel-mobile-arrow prev"
              aria-label="Previous reel"
              onClick={() => handleSlide("left")}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M15 18l-6-6 6-6" />
              </svg>
            </button>
          )}

          <div className="reel-track" ref={trackRef}>
            {reels.map((reel, idx) => (
              <button
                key={reel.id || idx}
                type="button"
                className="reel"
                onClick={() => setSelectedReel(reel)}
              >
                <img
                  src={reel.thumbnail}
                  alt={reel.title || `Abby Lighting reel ${idx + 1}`}
                  onError={(e) => {
                    (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
                  }}
                />
                <span className="play">▶</span>
              </button>
            ))}
          </div>

          {/* Desktop Right Arrow */}
          <button
            type="button"
            className="slide-arrow next"
            aria-label="Next reel"
            onClick={() => handleSlide("right")}
            disabled={!canScrollRight}
          >
            ›
          </button>

          {/* Mobile Floating Next Arrow */}
          {canScrollRight && (
            <button
              type="button"
              className="reel-mobile-arrow next"
              aria-label="Next reel"
              onClick={() => handleSlide("right")}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M9 18l6-6-6-6" />
              </svg>
            </button>
          )}
        </div>
      </div>

      <ReelModal
        isOpen={Boolean(selectedReel)}
        reel={selectedReel}
        onClose={() => setSelectedReel(null)}
      />
    </section>
  );
}
