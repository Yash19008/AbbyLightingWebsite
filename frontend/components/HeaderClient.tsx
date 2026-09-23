"use client";

import React from "react";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";

type TabId = "home" | "products" | "work" | "inspiration" | "more";

interface TabItem {
  id: TabId;
  label: string;
  activeIcon: React.ReactNode;
  idleIcon: React.ReactNode;
  isProduct?: boolean;
}

const TABS: TabItem[] = [
  {
    id: "home",
    label: "Home",
    activeIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
        <path d="M3 11.5L12 4l9 7.5v7a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 18.5v-7z" />
      </svg>
    ),
    idleIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
        <path d="M3 11.5L12 4l9 7.5v7a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 18.5v-7z" />
      </svg>
    ),
  },
  {
    id: "products",
    label: "Products",
    isProduct: true,
    activeIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
        <line x1="12" y1="3" x2="12" y2="8" />
        <path d="M5 15a7 7 0 0 1 14 0H5z" />
        <path d="M10 15a2 2 0 0 0 4 0" />
      </svg>
    ),
    idleIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
        <line x1="12" y1="3" x2="12" y2="8" />
        <path d="M5 15a7 7 0 0 1 14 0H5z" />
        <path d="M10 15a2 2 0 0 0 4 0" />
      </svg>
    ),
  },
  {
    id: "work",
    label: "Our works",
    activeIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
        <path d="M4 6h4.5l2 2h8a1 1 0 0 1 1 1v1.5H3.5V7a1 1 0 0 1 1-1z" />
        <path d="M2.5 10.5h18a1 1 0 0 1 1 1.2l-1.3 6.8a1 1 0 0 1-1 .8H4.2a1 1 0 0 1-1-.8L1.8 11.7a1 1 0 0 1 .7-1.2z" />
      </svg>
    ),
    idleIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
        <path d="M4 6h4.5l2 2h8a1 1 0 0 1 1 1v1.5H3.5V7a1 1 0 0 1 1-1z" />
        <path d="M2.5 10.5h18a1 1 0 0 1 1 1.2l-1.3 6.8a1 1 0 0 1-1 .8H4.2a1 1 0 0 1-1-.8L1.8 11.7a1 1 0 0 1 .7-1.2z" />
      </svg>
    ),
  },
  {
    id: "inspiration",
    label: "Our inspiration",
    activeIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
        <path d="M6.5 9.5a5.5 5.5 0 1 1 11 0c0 2.2-1.2 3.8-2.2 5.1-.5.7-.8 1.4-.8 2.4H9.5c0-1-.3-1.7-.8-2.4-1-1.3-2.2-2.9-2.2-5.1z" />
        <line x1="9" y1="20" x2="15" y2="20" />
      </svg>
    ),
    idleIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
        <path d="M6.5 9.5a5.5 5.5 0 1 1 11 0c0 2.2-1.2 3.8-2.2 5.1-.5.7-.8 1.4-.8 2.4H9.5c0-1-.3-1.7-.8-2.4-1-1.3-2.2-2.9-2.2-5.1z" />
        <line x1="9" y1="20" x2="15" y2="20" />
      </svg>
    ),
  },
  {
    id: "more",
    label: "More",
    activeIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111111" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
        <line x1="5" y1="8" x2="19" y2="8" />
        <line x1="5" y1="12" x2="19" y2="12" />
        <line x1="5" y1="16" x2="19" y2="16" />
      </svg>
    ),
    idleIcon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
        <line x1="5" y1="8" x2="19" y2="8" />
        <line x1="5" y1="12" x2="19" y2="12" />
        <line x1="5" y1="16" x2="19" y2="16" />
      </svg>
    ),
  },
];

export default function HeaderClient() {
  const router = useRouter();
  const [isScrolled, setIsScrolled] = React.useState(false);
  const [isSearchOpen, setIsSearchOpen] = React.useState(false);
  const searchRef = React.useRef<HTMLDivElement>(null);
  const searchInputRef = React.useRef<HTMLInputElement>(null);
  const [architecturalCategories, setArchitecturalCategories] = React.useState<Array<{ id: number; title?: string; name?: string; slug?: string; uri?: string }>>([]);
  const [decorativeCategories, setDecorativeCategories] = React.useState<Array<{ id: number | string; name: string; slug?: string }>>([]);
  const [collections, setCollections] = React.useState<Array<{ id: number | string; name: string; slug?: string }>>([]);

  React.useEffect(() => {
    async function fetchMenuData() {
      try {
        const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
        const [archRes, decRes, colRes] = await Promise.all([
          fetch(`${API_URL}/api/categories`, { cache: 'no-store' }),
          fetch(`${API_URL}/api/dec-categories`, { cache: 'no-store' }),
          fetch(`${API_URL}/api/collections`, { cache: 'no-store' })
        ]);

        if (archRes.ok) {
          const archData = await archRes.json();
          if (archData.success && Array.isArray(archData.data)) {
            setArchitecturalCategories(archData.data);
          }
        }

        if (decRes.ok) {
          const decData = await decRes.json();
          if (decData.success && Array.isArray(decData.data)) {
            setDecorativeCategories(decData.data);
          }
        }

        if (colRes.ok) {
          const colData = await colRes.json();
          if (colData.success && Array.isArray(colData.data)) {
            setCollections(colData.data);
          }
        }
      } catch (error) {
        console.error('Error fetching menu categories:', error);
      }
    }

    fetchMenuData();
  }, []);

  React.useEffect(() => {
    const isHome = window.location.pathname === '/';
    if (!isHome) {
      setIsScrolled(true);
      return;
    }
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 500);
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll();
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Global Reveal Observer for animations
  React.useEffect(() => {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      rootMargin: '0px',
      threshold: 0.15
    });

    revealElements.forEach(el => revealObserver.observe(el));

    // Handle dynamically added elements
    const observer = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
          if (node instanceof HTMLElement) {
            if (node.classList.contains('reveal')) {
              revealObserver.observe(node);
            }
            const childReveals = node.querySelectorAll('.reveal');
            childReveals.forEach(el => revealObserver.observe(el));
          }
        });
      });
    });

    observer.observe(document.body, { childList: true, subtree: true });

    return () => {
      revealObserver.disconnect();
      observer.disconnect();
    };
  }, []);

  // Search toggle functionality
  const handleSearchToggle = () => {
    setIsSearchOpen(!isSearchOpen);
    // Focus input when opening
    if (!isSearchOpen) {
      setTimeout(() => {
        searchInputRef.current?.focus();
      }, 100);
    }
  };

  // Close search when clicking outside
  React.useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (searchRef.current && !searchRef.current.contains(event.target as Node)) {
        setIsSearchOpen(false);
      }
    };

    if (isSearchOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }

    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, [isSearchOpen]);

  // Close search on Escape key
  React.useEffect(() => {
    const handleEscape = (event: KeyboardEvent) => {
      if (event.key === 'Escape' && isSearchOpen) {
        setIsSearchOpen(false);
      }
    };

    document.addEventListener('keydown', handleEscape);

    return () => {
      document.removeEventListener('keydown', handleEscape);
    };
  }, [isSearchOpen]);

  const pathname = usePathname();

  const [activeTab, setActiveTab] = React.useState<TabId | null>(null); // starts as null; only set by user click
  const [activeSheet, setActiveSheet] = React.useState<"products" | "work" | "more" | null>(null);
  const [productAccordion, setProductAccordion] = React.useState<"arch" | "dec" | null>(null);

  // Show dock on scroll, hide when scroll stops
  const [isDockVisible, setIsDockVisible] = React.useState(false);
  const scrollTimeoutRef = React.useRef<NodeJS.Timeout | null>(null);

  const getRouteTabFromPathname = React.useCallback((path: string): TabId => {
    if (path === "/" || path === "") {
      return "home";
    } else if (path.includes("/decorative") || path.includes("/product") || path.includes("/collections") || path.includes("/catalogues")) {
      return "products";
    } else if (path.includes("/projects") || path.includes("/clients")) {
      return "work";
    } else if (path.includes("/inspiration") || path.includes("/news")) {
      return "inspiration";
    } else if (path.includes("/company") || path.includes("/contact") || path.includes("/about") || path.includes("/career") || path.includes("/policies")) {
      return "more";
    }
    return "home";
  }, []);

  const scheduleDockHideTimeout = React.useCallback((delayMs = 1500) => {
    if (scrollTimeoutRef.current) {
      clearTimeout(scrollTimeoutRef.current);
    }
    setIsDockVisible(true);
    scrollTimeoutRef.current = setTimeout(() => {
      setIsDockVisible(false);
    }, delayMs);
  }, []);

  React.useEffect(() => {
    setActiveSheet(null);
    // Do NOT auto-set active tab from route — only user clicks should activate a tab
    scheduleDockHideTimeout(1500);
  }, [pathname, scheduleDockHideTimeout]);

  const [hoveredTab, setHoveredTab] = React.useState<TabId | null>(null);
  const dockBarRef = React.useRef<HTMLDivElement>(null);
  const isDraggingRef = React.useRef(false);
  const [isDraggingState, setIsDraggingState] = React.useState(false);

  // HaloNav SVG physics handles
  const dockNavRef = React.useRef<HTMLDivElement>(null);
  const dockSvgRef = React.useRef<SVGSVGElement>(null);
  const dockPlateRef = React.useRef<SVGPathElement>(null);
  const dockOrbRef = React.useRef<HTMLSpanElement>(null);

  const currentVisibleTab = isDraggingState
    ? activeTab
    : activeSheet
      ? activeTab
      : (hoveredTab ?? activeTab);

  const activeIndex = React.useMemo(() => {
    if (!currentVisibleTab) return -1;
    const idx = TABS.findIndex((tab) => tab.id === currentVisibleTab);
    return idx >= 0 ? idx : -1;
  }, [currentVisibleTab]);

  const activeTabItem = activeIndex >= 0 ? TABS[activeIndex] : null;

  const selectTabRef = React.useRef<((idx: number, options?: { animate?: boolean; focus?: boolean }) => void) | null>(null);

  // Physics animation loop & Pointer dragging gestures for Floating SVG Halo Dock
  React.useEffect(() => {
    const navEl = dockNavRef.current;
    const svgEl = dockSvgRef.current;
    const plateEl = dockPlateRef.current;
    const orbEl = dockOrbRef.current;
    if (!navEl || !svgEl || !plateEl || !orbEl) return;

    const tabs = Array.from(navEl.querySelectorAll<HTMLButtonElement>('[role="tab"]'));
    const tablist = navEl.querySelector('[role="tablist"]');
    if (!tabs.length || !tablist) return;

    const clamp = (v: number, min: number, max: number) => Math.min(max, Math.max(min, v));
    const ease = (t: number) => t * t * (3 - 2 * t);
    const prefersReducedMotion = () => matchMedia("(prefers-reduced-motion: reduce)").matches;

    const m = {
      width: 0,
      height: 0,
      radius: 6,
      beadDiameter: 56,
      beadRadius: 34,
      shoulderRadius: 12,
      beadY: 0,
      slots: [] as number[],
      span: 80,
    };

    let currX = 0;
    let velX = 0;
    let targetX = 0;
    let currentSlotIndex = -1;
    let isPointerDragging = false;
    let dragStartX = 0;
    let activePointerId: number | null = null;
    let didDragBeyondThreshold = false;
    let animId = 0;
    let resizeAnimId = 0;
    let lastTime = performance.now();

    const dist = (r: number, t: number, n: number) => Math.sqrt(Math.max((r + t) ** 2 - (r - n) ** 2, 1));

    const measure = () => {
      const rect = navEl.getBoundingClientRect();
      const w = Math.round(rect.width);
      const h = Math.round(rect.height);
      if (w < 40 || h < 30) return false;

      m.slots = tabs.map((tab) => {
        const tRect = tab.getBoundingClientRect();
        return tRect.left - rect.left + tRect.width / 2;
      });
      m.span = m.slots.length > 1 ? m.slots[1] - m.slots[0] : w;
      m.width = w;
      m.height = h;
      m.radius = clamp(h * 0.08, 5, 7);
      m.beadY = 0;

      let d = Math.min(h * 0.68, m.span * 0.78);
      const s = m.slots[0] - m.radius - 6;

      for (let i = 0; i < 3; i++) {
        const e = dist(d * 0.22, d / 2 + 6, m.beadY);
        if (e <= s) break;
        d *= s / e;
      }

      m.beadDiameter = Math.max(Math.round(d), 30);
      m.shoulderRadius = m.beadDiameter * 0.22;
      m.beadRadius = m.beadDiameter / 2 + 6;
      m.beadY = m.beadRadius * 0.2;

      svgEl.setAttribute("viewBox", `0 0 ${w} ${h}`);
      navEl.style.setProperty("--halo-radius", `${m.radius.toFixed(1)}px`);
      navEl.style.setProperty("--halo-size", `${m.beadDiameter}px`);
      navEl.style.setProperty("--halo-y", `${m.beadY}px`);
      navEl.style.setProperty("--halo-rise", `${(h / 2 - m.beadY).toFixed(1)}px`);
      return true;
    };

    const buildIdlePath = () => {
      const { width: w, height: h, radius: r } = m;
      const str = (v: number) => v.toFixed(2);
      return `M0 ${str(r)}A${str(r)} ${str(r)} 0 0 1 ${str(r)} 0L${str(w - r)} 0A${str(r)} ${str(r)} 0 0 1 ${str(w)} ${str(r)}L${str(w)} ${str(h - r)}A${str(r)} ${str(r)} 0 0 1 ${str(w - r)} ${str(h)}L${str(r)} ${str(h)}A${str(r)} ${str(r)} 0 0 1 0 ${str(h - r)}Z`;
    };

    const buildActivePath = (x: number, sLeft: number, sRight: number) => {
      const { width: w, height: h, radius: r, beadRadius: br, beadY: by } = m;
      const c = x - br;
      const l = x + br;
      const u = by - br;
      const d = clamp(r + 2, 7, br * 0.34);
      const f = clamp(c - sLeft, r, w - r);
      const p = clamp(l + sRight, r, w - r);
      const str = (v: number) => v.toFixed(2);

      return `M0 ${str(r)}A${str(r)} ${str(r)} 0 0 1 ${str(r)} 0L${str(f)} 0C${str(c - sLeft * 0.42)} 0 ${str(c)} 0 ${str(c)} ${str(-d)}L${str(c)} ${str(u + d)}Q${str(c)} ${str(u)} ${str(c + d)} ${str(u)}L${str(l - d)} ${str(u)}Q${str(l)} ${str(u)} ${str(l)} ${str(u + d)}L${str(l)} ${str(-d)}C${str(l)} 0 ${str(l + sRight * 0.42)} 0 ${str(p)} 0L${str(w - r)} 0A${str(r)} ${str(r)} 0 0 1 ${str(w)} ${str(r)}L${str(w)} ${str(h - r)}A${str(r)} ${str(r)} 0 0 1 ${str(w - r)} ${str(h)}L${str(r)} ${str(h)}A${str(r)} ${str(r)} 0 0 1 0 ${str(h - r)}Z`;
    };

    const setIdle = () => {
      plateEl.setAttribute("d", buildIdlePath());
      navEl.classList.add("is-idle");
      tabs.forEach((tab, i) => {
        tab.setAttribute("aria-selected", "false");
        tab.tabIndex = i === 0 ? 0 : -1;
        tab.style.setProperty("--halo-active", "0");
      });
    };

    const renderState = () => {
      const normV = clamp(velX / 1100, -1, 1) * (isPointerDragging ? 0.5 : 1);
      const absV = Math.abs(normV);
      const sLeft = clamp(m.shoulderRadius * (1 + 0.06 * absV + 0.4 * normV), m.shoulderRadius * 0.55, m.shoulderRadius * 2.1);
      const sRight = clamp(m.shoulderRadius * (1 + 0.06 * absV - 0.4 * normV), m.shoulderRadius * 0.55, m.shoulderRadius * 2.1);

      plateEl.setAttribute("d", buildActivePath(currX, sLeft, sRight));

      const scaleS = 1 + 0.07 * absV;
      orbEl.style.transform = `translate3d(${currX.toFixed(2)}px,0,0) scale(${scaleS.toFixed(3)},${(1 / scaleS).toFixed(3)})`;

      tabs.forEach((tab, i) => {
        const activeRatio = ease(clamp(1 - Math.abs(currX - m.slots[i]) / (m.span * 0.55), 0, 1));
        tab.style.setProperty("--halo-active", activeRatio.toFixed(3));
      });
    };

    const loopStep = (now: number) => {
      animId = 0;
      const dt = Math.min((now - lastTime) / 1000, 1 / 30);
      lastTime = now;

      const stiffness = isPointerDragging ? 900 : 142;
      const damping = isPointerDragging ? 52 : 19.3;

      let remaining = dt;
      while (remaining > 0) {
        const step = Math.min(remaining, 1 / 240);
        velX += (-stiffness * (currX - targetX) - damping * velX) * step;
        currX += velX * step;
        remaining -= step;
      }

      renderState();

      if (Math.abs(currX - targetX) > 0.05 || Math.abs(velX) > 0.6 || isPointerDragging) {
        triggerPhysicsLoop();
      } else {
        currX = targetX;
        velX = 0;
        renderState();
      }
    };

    const triggerPhysicsLoop = () => {
      if (!animId) {
        lastTime = performance.now();
        animId = requestAnimationFrame(loopStep);
      }
    };

    const animateToSlot = (pos: number) => {
      targetX = pos;
      if (prefersReducedMotion() && !isPointerDragging) {
        currX = targetX;
        velX = 0;
        renderState();
        return;
      }
      triggerPhysicsLoop();
    };

    const selectTab = (idx: number, options: { animate?: boolean; focus?: boolean } = {}) => {
      const { focus = false, animate = true } = options;
      if (idx == null || idx < 0 || !m.slots.length) {
        currentSlotIndex = -1;
        setIdle();
        return;
      }
      const slotIdx = (idx + tabs.length) % tabs.length;
      const isSame = currentSlotIndex === slotIdx;
      currentSlotIndex = slotIdx;
      navEl.classList.remove("is-idle");

      tabs.forEach((tab, i) => {
        tab.setAttribute("aria-selected", String(i === currentSlotIndex));
        tab.tabIndex = i === currentSlotIndex ? 0 : -1;
      });

      if (focus) tabs[currentSlotIndex].focus();

      if (animate) {
        animateToSlot(m.slots[currentSlotIndex]);
      } else if (!isSame || Math.abs(currX - m.slots[currentSlotIndex]) > 0.05) {
        currX = targetX = m.slots[currentSlotIndex];
        velX = 0;
        renderState();
      }
    };

    selectTabRef.current = selectTab;

    const onPointerDown = (e: PointerEvent) => {
      if (e.button !== 0 && e.pointerType === "mouse") return;
      activePointerId = e.pointerId;
      dragStartX = e.clientX;
      didDragBeyondThreshold = false;
    };

    const onPointerMove = (e: PointerEvent) => {
      if (e.pointerId !== activePointerId || (!isPointerDragging && Math.abs(e.clientX - dragStartX) < 14)) return;
      if (!isPointerDragging) {
        isPointerDragging = true;
        didDragBeyondThreshold = true;
        setIsDraggingState(true);
        navEl.classList.remove("is-idle");
        navEl.classList.add("is-dragging");
        try { navEl.setPointerCapture(activePointerId); } catch { }
      }
      e.preventDefault();
      const rect = navEl.getBoundingClientRect();
      targetX = clamp(e.clientX - rect.left, m.slots[0], m.slots[m.slots.length - 1]);
      triggerPhysicsLoop();
    };

    const onPointerUp = (e: PointerEvent) => {
      if (e.pointerId !== activePointerId || (activePointerId = null, !isPointerDragging)) return;
      isPointerDragging = false;
      setIsDraggingState(false);
      navEl.classList.remove("is-dragging");

      let closestIdx = 0;
      let minDiff = Infinity;
      m.slots.forEach((slot, i) => {
        const diff = Math.abs(targetX - slot);
        if (diff < minDiff) {
          minDiff = diff;
          closestIdx = i;
        }
      });
      const tab = TABS[closestIdx];
      if (tab) {
        handleTabClick(tab.id);
      }
      setTimeout(() => { didDragBeyondThreshold = false; }, 0);
    };

    navEl.addEventListener("pointerdown", onPointerDown);
    navEl.addEventListener("pointermove", onPointerMove);
    navEl.addEventListener("pointerup", onPointerUp);
    navEl.addEventListener("pointercancel", onPointerUp);

    const updateLayout = (animate = false) => {
      if (measure()) {
        if (currentSlotIndex < 0) {
          currX = velX = targetX = 0;
          setIdle();
        } else if (animate) {
          animateToSlot(m.slots[currentSlotIndex]);
        } else {
          currX = targetX = m.slots[currentSlotIndex];
          velX = 0;
          navEl.classList.remove("is-idle");
          renderState();
        }
        navEl.classList.add("is-ready");
      }
    };

    updateLayout(false);

    const resizeObserver = new ResizeObserver(() => {
      cancelAnimationFrame(resizeAnimId);
      resizeAnimId = requestAnimationFrame(() => updateLayout(false));
    });
    resizeObserver.observe(navEl);
    document.fonts?.ready.then(() => updateLayout(false));

    return () => {
      if (animId) cancelAnimationFrame(animId);
      if (resizeAnimId) cancelAnimationFrame(resizeAnimId);
      resizeObserver.disconnect();
      navEl.removeEventListener("pointerdown", onPointerDown);
      navEl.removeEventListener("pointermove", onPointerMove);
      navEl.removeEventListener("pointerup", onPointerUp);
      navEl.removeEventListener("pointercancel", onPointerUp);
      selectTabRef.current = null;
    };
  }, []);

  React.useEffect(() => {
    if (activeIndex >= 0) {
      selectTabRef.current?.(activeIndex, { animate: true });
    } else {
      selectTabRef.current?.(-1);
    }
  }, [activeIndex]);

  React.useEffect(() => {
    const handleScrollVisibility = () => {
      setIsDockVisible(true);

      if (activeSheet) return;

      if (scrollTimeoutRef.current) {
        clearTimeout(scrollTimeoutRef.current);
      }

      scrollTimeoutRef.current = setTimeout(() => {
        setIsDockVisible(false);
      }, 1500);
    };

    window.addEventListener("scroll", handleScrollVisibility, { passive: true });

    return () => {
      window.removeEventListener("scroll", handleScrollVisibility);
      if (scrollTimeoutRef.current) {
        clearTimeout(scrollTimeoutRef.current);
      }
    };
  }, [activeSheet]);

  const handleDockPointerEnter = () => {
    if (scrollTimeoutRef.current) {
      clearTimeout(scrollTimeoutRef.current);
    }
    setIsDockVisible(true);
  };

  const handleDockPointerLeave = () => {
    setHoveredTab(null);
    if (activeSheet) return;
    if (scrollTimeoutRef.current) {
      clearTimeout(scrollTimeoutRef.current);
    }
    scrollTimeoutRef.current = setTimeout(() => {
      setIsDockVisible(false);
    }, 1500);
  };

  React.useEffect(() => {
    if (activeSheet) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }
    return () => {
      document.body.style.overflow = "";
    };
  }, [activeSheet]);

  const handleTabClick = (tabId: "home" | "products" | "work" | "inspiration" | "more") => {
    setHoveredTab(null);
    setActiveTab(tabId);

    if (tabId === "products" || tabId === "work" || tabId === "more") {
      // Menu Icon Click (drawer sheet) -> Keep bottom bar visible constantly without hiding
      if (scrollTimeoutRef.current) {
        clearTimeout(scrollTimeoutRef.current);
      }
      setIsDockVisible(true);

      if (activeSheet === tabId) {
        // Toggling same menu closed -> close sheet and keep dock visible
        setActiveSheet(null);
        setIsDockVisible(true);
      } else {
        // Opening menu sheet -> set active sheet & keep dock visible
        setActiveSheet(tabId);
        setIsDockVisible(true);
      }
    } else if (tabId === "home") {
      // Home Route Click -> Option active, bottom bar hides after a short delay
      setActiveSheet(null);
      scheduleDockHideTimeout(1500);
      if (pathname === "/") {
        window.scrollTo({ top: 0, behavior: "smooth" });
      } else {
        router.push("/");
      }
    } else if (tabId === "inspiration") {
      // Inspiration Route Click -> Option active, bottom bar hides after a short delay
      setActiveSheet(null);
      scheduleDockHideTimeout(1500);
      if (pathname === "/inspiration") {
        window.scrollTo({ top: 0, behavior: "smooth" });
      } else {
        router.push("/inspiration");
      }
    }
  };

  const closeSheet = () => {
    if (scrollTimeoutRef.current) {
      clearTimeout(scrollTimeoutRef.current);
    }
    setActiveSheet(null);
    setIsDockVisible(true);
  };

  return (
    <>
      <header className={`sitehead ${isScrolled ? 'scrolled' : ''}`}>
        <div className="wrap">
          <Link href="/" className="logo" aria-label="Abby Lighting home">
            <img className="logo-asset" src="/images/abby-logo.png" alt="Abby Lighting" />
          </Link>
          <nav aria-label="Primary navigation">
            <ul>
              <li className="has-mega">
                <a className="link" href="/products">
                  Product<span className="caret"></span>
                </a>
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

                      {/* DECORATIVE - Dynamic */}
                      <div className="mgroup m-dec">
                        <div className="mhead">
                          Decorative <span className="mnew">NEW</span>
                        </div>
                        <div className="mcols">
                          <div>
                            <div className="msub">Browse by category</div>
                            <ul>
                              {decorativeCategories.length > 0 ? (
                                decorativeCategories.map((cat) => (
                                  <li key={cat.id || cat.slug || cat.name}>
                                    <Link href={`/decorative-products?category=${cat.slug || cat.name}`}>
                                      {cat.name}
                                    </Link>
                                  </li>
                                ))
                              ) : (
                                <>
                                  <li><Link href="/decorative-products?category=chandelier">Chandelier</Link></li>
                                  <li><Link href="/decorative-products?category=pendant-lights">Pendant Lights</Link></li>
                                  <li><Link href="/decorative-products?category=wall-lights">Wall Lights</Link></li>
                                  <li><Link href="/decorative-products?category=floor-lamps">Floor Lamps</Link></li>
                                  <li><Link href="/decorative-products?category=table-lamps">Table Lamps</Link></li>
                                </>
                              )}
                            </ul>
                          </div>
                          <div className="msep sm"></div>
                          <div>
                            <div className="msub">Browse by collection</div>
                            <ul>
                              {collections.length > 0 ? (
                                collections.map((col) => (
                                  <li key={col.id || col.slug || col.name}>
                                    <Link href={`/collections/${col.slug || col.name.toLowerCase()}`}>
                                      {col.name}
                                    </Link>
                                  </li>
                                ))
                              ) : (
                                <>
                                  <li><Link href="/collections/symphony">Symphony</Link></li>
                                  <li><Link href="/collections/quarry">Quarry</Link></li>
                                  <li><Link href="/collections/neoma">Neoma</Link></li>
                                </>
                              )}
                            </ul>
                          </div>
                        </div>
                      </div>

                      <div className="msep lg"></div>
                      <div className="m-worlds">
                        <Link className="mgroup m-out" href="/#worlds">
                          <span className="mhead">Outdoor</span>
                        </Link>
                        <div className="msep hz"></div>
                        <Link className="mgroup m-smart" href="/#worlds">
                          <span className="mhead">Smart Lighting</span>
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li className="has-drop">
                <Link className="link" href="/projects">
                  Our Work<span className="caret"></span>
                </Link>
                <div className="drop">
                  <a href="/projects">Projects</a>
                  <a href="/clients">Clients</a>
                </div>
              </li>
              <li>
                <Link className="link" href="/inspiration">Inspiration</Link>
              </li>
              <li className="has-drop">
                <Link className="link" href="/company">
                  More<span className="caret"></span>
                </Link>
                <div className="drop">
                  <Link href="/company">About Us</Link>
                  <Link href="/contact">Contact Us</Link>
                  <Link href="/career">Careers</Link>
                  <Link href="/catalogues">Catalogues</Link>
                </div>
              </li>
            </ul>
          </nav>
          <div className="right">
            <div ref={searchRef} className={`abby-search ${isSearchOpen ? 'is-open' : ''}`}>
              <form className="abby-search-form" role="search">
                <span className="abby-search-leading">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.7" cy="10.7" r="6.7"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                  </svg>
                </span>
                <input
                  ref={searchInputRef}
                  type="search"
                  placeholder="Search for products, collections and more"
                  aria-label="Search Abby Lighting"
                  autoComplete="off"
                  defaultValue=""
                />
                <button className="abby-search-go" type="submit" aria-label="Submit search" disabled>
                  →
                </button>
              </form>
              <button
                type="button"
                className="abby-search-toggle"
                aria-label={isSearchOpen ? "Close search" : "Open search"}
                aria-expanded={isSearchOpen}
                onClick={handleSearchToggle}
              >
                {isSearchOpen ? (
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                  </svg>
                ) : (
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="10.7" cy="10.7" r="6.7"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                  </svg>
                )}
              </button>
            </div>
            <a href="/contact" className="nav-cta">
              <span className="lbl-d">Get in Touch</span>
              <span className="lbl-m">Contact</span>
            </a>
          </div>
        </div>
      </header>

      {/* Floating Bottom Dock (Mobile & Tablet Floating SVG Halo Dock) */}
      <nav
        className={`halo-nav-wrap ${isDockVisible || activeSheet !== null ? 'halo-visible' : 'halo-hidden'}`}
        aria-label="Mobile bottom navigation"
        onPointerEnter={handleDockPointerEnter}
        onPointerLeave={handleDockPointerLeave}
        onTouchStart={handleDockPointerEnter}
      >
        <div ref={dockNavRef} className="halo-nav is-ready">
          <span className="halo-nav-shadow" aria-hidden="true" />
          <svg
            ref={dockSvgRef}
            className="halo-nav-skin"
            aria-hidden="true"
            focusable="false"
            preserveAspectRatio="none"
          >
            <defs>
              <linearGradient id="internalHaloPlateGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-plate-highlight" offset="0" />
                <stop className="halo-plate-shadow" offset="1" />
              </linearGradient>
              <linearGradient id="internalHaloRimGradient" x1="0" y1="0" x2="0" y2="1">
                <stop className="halo-rim-strong" offset="0" />
                <stop className="halo-rim-soft" offset="1" />
              </linearGradient>
            </defs>
            <path ref={dockPlateRef} className="halo-nav-plate" />
          </svg>
          <span ref={dockOrbRef} className="halo-orb" aria-hidden="true" />
          <div className="halo-tabs" role="tablist" aria-label="Page sections">
            {TABS.map((tab, idx) => {
              const isActive = currentVisibleTab === tab.id;
              return (
                <button
                  key={tab.id}
                  className="halo-tab"
                  role="tab"
                  type="button"
                  id={`halo-tab-${tab.id}`}
                  aria-selected={isActive}
                  tabIndex={idx === 0 ? 0 : -1}
                  onClick={() => {
                    setHoveredTab(null);
                    handleTabClick(tab.id);
                  }}
                  onPointerEnter={(e) => {
                    handleDockPointerEnter();
                    if (!isDraggingRef.current && e.pointerType !== "touch") {
                      setHoveredTab(tab.id);
                    }
                  }}
                  aria-label={tab.label}
                >
                  <svg className="halo-icon" viewBox="0 0 24 24" aria-hidden="true">
                    {isActive ? tab.activeIcon : tab.idleIcon}
                  </svg>
                  <span className="halo-label">{tab.label}</span>
                </button>
              );
            })}
          </div>
        </div>
      </nav>

      {/* Backdrop for Mobile Sheets */}
      <div
        className={`custom-sheet-backdrop ${activeSheet ? 'is-open' : ''}`}
        onClick={closeSheet}
        aria-hidden="true"
      />

      {/* Fullscreen Mobile Drawer for Products / Works / More */}
      <div className={`pdrop-modal ${activeSheet ? 'is-open' : ''}`} role="dialog" aria-modal="true">
        <div className="pdrop-container">
          {/* Top Header Row with Logo, Search and Contact button */}
          <div className="pdrop-header">
            <Link href="/" onClick={closeSheet} className="pdrop-logo">
              <img src="/images/abby-logo.png" alt="Abby Lighting" />
            </Link>
            <div className="pdrop-actions">
              <button
                type="button"
                className="pdrop-search-btn"
                onClick={() => { closeSheet(); handleSearchToggle(); }}
                aria-label="Search"
              >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <circle cx="11" cy="11" r="7" />
                  <path d="M21 21l-4.35-4.35" />
                </svg>
              </button>
              <a href="/contact" onClick={closeSheet} className="pdrop-contact-btn">
                CONTACT
              </a>
            </div>
          </div>

          {/* PRODUCTS MENU (Matching Figma Design) */}
          {activeSheet === 'products' && (
            <div className="pdrop-body">
              {/* Title row with (X) close */}
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">PRODUCTS</h2>
                <button
                  type="button"
                  className="pdrop-circle-close"
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>

              {/* 1. Architectural Accordion */}
              <div className={`pdrop-acc ${productAccordion === 'arch' ? 'is-open' : ''}`}>
                <button
                  type="button"
                  className="pdrop-acc-toggle"
                  onClick={() => setProductAccordion(prev => prev === 'arch' ? null : 'arch')}
                >
                  <span className={productAccordion === 'arch' ? 'pdrop-amber-text' : ''}>Architectural</span>
                  <span className="pdrop-caret">
                    {productAccordion === 'arch' ? (
                      <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,7 11,7 5.5,0" />
                      </svg>
                    ) : (
                      <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,0 11,0 5.5,7" />
                      </svg>
                    )}
                  </span>
                </button>
                {productAccordion === 'arch' && (
                  <div className="pdrop-acc-content">
                    <div className="pdrop-section-label">BROWSE BY CATEGORY</div>
                    <ul className="pdrop-list">
                      {architecturalCategories.length > 0 ? (
                        architecturalCategories.map(cat => (
                          <li key={cat.id}>
                            <a
                              href={cat.uri || `/products?category=${cat.slug || ''}`}
                              onClick={closeSheet}
                            >
                              {cat.title || cat.name}
                            </a>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><a href="/products" onClick={closeSheet}>Spots &amp; Accents</a></li>
                          <li><a href="/products" onClick={closeSheet}>Downlights</a></li>
                          <li><a href="/products" onClick={closeSheet}>Profiles</a></li>
                          <li><a href="/products" onClick={closeSheet}>Track Lights</a></li>
                          <li><a href="/products" onClick={closeSheet}>Washers &amp; Grazers</a></li>
                        </>
                      )}
                    </ul>
                  </div>
                )}
              </div>

              {/* 2. Decorative Accordion */}
              <div className={`pdrop-acc ${productAccordion === 'dec' ? 'is-open' : ''}`}>
                <button
                  type="button"
                  className="pdrop-acc-toggle"
                  onClick={() => setProductAccordion(prev => prev === 'dec' ? null : 'dec')}
                >
                  <span className={productAccordion === 'dec' ? 'pdrop-amber-text' : ''}>
                    Decorative <span className="mnew" style={{ marginLeft: 6, fontSize: '0.65rem', padding: '1px 5px', color: '#fff', borderRadius: 3 }}>NEW</span>
                  </span>
                  <span className="pdrop-caret">
                    {productAccordion === 'dec' ? (
                      <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,7 11,7 5.5,0" />
                      </svg>
                    ) : (
                      <svg width="14" height="36" viewBox="0 0 11 7" fill="currentColor">
                        <polygon points="0,0 11,0 5.5,7" />
                      </svg>
                    )}
                  </span>
                </button>
                {productAccordion === 'dec' && (
                  <div className="pdrop-acc-content">
                    <div className="pdrop-section-label">BROWSE BY CATEGORY</div>
                    <ul className="pdrop-list">
                      {decorativeCategories.length > 0 ? (
                        decorativeCategories.map(cat => (
                          <li key={cat.id || cat.slug || cat.name}>
                            <Link
                              href={`/decorative-products?category=${cat.slug || cat.name}`}
                              onClick={closeSheet}
                            >
                              {cat.name}
                            </Link>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><Link href="/decorative-products?category=chandelier" onClick={closeSheet}>Chandelier</Link></li>
                          <li><Link href="/decorative-products?category=pendant-lights" onClick={closeSheet}>Pendant Lights</Link></li>
                          <li><Link href="/decorative-products?category=wall-lights" onClick={closeSheet}>Wall Lights</Link></li>
                          <li><Link href="/decorative-products?category=floor-lamps" onClick={closeSheet}>Floor Lamps</Link></li>
                          <li><Link href="/decorative-products?category=table-lamps" onClick={closeSheet}>Table Lamps</Link></li>
                        </>
                      )}
                    </ul>
                    <div className="pdrop-section-label" style={{ marginTop: 16 }}>BROWSE BY COLLECTION</div>
                    <ul className="pdrop-list">
                      {collections.length > 0 ? (
                        collections.map(col => (
                          <li key={col.id || col.slug || col.name}>
                            <Link
                              href={`/collections/${col.slug || col.name.toLowerCase()}`}
                              onClick={closeSheet}
                            >
                              {col.name}
                            </Link>
                          </li>
                        ))
                      ) : (
                        <>
                          <li><Link href="/collections/symphony" onClick={closeSheet}>Symphony</Link></li>
                          <li><Link href="/collections/quarry" onClick={closeSheet}>Quarry</Link></li>
                          <li><Link href="/collections/neoma" onClick={closeSheet}>Neoma</Link></li>
                        </>
                      )}
                    </ul>
                  </div>
                )}
              </div>

              {/* 3. Outdoor */}
              <div className="pdrop-direct-row">
                <Link href="/#worlds" onClick={closeSheet}>
                  Outdoor
                </Link>
              </div>

              {/* 4. Smart Lighting */}
              <div className="pdrop-direct-row">
                <Link href="/#worlds" onClick={closeSheet}>
                  Smart Lighting
                </Link>
              </div>
            </div>
          )}

          {/* OUR WORKS MENU */}
          {activeSheet === 'work' && (
            <div className="pdrop-body">
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">OUR WORKS</h2>
                <button
                  type="button"
                  className="pdrop-circle-close"
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/projects" onClick={closeSheet}>
                  <span>Projects</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/clients" onClick={closeSheet}>
                  <span>Clients</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
            </div>
          )}

          {/* MORE MENU (Matching Figma Design) */}
          {activeSheet === 'more' && (
            <div className="pdrop-body">
              <div className="pdrop-title-row">
                <h2 className="pdrop-title">MORE</h2>
                <button
                  type="button"
                  className="pdrop-circle-close"
                  onClick={closeSheet}
                  aria-label="Close menu"
                >
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round">
                    <path d="M18 6L6 18M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/company" onClick={closeSheet}>
                  <span>About</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/career" onClick={closeSheet}>
                  <span>Careers</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/contact" onClick={closeSheet}>
                  <span>Contact</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
              <div className="pdrop-arrow-row">
                <a href="/policies" onClick={closeSheet}>
                  <span>Policies</span>
                  <svg className="pdrop-arrow" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.2" strokeLinecap="round" strokeLinejoin="round">
                    <line x1="3" y1="12" x2="20" y2="12" />
                    <polyline points="14 6 20 12 14 18" />
                  </svg>
                </a>
              </div>
            </div>
          )}
        </div>
      </div>
    </>
  );
}
