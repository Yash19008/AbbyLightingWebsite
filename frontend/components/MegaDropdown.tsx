import React from "react";
import { getDecorativeCategories, getArchitecturalCategories } from "@/lib/api/categories";
import { getCollections } from "@/lib/api/collections";

export default async function MegaDropdown() {
  // Fetch categories and collections dynamically
  const [decorativeCategories, architecturalCategories, collectionsData] = await Promise.all([
    getDecorativeCategories(),
    getArchitecturalCategories(),
    getCollections().catch(() => ({ success: false, data: [] }))
  ]);

  const collections = collectionsData?.data || [];

  return (
    <div className="mega">
      <div className="mega-panel">
        <div className="mega-grid">
          {/* ARCHITECTURAL - Dynamic */}
          <div className="mgroup m-arch">
            <div className="mhead">Architectural</div>
            <div className="msub">Browse by category</div>
            <ul>
              {architecturalCategories.length > 0 ? (
                architecturalCategories.map((category) => (
                  <li key={category.id}>
                    <a href={category.uri ? category.uri : `/products?category=${category.slug || ''}`}>
                      {category.title || category.name}
                    </a>
                  </li>
                ))
              ) : (
                <>
                  <li><a href="/products">Spots &amp; Accents</a></li>
                  <li><a href="/products">Downlights</a></li>
                  <li><a href="/products">Profiles</a></li>
                  <li><a href="/products">Track Lights</a></li>
                  <li><a href="/products">Washers &amp; Grazers</a></li>
                </>
              )}
            </ul>
          </div>

          <div className="msep"></div>

          {/* DECORATIVE - Dynamic (Two Columns) */}
          <div className="mgroup m-dec">
            <div className="mhead">Decorative <span className="mnew">NEW</span></div>
            <div className="mcols">
              {/* Browse by Category */}
              <div>
                <div className="msub">Browse by category</div>
                <ul>
                  {decorativeCategories.length > 0 ? (
                    decorativeCategories.map((category) => (
                      <li key={category.id}>
                        <a href={`/decorative/${category.slug}`}>{category.name}</a>
                      </li>
                    ))
                  ) : (
                    <>
                      <li><a href="/decorative">Chandelier</a></li>
                      <li><a href="/decorative">Pendant Lights</a></li>
                      <li><a href="/decorative">Wall Lights</a></li>
                      <li><a href="/decorative">Floor Lamps</a></li>
                      <li><a href="/decorative">Table Lamps</a></li>
                    </>
                  )}
                </ul>
              </div>

              <div className="msep sm"></div>

              {/* Browse by Collection */}
              <div>
                <div className="msub">Browse by collection</div>
                <ul>
                  {collections.length > 0 ? (
                    collections.map((collection) => (
                      <li key={collection.id}>
                        <a href={`/collections/${collection.slug}`}>{collection.name}</a>
                      </li>
                    ))
                  ) : (
                    <>
                      <li><a href="/collections/symphony">Symphony</a></li>
                      <li><a href="/collections/quarry">Quarry</a></li>
                      <li><a href="/collections/neoma">Neoma</a></li>
                    </>
                  )}
                </ul>
              </div>
            </div>
          </div>

          <div className="msep"></div>

          {/* OUTDOOR - Static */}
          <div className="mgroup m-out">
            <div className="mhead">Outdoor</div>
            <div className="msub">Browse by category</div>
            <ul>
              <li><a href="/products">Wall Lights</a></li>
              <li><a href="/products">Path Lights</a></li>
              <li><a href="/products">Bollards</a></li>
              <li><a href="/products">Flood Lights</a></li>
            </ul>
            <div className="msub" style={{ marginTop: '1rem' }}>Smart Lighting</div>
            <ul>
              <li><a href="/abby-smart">Abby Smart</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  );
}
