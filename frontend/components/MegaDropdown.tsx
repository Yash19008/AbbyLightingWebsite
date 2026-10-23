import React from "react";
import Link from "next/link";
import { getArchitecturalCategories } from "@/lib/api/categories";

export default async function MegaDropdown() {
  // Fetch categories dynamically
  const architecturalCategories = await getArchitecturalCategories();

  return (
    <div className="mega">
      <div className="mega-panel">
        <div className="mega-grid">
          {/* ARCHITECTURAL - Dynamic */}
          <div className="mgroup m-arch">
            <Link href="/products" className="mhead">
              Architectural
            </Link>
            <div className="msub">Browse by category</div>
            <ul>
              {architecturalCategories.length > 0 ? (
                architecturalCategories.map((category) => (
                  <li key={category.id}>
                    <Link href={category.uri || (category.slug ? `/products/category/${category.slug}` : '/products')}>
                      {category.title || category.name}
                    </Link>
                  </li>
                ))
              ) : (
                <>
                  <li><Link href="/products/category/spots-&-accents">Spots &amp; Accents</Link></li>
                  <li><Link href="/products/category/downlights">Downlights</Link></li>
                  <li><Link href="/products/category/profiles">Profiles</Link></li>
                  <li><Link href="/products/category/track-lights">Track Lights</Link></li>
                  <li><Link href="/products/category/washers-grazers">Washers &amp; Grazers</Link></li>
                </>
              )}
            </ul>
          </div>

          <div className="msep"></div>

          {/* DECORATIVE */}
          <div className="mgroup m-dec">
            <Link href="/decorative-lights" className="mhead">
              Decorative <span className="mnew">NEW</span>
            </Link>
            <div className="mcols">
              <div>
                <div className="msub">Browse by category</div>
                <ul>
                  <li><Link href="/decorative-lights?category=chandelier">Chandelier</Link></li>
                  <li><Link href="/decorative-lights?category=pendant-lights">Pendant Lights</Link></li>
                  <li><Link href="/decorative-lights?category=wall-lights">Wall Lights</Link></li>
                  <li><Link href="/decorative-lights?category=floor-lamps">Floor Lamps</Link></li>
                  <li><Link href="/decorative-lights?category=table-lamps">Table Lamps</Link></li>
                </ul>
              </div>
              <div className="msep sm"></div>
              <div>
                <div className="msub">Browse by collection</div>
                <ul>
                  <li><Link href="/collections/symphony">Symphony</Link></li>
                  <li><Link href="/collections/quarry">Quarry</Link></li>
                  <li><Link href="/collections/neoma">Neoma</Link></li>
                </ul>
              </div>
            </div>
          </div>

          <div className="msep lg"></div>
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
