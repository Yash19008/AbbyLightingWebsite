"use client";

import { useRef, useState, useEffect, useCallback } from "react";
import type { NewsItem } from "@/types/news-item";
import { useHorizontalCarousel } from "@/lib/hooks/useHorizontalCarousel";

interface NewsSectionProps {
  newsItems?: NewsItem[];
}

export default function NewsSection({ newsItems = [] }: NewsSectionProps) {
  const items = newsItems || [];
  
  const {
    trackRef,
    isMobile,
    isTablet,
    canScrollLeft,
    canScrollRight,
    scrollLeft,
    scrollRight,
    handleScroll,
    gap,
  } = useHorizontalCarousel({
    itemCount: items.length,
    gap: { mobile: 12, desktop: 18 },
    desktopCardsVisible: 3,
    tabletCardsVisible: 2.45,
    mobileCardsVisible: 1,
  });

  const showNav = items.length > (isMobile ? 1 : isTablet ? 2 : 3);

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

  // If no dynamic news items exist, hide the entire section (including heading)
  if (!items || items.length === 0) {
    return null;
  }

  const cardFlex = isMobile
    ? "0 0 100%"
    : isTablet
    ? `0 0 calc((100% - 2 * ${gap}px) / 2.45)`
    : `0 0 calc((100% - 2 * ${gap}px) / 3)`;

  const cardDim = isMobile
    ? "100%"
    : isTablet
    ? `calc((100% - 2 * ${gap}px) / 2.45)`
    : undefined;

  return (
    <section className="section" id="news">
      <div className="shell" style={{ width: "100%", boxSizing: "border-box" }}>
        <div className="section-head reveal" suppressHydrationWarning>
          <h2>In the news</h2>
        </div>

        <div style={{ position: "relative", width: "100%" }}>
          {/* Desktop Previous Arrow */}
          {!isMobile && (
            <button
              onClick={scrollLeft}
              aria-label="Previous news stories"
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
              onScroll={handleScroll}
              className="news"
              style={{
                display: "flex",
                gap: gap,
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
              {items.map((item, index) => (
                <a
                  key={item.id}
                  href={item.link || "#"}
                  target={item.link ? "_blank" : "_self"}
                  rel={item.link ? "noopener noreferrer" : undefined}
                  className="story"
                  style={{
                    flex: cardFlex,
                    minWidth: cardDim,
                    maxWidth: cardDim,
                    width: cardDim,
                    scrollSnapAlign: "start",
                    scrollSnapStop: "always",
                    margin: 0,
                    textDecoration: "none",
                    display: "flex",
                    flexDirection: "column",
                    position: "relative",
                    boxSizing: "border-box",
                    background: "#f7f7f7",
                    borderRadius: 6,
                    overflow: "hidden",
                    "--i": index,
                  } as React.CSSProperties}
                >
                  <div
                    className="photo"
                    style={{
                      position: "relative",
                      width: "100%",
                      aspectRatio: isMobile ? "16/10" : "4/3",
                      overflow: "hidden",
                      backgroundColor: "#e8e8e8",
                    }}
                  >
                    {item.image ? (
                      <img
                        src={item.image}
                        alt={item.title}
                        style={{
                          width: "100%",
                          height: "100%",
                          objectFit: "cover",
                          display: "block",
                          transition: "transform 0.4s ease",
                        }}
                      />
                    ) : (
                      <div style={{ width: "100%", height: "100%", background: "#e0e0e0" }} />
                    )}
                  </div>
                  <div
                    style={{
                      padding: isMobile ? "14px 16px 18px" : "20px 22px 24px",
                      background: "#f7f7f7",
                      display: "flex",
                      flexDirection: "column",
                      justifyContent: "flex-start",
                      flexGrow: 1,
                      boxSizing: "border-box",
                    }}
                  >
                    <p
                      style={{
                        margin: "0 0 8px 0",
                        color: "#777777",
                        fontFamily: "Inter, sans-serif",
                        fontSize: isMobile ? "11px" : "15px",
                        fontWeight: 300,
                        letterSpacing: "0.06em",
                        textTransform: "uppercase",
                      }}
                    >
                      {item.subtitle || "NEWS"}
                    </p>
                    <h3
                      style={{
                        margin: 0,
                        color: "#111111",
                        fontFamily: "Inter, sans-serif",
                        fontSize: isMobile ? "16px" : "22px",
                        fontWeight: 600,
                        lineHeight: 1.35,
                      }}
                    >
                      {item.title}
                    </h3>
                  </div>
                </a>
              ))}
            </div>
          </div>

          {/* Desktop Next Arrow */}
          {!isMobile && (
            <button
              onClick={scrollRight}
              aria-label="Next news stories"
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
              onClick={scrollLeft}
              aria-label="Previous news stories"
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
              onClick={scrollRight}
              aria-label="Next news stories"
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


