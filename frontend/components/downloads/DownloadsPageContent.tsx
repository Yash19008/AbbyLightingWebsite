"use client";
import { API_URL, API_BASE } from '@/lib/config';


import React, { useState, useRef, useEffect, useMemo } from "react";
import { getCatalogueCategories, getCatalogues } from "@/lib/api/catalogues";
import SharedInquiryModal from "@/components/shared/SharedInquiryModal";

interface CatalogueItem {
  id: number;
  title: string;
  category: string;
  categorySlug: string;
  image: string;
  pdfUrl?: string | null;
  downloadUrl?: string | null;
  fileSize?: string | null;
  isFeatured?: boolean;
}

const DEFAULT_CATEGORIES = [
  { id: 0, name: "All", slug: "all" },
];

const SORT_OPTIONS = [
  { id: "new", label: "New Catalogue" },
  { id: "az", label: "Alphabetical A-Z" },
  { id: "za", label: "Alphabetical Z-A" },
];

type SortMode = "new" | "az" | "za";

export default function DownloadsPageContent() {
  const [categories, setCategories] = useState<{ id: number; name: string; slug: string }[]>(DEFAULT_CATEGORIES);
  const [catalogues, setCatalogues] = useState<CatalogueItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [loadingMore, setLoadingMore] = useState(false);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(false);

  const [activeFilter, setActiveFilter] = useState<string>("All");
  const [sortMode, setSortMode] = useState<SortMode>("new");
  const [sortMenuOpen, setSortMenuOpen] = useState(false);
  const [mobileFilterOpen, setMobileFilterOpen] = useState(false);
  const [selectedCardId, setSelectedCardId] = useState<number | null>(null);

  // Modal State
  const [modalOpen, setModalOpen] = useState(false);
  const [selectedCatalogue, setSelectedCatalogue] = useState<CatalogueItem | null>(null);


  const sortRef = useRef<HTMLDivElement>(null);
  const mobileFilterRef = useRef<HTMLDivElement>(null);

  // Fetch categories on mount
  useEffect(() => {
    let isMounted = true;
    async function loadCategories() {
      try {
        const catRes = await getCatalogueCategories();
        if (isMounted && catRes.success && catRes.data && catRes.data.length > 0) {
          const activeCats = catRes.data.filter((c) => (c.catalogues_count ?? 0) > 0);
          setCategories([
            { id: 0, name: "All", slug: "all" },
            ...activeCats.map((c) => ({
              id: c.id,
              name: c.name,
              slug: c.slug,
            })),
          ]);
        }
      } catch (e) {
        console.error("Error loading catalogue categories:", e);
      }
    }
    loadCategories();
    return () => {
      isMounted = false;
    };
  }, []);

  // Fetch dynamic catalogues from API with server-side pagination & filter & sort
  useEffect(() => {
    let isMounted = true;
    async function loadCatalogues() {
      try {
        setLoading(true);
        setPage(1);
        const itemsRes = await getCatalogues({
          category: activeFilter,
          sort: sortMode,
          page: 1,
          per_page: 6,
        });

        if (isMounted) {

          if (itemsRes.success && itemsRes.data && itemsRes.data.length > 0) {
            const mapped = itemsRes.data.map((item) => ({
              id: item.id,
              title: item.title,
              category: item.category?.name || "Uncategorized",
              categorySlug: item.category?.slug || "all",
              image: item.cover_image || "/images/figma-update/catalogue.png",
              pdfUrl: item.pdf_url,
              downloadUrl: item.download_url || (item.id ? `${API_URL}/catalogues/${item.id}/download-pdf` : null),
              fileSize: item.file_size,
              isFeatured: item.is_featured,
            }));
            setCatalogues(mapped);
            setHasMore(itemsRes.pagination?.has_more ?? false);
          } else {
            setCatalogues([]);
            setHasMore(false);
          }
        }
      } catch (err) {
        console.error("Error loading catalogue data:", err);
      } finally {
        setLoading(false);
      }
    }

    loadCatalogues();
    return () => {
      isMounted = false;
    };
  }, [activeFilter, sortMode]);

  // Server-side Load More handler
  const handleLoadMore = async () => {
    if (loadingMore || !hasMore) return;
    try {
      setLoadingMore(true);
      const nextPage = page + 1;
      const itemsRes = await getCatalogues({
        category: activeFilter,
        sort: sortMode,
        page: nextPage,
        per_page: 6,
      });

      if (itemsRes.success && itemsRes.data && itemsRes.data.length > 0) {
        const mapped = itemsRes.data.map((item) => ({
          id: item.id,
          title: item.title,
          category: item.category?.name || "Uncategorized",
          categorySlug: item.category?.slug || "all",
          image: item.cover_image || "/images/figma-update/catalogue.png",
          pdfUrl: item.pdf_url,
          downloadUrl: item.download_url || (item.id ? `${API_URL}/catalogues/${item.id}/download-pdf` : null),
          fileSize: item.file_size,
          isFeatured: item.is_featured,
        }));
        setCatalogues((prev) => [...prev, ...mapped]);
        setPage(nextPage);
        setHasMore(itemsRes.pagination?.has_more ?? false);
      } else {
        setHasMore(false);
      }
    } catch (err) {
      console.error("Error loading more catalogues:", err);
    } finally {
      setLoadingMore(false);
    }
  };

  const handleFilterSelect = (categoryName: string) => {
    setActiveFilter(categoryName);
    setMobileFilterOpen(false);
  };

  const handleSortSelect = (mode: SortMode) => {
    setSortMode(mode);
    setSortMenuOpen(false);
  };

  // Close dropdowns on outside click
  useEffect(() => {
    function handleClickOutside(e: MouseEvent) {
      if (sortRef.current && !sortRef.current.contains(e.target as Node)) {
        setSortMenuOpen(false);
      }
      if (mobileFilterRef.current && !mobileFilterRef.current.contains(e.target as Node)) {
        setMobileFilterOpen(false);
      }
    }
    document.addEventListener("click", handleClickOutside);
    return () => document.removeEventListener("click", handleClickOutside);
  }, []);

  // Keyboard navigation for modal
  useEffect(() => {
    function handleKeyDown(e: KeyboardEvent) {
      if (e.key === "Escape") {
        if (modalOpen) {
          closeModal();
        } else {
          setSortMenuOpen(false);
          setMobileFilterOpen(false);
        }
      }
    }
    document.addEventListener("keydown", handleKeyDown);
    return () => document.removeEventListener("keydown", handleKeyDown);
  }, [modalOpen]);

  // Lock body scroll when modal is open
  useEffect(() => {
    if (modalOpen) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [modalOpen]);

  const openDownloadModal = (item: CatalogueItem, e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSelectedCatalogue(item);
    setModalOpen(true);
  };


  const closeModal = () => {
    setModalOpen(false);
  };





  return (
    <div className="downloads-page">
      {/* Hero Section */}
      <section className="downloads-hero" aria-labelledby="downloads-title">
        <div className="downloads-hero-shade" />
        <div className="downloads-hero-copy">
          <h1 id="downloads-title">
            <span className="hero-copy-desktop">Our Product</span>
            <span className="hero-copy-mobile">Our Product</span>
          </h1>
          <p>
            <span className="hero-copy-desktop">Catalogs</span>
            <span className="hero-copy-mobile">Catalogs</span>
          </p>
        </div>
      </section>

      {/* Catalogue Grid & Toolbar Section */}
      <section className="catalogue-section" aria-label="Product catalogues">
        {/* Toolbar */}
        <div className="catalogue-toolbar">
          <div
            className="catalogue-filters"
            role="tablist"
            aria-label="Catalogue categories"
            onWheel={(e) => {
              if (e.deltaY !== 0) {
                e.currentTarget.scrollLeft += e.deltaY;
              }
            }}
          >
            {categories.map((cat) => (
              <button
                key={cat.id}
                className={activeFilter.toLowerCase() === cat.name.toLowerCase() ? "active" : ""}
                type="button"
                role="tab"
                aria-selected={activeFilter.toLowerCase() === cat.name.toLowerCase()}
                onClick={() => handleFilterSelect(cat.name)}
              >
                {cat.name}
              </button>
            ))}
          </div>

          <div className="catalogue-actions">
            {/* Mobile Filter */}
            <div className="catalogue-mobile-filter" ref={mobileFilterRef}>
              <button
                className={`catalogue-filter-trigger ${mobileFilterOpen ? 'is-open' : ''}`}
                type="button"
                aria-haspopup="menu"
                aria-expanded={mobileFilterOpen}
                onClick={() => {
                  setMobileFilterOpen((prev) => !prev);
                  setSortMenuOpen(false);
                }}
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z" />
                </svg>
                <span>FILTER BY</span>
              </button>
              {mobileFilterOpen && (
                <div className="catalogue-filter-menu" role="menu">
                  {categories.map((cat) => (
                    <button
                      key={cat.id}
                      className={activeFilter.toLowerCase() === cat.name.toLowerCase() ? "is-active" : ""}
                      type="button"
                      role="menuitemradio"
                      aria-checked={activeFilter.toLowerCase() === cat.name.toLowerCase()}
                      onClick={() => handleFilterSelect(cat.name)}
                    >
                      {cat.name === "Architecture" ? "Architectural" : cat.name}
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* Sort By Dropdown */}
            <div className="catalogue-sort-wrap" ref={sortRef}>
              <button
                className={`catalogue-sort ${sortMenuOpen ? 'is-open' : ''}`}
                type="button"
                aria-haspopup="menu"
                aria-expanded={sortMenuOpen}
                onClick={() => {
                  setSortMenuOpen((prev) => !prev);
                  setMobileFilterOpen(false);
                }}
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                </svg>
                <span>SORT BY</span>
              </button>
              {sortMenuOpen && (
                <div className="catalogue-sort-menu" role="menu">
                  {SORT_OPTIONS.map((opt) => (
                    <button
                      key={opt.id}
                      type="button"
                      role="menuitem"
                      className={sortMode === opt.id ? "is-active" : ""}
                      onClick={() => handleSortSelect(opt.id as SortMode)}
                    >
                      {opt.label}
                    </button>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>

        {/* Empty State */}
        {!loading && catalogues.length === 0 && (
          <div className="empty-state">
            <p>No catalogues available at the moment.</p>
          </div>
        )}

        {/* Cards Grid */}
        {loading ? (
          <div className="catalogue-grid catalogue-grid-skeleton" aria-busy="true" aria-label="Loading catalogues">
            {[0, 1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="catalogue-card catalogue-card-skel" style={{ pointerEvents: "none" }}>
                <div
                  className="catalogue-cover"
                  style={{
                    position: "relative",
                    overflow: "hidden",
                    aspectRatio: "359 / 427",
                    background: "linear-gradient(90deg, #ececec 0%, #f5f5f5 40%, #ffffff 50%, #f5f5f5 60%, #ececec 100%)",
                    backgroundSize: "1200px 100%",
                    animation: "cat-shimmer 1.6s ease-in-out infinite",
                  }}
                />
                <div
                  style={{
                    height: 22,
                    width: "65%",
                    marginTop: 13,
                    borderRadius: 4,
                    background: "linear-gradient(90deg, #e4e4e4 0%, #efefef 40%, #f8f8f8 50%, #efefef 60%, #e4e4e4 100%)",
                    backgroundSize: "1200px 100%",
                    animation: "cat-shimmer 1.6s ease-in-out infinite",
                  }}
                />
              </div>
            ))}
          </div>
        ) : catalogues.length > 0 ? (
          <div className="catalogue-grid">
            {catalogues.map((item) => {
              return (
                <article
                  key={item.id}
                  className="catalogue-card"
                  data-category={item.category}
                  data-title={item.title}
                  tabIndex={0}
                  aria-label={`Open download form for ${item.title} catalogue`}
                  onClick={(e) => openDownloadModal(item, e)}
                  onKeyDown={(e) => {
                    if (e.key === "Enter" || e.key === " ") {
                      e.preventDefault();
                      openDownloadModal(item);
                    }
                  }}
                >
                  <div className="catalogue-cover">
                    <img src={item.image} alt={`${item.title} catalogue cover`} />
                    <div className="catalogue-selected">
                      <div className="catalogue-download-content">
                        <svg
                          className="catalogue-download-svg"
                          width="32"
                          height="32"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#ffffff"
                          strokeWidth="1"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          aria-hidden="true"
                        >
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                          <polyline points="7 10 12 15 17 10" />
                          <line x1="12" y1="15" x2="12" y2="-4" />
                        </svg>
                        <strong>Download PDF</strong>
                      </div>
                      <small>{item.title}</small>
                    </div>
                  </div>
                  <h2>{item.title}</h2>
                </article>
              );
            })}
          </div>
        ) : (
          <div className="catalogue-empty" style={{ textAlign: "center", padding: "60px 20px", color: "rgba(255,255,255,0.6)" }}>
            <p>No catalogues available at this time.</p>
          </div>
        )}

        {/* View More Button */}
        {hasMore && (
          <button
            className="catalogue-more"
            type="button"
            disabled={loadingMore}
            onClick={handleLoadMore}
          >
            {loadingMore ? "Loading..." : "View More"}
          </button>
        )}
      </section>

      {/* Download Modal */}
      <SharedInquiryModal
        isOpen={modalOpen}
        onClose={closeModal}
        type="catalogue"
        title="Download Catalogue"
        subtitle={`Fill in your details to access Abby Lighting's complete ${selectedCatalogue?.title} catalogue.`}
        reference={selectedCatalogue?.title}
        catalogueDownloadUrl={
          selectedCatalogue?.downloadUrl ||
          (selectedCatalogue?.id
            ? `${API_URL}/catalogues/${selectedCatalogue.id}/download-pdf`
            : undefined)
        }
        cataloguePdfUrl={selectedCatalogue?.pdfUrl || undefined}
      />
    </div>
  );
}