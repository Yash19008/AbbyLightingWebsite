'use client';

import React, { useState, useEffect } from 'react';
import CustomSortDropdown from './CustomSortDropdown';
import { FilterState } from './DecorativeFilterModal';

interface DecorativeStickyWidgetProps {
  activeCategory: string;
  setActiveCategory: (cat: string) => void;
  categories: string[];
  activeFilters: FilterState;
  setIsFilterModalOpen: (isOpen: boolean) => void;
  sortBy: string;
  setSortBy: (val: string) => void;
  isLightOn: boolean;
  setIsLightOn: (on: boolean) => void;
  targetRef?: React.RefObject<HTMLElement | null>;
}

export default function DecorativeStickyWidget({
  activeCategory,
  setActiveCategory,
  categories,
  activeFilters,
  setIsFilterModalOpen,
  sortBy,
  setSortBy,
  isLightOn,
  setIsLightOn,
  targetRef,
}: DecorativeStickyWidgetProps) {
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      if (targetRef && targetRef.current) {
        const rect = targetRef.current.getBoundingClientRect();
        // Show sticky widget when the in-page toolbar has scrolled above the header (e.g. rect.bottom < 80)
        setIsVisible(rect.bottom < 80);
      } else {
        // Fallback: show after scrolling down 380px (past hero)
        setIsVisible(window.scrollY > 380);
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    return () => window.removeEventListener('scroll', handleScroll);
  }, [targetRef]);

  const hasActiveFilters =
    activeFilters.category.length > 0 || activeFilters.collection.length > 0;

  return (
    <div
      className={`decorative-sticky-widget ${isVisible ? 'is-visible' : ''}`}
      role="toolbar"
      aria-label="Sticky product tools"
    >
      <div className="decorative-sticky-widget-inner">
        {/* Category Tabs */}
        {categories && categories.length > 0 && (
          <div className="decorative-tabs decorative-sticky-tabs" role="tablist">
            {categories.map((cat) => (
              <button
                key={cat}
                type="button"
                role="tab"
                aria-selected={activeCategory === cat}
                className={activeCategory === cat ? 'active' : ''}
                onClick={() => setActiveCategory(cat)}
              >
                {cat}
              </button>
            ))}
          </div>
        )}

        <div className="decorative-sticky-right-tools">
          {/* Filter By Button */}
          <button
            type="button"
            className="decorative-sticky-btn decorative-sticky-filter"
            onClick={() => setIsFilterModalOpen(true)}
          >
            <span className="decorative-sticky-icon" aria-hidden="true">
              ☷
            </span>
            <span>FILTER BY</span>
            {hasActiveFilters && <span className="decorative-sticky-dot" />}
          </button>

          {/* Sort By Dropdown */}
          <div className="decorative-sticky-sort-wrapper">
            <CustomSortDropdown value={sortBy} onChange={setSortBy} />
          </div>

          {/* Light On / Off Toggle */}
          <label
            className={`decorative-sticky-light-toggle ${
              isLightOn ? 'is-on' : 'is-off'
            }`}
          >
            <span className="decorative-sticky-light-text">
              LIGHT {isLightOn ? 'ON' : 'OFF'}
            </span>
            <input
              type="checkbox"
              checked={isLightOn}
              onChange={(e) => setIsLightOn(e.target.checked)}
              aria-label="Toggle lights on or off"
            />
            <i aria-hidden="true" />
          </label>
        </div>
      </div>
    </div>
  );
}
