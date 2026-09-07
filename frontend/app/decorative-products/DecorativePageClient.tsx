"use client";

import { useState, useEffect, useRef, useMemo } from "react";

// ─── Interfaces ───────────────────────────────────────────────────────────────

interface Swatch {
  name: string;
  colour: string;
}

interface Product {
  key: string;
  name: string;
  type: string;
  category: string;
  collection?: string; // optional — not used for filtering
  href: string;
  finishes: string[];
  palette: Swatch[];
  isNew?: boolean;
  image_url?: string | null; // real image from backend
  light_on_image_url?: string | null; // real light on image from backend
}

// ─── Static fallback data ─────────────────────────────────────────────────────

const PALETTES = {
  Quarry: [{ name: "White", colour: "#f2f0ea" }, { name: "Black", colour: "#1f1f1f" }, { name: "Terra", colour: "#9c482a" }],
  Symphony: [{ name: "Coral", colour: "#c0392b" }, { name: "Amber", colour: "#e6b422" }, { name: "Teal", colour: "#1f7a7a" }],
  Neoma: [{ name: "Brass", colour: "#c9a24b" }, { name: "Navy", colour: "#263541" }],
};

const STATIC_PRODUCTS: Product[] = [
  { key: "cymbal", name: "Cymbal", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry, isNew: true },
  { key: "dew", name: "Dew", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "apex", name: "Apex", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "node", name: "Node", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "seam", name: "Seam", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "orb", name: "Orb", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "canopy", name: "Canopy", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "turret", name: "Turret", type: "Pendant", category: "Pendant Light", collection: "Quarry", href: "/product-detail", finishes: ["White", "Black", "Terra"], palette: PALETTES.Quarry },
  { key: "symphonyiv", name: "Symphony IV", type: "Pendant", category: "Pendant Light", collection: "Symphony", href: "/product-detail/symphony-iv", finishes: ["Colour"], palette: PALETTES.Symphony },
  { key: "aria", name: "Aria", type: "Pendant", category: "Pendant Light", collection: "Symphony", href: "/product-detail", finishes: ["Colour"], palette: PALETTES.Symphony },
  { key: "cadence", name: "Cadence", type: "Pendant", category: "Pendant Light", collection: "Symphony", href: "/product-detail", finishes: ["Colour"], palette: PALETTES.Symphony },
  { key: "wallmount", name: "Wall Mount", type: "Wall", category: "Wall Light", collection: "Neoma", href: "/product-detail", finishes: ["Brass"], palette: PALETTES.Neoma },
];

const CATEGORY_META = {
  All: { title: "Decorative", accent: "Lights", intro: "Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order." },
  Pendant: { title: "Pendant", accent: "Lights", intro: "Sculptural suspensions across the Symphony, Quarry and Neoma collections — from cast concrete discs to colour-blocked forms and lunar orbs, made to order." },
  Wall: { title: "Wall", accent: "Lights", intro: "Wall lights and sconces that wash a surface in warmth — folded concrete, ceramic and metal forms, made to order in a range of finishes." },
  Floor: { title: "Floor", accent: "Lamps", intro: "Standing sculptures of light — columns, pillars and reeds in cast stone and metal that anchor a corner or a reading nook." },
  Table: { title: "Table", accent: "Lamps", intro: "Quiet companions for a console, bedside or desk — pebbles, orbs and blooms that glow softly, made to order." },
};

// ─── Helpers ──────────────────────────────────────────────────────────────────

function makeSvgUrl(product: Product, swatch: Swatch, viewIndex: number, lit: boolean) {
  const bg = lit ? "#faf8f4" : "#efece7";
  const r = [44, 58, 70][viewIndex];
  const cy = [118, 140, 150][viewIndex];
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 350"><rect width="300" height="350" fill="${bg}"/><circle cx="150" cy="${cy}" r="${r}" fill="${swatch.colour}"/><circle cx="150" cy="${cy}" r="${r}" fill="none" stroke="rgba(0,0,0,.14)"/><text x="150" y="246" text-anchor="middle" font-family="Poppins,Arial" font-weight="500" font-size="22" fill="#1a1c1d">${product.name}</text><text x="150" y="274" text-anchor="middle" font-family="Inter,Arial" font-size="12" letter-spacing="1.5" fill="#6f6f6f">${swatch.name.toUpperCase()} · VIEW ${viewIndex + 1}</text></svg>`;
  return `data:image/svg+xml,${encodeURIComponent(svg)}`;
}

// ─── ProductCard ──────────────────────────────────────────────────────────────

function ProductCard({ product, lightOn, order }: { product: Product; lightOn: boolean; order: number }) {
  const [swatchIdx, setSwatchIdx] = useState(0);
  const [viewIdx, setViewIdx] = useState(0);
  const [prevImgs, setPrevImgs] = useState<{ on: string; off: string } | null>(null);
  const [swatchReady, setSwatchReady] = useState(true);
  const rafRef = useRef<number | null>(null);
  const timerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => () => {
    if (rafRef.current !== null) cancelAnimationFrame(rafRef.current);
    if (timerRef.current) clearTimeout(timerRef.current);
  }, []);

  const swatch = product.palette[swatchIdx] ?? { name: "Default", colour: "#cccccc" };

  const imgUrl = (si: number, vi: number, lit: boolean): string => {
    const sw = product.palette[si] ?? swatch;
    const swGallery: string[] = (sw as any)?.gallery_images ?? [];

    // 1. Slide 0: Main Product Images (Light OFF Primary Image vs Light ON Image)
    if (vi === 0) {
      if (lit) {
        if (product.light_on_image_url) return product.light_on_image_url;
        if (product.key && product.key !== "aria") return `/images/decorative/${product.key}-on.png`;
      } else {
        if (product.image_url) return product.image_url;
        if (product.key && product.key !== "aria") return `/images/decorative/${product.key}-off.png`;
      }
    }

    // 2. Slide > 0: Color Variation Gallery Images for selected color
    if (swGallery.length > 0) {
      const idx = Math.min(vi - 1, swGallery.length - 1);
      return swGallery[idx];
    }

    // 3. Fallbacks
    if (lit && product.light_on_image_url) return product.light_on_image_url;
    if (!lit && product.image_url) return product.image_url;
    if (product.key && product.key !== "aria") return `/images/decorative/${product.key}-${lit ? "on" : "off"}.png`;

    return makeSvgUrl(product, sw, vi, lit);
  };

  const current = { on: imgUrl(swatchIdx, viewIdx, true), off: imgUrl(swatchIdx, viewIdx, false) };

  const swGallery: string[] = (swatch as any)?.gallery_images ?? [];
  const totalViews = swGallery.length > 0 ? swGallery.length + 1 : 1;

  const navigate = (delta: number) => change(swatchIdx, (viewIdx + delta + totalViews) % totalViews);

  const change = (newSwatch: number, newView: number) => {
    if (newSwatch === swatchIdx && newView === viewIdx) return;
    if (rafRef.current !== null) cancelAnimationFrame(rafRef.current);
    if (timerRef.current) clearTimeout(timerRef.current);
    setPrevImgs(current);
    setSwatchReady(false);
    setSwatchIdx(newSwatch);
    setViewIdx(newView);
    rafRef.current = requestAnimationFrame(() => {
      rafRef.current = requestAnimationFrame(() => setSwatchReady(true));
    });
    timerRef.current = setTimeout(() => setPrevImgs(null), 280);
  };

  const handleSwatchClick = (sIdx: number) => {
    const targetSw = product.palette[sIdx];
    const targetGallery = (targetSw as any)?.gallery_images ?? [];
    // Jumps to Slide 1 (1st color image) if gallery exists, else Slide 0
    const targetView = targetGallery.length > 0 ? (sIdx === swatchIdx && viewIdx > 0 ? viewIdx : 1) : 0;
    change(sIdx, targetView);
  };

  const transitionClass = prevImgs
    ? swatchReady ? "is-swatch-transition-ready" : "is-swatch-transition-start"
    : "";

  return (
    <div
      className="decorative-grid-item-motion"
      style={{ "--filter-delay": `${order * 40}ms` } as React.CSSProperties}
    >
      <article
        className="decorative-card decorative-reveal"
        style={{ "--card-order": order % 3, "--light-delay": `${order * 50}ms` } as React.CSSProperties}
      >
        <div className={`decorative-card-image ${lightOn ? "is-lit" : "is-unlit"} ${transitionClass}`}>
          <a className="decorative-card-main-link" href={product.href} aria-label={`View ${product.name}`}>
            <img className="decorative-product-image is-current is-light-off" src={current.off} alt="" aria-hidden="true" />
            <img className="decorative-product-image is-current is-light-on" src={current.on} alt={`${product.name} in ${swatch.name}, ${lightOn ? "light on" : "light off"}`} />
            {prevImgs && <>
              <img className="decorative-product-image is-previous is-light-off" src={prevImgs.off} alt="" aria-hidden="true" />
              <img className="decorative-product-image is-previous is-light-on" src={prevImgs.on} alt="" aria-hidden="true" />
            </>}
          </a>
          {product.isNew && <span className="decorative-badge">New</span>}
          <button type="button" className="decorative-card-arrow decorative-card-prev" aria-label={`Previous ${product.name} image`} onClick={() => navigate(-1)}>
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
          <button type="button" className="decorative-card-arrow decorative-card-next" aria-label={`Next ${product.name} image`} onClick={() => navigate(1)}>
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
          <span className="decorative-gallery-dots">
            {Array.from({ length: totalViews }).map((_, i) => (
              <button key={i} type="button" className={viewIdx === i ? "active" : ""} aria-label={`Show ${product.name} view ${i + 1}`} onClick={() => change(swatchIdx, i)} />
            ))}
          </span>
        </div>
        <div className="decorative-card-copy">
          <h3>{product.name}</h3>
          <p>{product.category}</p>
          <div className="decorative-swatches" aria-label={`${product.name} finishes`}>
            {product.palette.map((sw, i) => (
              <button
                key={sw.name}
                type="button"
                className={swatchIdx === i ? "active" : ""}
                style={{ background: sw.colour }}
                aria-label={`Select ${sw.name}`}
                title={sw.name}
                onClick={() => handleSwatchClick(i)}
              />
            ))}
          </div>
          {product.collection && (
            <span className="decorative-collection">{product.collection} Collection</span>
          )}
        </div>
      </article>
    </div>
  );
}

// ─── FilterAccordion ──────────────────────────────────────────────────────────

function FilterAccordion({ title, options, selected, onToggle, onClear, id }: {
  title: string;
  options: string[];
  selected: Set<string>;
  onToggle: (v: string) => void;
  onClear: () => void;
  id: string;
}) {
  const [open, setOpen] = useState(true);
  const allChecked = selected.size === 0;
  return (
    <section className="decorative-filter-accordion">
      <button
        type="button"
        className="decorative-filter-accordion-toggle"
        aria-expanded={open}
        aria-controls={id}
        onClick={() => setOpen(o => !o)}
      >
        <span>{title}</span>
        <i className="decorative-filter-chevron" aria-hidden="true" />
      </button>
      <div className="decorative-filter-options" id={id} hidden={!open}>
        <label className="decorative-filter-option">
          <input type="checkbox" checked={allChecked} onChange={onClear} />
          <span>All</span>
        </label>
        {options.map(opt => (
          <label key={opt} className="decorative-filter-option">
            <input
              type="checkbox"
              checked={selected.has(opt)}
              onChange={() => onToggle(opt)}
            />
            <span>{opt}</span>
          </label>
        ))}
      </div>
    </section>
  );
}

// ─── Types ────────────────────────────────────────────────────────────────────

type Category = string;
type Finish = "All" | string;
type SortBy = "popular" | "new" | "name_asc" | "name_desc";
type GridState = "settled" | "exiting" | "entering";

type FilterSets = { category: Set<string>; finish: Set<string>; collection: Set<string> };
function emptyFilters(): FilterSets { return { category: new Set(), finish: new Set(), collection: new Set() }; }
function toggleSet(s: Set<string>, v: string): Set<string> {
  const n = new Set(s);
  n.has(v) ? n.delete(v) : n.add(v);
  return n;
}

// ─── Merge helper: API products first, then static ones not in API ─────────────

function mergeProducts(apiProducts: Product[]): Product[] {
  const apiKeys = new Set(apiProducts.map(p => p.key));
  const staticOnly = STATIC_PRODUCTS.filter(p => !apiKeys.has(p.key));
  return [...apiProducts, ...staticOnly];
}

// ─── Main Page ────────────────────────────────────────────────────────────────

const API_BASE = process.env.NEXT_PUBLIC_API_URL ?? "http://127.0.0.1:8000";

export default function DecorativePageClient() {
  const [activeCategory, setActiveCategory] = useState<Category>("All");
  const [gridCategory, setGridCategory] = useState<Category>("All");
  const [gridState, setGridState] = useState<GridState>("settled");
  const [selectedFinish, setSelectedFinish] = useState<Finish>("All");
  const [selectedCollection, setSelectedCollection] = useState<string>("All");
  const [sortBy, setSortBy] = useState<SortBy>("popular");
  const [lightOn, setLightOn] = useState(true);
  const [filterOpen, setFilterOpen] = useState(false);
  const [visibleCount, setVisibleCount] = useState(9);

  // API products state
  const [apiProducts, setApiProducts] = useState<Product[]>([]);

  // Merged product list: strictly dynamic API products only
  const PRODUCTS = apiProducts;

  // Derive category options dynamically from products (only categories with at least one active product)
  const tabCategories = useMemo(() => {
    const all = new Set<string>();
    PRODUCTS.forEach(p => {
      const catName = p.category || p.type;
      if (catName) {
        all.add(catName);
      }
    });
    return [...all].sort((a, b) => a.localeCompare(b));
  }, [PRODUCTS]);

  // Derive collection options dynamically from products (only collections with at least one active product)
  const collectionOptions = useMemo(() => {
    const all = new Set<string>();
    PRODUCTS.forEach(p => {
      if (p.collection) {
        all.add(p.collection);
      }
    });
    return [...all].sort((a, b) => a.localeCompare(b));
  }, [PRODUCTS]);

  // Derive finish options dynamically from merged products
  const finishOptions = useMemo(() => {
    const all = new Set<string>();
    PRODUCTS.forEach(p => p.finishes.forEach(f => all.add(f)));
    return [...all].sort();
  }, [PRODUCTS]);

  // Draft filter state for checkbox modal
  const [draft, setDraft] = useState<FilterSets>(emptyFilters);

  const tabsRef = useRef<Record<string, HTMLButtonElement | null>>({});
  const exitTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const enterTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const [tabStyle, setTabStyle] = useState({ x: 0, width: 80 });

  // Fetch API products on mount
  useEffect(() => {
    fetch(`${API_BASE}/api/decorative-products`)
      .then(r => r.ok ? r.json() : Promise.reject(r.status))
      .then(json => {
        if (json.success && Array.isArray(json.data)) {
          const shaped: Product[] = json.data.map((p: Product) => ({
            ...p,
            palette: p.palette?.length ? p.palette : [{ name: "Default", colour: "#cccccc" }],
            finishes: p.finishes?.length ? p.finishes : ["Default"],
          }));
          setApiProducts(shaped);
        }
      })
      .catch(() => { });
  }, []);

  // Cleanup timers
  useEffect(() => () => {
    if (exitTimer.current) clearTimeout(exitTimer.current);
    if (enterTimer.current) clearTimeout(enterTimer.current);
  }, []);

  // Tab indicator position
  useEffect(() => {
    const update = () => {
      const btn = tabsRef.current[activeCategory];
      if (btn) setTabStyle({ x: btn.offsetLeft, width: btn.offsetWidth });
    };
    update();
    window.addEventListener("resize", update);
    return () => window.removeEventListener("resize", update);
  }, [activeCategory]);

  // Body scroll lock when filter open
  useEffect(() => {
    document.body.style.overflow = filterOpen ? "hidden" : "";
    const onKey = (e: KeyboardEvent) => { if (e.key === "Escape") setFilterOpen(false); };
    window.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", onKey);
    };
  }, [filterOpen]);

  // IntersectionObserver for reveal animations
  useEffect(() => {
    const observer = new IntersectionObserver(
      entries => entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      }),
      { threshold: 0.08, rootMargin: "0px 0px -45px" }
    );
    document.querySelectorAll(".decorative-reveal").forEach(el => observer.observe(el));
    return () => observer.disconnect();
  }, [gridCategory, selectedFinish, sortBy, visibleCount, PRODUCTS]);

  // Floating toolbar — appears when native toolbar scrolls out of view
  useEffect(() => {
    const toolbar = document.querySelector<HTMLElement>(".decorative-toolbar");
    const sourceTools = toolbar?.querySelector<HTMLElement>(".decorative-tools");
    if (!toolbar || !sourceTools) return;
    if (document.querySelector(".decorative-floating-tools")) return;

    const floating = document.createElement("aside");
    floating.className = "decorative-floating-tools";
    floating.setAttribute("aria-label", "Product controls");
    floating.setAttribute("aria-hidden", "true");
    floating.setAttribute("inert", "");
    floating.appendChild(sourceTools.cloneNode(true));
    document.body.appendChild(floating);

    const sourceFilter = sourceTools.querySelector<HTMLButtonElement>(".decorative-filter-button");
    const sourceSort = sourceTools.querySelector<HTMLSelectElement>(".decorative-sort select");
    const sourceLight = sourceTools.querySelector<HTMLInputElement>(".decorative-light-toggle input");
    const floatingFilter = floating.querySelector<HTMLButtonElement>(".decorative-filter-button");
    const floatingSort = floating.querySelector<HTMLSelectElement>(".decorative-sort select");
    const floatingLight = floating.querySelector<HTMLInputElement>(".decorative-light-toggle input");

    const sync = () => {
      if (sourceSort && floatingSort) floatingSort.value = sourceSort.value;
      if (sourceLight && floatingLight) floatingLight.checked = sourceLight.checked;
      const lit = sourceLight?.checked !== false;
      floating.querySelector(".decorative-light-toggle")?.classList.toggle("is-on", lit);
      floating.querySelector(".decorative-light-toggle")?.classList.toggle("is-off", !lit);
      const dt = floating.querySelector(".decorative-light-desktop");
      const mt = floating.querySelector(".decorative-light-mobile");
      if (dt) dt.textContent = lit ? "Light on" : "Light off";
      if (mt) mt.textContent = lit ? "On" : "Off";
    };

    floatingFilter?.addEventListener("click", () => sourceFilter?.click());
    floatingSort?.addEventListener("change", () => {
      if (!sourceSort) return;
      sourceSort.value = floatingSort!.value;
      sourceSort.dispatchEvent(new Event("change", { bubbles: true }));
      requestAnimationFrame(sync);
    });
    floatingLight?.addEventListener("change", () => {
      if (!sourceLight || sourceLight.checked === floatingLight!.checked) return;
      sourceLight.click();
      requestAnimationFrame(sync);
    });
    sourceTools.addEventListener("change", () => requestAnimationFrame(sync));

    const header = document.querySelector<HTMLElement>(".sitehead");
    const headerH = () => Math.ceil(header?.getBoundingClientRect().height || 88);
    let visObs: IntersectionObserver | null = null;
    const setupObs = () => {
      const h = headerH();
      floating.style.setProperty("--decorative-floating-top", `${h}px`);
      visObs?.disconnect();
      visObs = new IntersectionObserver(([entry]) => {
        const show = !entry.isIntersecting && entry.boundingClientRect.bottom <= h;
        floating.classList.toggle("is-visible", show);
        floating.setAttribute("aria-hidden", String(!show));
        show ? floating.removeAttribute("inert") : floating.setAttribute("inert", "");
      }, { threshold: 0, rootMargin: `-${headerH()}px 0px 0px 0px` });
      visObs.observe(toolbar);
    };
    setupObs();
    if (header && "ResizeObserver" in window) new ResizeObserver(setupObs).observe(header);

    return () => {
      visObs?.disconnect();
      floating.remove();
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // Sync state changes with floating toolbar clone
  useEffect(() => {
    const floating = document.querySelector<HTMLElement>(".decorative-floating-tools");
    if (!floating) return;

    // Sync light toggle
    const floatingLight = floating.querySelector<HTMLInputElement>(".decorative-light-toggle input");
    if (floatingLight) {
      floatingLight.checked = lightOn;
      floating.querySelector(".decorative-light-toggle")?.classList.toggle("is-on", lightOn);
      floating.querySelector(".decorative-light-toggle")?.classList.toggle("is-off", !lightOn);
      const dt = floating.querySelector(".decorative-light-desktop");
      const mt = floating.querySelector(".decorative-light-mobile");
      if (dt) dt.textContent = lightOn ? "Light on" : "Light off";
      if (mt) mt.textContent = lightOn ? "On" : "Off";
    }

    // Sync sort select
    const floatingSort = floating.querySelector<HTMLSelectElement>(".decorative-sort select");
    if (floatingSort) {
      floatingSort.value = sortBy;
    }
  }, [lightOn, sortBy]);

  // Animated category switch
  const switchCategory = (next: Category) => {
    if (next === activeCategory && next === gridCategory) return;
    if (exitTimer.current) clearTimeout(exitTimer.current);
    if (enterTimer.current) clearTimeout(enterTimer.current);
    setActiveCategory(next);
    setVisibleCount(9);

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      setGridCategory(next);
      setGridState("settled");
      return;
    }
    setGridState("exiting");
    exitTimer.current = setTimeout(() => {
      setGridCategory(next);
      setGridState("entering");
      enterTimer.current = setTimeout(() => setGridState("settled"), 24);
    }, 150);
  };

  const filtered = useMemo(() =>
    PRODUCTS.filter(p =>
      (gridCategory === "All" ||
        p.category.toLowerCase() === gridCategory.toLowerCase() ||
        p.type.toLowerCase() === gridCategory.toLowerCase()) &&
      (selectedFinish === "All" || p.finishes.includes(selectedFinish)) &&
      (selectedCollection === "All" || p.collection === selectedCollection)
    ).sort((a, b) => {
      if (sortBy === "name_asc") return a.name.localeCompare(b.name);
      if (sortBy === "name_desc") return b.name.localeCompare(a.name);
      if (sortBy === "new") return Number(!!b.isNew) - Number(!!a.isNew);
      return 0;
    }),
    [PRODUCTS, gridCategory, selectedFinish, selectedCollection, sortBy]
  );

  const meta = CATEGORY_META[activeCategory as keyof typeof CATEGORY_META] ?? {
    title: activeCategory,
    accent: "Lights",
    intro: "Explore our decorative lighting collection."
  };

  const clearFilters = () => {
    switchCategory("All");
    setSelectedFinish("All");
    setSelectedCollection("All");
  };

  const openFilter = () => {
    const catSet = activeCategory === "All" ? new Set<string>() : new Set([activeCategory]);
    const finSet = selectedFinish === "All" ? new Set<string>() : new Set([selectedFinish]);
    const colSet = selectedCollection === "All" ? new Set<string>() : new Set([selectedCollection]);
    setDraft({ category: catSet, finish: finSet, collection: colSet });
    setFilterOpen(true);
  };

  const draftCount = useMemo(() => {
    const cat = draft.category;
    const fin = draft.finish;
    return PRODUCTS.filter(p =>
      (!cat.size || Array.from(cat).some(c => p.category.toLowerCase() === c.toLowerCase() || p.type.toLowerCase() === c.toLowerCase())) &&
      (!fin.size || p.finishes.some(f => fin.has(f)))
    ).length;
  }, [PRODUCTS, draft]);

  const applyFilters = () => {
    const cat = draft.category.size === 0 ? "All" : [...draft.category][0] as Category;
    const fin = draft.finish.size === 0 ? "All" : [...draft.finish][0] as Finish;
    switchCategory(cat);
    setSelectedFinish(fin);
    setVisibleCount(9);
    setFilterOpen(false);
  };

  return (
    <main className="decorative-page">
      {/* Hero */}
      <section className="decorative-hero">
        <p className="site-breadcrumb site-breadcrumb--on-dark decorative-breadcrumb">
          <a href="/">Home</a> / <a href="/decorative-products">Decorative</a>
          {activeCategory !== "All" && ` / ${meta.title} ${meta.accent}`}
        </p>
        <div className="decorative-shell">
          <h1>{meta.title} <em>{meta.accent}</em></h1>
          <p className="decorative-intro">{meta.intro}</p>
        </div>
      </section>

      {/* Catalogue */}
      <section className="decorative-catalogue">
        <div className="decorative-shell">
          {/* Toolbar */}
          <div className="decorative-toolbar">
            {/* Mobile dropdown */}
            <label className="decorative-mobile-category">
              <span>{activeCategory}</span>
              <i aria-hidden="true" />
              <select aria-label="Product category" value={activeCategory} onChange={e => switchCategory(e.target.value as Category)}>
                {(["All", ...tabCategories]).map(opt => (
                  <option key={opt} value={opt}>{opt}</option>
                ))}
              </select>
            </label>

            {/* Desktop tabs */}
            <div className="decorative-tabs" role="tablist" aria-label="Product category">
              <span
                className="decorative-tab-indicator"
                aria-hidden="true"
                style={{ width: tabStyle.width, transform: `translateX(${tabStyle.x}px)` }}
              />
              {(["All", ...tabCategories]).map(tab => (
                <button
                  key={tab}
                  ref={el => { tabsRef.current[tab] = el; }}
                  type="button"
                  role="tab"
                  aria-selected={activeCategory === tab}
                  className={activeCategory === tab ? "active" : ""}
                  onClick={() => switchCategory(tab)}
                >
                  {tab}
                </button>
              ))}
            </div>

            {/* Tools */}
            <div className="decorative-tools">
              <button type="button" className="decorative-filter-button" onClick={openFilter}>
                <span className="decorative-filter-icon" aria-hidden="true">☷</span>
                Filter by
              </button>
              <label className="decorative-sort">
                <span>Sort by</span>
                <i aria-hidden="true" />
                <select aria-label="Sort products" value={sortBy} onChange={e => setSortBy(e.target.value as SortBy)}>
                  <option value="popular">Most popular</option>
                  <option value="new">New products</option>
                  <option value="name_asc">Alphabetical A-Z</option>
                  <option value="name_desc">Alphabetical Z-A</option>
                </select>
              </label>
              <label className={`decorative-light-toggle ${lightOn ? "is-on" : "is-off"}`}>
                <span className="decorative-light-desktop">{lightOn ? "Light on" : "Light off"}</span>
                <span className="decorative-light-mobile">{lightOn ? "On" : "Off"}</span>
                <input type="checkbox" checked={lightOn} onChange={e => setLightOn(e.target.checked)} />
                <i aria-hidden="true" />
              </label>
            </div>
          </div>

          {/* Grid */}
          <div className={`decorative-grid is-${gridState}`}>
            {filtered.slice(0, visibleCount).map((product, idx) => (
              <ProductCard key={product.key} product={product} lightOn={lightOn} order={idx} />
            ))}
          </div>

          {filtered.length === 0 && (
            <p className="decorative-empty">No pieces match these filters yet.</p>
          )}

          {visibleCount < filtered.length && (
            <div className="decorative-load-more">
              <button type="button" onClick={() => setVisibleCount(v => v + 6)}>Load more</button>
            </div>
          )}
        </div>
      </section>

      {/* CTA */}
      <section className="decorative-finder">
        <div className="decorative-shell">
          <h2 className="decorative-reveal">Didn&apos;t find what you&apos;re looking for?</h2>
          <p className="decorative-reveal">
            Our lighting advisors can help you choose the right piece, finish and configuration for your space or project — and share pricing on request.
          </p>
          <a className="decorative-reveal" href="https://wa.me/919820356488" target="_blank" rel="noopener noreferrer">
            Chat with us <span>→</span>
          </a>
        </div>
      </section>

      {/* Filter modal */}
      {filterOpen && (
        <div
          className="decorative-filter-modal"
          role="dialog"
          aria-modal="true"
          aria-label="Filter products"
          onMouseDown={e => { if (e.target === e.currentTarget) setFilterOpen(false); }}
        >
          <div className="decorative-filter-panel decorative-filter-panel--checkboxes">
            <div className="decorative-filter-head">
              <h3>Filter by</h3>
              <button type="button" className="decorative-filter-close" aria-label="Close filters" onClick={() => setFilterOpen(false)}>×</button>
            </div>
            <div className="decorative-filter-accordions">
              <FilterAccordion
                id="decorative-filter-section-0"
                title="Category"
                options={tabCategories}
                selected={draft.category}
                onToggle={v => setDraft(d => ({ ...d, category: toggleSet(d.category, v) }))}
                onClear={() => setDraft(d => ({ ...d, category: new Set() }))}
              />
              <FilterAccordion
                id="decorative-filter-section-1"
                title="Finish"
                options={finishOptions}
                selected={draft.finish}
                onToggle={v => setDraft(d => ({ ...d, finish: toggleSet(d.finish, v) }))}
                onClear={() => setDraft(d => ({ ...d, finish: new Set() }))}
              />
              {collectionOptions.length > 0 && (
                <FilterAccordion
                  id="decorative-filter-section-2"
                  title="Collection"
                  options={collectionOptions}
                  selected={draft.collection}
                  onToggle={v => setDraft(d => ({ ...d, collection: toggleSet(d.collection, v) }))}
                  onClear={() => setDraft(d => ({ ...d, collection: new Set() }))}
                />
              )}
            </div>
            <div className="decorative-filter-foot">
              <button type="button" className="decorative-filter-clear" onClick={() => setDraft(emptyFilters())}>Clear all</button>
              <button type="button" className="decorative-filter-show" onClick={applyFilters}>
                Show {draftCount} results
              </button>
            </div>
          </div>
        </div>
      )}
    </main>
  );
}
