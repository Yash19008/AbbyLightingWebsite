"use client";

import React, { useState, useEffect, useRef, useMemo } from "react";
import LookModal, { LookItem } from "./LookModal";



interface CompositionItem {
  title: string;
  kicker?: string;
  category?: string;
  image?: string;
  products?: any[];
}

interface Props {
  compositions?: CompositionItem[];
}

export default function LooksInPlaceSection({ compositions = [] }: Props) {
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

  const categories = useMemo(() => {
    const catsMap = new Map<string, string>();
    catsMap.set("all", "All");

    if (compositions && compositions.length > 0) {
      compositions.forEach(c => {
        if (c.category) {
          const id = c.category.toLowerCase();
          if (!catsMap.has(id)) {
            catsMap.set(id, c.category);
          }
        }
      });
    }

    return Array.from(catsMap.entries()).map(([id, label]) => ({ id, label }));
  }, [compositions]);

  const mappedLooks: LookItem[] = compositions && compositions.length > 0
    ? compositions.map((c: CompositionItem) => ({
      title: c.title,
      kicker: c.kicker || c.category || "",
      room: c.category ? c.category.toLowerCase() : "all",
      image: c.image || "/images/reference/project-atlas.png",
      productsUsed: c.products || []
    }))
    : [];

  const filteredLooks = mappedLooks.filter(
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
                <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z" />
              </svg>
              <span>FILTER BY</span>
            </button>
            {isFilterOpen && (
              <div className="filter-menu">
                {categories.map((room) => (
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
          {filteredLooks.length > 0 ? (
            displayedLooks.map((item, index) => (
              <button
                key={`${item.title}-${index}`}
                type="button"
                className="look-card"
                onClick={() => setActiveLook(item)}
                aria-label={`View details for ${item.title}`}
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
            ))
          ) : (
            <div className="w-100 text-center py-5" style={{ gridColumn: "1 / -1", color: "#666" }}>
              <p>No looks found for this category.</p>
            </div>
          )}
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
