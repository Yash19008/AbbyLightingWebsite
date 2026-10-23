'use client';
import { API_URL, API_BASE } from '@/lib/config';


import React, { useState, useMemo, useEffect } from 'react';
import Link from 'next/link';
import { useRouter, usePathname } from 'next/navigation';
import DecorativeCard from './DecorativeCard';
import DecorativeFilterModal, { FilterState, FinishOption } from './DecorativeFilterModal';
import DecorativeToolbar from './DecorativeToolbar';
import DecorativeStickyWidget from './DecorativeStickyWidget';

import { Product } from './DecorativeCard';
import { DecorativeHeroData } from '@/types/decorative';
import { getDecorativeHero } from '@/lib/api/decorative-hero';

interface DecorativeListingClientProps {
  initialCategory?: string;
  initialHero?: DecorativeHeroData;
}

interface CategoryItem {
  id?: number | string;
  name: string;
  slug?: string;
  [key: string]: unknown;
}

export default function DecorativeListingClient({ initialCategory, initialHero }: DecorativeListingClientProps) {
  const [heroData, setHeroData] = useState<DecorativeHeroData | undefined>(initialHero);
  const [products, setProducts] = useState<Product[]>([]);
  const [featuredCategories, setFeaturedCategories] = useState<string[]>(['All']);
  const [rawCategories, setRawCategories] = useState<CategoryItem[]>([]);
  const [availableCollections, setAvailableCollections] = useState<string[]>([]);
  const [availableFinishes, setAvailableFinishes] = useState<FinishOption[]>([]);

  const [isLoading, setIsLoading] = useState(true);
  const [isPaginating, setIsPaginating] = useState(false);
  const [page, setPage] = useState(1);

  const [hasMore, setHasMore] = useState(false);

  const [isMobile, setIsMobile] = useState<boolean>(() => {
    if (typeof window !== 'undefined') {
      return window.innerWidth <= 768;
    }
    return false;
  });

  useEffect(() => {
    const handleResize = () => {
      const mobile = window.innerWidth <= 768;
      setIsMobile((prev) => {
        if (prev !== mobile) {
          setPage(1);
          return mobile;
        }
        return prev;
      });
    };

    handleResize();
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const [activeCategory, setActiveCategory] = useState(initialCategory || 'All');
  const [isLightOn, setIsLightOn] = useState(true);
  const [sortBy, setSortBy] = useState('new');
  const [isFilterModalOpen, setIsFilterModalOpen] = useState(false);
  const [activeFilters, setActiveFilters] = useState<FilterState>({
    category: initialCategory ? [initialCategory] : [],
    collection: [],
    finish: [],
  });

  const router = useRouter();
  const pathname = usePathname();

  const updateUrlCategory = (cat: string) => {
    if (typeof window === 'undefined') return;

    let urlValue = cat;
    if (cat !== 'All' && rawCategories.length > 0) {
      const matched = rawCategories.find(c => c.name === cat);
      if (matched && matched.slug) {
        urlValue = matched.slug;
      }
    }

    const params = new URLSearchParams(window.location.search);
    if (cat === 'All') {
      params.delete('category');
    } else {
      params.set('category', urlValue);
    }

    // Construct new URL string
    const newSearch = params.toString();
    const newUrl = newSearch ? `${pathname}?${newSearch}` : pathname;

    // Update URL without triggering a full page reload or scrolling
    window.history.replaceState(null, '', newUrl);
  };

  const handleTabChange = (cat: string) => {
    setActiveCategory(cat);
    setActiveFilters((prev) => ({
      ...prev,
      category: cat === 'All' ? [] : [cat]
    }));
    setPage(1);
    updateUrlCategory(cat);
  };

  useEffect(() => {
    if (initialCategory) {
      let resolvedName = initialCategory;
      if (rawCategories.length > 0) {
        const matched = rawCategories.find((c: CategoryItem) => c.slug === initialCategory || c.name === initialCategory);
        if (matched) {
          resolvedName = matched.name;
        }
      }
      if (activeCategory !== resolvedName) {
        setActiveCategory(resolvedName);
        setActiveFilters((prev) => ({ ...prev, category: [resolvedName] }));
        setPage(1);
      }
    } else if (activeCategory !== 'All' && !initialCategory) {
      setActiveCategory('All');
      setActiveFilters((prev) => ({ ...prev, category: [] }));
      setPage(1);
    }
  }, [initialCategory, rawCategories]);

  useEffect(() => {
    if (!initialHero) {
      getDecorativeHero().then((res) => {
        if (res?.data) {
          setHeroData(res.data);
        }
      });
    }
  }, [initialHero]);

  useEffect(() => {
    const fetchFilters = async () => {
      try {


        const [categoriesRes, collectionsRes, finishesRes] = await Promise.all([
          fetch(`${API_URL}/dec-categories`, { cache: 'no-store' }),
          fetch(`${API_URL}/dec-collections`, { cache: 'no-store' }),
          fetch(`${API_URL}/dec-finishes`, { cache: 'no-store' })
        ]);

        const categoriesData = await categoriesRes.json();
        if (categoriesData.data) {
          setRawCategories(categoriesData.data);
          const categoryNames = categoriesData.data.map((c: { name: string }) => c.name);
          const combined = Array.from(new Set(['All', ...categoryNames]));
          setFeaturedCategories(combined);
        }

        const collectionsData = await collectionsRes.json();
        if (collectionsData.data) {
          const collectionNames = collectionsData.data.map((c: { name: string }) => c.name);
          setAvailableCollections(collectionNames);
        }

        const finishesData = await finishesRes.json();
        if (finishesData.data) {
          setAvailableFinishes(finishesData.data);
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


        const perPage = isMobile ? 8 : 9;

        const params = new URLSearchParams({
          page: page.toString(),
          per_page: perPage.toString(),
          sort: sortBy,
        });

        if (activeFilters.category.length > 0) {
          params.append('category', activeFilters.category.join(','));
        }
        if (activeFilters.collection.length > 0) {
          params.append('collection', activeFilters.collection.join(','));
        }
        if (activeFilters.finish && activeFilters.finish.length > 0) {
          params.append('finish', activeFilters.finish.join(','));
        }

        const response = await fetch(`${API_URL}/dec-products?${params.toString()}`, { cache: 'no-store' });
        let data = await response.json();

        // Handle API success envelope if present
        if (data.success && data.data && data.data.data) {
          data = data.data;
        }

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
  }, [page, activeFilters, sortBy, isMobile]);

  const availableCategories = useMemo(() => {
    return featuredCategories.filter((c) => c !== 'All');
  }, [featuredCategories]);



  const toolbarRef = React.useRef<HTMLDivElement>(null);

  return (
    <div className="decorative-page">
      <DecorativeStickyWidget
        activeCategory={activeCategory}
        setActiveCategory={handleTabChange}
        categories={featuredCategories}
        activeFilters={activeFilters}
        setIsFilterModalOpen={setIsFilterModalOpen}
        sortBy={sortBy}
        setSortBy={(val) => {
          setSortBy(val);
          setPage(1);
        }}
        isLightOn={isLightOn}
        setIsLightOn={setIsLightOn}
        targetRef={toolbarRef}
      />

      <section className="decorative-hero">
        <picture className="decorative-hero-picture">
          {heroData?.image_mobile ? (
            <source media="(max-width: 640px)" srcSet={heroData.image_mobile} />
          ) : heroData?.image_tablet ? (
            <source media="(max-width: 640px)" srcSet={heroData.image_tablet} />
          ) : heroData?.image_desktop ? (
            <source media="(max-width: 640px)" srcSet={heroData.image_desktop} />
          ) : null}
          {heroData?.image_tablet ? (
            <source media="(max-width: 1024px)" srcSet={heroData.image_tablet} />
          ) : heroData?.image_desktop ? (
            <source media="(max-width: 1024px)" srcSet={heroData.image_desktop} />
          ) : null}
          <img
            src={heroData?.image_desktop || '/images/decorative/hero.png'}
            alt={`${heroData?.heading || 'Decorative'} ${heroData?.styled_heading || 'Lights'}`}
            className="decorative-hero-img"
          />
        </picture>

        <p className="site-breadcrumb site-breadcrumb--on-dark decorative-breadcrumb">
          <Link href="/">Home</Link> / <Link href="/decorative-lights">Decorative</Link>
        </p>
        <div className="decorative-shell">
          <h1>
            {heroData?.heading || 'Decorative'}
            {heroData?.styled_heading ? (
              <>
                {' '}
                <em>{heroData.styled_heading}</em>
              </>
            ) : null}
          </h1>
          {heroData?.description && (
            <p className="decorative-intro">
              {heroData.description}
            </p>
          )}
        </div>
      </section>

      <section className="decorative-catalogue">
        <div className="decorative-shell">
          <div ref={toolbarRef}>
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
          </div>

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
                  order={index % (isMobile ? 2 : 3)} // for staggered animation
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
                {isPaginating ? 'LOADING...' : 'VIEW MORE'}
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
          <h2 className="decorative-reveal is-visible" suppressHydrationWarning>Didn&apos;t find what you&apos;re looking for?</h2>
          <p className="decorative-reveal is-visible" suppressHydrationWarning>
            Our lighting advisors can help you choose the right piece, finish
            and configuration for your space or project.
          </p>
          <a className="decorative-reveal is-visible" href="https://wa.me/917304623220" suppressHydrationWarning>
            CHAT WITH US
          </a>
        </div>
      </section>

      {isFilterModalOpen && (
        <DecorativeFilterModal
          initialFilters={activeFilters}
          availableCategories={availableCategories}
          availableCollections={availableCollections}
          availableFinishes={availableFinishes}
          onApply={(filters) => {
            setActiveFilters(filters);
            setPage(1);
            if (filters.category.length === 1) {
              setActiveCategory(filters.category[0]);
              updateUrlCategory(filters.category[0]);
            } else {
              setActiveCategory('All');
              updateUrlCategory('All');
            }
          }}
          onClose={() => setIsFilterModalOpen(false)}
        />
      )}
    </div>
  );
}
