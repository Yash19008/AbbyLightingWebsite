"use client";

import { useState } from 'react';
import { ParametersSection as ParametersSectionType } from '@/types/collection';
import { getCollectionParameters } from '@/lib/api/collections';

interface ParametersSectionProps {
  parametersSection: ParametersSectionType;
  collectionSlug?: string;
}

export default function ParametersSectionDynamic({ parametersSection, collectionSlug }: ParametersSectionProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

  const initialItems = parametersSection.items || [];
  const [items, setItems] = useState(initialItems);
  const [page, setPage] = useState(1);
  const [visibleCount, setVisibleCount] = useState(8);
  const [hasMoreServerItems, setHasMoreServerItems] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  const visibleItems = items.slice(0, visibleCount);
  const canViewMore = collectionSlug
    ? hasMoreServerItems || visibleCount < items.length
    : visibleCount < items.length;

  const handleClick = (index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  const handleKeyDown = (e: React.KeyboardEvent, index: number) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      handleClick(index);
    } else if (e.key === 'Escape') {
      setActiveIndex(null);
    }
  };

  const handleViewMore = async () => {
    if (isLoading) return;

    // 1. If we have client items buffered already beyond visibleCount, show up to 8 more
    if (visibleCount < items.length) {
      setVisibleCount((prev) => Math.min(prev + 8, items.length));
      return;
    }

    // 2. If all client items are visible and server has more items, fetch next page of 8 from backend API
    if (collectionSlug && hasMoreServerItems) {
      setIsLoading(true);
      const nextPage = page + 1;
      try {
        const res = await getCollectionParameters(collectionSlug, nextPage, 8);
        if (res.success && res.data) {
          const newItems = res.data.items || [];
          if (newItems.length > 0) {
            const existingIds = new Set(items.map((i) => i.id));
            const uniqueNew = newItems.filter((i: any) => !existingIds.has(i.id));

            if (uniqueNew.length > 0) {
              setItems((prev) => [...prev, ...uniqueNew]);
              setVisibleCount((prev) => prev + 8);
            } else {
              setHasMoreServerItems(false);
            }
          } else {
            setHasMoreServerItems(false);
          }
          setHasMoreServerItems(res.data.has_more ?? false);
          setPage(nextPage);
        } else {
          setHasMoreServerItems(false);
        }
      } catch (error) {
        console.error('Error loading more parameter items from server:', error);
      } finally {
        setIsLoading(false);
      }
    } else {
      setVisibleCount((prev) => prev + 8);
    }
  };

  return (
    <section className="s-section s-parameters">
      <div className="s-head">
        <h2>{parametersSection.title}</h2>
        <p>{parametersSection.subtitle}</p>
      </div>
      <div className="s-parameter-grid">
        {visibleItems.map((param, index) => {
          const cardBg = param.bg_color || undefined;
          const cardHoverBg = param.hover_bg_color || undefined;
          return (
            <article
              key={`${param.id}-${index}`}
              tabIndex={0}
              className={activeIndex === index ? 'is-active' : ''}
              onClick={() => handleClick(index)}
              onKeyDown={(e) => handleKeyDown(e, index)}
              style={
                cardBg
                  ? { backgroundColor: cardBg }
                  : undefined
              }
              onMouseEnter={
                cardHoverBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = cardHoverBg;
                    }
                  : undefined
              }
              onMouseLeave={
                cardBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = cardBg;
                    }
                  : cardHoverBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = '';
                    }
                  : undefined
              }
            >
              <small>{param.small_text}</small>
              <h3>{param.title}</h3>
              <span className="s-parameter-arrow" aria-hidden="true" />
              <p>{param.description}</p>
            </article>
          );
        })}
      </div>

      {canViewMore && (
        <div style={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
          <button
            type="button"
            className="s-view-more-btn"
            onClick={handleViewMore}
            disabled={isLoading}
          >
            {isLoading ? 'Loading...' : 'View More'}
          </button>
        </div>
      )}
    </section>
  );
}
