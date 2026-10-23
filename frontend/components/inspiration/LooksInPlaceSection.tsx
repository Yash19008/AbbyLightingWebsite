"use client";

import React, { useState, useEffect, useRef, useMemo } from "react";
import LookModal, { LookItem, ProductUsedItem } from "./LookModal";
import { getShowcaseCompositions } from "@/lib/api/compositions";

interface CompositionItem {
  id?: number | string;
  title: string;
  kicker?: string;
  category?: string;
  collection?: string;
  image?: string;
  products?: ProductUsedItem[];
}

interface PaginationInfo {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  has_more: boolean;
}

interface Props {
  compositions?: CompositionItem[];
  initialPagination?: PaginationInfo | null;
  initialCategories?: string[];
}

export default function LooksInPlaceSection({
  compositions: initialCompositions = [],
  initialPagination = null,
  initialCategories = [],
}: Props) {
  const [compositionsList, setCompositionsList] = useState<CompositionItem[]>(initialCompositions);
  const [selectedRoom, setSelectedRoom] = useState("all");
  const [isFilterOpen, setIsFilterOpen] = useState(false);
  const [page, setPage] = useState(initialPagination?.current_page || 1);
  const [hasMore, setHasMore] = useState<boolean>(
    initialPagination ? initialPagination.has_more : initialCompositions.length >= 8
  );
  const [isLoadingMore, setIsLoadingMore] = useState(false);
  const [isFiltering, setIsFiltering] = useState(false);
  const [activeLookIndex, setActiveLookIndex] = useState<number | null>(null);
  const filterRef = useRef<HTMLDivElement>(null);

  // Sync if initialCompositions change
  useEffect(() => {
    setCompositionsList(initialCompositions);
    if (initialPagination) {
      setHasMore(initialPagination.has_more);
      setPage(initialPagination.current_page);
    }
  }, [initialCompositions, initialPagination]);

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

    if (initialCategories && initialCategories.length > 0) {
      initialCategories.forEach((cat) => {
        if (cat) {
          catsMap.set(cat.toLowerCase(), cat);
        }
      });
    }

    if (compositionsList && compositionsList.length > 0) {
      compositionsList.forEach((c) => {
        if (c.category) {
          const id = c.category.toLowerCase();
          if (!catsMap.has(id)) {
            catsMap.set(id, c.category);
          }
        }
      });
    }

    return Array.from(catsMap.entries()).map(([id, label]) => ({ id, label }));
  }, [initialCategories, compositionsList]);

  const mappedLooks: LookItem[] = compositionsList.map((c: CompositionItem) => ({
    title: c.title || c.kicker || "",
    kicker: [c.kicker, c.category, c.collection].filter(Boolean).join(" · ") || c.kicker || c.category || "",
    room: c.category ? c.category.toLowerCase() : "all",
    image: c.image || "/images/placeholder.svg",
    productsUsed: c.products || [],
  }));

  const handleRoomSelect = async (roomId: string) => {
    setSelectedRoom(roomId);
    setIsFilterOpen(false);
    setIsFiltering(true);
    setPage(1);

    try {
      const res = await getShowcaseCompositions({
        page: 1,
        per_page: 8,
        category: roomId === "all" ? undefined : roomId,
      });

      if (res.success && Array.isArray(res.data)) {
        setCompositionsList(res.data);
        setHasMore(res.pagination?.has_more ?? false);
      }
    } catch (err) {
      console.error("Error filtering showcase compositions:", err);
    } finally {
      setIsFiltering(false);
    }
  };

  const handleViewMore = async () => {
    if (isLoadingMore || !hasMore) return;
    try {
      setIsLoadingMore(true);
      const nextPage = page + 1;
      const res = await getShowcaseCompositions({
        page: nextPage,
        per_page: 8,
        category: selectedRoom === "all" ? undefined : selectedRoom,
      });

      if (res.success && Array.isArray(res.data) && res.data.length > 0) {
        setCompositionsList((prev) => [...prev, ...res.data]);
        setPage(nextPage);
        setHasMore(res.pagination?.has_more ?? false);
      } else {
        setHasMore(false);
      }
    } catch (err) {
      console.error("Error loading more showcase compositions:", err);
    } finally {
      setIsLoadingMore(false);
    }
  };

  return (
    <section className="insp-section">
      <div className="insp-shell">
        <div className="looks-head">
          <div>
            <h2 className="section-title">Picture the Possibilities</h2>
            <p className="section-deck">
              See how our products can become part of your space and bring it to life.
            </p>
          </div>
          <div className="filter-wrap" ref={filterRef}>
            <button
              type="button"
              className={`filter-button ${isFilterOpen ? "is-open" : ""}`}
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

        <div className="looks-grid" style={{ opacity: isFiltering ? 0.6 : 1 }}>
          {mappedLooks.length > 0 ? (
            mappedLooks.map((item, index) => (
              <button
                key={`${item.title}-${index}`}
                type="button"
                className="look-card"
                onClick={() => setActiveLookIndex(index)}
                aria-label={`View details for ${item.title}`}
              >
                <img
                  src={item.image}
                  alt={item.title}
                  onError={(e) => {
                    (e.currentTarget as HTMLImageElement).src = "/images/placeholder.svg";
                  }}
                />
                <span className="look-copy">
                  <span>{item.title}</span>
                  <strong>{item.kicker}</strong>
                </span>
              </button>
            ))
          ) : (
            <div className="w-100 text-center py-5" style={{ gridColumn: "1 / -1", color: "#666" }}>
              <p>No looks found for this category.</p>
            </div>
          )}
        </div>

        {hasMore && (
          <button
            type="button"
            className="view-more looks-view-more"
            onClick={handleViewMore}
            disabled={isLoadingMore}
          >
            {isLoadingMore ? "Loading…" : "View more"}
          </button>
        )}
      </div>

      <LookModal
        isOpen={activeLookIndex !== null}
        look={activeLookIndex !== null ? mappedLooks[activeLookIndex] : null}
        onClose={() => setActiveLookIndex(null)}
        hasPrev={activeLookIndex !== null && activeLookIndex > 0}
        hasNext={activeLookIndex !== null && activeLookIndex < mappedLooks.length - 1}
        onPrev={() => {
          if (activeLookIndex !== null && activeLookIndex > 0) {
            setActiveLookIndex(activeLookIndex - 1);
          }
        }}
        onNext={() => {
          if (activeLookIndex !== null && activeLookIndex < mappedLooks.length - 1) {
            setActiveLookIndex(activeLookIndex + 1);
          }
        }}
      />
    </section>
  );
}
