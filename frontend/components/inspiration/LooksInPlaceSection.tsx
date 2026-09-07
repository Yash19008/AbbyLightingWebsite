"use client";

import React, { useState, useEffect, useRef } from "react";
import LookModal, { LookItem } from "./LookModal";

const INITIAL_LOOKS: LookItem[] = [
  {
    title: "Stone & light in a hotel arrival",
    kicker: "Lookbook · Hospitality",
    room: "living",
    image: "/images/figma-update/hero-decorative.png",
  },
  {
    title: "The corner cafe",
    kicker: "Dining · Quarry",
    room: "dining",
    image: "/images/reference/news-elle.png",
  },
  {
    title: "Pastel calm",
    kicker: "Living · Symphony",
    room: "living",
    image: "/images/reference/news-architectural.png",
  },
  {
    title: "Geometry & glow",
    kicker: "Workspace · Quarry",
    room: "workspace",
    image: "/images/figma-update/manufacturing.png",
  },
  {
    title: "Earthen warmth",
    kicker: "Bedroom · Symphony",
    room: "bedroom",
    image: "/images/reference/news-business.png",
  },
  {
    title: "A working kitchen",
    kicker: "Dining · Quarry",
    room: "dining",
    image: "/images/figma-update/catalogue.png",
  },
  {
    title: "Vivid accents",
    kicker: "Living · Symphony",
    room: "living",
    image: "/images/reference/product-neoma.png",
  },
  {
    title: "Calm office",
    kicker: "Workspace · Quarry",
    room: "workspace",
    image: "/images/figma-update/hero-architecture-desktop.png",
  },
  {
    title: "A quiet arrival",
    kicker: "Hospitality",
    room: "living",
    image: "/images/reference/project-atlas.png",
  },
  {
    title: "Warm conversations",
    kicker: "Dining",
    room: "dining",
    image: "/images/reference/news-elle.png",
  },
  {
    title: "Focused light",
    kicker: "Workspace",
    room: "workspace",
    image: "/images/reference/news-business.png",
  },
  {
    title: "Layered living",
    kicker: "Living",
    room: "living",
    image: "/images/figma-update/catalogue.png",
  },
  {
    title: "A softer bedroom",
    kicker: "Bedroom",
    room: "bedroom",
    image: "/images/world-decorative-on.png",
  },
  {
    title: "Evening dining",
    kicker: "Dining",
    room: "dining",
    image: "/images/figma-update/hero-decorative.png",
  },
  {
    title: "Sculptural accents",
    kicker: "Living",
    room: "living",
    image: "/images/reference/product-neoma.png",
  },
  {
    title: "Light for focus",
    kicker: "Workspace",
    room: "workspace",
    image: "/images/figma-update/hero-architecture-desktop.png",
  },
];

const ROOMS = [
  { id: "all", label: "All" },
  { id: "living", label: "Living" },
  { id: "dining", label: "Dining" },
  { id: "bedroom", label: "Bedroom" },
  { id: "workspace", label: "Workspace" },
];

export default function LooksInPlaceSection() {
  const [selectedRoom, setSelectedRoom] = useState("all");
  const [isFilterOpen, setIsFilterOpen] = useState(false);
  const [visibleCount, setVisibleCount] = useState(8);
  const [activeLook, setActiveLook] = useState<LookItem | null>(null);
  const filterRef = useRef<HTMLDivElement>(null);

  // Close filter dropdown on outside click
  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (filterRef.current && !filterRef.current.contains(event.target as Node)) {
        setIsFilterOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const filteredLooks = INITIAL_LOOKS.filter(
    (item) => selectedRoom === "all" || item.room === selectedRoom
  );

  const displayedLooks = filteredLooks.slice(0, visibleCount);

  const handleRoomSelect = (roomId: string) => {
    setSelectedRoom(roomId);
    setIsFilterOpen(false);
    setVisibleCount(8);
  };

  const handleViewMore = () => {
    setVisibleCount((prev) => Math.min(prev + 8, filteredLooks.length));
  };

  return (
    <section className="insp-section">
      <div className="insp-shell">
        <div className="looks-head">
          <div>
            <h2 className="section-title">See light in place</h2>
            <p className="section-deck">
              Real spaces, styled with Abby — filter by room and find the pieces to recreate the look.
            </p>
          </div>
          <div className="filter-wrap" ref={filterRef}>
            <button
              type="button"
              className={`filter-button ${isFilterOpen ? 'is-open' : ''}`}
              onClick={() => setIsFilterOpen(!isFilterOpen)}
              aria-expanded={isFilterOpen}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
              </svg>
              <span>FILTER BY</span>
            </button>
            {isFilterOpen && (
              <div className="filter-menu">
                {ROOMS.map((room) => (
                  <button
                    key={room.id}
                    type="button"
                    className={selectedRoom === room.id ? "is-active" : ""}
                    onClick={() => handleRoomSelect(room.id)}
                  >
                    {room.label}
                  </button>
                ))}
              </div>
            )}
          </div>
        </div>

        <div className="looks-grid">
          {displayedLooks.map((item, idx) => (
            <button
              key={`${item.title}-${idx}`}
              type="button"
              className="look-card"
              onClick={() => setActiveLook(item)}
            >
              <img
                src={item.image}
                alt={item.title}
                onError={(e) => {
                  (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
                }}
              />
              <span className="look-copy">
                <strong>{item.title}</strong>
                <span>{item.kicker}</span>
              </span>
            </button>
          ))}
        </div>

        {visibleCount < filteredLooks.length && (
          <button
            type="button"
            className="view-more looks-view-more"
            onClick={handleViewMore}
          >
            View more
          </button>
        )}
      </div>

      <LookModal
        isOpen={Boolean(activeLook)}
        look={activeLook}
        onClose={() => setActiveLook(null)}
      />
    </section>
  );
}
