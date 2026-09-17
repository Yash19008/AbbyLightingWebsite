'use client';

import React, { useState, useMemo } from 'react';
import DecorativeCard from './DecorativeCard';
import DecorativeFilterModal, { FilterState } from './DecorativeFilterModal';
import DecorativeToolbar from './DecorativeToolbar';

// Temporary static data based on reference.html
const STATIC_PRODUCTS = [
  {
    id: 'p1',
    slug: 'cymbal',
    name: 'Cymbal',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: true,
    variants: [
      { id: 'v1', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/cymbal-off.png', imageOn: '/images/decorative/cymbal-on.png' },
      { id: 'v2', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/cymbal-off.png', imageOn: '/images/decorative/cymbal-on.png' },
      { id: 'v3', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/cymbal-off.png', imageOn: '/images/decorative/cymbal-on.png' },
    ]
  },
  {
    id: 'p2',
    slug: 'dew',
    name: 'Dew',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v4', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/dew-off.png', imageOn: '/images/decorative/dew-on.png' },
      { id: 'v5', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/dew-off.png', imageOn: '/images/decorative/dew-on.png' },
      { id: 'v6', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/dew-off.png', imageOn: '/images/decorative/dew-on.png' },
    ]
  },
  {
    id: 'p3',
    slug: 'apex',
    name: 'Apex',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v7', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/apex-off.png', imageOn: '/images/decorative/apex-on.png' },
      { id: 'v8', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/apex-off.png', imageOn: '/images/decorative/apex-on.png' },
      { id: 'v9', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/apex-off.png', imageOn: '/images/decorative/apex-on.png' },
    ]
  },
  {
    id: 'p4',
    slug: 'node',
    name: 'Node',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v10', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/node-off.png', imageOn: '/images/decorative/node-on.png' },
      { id: 'v11', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/node-off.png', imageOn: '/images/decorative/node-on.png' },
      { id: 'v12', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/node-off.png', imageOn: '/images/decorative/node-on.png' },
    ]
  },
  {
    id: 'p5',
    slug: 'seam',
    name: 'Seam',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v13', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/seam-off.png', imageOn: '/images/decorative/seam-on.png' },
      { id: 'v14', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/seam-off.png', imageOn: '/images/decorative/seam-on.png' },
      { id: 'v15', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/seam-off.png', imageOn: '/images/decorative/seam-on.png' },
    ]
  },
  {
    id: 'p6',
    slug: 'orb',
    name: 'Orb',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v16', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/orb-off.png', imageOn: '/images/decorative/orb-on.png' },
      { id: 'v17', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/orb-off.png', imageOn: '/images/decorative/orb-on.png' },
      { id: 'v18', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/orb-off.png', imageOn: '/images/decorative/orb-on.png' },
    ]
  },
  {
    id: 'p7',
    slug: 'canopy',
    name: 'Canopy',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v19', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/canopy-off.png', imageOn: '/images/decorative/canopy-on.png' },
      { id: 'v20', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/canopy-off.png', imageOn: '/images/decorative/canopy-on.png' },
      { id: 'v21', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/canopy-off.png', imageOn: '/images/decorative/canopy-on.png' },
    ]
  },
  {
    id: 'p8',
    slug: 'turret',
    name: 'Turret',
    category: 'Pendant',
    collection: 'Quarry',
    isNew: false,
    variants: [
      { id: 'v22', name: 'White', color: '#f2f0ea', imageOff: '/images/decorative/turret-off.png', imageOn: '/images/decorative/turret-on.png' },
      { id: 'v23', name: 'Black', color: '#1f1f1f', imageOff: '/images/decorative/turret-off.png', imageOn: '/images/decorative/turret-on.png' },
      { id: 'v24', name: 'Terra', color: '#9c482a', imageOff: '/images/decorative/turret-off.png', imageOn: '/images/decorative/turret-on.png' },
    ]
  },
  {
    id: 'p9',
    slug: 'symphony-iv',
    name: 'Symphony IV',
    category: 'Pendant',
    collection: 'Symphony',
    isNew: false,
    variants: [
      { id: 'v25', name: 'Coral', color: '#c0392b', imageOff: '/images/decorative/symphonyiv-off.png', imageOn: '/images/decorative/symphonyiv-on.png' },
      { id: 'v26', name: 'Amber', color: '#e6b422', imageOff: '/images/decorative/symphonyiv-off.png', imageOn: '/images/decorative/symphonyiv-on.png' },
      { id: 'v27', name: 'Teal', color: '#1f7a7a', imageOff: '/images/decorative/symphonyiv-off.png', imageOn: '/images/decorative/symphonyiv-on.png' },
    ]
  },
];


export default function DecorativeListingClient() {
  const [activeCategory, setActiveCategory] = useState('All');
  const [isLightOn, setIsLightOn] = useState(true);
  const [sortBy, setSortBy] = useState('new');
  const [isFilterModalOpen, setIsFilterModalOpen] = useState(false);
  const [activeFilters, setActiveFilters] = useState<FilterState>({
    category: [],
    finish: [],
    collection: [],
  });


  const filteredProducts = useMemo(() => {
    let result = [...STATIC_PRODUCTS];

    if (activeCategory !== 'All') {
      result = result.filter((p) => p.category === activeCategory);
    }

    if (activeFilters.category.length > 0) {
      result = result.filter((p) => activeFilters.category.includes(p.category));
    }
    if (activeFilters.collection.length > 0) {
      result = result.filter((p) => activeFilters.collection.includes(p.collection));
    }
    if (activeFilters.finish.length > 0) {
      result = result.filter((p) =>
        p.variants.some((v) => activeFilters.finish.includes(v.name))
      );
    }

    if (sortBy === 'name_asc') {
      result.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'name_desc') {
      result.sort((a, b) => b.name.localeCompare(a.name));
    } else if (sortBy === 'popular') {
      // Stub: in real app, sort by view count or sales
      result.sort((a, b) => (a.isNew === b.isNew ? 0 : a.isNew ? -1 : 1));
    } else {
      // sort by new
      result.sort((a, b) => (a.isNew === b.isNew ? 0 : a.isNew ? -1 : 1));
    }

    return result;
  }, [activeCategory, sortBy, activeFilters]);

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
            setActiveCategory={setActiveCategory}
            activeFilters={activeFilters}
            setIsFilterModalOpen={setIsFilterModalOpen}
            sortBy={sortBy}
            setSortBy={setSortBy}
            isLightOn={isLightOn}
            setIsLightOn={setIsLightOn}
          />

          <div className="decorative-grid is-settled">
            {filteredProducts.map((product, index) => (
              <DecorativeCard
                key={product.id}
                product={product}
                order={index % 3} // for staggered animation
                filterDelay={index * 40}
                isGlobalLightOn={isLightOn}
              />
            ))}
          </div>

          {filteredProducts.length > 0 && (
            <div className="decorative-load-more">
              <button type="button">Load more</button>
            </div>
          )}
          {filteredProducts.length === 0 && (
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
          onApply={(filters) => {
            setActiveFilters(filters);
            // In original JS, applying filter also resets the top category tabs to "All"
            setActiveCategory('All');
          }}
          onClose={() => setIsFilterModalOpen(false)}
        />
      )}
    </div>
  );
}
