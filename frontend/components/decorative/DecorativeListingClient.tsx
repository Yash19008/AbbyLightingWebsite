'use client';

import React, { useState, useMemo, useEffect } from 'react';
import DecorativeCard from './DecorativeCard';
import DecorativeFilterModal, { FilterState } from './DecorativeFilterModal';
import DecorativeToolbar from './DecorativeToolbar';

import { Product } from './DecorativeCard';

export default function DecorativeListingClient() {
  const [products, setProducts] = useState<Product[]>([]);
  const [featuredCategories, setFeaturedCategories] = useState<string[]>(['All']);
  const [availableCollections, setAvailableCollections] = useState<string[]>([]);
  
  const [isLoading, setIsLoading] = useState(true);
  const [isPaginating, setIsPaginating] = useState(false);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(false);

  const [activeCategory, setActiveCategory] = useState('All');
  const [isLightOn, setIsLightOn] = useState(true);
  const [sortBy, setSortBy] = useState('new');
  const [isFilterModalOpen, setIsFilterModalOpen] = useState(false);
  const [activeFilters, setActiveFilters] = useState<FilterState>({
    category: [],
    collection: [],
  });

  const handleTabChange = (cat: string) => {
    setActiveCategory(cat);
    setActiveFilters((prev) => ({
      ...prev,
      category: cat === 'All' ? [] : [cat]
    }));
    setPage(1);
  };

  useEffect(() => {
    const fetchFilters = async () => {
      try {
        const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
        
        const [categoriesRes, collectionsRes] = await Promise.all([
          fetch(`${API_URL}/api/dec-categories`),
          fetch(`${API_URL}/api/dec-collections`)
        ]);

        const categoriesData = await categoriesRes.json();
        if (categoriesData.data) {
          const categoryNames = categoriesData.data.map((c: any) => c.name);
          setFeaturedCategories(['All', ...categoryNames]);
        }

        const collectionsData = await collectionsRes.json();
        if (collectionsData.data) {
          const collectionNames = collectionsData.data.map((c: any) => c.name);
          setAvailableCollections(collectionNames);
        }
      } catch (error) {
        console.error('Error fetching filters:', error);
      }
    };
    fetchFilters();
  }, []);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        if (page === 1) setIsLoading(true);
        else setIsPaginating(true);
        
        const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
        const params = new URLSearchParams({
          page: page.toString(),
          per_page: '8',
          sort: sortBy,
        });

        if (activeFilters.category.length > 0) {
          params.append('category', activeFilters.category.join(','));
        }
        if (activeFilters.collection.length > 0) {
          params.append('collection', activeFilters.collection.join(','));
        }

        const response = await fetch(`${API_URL}/api/dec-products?${params.toString()}`);
        const data = await response.json();
        
        if (data.data) {
          if (page === 1) {
            setProducts(data.data);
          } else {
            setProducts((prev) => [...prev, ...data.data]);
          }
          setHasMore(data.current_page < data.last_page);
        }
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setIsLoading(false);
        setIsPaginating(false);
      }
    };
    fetchProducts();
  }, [page, activeFilters, sortBy]);

  const availableCategories = useMemo(() => {
    return featuredCategories.filter((c) => c !== 'All');
  }, [featuredCategories]);



  return (
    <div className="decorative-page">
      <section className="decorative-hero">
        <p className="site-breadcrumb site-breadcrumb--on-dark decorative-breadcrumb">
          <a href="/">Home</a> / <a href="/decorative-products">Decorative</a>
        </p>
        <div className="decorative-shell">
          <h1>
            Decorative <em>Lights</em>
          </h1>
          <p className="decorative-intro">
            Sculptural pendants, wall lights, floor and table lamps across the
            Symphony, Quarry and Neoma collections — cast concrete discs,
            colour-blocked forms and lunar orbs, all made to order.
          </p>
        </div>
      </section>

      <section className="decorative-catalogue">
        <div className="decorative-shell">
          <DecorativeToolbar
            activeCategory={activeCategory}
            setActiveCategory={handleTabChange}
            activeFilters={activeFilters}
            setIsFilterModalOpen={setIsFilterModalOpen}
            sortBy={sortBy}
            setSortBy={(val) => {
              setSortBy(val);
              setPage(1);
            }}
            isLightOn={isLightOn}
            setIsLightOn={setIsLightOn}
            categories={featuredCategories}
          />

          <div className="decorative-grid is-settled">
            {isLoading ? (
              <div style={{ textAlign: 'center', padding: '64px 0', color: '#666', gridColumn: '1 / -1' }}>
                Loading products...
              </div>
            ) : products.length > 0 ? (
              products.map((product, index) => (
                <DecorativeCard
                  key={product.id}
                  product={product}
                  order={index % 3} // for staggered animation
                  filterDelay={index * 40}
                  isGlobalLightOn={isLightOn}
                />
              ))
            ) : null}
          </div>

          {hasMore && (
            <div className="decorative-load-more">
              <button 
                type="button" 
                onClick={() => setPage((p) => p + 1)}
                disabled={isPaginating}
              >
                {isPaginating ? 'Loading...' : 'Load more'}
              </button>
            </div>
          )}
          {!isLoading && products.length === 0 && (
            <div style={{ textAlign: 'center', padding: '64px 0', color: '#666' }}>
              No products found in this category.
            </div>
          )}
        </div>
      </section>

      <section className="decorative-finder">
        <div className="decorative-shell">
          <h2 className="decorative-reveal is-visible">Didn't find what you're looking for?</h2>
          <p className="decorative-reveal is-visible">
            Our lighting advisors can help you choose the right piece, finish
            and configuration for your space or project — and share pricing on
            request.
          </p>
          <a className="decorative-reveal is-visible" href="https://wa.me/919820356488">
            Chat with us <span>→</span>
          </a>
        </div>
      </section>

      {isFilterModalOpen && (
        <DecorativeFilterModal
          initialFilters={activeFilters}
          availableCategories={availableCategories}
          availableCollections={availableCollections}
          onApply={(filters) => {
            setActiveFilters(filters);
            setPage(1);
            if (filters.category.length === 1) {
              setActiveCategory(filters.category[0]);
            } else {
              setActiveCategory('All');
            }
          }}
          onClose={() => setIsFilterModalOpen(false)}
        />
      )}
    </div>
  );
}
