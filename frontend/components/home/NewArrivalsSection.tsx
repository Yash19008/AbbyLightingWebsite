"use client";

import { useState, useMemo } from "react";
import type { NewArrivalCategory, NewArrivalProduct } from "@/types/new-arrival";

interface NewArrivalsSectionProps {
  categories: NewArrivalCategory[];
}

export default function NewArrivalsSection({ categories }: NewArrivalsSectionProps) {
  const [activeTab, setActiveTab] = useState<string>("Architectural");

  // Derive products for the selected category tab dynamically
  const filteredProducts = useMemo(() => {
    const category = categories.find(
      cat => cat.name.toLowerCase() === activeTab.toLowerCase()
    );
    return category ? category.products : [];
  }, [categories, activeTab]);

  return (
    <section className="section" id="arrivals">
      <div className="shell">
        <div className="section-head reveal">
          <h2>New Arrivals</h2>
        </div>
        <div className="product-toolbar">
          <div className="filter-chips" role="tablist" aria-label="New arrival categories">
            {["Architectural", "Decorative", "Outdoor"].map((tabName) => (
              <button 
                key={tabName}
                type="button" 
                role="tab" 
                aria-selected={activeTab === tabName}
                className={activeTab === tabName ? 'active' : ''}
                onClick={() => setActiveTab(tabName)}
              >
                {tabName}
              </button>
            ))}
          </div>
        </div>
        <div className="products">
          {filteredProducts.length > 0 ? (
            filteredProducts.slice(0, 4).map((product: NewArrivalProduct, index: number) => (
              <a 
                key={product.id}
                className="product reveal is-visible" 
                style={{"--i": index} as any} 
                href="/#contact"
              >
                <div className="photo">
                  {product.image_url ? (
                    <img src={product.image_url} alt={product.name} />
                  ) : (
                    <div style={{width: '100%', height: '300px', background: '#f0f0f0', display: 'flex', alignItems: 'center', justifyContent: 'center'}}>
                      <span>No image</span>
                    </div>
                  )}
                </div>
                <h3>{product.name}</h3>
                <p>{product.parent_category} · {product.category}</p>
              </a>
            ))
          ) : (
            <div className="products-empty-state">
              <p>No products available in this category</p>
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
