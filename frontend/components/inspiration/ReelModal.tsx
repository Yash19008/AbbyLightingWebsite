"use client";

import React, { useEffect, useMemo } from "react";
import type { WatchAndShopItem } from "@/types/watch-and-shop";

interface ReelModalProps {
  isOpen: boolean;
  reel: WatchAndShopItem | null;
  onClose: () => void;
}

export default function ReelModal({ isOpen, reel, onClose }: ReelModalProps) {
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && isOpen) {
        onClose();
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, onClose]);

  const embedUrl = useMemo(() => {
    if (!reel || !reel.video_url) return null;

    if (reel.video_type === "youtube") {
      const match = reel.video_url.match(/(?:youtu\.be\/|youtube\.com\/(?:shorts\/|embed\/|watch\?v=))([\w-]{11})/);
      if (match && match[1]) {
        return `https://www.youtube.com/embed/${match[1]}?autoplay=1&rel=0&modestbranding=1`;
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
  }, [reel]);

  if (!isOpen || !reel) return null;

  return (
    <div
      className="reel-modal"
      role="dialog"
      aria-modal="true"
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div className="modal-card">
        <button className="modal-close" onClick={onClose} aria-label="Close reel">
          ×
        </button>

        <div className="reel-media-wrap">
          {reel.video_type === "upload" || reel.video_type === "url" ? (
            <video
              src={reel.video_url}
              poster={reel.thumbnail}
              controls
              autoPlay
              playsInline
              loop
              className="reel-video-player"
            />
          ) : embedUrl ? (
            <iframe
              src={embedUrl}
              title={reel.title || "Watch & Shop Reel"}
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowFullScreen
              className="reel-iframe-player"
            />
          ) : (
            <div
              className="reel-fallback"
              style={{
                backgroundImage: `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url(${reel.thumbnail})`,
              }}
            >
              <span>{reel.title || "Reel Preview"}</span>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
