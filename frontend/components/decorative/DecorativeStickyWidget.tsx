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

  const tabRefs = React.useRef<Record<string, HTMLButtonElement | null>>({});
  const [indicatorStyle, setIndicatorStyle] = useState({ left: 0, width: 0 });

  useEffect(() => {
    const updateIndicator = () => {
      const currentTab = tabRefs.current[activeCategory];
      if (currentTab) {
        setIndicatorStyle({
          left: currentTab.offsetLeft,
          width: currentTab.offsetWidth,
        });
      }
    };

    updateIndicator();
    window.addEventListener('resize', updateIndicator);
    return () => window.removeEventListener('resize', updateIndicator);
  }, [activeCategory, isVisible, categories]);

  return (
    <div
      className={`decorative-sticky-widget ${isVisible ? 'is-visible' : ''}`}
      role="toolbar"
      aria-label="Sticky product tools"
    >
      <div className="decorative-sticky-widget-inner">
        {/* Left: Category Tabs */}
        {categories && categories.length > 0 && (
          <div
            className="decorative-tabs decorative-sticky-tabs"
            role="tablist"
            aria-label="Product category"
          >
            <span
              className="decorative-tab-indicator"
              aria-hidden="true"
              style={{
                width: indicatorStyle.width,
                transform: `translateX(${indicatorStyle.left}px)`,
              }}
            />
            {categories.map((cat) => (
              <button
                key={cat}
                ref={(el) => {
                  tabRefs.current[cat] = el;
                }}
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

        {/* Right: Filter + Sort + Light Toggle */}
        <div className="decorative-sticky-right-tools">
          <button
            type="button"
            className="decorative-sticky-btn decorative-sticky-filter"
            onClick={(e) => {
              e.stopPropagation();
              setIsFilterModalOpen(true);
            }}
          >
            <span className="decorative-sticky-icon" aria-hidden="true">
              ☷
            </span>
            <span>FILTER BY</span>
            {hasActiveFilters && <span className="decorative-sticky-dot" />}
          </button>

          <div className="decorative-sticky-sort-wrapper">
            <CustomSortDropdown value={sortBy} onChange={setSortBy} />
          </div>

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
