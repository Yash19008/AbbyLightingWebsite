"use client";

import { useState, useEffect } from 'react';
import ProductCard from './ProductCard';
import { Product } from '@/types/collection';

interface ProductsSectionProps {
  products: Product[];
  collectionName: string;
}

const fallbackForms = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];

export default function ProductsSection({ products, collectionName }: ProductsSectionProps) {
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

  const hasDynamicProducts = products && products.length > 0;
  const itemsCount = hasDynamicProducts ? products.length : fallbackForms.length;
  const visibleCount = expanded ? itemsCount : (isMobile ? 4 : 8);

  const displayTitle = hasDynamicProducts ? `${collectionName} Products` : "Ten Forms";
  const displaySubtitle = hasDynamicProducts 
    ? `Explore our range of architectural silhouettes designed for the ${collectionName} collection.` 
    : "Each note is a distinct silhouette. Available across every Symphony tone.";

  return (
    <section className={`s-section s-products ${expanded ? 'is-expanded' : ''}`}>
      <div className="s-head">
        <h2>{displayTitle}</h2>
        <p>{displaySubtitle}</p>
      </div>
      <div className="s-products-carousel">
        <div className="decorative-grid is-settled">
          {hasDynamicProducts ? (
            products.slice(0, visibleCount).map((product, index) => (
              <ProductCard key={product.id} index={index} product={product} />
            ))
          ) : (
            fallbackForms.slice(0, visibleCount).map((roman, index) => (
              <ProductCard key={roman} roman={roman} index={index} />
            ))
          )}
        </div>
      </div>
      {!expanded && itemsCount > visibleCount && (
        <button 
          className="s-view" 
          type="button"
          onClick={() => setExpanded(true)}
        >
          <span className="s-view-desktop">View all</span>
          <span className="s-view-mobile">View more</span>
        </button>
      )}
    </section>
  );
}
