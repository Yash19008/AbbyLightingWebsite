"use client";

import React, { useState, useEffect } from "react";
import { createPortal } from "react-dom";
import type { ManufacturingSection } from "@/types/manufacturing-section";

interface ManufacturingSectionProps {
  data?: ManufacturingSection | null;
}

function renderTitle(title?: string | null, highlight?: string | null) {
  const cleanTitle = (title || "").trim();
  const cleanHighlight = (highlight || "").trim();

  if (!cleanHighlight) {
    return cleanTitle || "Built on Manufacturing Excellence";
  }

  if (!cleanTitle) {
    return <em>{cleanHighlight}</em>;
  }

  // Check if highlight is a substring of title (case-insensitive)
  const lowerTitle = cleanTitle.toLowerCase();
  const lowerHighlight = cleanHighlight.toLowerCase();
  const index = lowerTitle.indexOf(lowerHighlight);

  if (index !== -1) {
    const before = cleanTitle.slice(0, index);
    const matched = cleanTitle.slice(index, index + cleanHighlight.length);
    const after = cleanTitle.slice(index + cleanHighlight.length);
    return (
      <>
        {before}
        <em>{matched}</em>
        {after}
      </>
    );
  }

  // If highlight is separate from title (e.g. Title: "Built on", Highlight: "Manufacturing Excellence")
  return (
    <>
      {cleanTitle}{" "}
      <em>{cleanHighlight}</em>
    </>
  );
}

/**
 * Converts YouTube, Vimeo, or generic video URLs to a playable embed URL
 */
function getEmbedUrl(url?: string | null): string {
  if (!url) return "https://www.youtube-nocookie.com/embed/SPgaE-3jxEY?autoplay=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=1";
  const trimmed = url.trim();

  // YouTube URL matching (handles youtu.be, youtube.com/watch, embed, shorts, live, etc.)
  const ytMatch = trimmed.match(
    /(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:embed\/|shorts\/|live\/|v\/|watch\?(?:.*&)?v=))([\w-]{11})/
  );
  if (ytMatch && ytMatch[1]) {
    return `https://www.youtube-nocookie.com/embed/${ytMatch[1]}?autoplay=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=1`;
  }

  // Vimeo URL matching
  const vimeoMatch = trimmed.match(/vimeo\.com\/(?:video\/)?(\d+)/);
  if (vimeoMatch && vimeoMatch[1]) {
    return `https://player.vimeo.com/video/${vimeoMatch[1]}?autoplay=1`;
  }

  return trimmed;
}

export default function ManufacturingSection({ data }: ManufacturingSectionProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  // Handle escape key and lock body scroll when popup is open
  useEffect(() => {
    if (!isOpen) return;

    const prevOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        setIsOpen(false);
      }
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => {
      document.body.style.overflow = prevOverflow;
      window.removeEventListener("keydown", handleKeyDown);
    };
  }, [isOpen]);

  // If no dynamic manufacturing data exists or disabled in admin, hide section completely
  if (!data || data.is_active === false || data.is_active === "no" || data.is_active === "0") {
    return null;
  }

  const rawLink = data.button_link?.trim() || "";
  const embedUrl = getEmbedUrl(rawLink);
  const isDirectVideo = !!rawLink && /\.(mp4|webm|ogg|mov)(\?.*)?$/i.test(rawLink);

  const modalNode = isOpen ? (
    <div
      className="video-modal"
      role="dialog"
      aria-modal="true"
      aria-label="Abby Lighting manufacturing video"
      onMouseDown={(e) => {
        if (e.target === e.currentTarget) setIsOpen(false);
      }}
    >
      <div className="video-dialog">
        <button
          className="video-close"
          type="button"
          aria-label="Close video"
          onClick={() => setIsOpen(false)}
          style={{ display: "flex", alignItems: "center", justifyContent: "center" }}
        >
          <span aria-hidden="true" style={{ position: "relative", top: "-1px" }}>×</span>
        </button>
        <div className="video-frame" style={{ borderRadius: "6px", overflow: "hidden" }}>
          {isDirectVideo ? (
            <video
              src={rawLink}
              autoPlay
              controls
              playsInline
              style={{
                width: "100%",
                height: "100%",
                position: "absolute",
                inset: 0,
                objectFit: "contain",
                background: "#000",
              }}
            />
          ) : (
            <iframe
              referrerPolicy="strict-origin-when-cross-origin"
              src={embedUrl}
              title={data.title || "Abby Lighting manufacturing"}
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen"
              allowFullScreen
            />
          )}
        </div>
      </div>
    </div>
  ) : null;

  return (
    <>
      <section className="manufacturing">
        <img
          className="manufacturing-media section-parallax-media"
          src={data.background_image_url || "/images/figma-update/manufacturing.png"}
          alt="Manufacturing Excellence"
        />
        <div className="manufacturing-scrim" aria-hidden="true" />
        <div className="copy">
          <h2>
            {renderTitle(data.title, data.title_highlight)}
          </h2>
          {data.description && <p>{data.description}</p>}
          {data.button_text && (
            <button
              type="button"
              className="btn"
              onClick={() => setIsOpen(true)}
              aria-haspopup="dialog"
            >
              {data.button_text}
            </button>
          )}
        </div>
      </section>

      {mounted && modalNode && createPortal(modalNode, document.body)}
    </>
  );
}
