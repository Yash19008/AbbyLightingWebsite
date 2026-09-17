import React from "react";
import { getArchitecturalCategories } from "@/lib/api/categories";
import { getCollections } from "@/lib/api/collections";

export default async function MegaDropdown() {
  // Fetch categories and collections dynamically
  const [architecturalCategories, collectionsData] = await Promise.all([
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
                architecturalCategories.map((category: Record<string, unknown>) => (
                  <li key={category.id as number}>
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
