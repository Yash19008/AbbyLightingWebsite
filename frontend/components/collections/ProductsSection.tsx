"use client";

import { useState, useEffect } from 'react';
import DecorativeCard, { Product } from '../decorative/DecorativeCard';
import { ProductsSection as ProductsSectionType } from '@/types/collection';

interface ProductsSectionProps {
  products?: Product[];
  collectionName: string;
  sectionData?: ProductsSectionType | null;
}

export default function ProductsSection({ products = [], collectionName, sectionData }: ProductsSectionProps) {
  const [expanded, setExpanded] = useState(false);
  const [isMobile, setIsMobile] = useState(false);

  useEffect(() => {
    const checkMobile = () => {
      setIsMobile(window.innerWidth <= 700);
    };
    
    checkMobile();
    window.addEventListener('resize', checkMobile);
    return () => window.removeEventListener('resize', checkMobile);
  }, []);

  // If no dynamic products exist for this collection, hide the section
  if (!products || products.length === 0) {
    return null;
  }

  const itemsCount = products.length;
  const visibleCount = expanded ? itemsCount : (isMobile ? 4 : 8);

  const displayTitle = sectionData?.heading || `${collectionName} Products`;
  const displaySubtitle = sectionData?.subtitle || `Explore our range of architectural silhouettes designed for the ${collectionName} collection.`;
  const viewMoreText = sectionData?.view_more_text || 'View all';

  return (
    <section 
      className={`s-section s-products ${expanded ? 'is-expanded' : ''}`} 
      style={{ 
        '--decorative-graphite': '#1a1c1d',
        '--decorative-amber': '#f6c177',
        '--decorative-muted': '#6f6f6f',
        '--decorative-hair': '#e4e2de'
      } as React.CSSProperties}
    >
      <div className="s-head">
        <h2>{displayTitle}</h2>
        <p>{displaySubtitle}</p>
      </div>
      <div className="s-products-carousel">
        <div className="decorative-grid is-settled">
          {products.slice(0, visibleCount).map((product, index) => (
            <DecorativeCard 
              key={product.id} 
              product={product} 
              order={index % 4} 
              filterDelay={(index % 4) * 50}
              isGlobalLightOn={true}
              hideNewBadge={true}
            />
          ))}
        </div>
      </div>
      {!expanded && itemsCount > visibleCount && (
        <button 
          className="s-view" 
          type="button"
          onClick={() => setExpanded(true)}
        >
          <span className="s-view-desktop">{viewMoreText}</span>
          <span className="s-view-mobile">{viewMoreText}</span>
        </button>
      )}
    </section>
  );
}
