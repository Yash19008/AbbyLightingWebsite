"use client";

import React, { useState, useRef, useEffect } from "react";
import Link from "next/link";

interface CategoryItem {
  id: string;
  label: string;
  slug?: string;
}

interface StoryItem {
  tag: string;
  title: string;
  categorySlug: string;
  image: string;
  link: string;
  order: number;
  views: number;
  publishedAt?: string;
}

const SORT_OPTIONS = [
  { id: "popular", label: "Most popular" },
  { id: "new", label: "New products" },
  { id: "az", label: "Alphabetical A-Z" },
  { id: "za", label: "Alphabetical Z-A" },
];

export default function JournalSection() {
  const [categories, setCategories] = useState<CategoryItem[]>([]);
  const [stories, setStories] = useState<StoryItem[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [isLoadingMore, setIsLoadingMore] = useState(false);
  const [page, setPage] = useState(1);
  const [hasMore, setHasMore] = useState(false);
  const [activeCategory, setActiveCategory] = useState("all");
  const [activeSort, setActiveSort] = useState("popular");
  const [isFilterOpen, setIsFilterOpen] = useState(false);
  const [isSortOpen, setIsSortOpen] = useState(false);
  const sortRef = useRef<HTMLDivElement>(null);
  const filterRef = useRef<HTMLDivElement>(null);

  const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";

  // Close sort & filter dropdowns on outside click
  useEffect(() => {
    function handleClickOutside(event: MouseEvent) {
      if (sortRef.current && !sortRef.current.contains(event.target as Node)) {
        setIsSortOpen(false);
      }
      if (filterRef.current && !filterRef.current.contains(event.target as Node)) {
        setIsFilterOpen(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  // Fetch dynamic categories on mount
  useEffect(() => {
    async function loadCategories() {
      try {
        const catRes = await fetch(`${API_URL}/api/blog-categories?only_used=1`);
        if (catRes.ok) {
          const catJson = await catRes.json();
          if (catJson.success && Array.isArray(catJson.data)) {
            const dynamicCats: CategoryItem[] = [
              { id: "all", label: "All" },
              ...catJson.data.map((c: { slug: string; name: string }) => ({
                id: c.slug,
                label: c.name ? c.name.charAt(0).toUpperCase() + c.name.slice(1) : c.slug,
                slug: c.slug,
              })),
            ];
            setCategories(dynamicCats);
          }
        }
      } catch (err) {
        console.error("Error loading blog categories:", err);
      }
    }
    loadCategories();
  }, [API_URL]);

  // Fetch dynamic blogs on filter / sort change (page 1)
  useEffect(() => {
    let isMounted = true;
    async function loadBlogs() {
      try {
        setIsLoading(true);
        setPage(1);
        const params = new URLSearchParams({
          page: "1",
          per_page: "6",
          sort: activeSort,
        });
        if (activeCategory !== "all") {
          params.set("category", activeCategory);
        }

        const res = await fetch(`${API_URL}/api/blogs?${params.toString()}`);
        if (res.ok && isMounted) {
          const json = await res.json();
          if (json.success && Array.isArray(json.data)) {
            const mapped = json.data.map(
              (b: {
                title: string;
                slug: string;
                featured_image?: string;
                category?: { name: string; slug: string };
                sort_order?: number;
                views_count?: number;
                published_at?: string;
              }, idx: number) => ({
                tag: b.category?.name || "Story",
                title: b.title,
                categorySlug: b.category?.slug || "general",
                image: b.featured_image
                  ? `${API_URL}/uploads/blogs/${b.featured_image}`
                  : "/images/reference/project-atlas.png",
                link: `/blogs/${b.slug}`,
                order: b.sort_order ?? idx,
                views: b.views_count ?? 0,
                publishedAt: b.published_at,
              })
            );
            setStories(mapped);
            setHasMore(json.pagination?.has_more ?? false);
          } else {
            setStories([]);
            setHasMore(false);
          }
        }
      } catch (error) {
        console.error("Error loading blogs:", error);
      } finally {
        if (isMounted) setIsLoading(false);
      }
    }

    loadBlogs();
    return () => {
      isMounted = false;
    };
  }, [API_URL, activeCategory, activeSort]);

  // Handle server-side load more
  const handleLoadMore = async () => {
    if (isLoadingMore || !hasMore) return;
    try {
      setIsLoadingMore(true);
      const nextPage = page + 1;
      const params = new URLSearchParams({
        page: String(nextPage),
        per_page: "6",
        sort: activeSort,
      });
      if (activeCategory !== "all") {
        params.set("category", activeCategory);
      }

      const res = await fetch(`${API_URL}/api/blogs?${params.toString()}`);
      if (res.ok) {
        const json = await res.json();
        if (json.success && Array.isArray(json.data) && json.data.length > 0) {
          const mapped = json.data.map(
            (b: {
              title: string;
              slug: string;
              featured_image?: string;
              category?: { name: string; slug: string };
              sort_order?: number;
              views_count?: number;
              published_at?: string;
            }, idx: number) => ({
              tag: b.category?.name || "Story",
              title: b.title,
              categorySlug: b.category?.slug || "general",
              image: b.featured_image
                ? `${API_URL}/uploads/blogs/${b.featured_image}`
                : "/images/reference/project-atlas.png",
              link: `/blogs/${b.slug}`,
              order: b.sort_order ?? idx,
              views: b.views_count ?? 0,
              publishedAt: b.published_at,
            })
          );
          setStories((prev) => [...prev, ...mapped]);
          setPage(nextPage);
          setHasMore(json.pagination?.has_more ?? false);
        } else {
          setHasMore(false);
        }
      }
    } catch (err) {
      console.error("Error loading more blogs:", err);
    } finally {
      setIsLoadingMore(false);
    }
  };

  if (!isLoading && stories.length === 0 && activeCategory === "all") {
    return null;
  }

  if (stories.length === 0) {
    return (
      <section className="journal-section">
        <div className="insp-shell" style={{ textAlign: "center", padding: "120px 20px" }}>
          <h2 className="section-title">Guides, trends &amp; stories</h2>
          <p style={{ marginTop: "20px", color: "#888", fontSize: "1.1rem" }}>
            No stories available at the moment.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section className="journal-section">
      <div className="insp-shell">
        <div className="journal-head">
          <h2 className="section-title">Guides, trends &amp; stories</h2>
          
          <div className="journal-actions">
            {/* Filter By */}
            {categories.length > 1 && (
              <div className="filter-wrap journal-filter-wrap" ref={filterRef}>
                <button
                  type="button"
                  className={`filter-button ${isFilterOpen ? 'is-open' : ''}`}
                  onClick={() => {
                    setIsFilterOpen(!isFilterOpen);
                    setIsSortOpen(false);
                  }}
                  aria-expanded={isFilterOpen}
                >
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4z"/>
                  </svg>
                  <span>FILTER BY</span>
                </button>
                {isFilterOpen && (
                  <div className="filter-menu">
                    {categories.map((cat) => (
                      <button
                        key={cat.id}
                        type="button"
                        className={activeCategory === cat.id ? "is-active" : ""}
                        onClick={() => {
                          setActiveCategory(cat.id);
                          setIsFilterOpen(false);
                        }}
                      >
                        {cat.label}
                      </button>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* Sort By Dropdown */}
            <div className="sort-wrap journal-sort-wrap" ref={sortRef}>
              <button
                type="button"
                className={`sort-button ${isSortOpen ? 'is-open' : ''}`}
                onClick={() => {
                  setIsSortOpen(!isSortOpen);
                  setIsFilterOpen(false);
                }}
                aria-expanded={isSortOpen}
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                </svg>
                <span>Sort By</span>
              </button>
              {isSortOpen && (
                <div className="sort-menu">
                  {SORT_OPTIONS.map((opt) => (
                    <button
                      key={opt.id}
                      type="button"
                      className={activeSort === opt.id ? "is-active" : ""}
                      onClick={() => {
                        setActiveSort(opt.id);
                        setIsSortOpen(false);
                      }}
                    >
                      {opt.label}
                    </button>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>

        {categories.length > 1 && (
          <div className="journal-tabs">
            {categories.map((cat) => (
              <button
                key={cat.id}
                type="button"
                className={activeCategory === cat.id ? "active" : ""}
                onClick={() => {
                  setActiveCategory(cat.id);
                }}
              >
                {cat.label}
              </button>
            ))}
          </div>
        )}

        {isLoading ? (
          <div className="story-grid story-grid-skeleton" aria-busy="true" aria-label="Loading stories">
            {[0, 1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="story-card story-card-skel" style={{ pointerEvents: "none" }}>
                <div
                  style={{
                    height: 300,
                    position: "relative",
                    overflow: "hidden",
                    background: "linear-gradient(90deg, #ececec 0%, #f5f5f5 40%, #ffffff 50%, #f5f5f5 60%, #ececec 100%)",
                    backgroundSize: "1200px 100%",
                    animation: "insp-shimmer 1.6s ease-in-out infinite",
                  }}
                />
                <div className="story-copy">
                  <div
                    style={{
                      height: 14,
                      width: "30%",
                      marginBottom: 10,
                      borderRadius: 3,
                      background: "linear-gradient(90deg, #e4e4e4 0%, #efefef 40%, #f8f8f8 50%, #efefef 60%, #e4e4e4 100%)",
                      backgroundSize: "1200px 100%",
                      animation: "insp-shimmer 1.6s ease-in-out infinite",
                    }}
                  />
                  <div
                    style={{
                      height: 20,
                      width: "85%",
                      marginBottom: 8,
                      borderRadius: 3,
                      background: "linear-gradient(90deg, #e4e4e4 0%, #efefef 40%, #f8f8f8 50%, #efefef 60%, #e4e4e4 100%)",
                      backgroundSize: "1200px 100%",
                      animation: "insp-shimmer 1.6s ease-in-out infinite",
                    }}
                  />
                  <div
                    style={{
                      height: 20,
                      width: "60%",
                      borderRadius: 3,
                      background: "linear-gradient(90deg, #e4e4e4 0%, #efefef 40%, #f8f8f8 50%, #efefef 60%, #e4e4e4 100%)",
                      backgroundSize: "1200px 100%",
                      animation: "insp-shimmer 1.6s ease-in-out infinite",
                    }}
                  />
                </div>
              </div>
            ))}
          </div>
        ) : stories.length > 0 ? (
          <div className="story-grid">
            {stories.map((story, idx) => (
              <Link
                key={`${story.title}-${idx}`}
                href={story.link}
                className="story-card"
              >
                <img
                  src={story.image}
                  alt={story.title}
                  onError={(e) => {
                    (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
                  }}
                />
                <div className="story-copy">
                  <span>{story.tag}</span>
                  <h3>{story.title}</h3>
                </div>
              </Link>
            ))}
          </div>
        ) : (
          <div style={{ textAlign: "center", padding: "40px 0", color: "rgba(255,255,255,0.6)" }}>
            <p>No stories found for this category.</p>
          </div>
        )}

        {hasMore && (
          <button
            type="button"
            className="view-more stories-view-more"
            disabled={isLoadingMore}
            onClick={handleLoadMore}
          >
            {isLoadingMore ? "Loading..." : "View more"}
          </button>
        )}
      </div>
    </section>
  );
}
