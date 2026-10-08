import { useRef, useState, useEffect, useCallback, CSSProperties } from 'react';

interface UseHorizontalCarouselOptions {
  itemCount: number;
  gap?: number | { mobile: number; tablet?: number; desktop: number };
  desktopCardsVisible?: number;
  tabletCardsVisible?: number;
  mobileCardsVisible?: number;
}

export function useHorizontalCarousel<T extends HTMLElement = HTMLDivElement>({
  itemCount,
  gap = { mobile: 10, desktop: 16 },
  desktopCardsVisible = 4,
  tabletCardsVisible = 2.45,
  mobileCardsVisible = 2,
}: UseHorizontalCarouselOptions) {
  const trackRef = useRef<T>(null);
  const [isMobile, setIsMobile] = useState(false);
  const [isTablet, setIsTablet] = useState(false);
  const [canScrollLeft, setCanScrollLeft] = useState(false);
  const [canScrollRight, setCanScrollRight] = useState(false);

  const currentGap = typeof gap === 'number' 
    ? gap 
    : (isMobile ? gap.mobile : (isTablet ? (gap.tablet ?? gap.desktop) : gap.desktop));

  const getCardWidth = useCallback(() => {
    const el = trackRef.current;
    if (!el) return isMobile ? 160 : isTablet ? 280 : 280;
    
    const cardEl = el.firstElementChild as HTMLElement;
    if (cardEl && cardEl.getBoundingClientRect().width) {
      return cardEl.getBoundingClientRect().width;
    }
    
    // Fallback calculation if element isn't rendered yet
    if (isMobile) {
      return (el.clientWidth - currentGap * (Math.ceil(mobileCardsVisible) - 1)) / mobileCardsVisible;
    } else if (isTablet) {
      return (el.clientWidth - currentGap * (Math.ceil(tabletCardsVisible) - 1)) / tabletCardsVisible;
    } else {
      return (el.clientWidth - currentGap * (Math.ceil(desktopCardsVisible) - 1)) / desktopCardsVisible;
    }
  }, [isMobile, isTablet, currentGap, desktopCardsVisible, tabletCardsVisible, mobileCardsVisible]);

  const updateScrollState = useCallback(() => {
    const el = trackRef.current;
    if (!el || itemCount === 0) {
      setCanScrollLeft(false);
      setCanScrollRight(false);
      return;
    }

    const scrollLeft = el.scrollLeft;
    const maxScroll = Math.max(0, el.scrollWidth - el.clientWidth);

    setCanScrollLeft(scrollLeft > 10);
    setCanScrollRight(scrollLeft < maxScroll - 10);
  }, [itemCount]);

  useEffect(() => {
    const checkViewport = () => {
      const w = window.innerWidth;
      setIsMobile(w <= 700);
      setIsTablet(w > 700 && w <= 1024);
    };
    checkViewport();

    const handleResize = () => {
      checkViewport();
      updateScrollState();
    };
    window.addEventListener('resize', handleResize);

    return () => window.removeEventListener('resize', handleResize);
  }, [updateScrollState]);

  useEffect(() => {
    const timer = setTimeout(updateScrollState, 150);
    return () => clearTimeout(timer);
  }, [itemCount, isMobile, currentGap, updateScrollState]);

  const scrollLeft = useCallback(() => {
    const el = trackRef.current;
    if (el) {
      el.scrollBy({ left: -(getCardWidth() + currentGap), behavior: 'smooth' });
      setTimeout(updateScrollState, 300);
    }
  }, [getCardWidth, currentGap, updateScrollState]);

  const scrollRight = useCallback(() => {
    const el = trackRef.current;
    if (el) {
      el.scrollBy({ left: getCardWidth() + currentGap, behavior: 'smooth' });
      setTimeout(updateScrollState, 300);
    }
  }, [getCardWidth, currentGap, updateScrollState]);

  // Handler to attach to the track element
  const handleScroll = updateScrollState;

  return {
    trackRef,
    isMobile,
    isTablet,
    canScrollLeft,
    canScrollRight,
    scrollLeft,
    scrollRight,
    handleScroll,
    gap: currentGap,
    getCardWidth
  };
}
