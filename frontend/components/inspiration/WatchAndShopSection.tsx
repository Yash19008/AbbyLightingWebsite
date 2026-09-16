"use client";

import React, { useState, useRef, useEffect, useCallback } from "react";
import { getWatchAndShops } from "@/lib/api/watch-and-shop";
import type { WatchAndShopItem } from "@/types/watch-and-shop";

function getEmbedUrl(reel: WatchAndShopItem, autoplay: boolean = true): string | null {
  if (!reel || !reel.video_url) return null;

  if (reel.video_type === "youtube") {
    const match = reel.video_url.match(/(?:youtu\.be\/|youtube\.com\/(?:shorts\/|embed\/|watch\?v=))([\w-]{11})/);
    if (match && match[1]) {
      return `https://www.youtube.com/embed/${match[1]}?${autoplay ? "autoplay=1&" : ""}rel=0&modestbranding=1&playsinline=1`;
    }
    return reel.video_url;
  }

  if (reel.video_type === "instagram") {
    const match = reel.video_url.match(/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/);
    if (match && match[1]) {
      return `https://www.instagram.com/reel/${match[1]}/embed`;
    }
    return reel.video_url;
  }

  return null;
}

export default function WatchAndShopSection() {
  const trackRef = useRef<HTMLDivElement>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(true);
  const [reels, setReels] = useState<WatchAndShopItem[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [playingReelId, setPlayingReelId] = useState<number | string | null>(null);

  useEffect(() => {
    async function loadReels() {
      try {
        const data = await getWatchAndShops();
        if (data && data.length > 0) {
          setReels(data);
        } else {
          setReels([]);
        }
      } catch (err) {
        console.warn("Failed to load reels:", err);
        setReels([]);
      } finally {
        setIsLoading(false);
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
    const checkMobile = () => setIsMobile(window.innerWidth <= 768);
    checkMobile();
    window.addEventListener("resize", checkMobile);

    checkScroll();
    const track = trackRef.current;
    if (track) {
      track.addEventListener("scroll", checkScroll, { passive: true });
      window.addEventListener("resize", checkScroll);
      return () => {
        window.removeEventListener("resize", checkMobile);
        track.removeEventListener("scroll", checkScroll);
        window.removeEventListener("resize", checkScroll);
      };
    }
    return () => window.removeEventListener("resize", checkMobile);
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

  const showNav = reels.length > (isMobile ? 1 : 4);

  const desktopArrow = (isEnabled: boolean): React.CSSProperties => ({
    position: "absolute",
    top: "50%",
    transform: "translateY(-50%)",
    zIndex: 10,
    width: 44,
    height: 60,
    display: !isMobile && showNav ? "grid" : "none",
    placeItems: "center",
    background: "transparent",
    border: "none",
    cursor: isEnabled ? "pointer" : "default",
    color: "#111111",
    opacity: isEnabled ? 1 : 0.25,
    pointerEvents: isEnabled ? "auto" : "none",
    transition: "opacity 0.25s ease",
    padding: 0,
    lineHeight: 1,
  });

  // If loading or no dynamic reels exist, hide section completely
  if (isLoading || !reels || reels.length === 0) {
    return null;
  }

  return (
    <section className="reels-section">
      <div className="insp-shell">
        <h2 className="section-title">Watch &amp; shop</h2>
        <p className="section-deck">
          Play our latest Instagram reels right here — tap a reel to watch without leaving the site.
        </p>
        <div className="reel-track-wrap" style={{ position: "relative", width: "100%" }}>
          {/* Desktop Left Arrow */}
          {!isMobile && (
            <button
              type="button"
              onClick={() => handleSlide("left")}
              aria-label="Previous reel"
              style={{ ...desktopArrow(canScrollLeft), left: -50 }}
              disabled={!canScrollLeft}
            >
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M15 18l-6-6 6-6"></path>
              </svg>
            </button>
          )}

          {/* Mobile Floating Previous Arrow */}
          {isMobile && canScrollLeft && (
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
            {reels.map((reel, idx) => {
              const reelKey = reel.id || idx;
              const isPlaying = playingReelId === reelKey;
              const hasThumbnail = Boolean(reel.thumbnail && reel.thumbnail.trim());
              const embed = getEmbedUrl(reel, isPlaying);
              const isDirectVideo =
                reel.video_type === "upload" ||
                reel.video_type === "url" ||
                (!embed && Boolean(reel.video_url));

              return (
                <div
                  key={reelKey}
                  className={`reel ${isPlaying ? "is-playing" : ""}`}
                  onClick={() => {
                    if (!isPlaying) {
                      setPlayingReelId(reelKey);
                    }
                  }}
                  role={isPlaying ? undefined : "button"}
                  tabIndex={isPlaying ? undefined : 0}
                  onKeyDown={(e) => {
                    if (!isPlaying && (e.key === "Enter" || e.key === " ")) {
                      e.preventDefault();
                      setPlayingReelId(reelKey);
                    }
                  }}
                >
                  {hasThumbnail && !isPlaying ? (
                    <>
                      <img
                        src={reel.thumbnail}
                        alt={reel.title || `Abby Lighting reel ${idx + 1}`}
                      />
                      <span className="play">▶</span>
                    </>
                  ) : isDirectVideo ? (
                    <>
                      <video
                        src={reel.video_url}
                        poster={hasThumbnail ? reel.thumbnail : undefined}
                        controls={isPlaying}
                        autoPlay={isPlaying}
                        playsInline
                        loop
                        preload="metadata"
                        className="reel-inline-video"
                      />
                      {!isPlaying && <span className="play">▶</span>}
                    </>
                  ) : embed ? (
                    <iframe
                      src={embed}
                      title={reel.title || "Watch & Shop Reel"}
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                      allowFullScreen
                      className="reel-inline-iframe"
                    />
                  ) : hasThumbnail ? (
                    <>
                      <img
                        src={reel.thumbnail}
                        alt={reel.title || `Abby Lighting reel ${idx + 1}`}
                      />
                      <span className="play">▶</span>
                    </>
                  ) : (
                    <div className="reel-placeholder">
                      <span className="play">▶</span>
                    </div>
                  )}
                </div>
              );
            })}
          </div>

          {/* Desktop Right Arrow */}
          {!isMobile && (
            <button
              type="button"
              onClick={() => handleSlide("right")}
              aria-label="Next reel"
              style={{ ...desktopArrow(canScrollRight), right: -50 }}
              disabled={!canScrollRight}
            >
              <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                <path d="M9 18l6-6-6-6"></path>
              </svg>
            </button>
          )}

          {/* Mobile Floating Next Arrow */}
          {isMobile && canScrollRight && (
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
    </section>
  );
}
