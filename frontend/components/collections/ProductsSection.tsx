"use client";

import { useState, useEffect } from 'react';
import ProductCard from './ProductCard';
import { Product } from '@/types/collection';

interface ProductsSectionProps {
  products: Product[];
  collectionName: string;
}


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

  if (!products || products.length === 0) {
    return null;
  }

  const itemsCount = products.length;
  const visibleCount = expanded ? itemsCount : (isMobile ? 4 : 8);

  const displayTitle = `${collectionName} Products`;
  const displaySubtitle = `Explore our range of architectural silhouettes designed for the ${collectionName} collection.`;

  return (
    <section className={`s-section s-products ${expanded ? 'is-expanded' : ''}`}>
      <div className="s-head">
        <h2>{displayTitle}</h2>
        <p>{displaySubtitle}</p>
      </div>
      <div className="s-products-carousel">
        <div className="decorative-grid is-settled">
          {products.slice(0, visibleCount).map((product, index) => (
            <ProductCard key={product.id} index={index} product={product} />
          ))}
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
