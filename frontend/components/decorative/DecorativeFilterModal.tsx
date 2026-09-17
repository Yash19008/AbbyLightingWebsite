'use client';

import React, { useState, useEffect } from 'react';

export interface FilterState {
  category: string[];
  finish: string[];
  collection: string[];
}

interface DecorativeFilterModalProps {
  initialFilters: FilterState;
  onApply: (filters: FilterState) => void;
  onClose: () => void;
}

export default function DecorativeFilterModal({ initialFilters, onApply, onClose }: DecorativeFilterModalProps) {
  // Draft state for filters before applying
  const [draft, setDraft] = useState<FilterState>(initialFilters);

  // Accordion open state
  const [openSections, setOpenSections] = useState({
    category: true,
    finish: true,
    collection: true,
  });

  const toggleSection = (section: keyof typeof openSections) => {
    setOpenSections((prev) => ({ ...prev, [section]: !prev[section] }));
  };

  const handleToggleFilter = (group: keyof FilterState, value: string) => {
    setDraft((prev) => {
      const current = prev[group];
      if (current.includes(value)) {
        return { ...prev, [group]: current.filter((v) => v !== value) };
      } else {
        return { ...prev, [group]: [...current, value] };
      }
    });
  };

  const handleToggleAll = (group: keyof FilterState) => {
    setDraft((prev) => ({ ...prev, [group]: [] }));
  };

  const clearAll = () => {
    setDraft({ category: [], finish: [], collection: [] });
  };

  const applyFilters = () => {
    onApply(draft);
    onClose();
  };

  return (
    <div
      className="decorative-filter-modal"
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div className="decorative-filter-panel decorative-filter-panel--checkboxes">
        <div className="decorative-filter-head">
          <h3>Filter by</h3>
          <button type="button" className="decorative-filter-close" aria-label="Close filters" onClick={onClose}>
            ×
          </button>
        </div>
        <div className="decorative-filter-accordions">
          {/* Category */}
          <section className="decorative-filter-accordion">
            <button
              className="decorative-filter-accordion-toggle"
              type="button"
              aria-expanded={openSections.category}
              onClick={() => toggleSection('category')}
            >
              <span>Category</span>
              <i className="decorative-filter-chevron" aria-hidden="true" />
            </button>
            <div className="decorative-filter-options" hidden={!openSections.category}>
              <label className="decorative-filter-option">
                <input
                  type="checkbox"
                  checked={draft.category.length === 0}
                  onChange={() => handleToggleAll('category')}
                />
                <span>All</span>
              </label>
              {['Pendant', 'Wall', 'Floor', 'Table'].map((opt) => (
                <label key={opt} className="decorative-filter-option">
                  <input
                    type="checkbox"
                    checked={draft.category.includes(opt)}
                    onChange={() => handleToggleFilter('category', opt)}
                  />
                  <span>{opt}</span>
                </label>
              ))}
            </div>
          </section>

          {/* Finish */}
          <section className="decorative-filter-accordion">
            <button
              className="decorative-filter-accordion-toggle"
              type="button"
              aria-expanded={openSections.finish}
              onClick={() => toggleSection('finish')}
            >
              <span>Finish</span>
              <i className="decorative-filter-chevron" aria-hidden="true" />
            </button>
            <div className="decorative-filter-options" hidden={!openSections.finish}>
              <label className="decorative-filter-option">
                <input
                  type="checkbox"
                  checked={draft.finish.length === 0}
                  onChange={() => handleToggleAll('finish')}
                />
                <span>All</span>
              </label>
              {['White', 'Black', 'Terra', 'Brass', 'Colour'].map((opt) => (
                <label key={opt} className="decorative-filter-option">
                  <input
                    type="checkbox"
                    checked={draft.finish.includes(opt)}
                    onChange={() => handleToggleFilter('finish', opt)}
                  />
                  <span>{opt}</span>
                </label>
              ))}
            </div>
          </section>

          {/* Collections */}
          <section className="decorative-filter-accordion">
            <button
              className="decorative-filter-accordion-toggle"
              type="button"
              aria-expanded={openSections.collection}
              onClick={() => toggleSection('collection')}
            >
              <span>Collections</span>
              <i className="decorative-filter-chevron" aria-hidden="true" />
            </button>
            <div className="decorative-filter-options" hidden={!openSections.collection}>
              <label className="decorative-filter-option">
                <input
                  type="checkbox"
                  checked={draft.collection.length === 0}
                  onChange={() => handleToggleAll('collection')}
                />
                <span>All</span>
              </label>
              {['Symphony', 'Quarry', 'Neoma'].map((opt) => (
                <label key={opt} className="decorative-filter-option">
                  <input
                    type="checkbox"
                    checked={draft.collection.includes(opt)}
                    onChange={() => handleToggleFilter('collection', opt)}
                  />
                  <span>{opt}</span>
                </label>
              ))}
            </div>
          </section>
        </div>
        <div className="decorative-filter-foot">
          <button type="button" className="decorative-filter-clear" onClick={clearAll}>
            Clear all
          </button>
          <button type="button" className="decorative-filter-show show" onClick={applyFilters}>
            Show results
          </button>
        </div>
      </div>
    </div>
  );
}
