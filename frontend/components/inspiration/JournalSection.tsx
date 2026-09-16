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

const DEFAULT_CATEGORIES: CategoryItem[] = [
  { id: "all", label: "All" }
];

const DEFAULT_STORIES: StoryItem[] = [];

const SORT_OPTIONS = [
  { id: "popular", label: "Most popular" },
  { id: "new", label: "New products" },
  { id: "az", label: "Alphabetical A-Z" },
  { id: "za", label: "Alphabetical Z-A" },
];

export default function JournalSection() {
  const [categories, setCategories] = useState<CategoryItem[]>(DEFAULT_CATEGORIES);
  const [stories, setStories] = useState<StoryItem[]>(DEFAULT_STORIES);
  const [activeCategory, setActiveCategory] = useState("all");
  const [activeSort, setActiveSort] = useState("popular");
  const [isFilterOpen, setIsFilterOpen] = useState(false);
  const [isSortOpen, setIsSortOpen] = useState(false);
  const [visibleCount, setVisibleCount] = useState(6);
  const sortRef = useRef<HTMLDivElement>(null);
  const filterRef = useRef<HTMLDivElement>(null);

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

  // Fetch dynamic categories and blogs from backend API
  useEffect(() => {
    const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";

    async function loadData() {
      try {
        const [catRes, blogRes] = await Promise.all([
          fetch(`${API_URL}/api/blog-categories?only_used=1`),
          fetch(`${API_URL}/api/blogs`),
        ]);

        let loadedStories = DEFAULT_STORIES;

        if (blogRes.ok) {
          const blogJson = await blogRes.json();
          if (blogJson.success && Array.isArray(blogJson.data) && blogJson.data.length > 0) {
            loadedStories = blogJson.data.map(
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
            setStories(loadedStories);
          }
        }

        if (catRes.ok) {
          const catJson = await catRes.json();
          if (catJson.success && Array.isArray(catJson.data) && catJson.data.length > 0) {
            // Filter categories to only those that are actually present in loaded stories
            const usedCategorySlugs = new Set(loadedStories.map((s) => s.categorySlug));

            const dynamicCats: CategoryItem[] = [
              { id: "all", label: "All" },
              ...catJson.data
                .filter((c: { slug: string; blogs_count?: number }) => {
                  return (c.blogs_count && c.blogs_count > 0) || usedCategorySlugs.has(c.slug);
                })
                .map((c: { slug: string; name: string }) => ({
                  id: c.slug,
                  label: c.name ? c.name.charAt(0).toUpperCase() + c.name.slice(1) : c.slug,
                  slug: c.slug,
                })),
            ];

            setCategories(dynamicCats);
          } else {
            // If API categories are empty, extract categories directly from loaded stories
            const uniqueCatsMap = new Map<string, string>();
            loadedStories.forEach((s) => {
              if (s.categorySlug && s.tag) {
                uniqueCatsMap.set(s.categorySlug, s.tag);
              }
            });

            const derivedCats: CategoryItem[] = [
              { id: "all", label: "All" },
              ...Array.from(uniqueCatsMap.entries()).map(([slug, label]) => ({
                id: slug,
                label: label ? label.charAt(0).toUpperCase() + label.slice(1) : slug,
                slug: slug,
              })),
            ];

            setCategories(derivedCats);
          }
        }
      } catch (error) {
        console.error("Error loading blog data for inspiration page:", error);
      }
    }

    loadData();
  }, []);

  let filteredStories = stories.filter(
    (story) => activeCategory === "all" || story.categorySlug === activeCategory
  );

  filteredStories = [...filteredStories].sort((a, b) => {
    if (activeSort === "az") return a.title.localeCompare(b.title);
    if (activeSort === "za") return b.title.localeCompare(a.title);
    if (activeSort === "new") {
      if (a.publishedAt && b.publishedAt) {
        return new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime();
      }
      return b.order - a.order;
    }
    // Most popular
    return b.views - a.views || a.order - b.order;
  });

  const displayedStories = filteredStories.slice(0, visibleCount);

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
            {/* Filter By (Dropdown on mobile / small screens) */}
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
                        setVisibleCount(6);
                        setIsFilterOpen(false);
                      }}
                    >
                      {cat.label}
                    </button>
                  ))}
                </div>
              )}
            </div>

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

        <div className="journal-tabs">
          {categories.map((cat) => (
            <button
              key={cat.id}
              type="button"
              className={activeCategory === cat.id ? "active" : ""}
              onClick={() => {
                setActiveCategory(cat.id);
                setVisibleCount(6);
              }}
            >
              {cat.label}
            </button>
          ))}
        </div>

        <div className="story-grid">
          {displayedStories.map((story, idx) => (
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

        {visibleCount < filteredStories.length && (
          <button
            type="button"
            className="view-more stories-view-more"
            onClick={() => setVisibleCount((prev) => Math.min(prev + 6, filteredStories.length))}
          >
            View more
          </button>
        )}
      </div>
    </section>
  );
}
