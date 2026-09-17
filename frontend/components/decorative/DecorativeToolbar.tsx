'use client';

import React, { useRef, useState, useEffect } from 'react';
import CustomSortDropdown from './CustomSortDropdown';
import { FilterState } from './DecorativeFilterModal';

export const CATEGORIES = ['All', 'Pendant', 'Wall', 'Floor', 'Table'];

interface DecorativeToolbarProps {
  activeCategory: string;
  setActiveCategory: (cat: string) => void;
  activeFilters: FilterState;
  setIsFilterModalOpen: (isOpen: boolean) => void;
  sortBy: string;
  setSortBy: (val: string) => void;
  isLightOn: boolean;
  setIsLightOn: (on: boolean) => void;
  categories: string[];
}

export default function DecorativeToolbar({
  activeCategory,
  setActiveCategory,
  activeFilters,
  setIsFilterModalOpen,
  sortBy,
  setSortBy,
  isLightOn,
  setIsLightOn,
  categories,
}: DecorativeToolbarProps) {
  const tabRefs = useRef<Record<string, HTMLButtonElement | null>>({});
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
  }, [activeCategory]);

  return (
    <div className="decorative-toolbar">
      <label className="decorative-mobile-category">
        <span>{activeCategory}</span>
        <i aria-hidden="true" />
        <select
          aria-label="Product category"
          value={activeCategory}
          onChange={(e) => setActiveCategory(e.target.value)}
        >
          {categories.map((cat) => (
            <option key={cat} value={cat}>
              {cat}
            </option>
          ))}
        </select>
      </label>

      <div className="decorative-tabs" role="tablist" aria-label="Product category">
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

      <div className="decorative-tools">
        <button
          type="button"
          className="abby-desktop-action-btn decorative-filter-button"
          onClick={() => setIsFilterModalOpen(true)}
          style={{ whiteSpace: 'nowrap' }}
        >
          <span style={{ fontSize: '14px', lineHeight: 1 }} aria-hidden="true">☷</span>
          FILTER BY
          {(activeFilters.category.length > 0 || activeFilters.collection.length > 0) && (
            <span style={{ background: '#f6c177', borderRadius: '50%', width: '6px', height: '6px', display: 'inline-block' }} />
          )}
        </button>
        <CustomSortDropdown value={sortBy} onChange={setSortBy} />

        <label className={`decorative-light-toggle ${isLightOn ? 'is-on' : 'is-off'}`}>
          <span className="decorative-light-desktop">Light {isLightOn ? 'on' : 'off'}</span>
          <span className="decorative-light-mobile">{isLightOn ? 'On' : 'Off'}</span>
          <input
            type="checkbox"
            checked={isLightOn}
            onChange={(e) => setIsLightOn(e.target.checked)}
          />
          <i aria-hidden="true" />
        </label>
      </div>
    </div>
  );
}
