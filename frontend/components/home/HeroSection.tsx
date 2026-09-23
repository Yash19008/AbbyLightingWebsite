"use client";

import { useEffect, useRef } from "react";
import type { Slider } from "@/types/slider";

interface HeroSectionProps {
  sliders?: Slider[];
}

function formatHeadingHtml(heading?: string | null, highlight?: string | null): string {
  if (!heading && !highlight) return "";
  const h = (heading || "").trim();
  const hl = (highlight || "").trim();

  if (!hl) {
    return h;
  }

  if (!h) {
    return `<em>${hl}</em>`;
  }

  if (h.includes("<em>")) {
    return h;
  }

  const lowerH = h.toLowerCase();
  const lowerHl = hl.toLowerCase();
  const idx = lowerH.indexOf(lowerHl);
  if (idx !== -1) {
    const before = h.slice(0, idx);
    const matched = h.slice(idx, idx + hl.length);
    const after = h.slice(idx + hl.length);
    return `${before}<em>${matched}</em>${after}`.trim();
  }

  return `${h} <em>${hl}</em>`.trim();
}

export default function HeroSection({ sliders = [] }: HeroSectionProps) {
  const heroRef = useRef<HTMLElement>(null);

  // Hero carousel logic
  useEffect(() => {
    const hero = heroRef.current;
    if (!hero) return;

    const track = hero.querySelector(".hero-track") as HTMLElement;
    const slides = Array.from(hero.querySelectorAll("[data-hero-slide]"));

    if (!track || slides.length === 0) return;

    if (slides.length === 1) {
      // For a single slide, just make it active and skip carousel logic
      slides[0].classList.add("is-active");
      return;
    }

    const dots = Array.from(hero.querySelectorAll(".dots button"));
    const previous = hero.querySelector('[aria-label="Previous hero banner"]');
    const next = hero.querySelector('[aria-label="Next hero banner"]');

    if (!track || slides.length === 0) return;

    // Ensure first slide is active
    slides[0]?.classList.add("is-active");

    if (slides.length <= 1) {
      return;
    }

    let currentIndex = 0;
    let autoplayTimer: number = 0;
    let activationFrame = 0;
    let touchStartX: number | null = null;
    let touchStartY: number | null = null;

    const render = (nextIndex: number, restartAutoplay = true, immediate = false) => {
      currentIndex = (nextIndex + slides.length) % slides.length;
      if (activationFrame) window.cancelAnimationFrame(activationFrame);
      slides.forEach(slide => slide.classList.remove("is-active"));
      track.style.transform = "translate3d(" + (-currentIndex * 100) + "%, 0, 0)";
      slides.forEach((slide, index) => slide.setAttribute("aria-hidden", String(index !== currentIndex)));
      dots.forEach((dot, index) => {
        const isActive = index === currentIndex;
        dot.classList.toggle("active", isActive);
        if (isActive) dot.setAttribute("aria-current", "true");
        else dot.removeAttribute("aria-current");
      });
      const activatedIndex = currentIndex;
      if (immediate) {
        slides[activatedIndex]?.classList.add("is-active");
      } else {
        activationFrame = window.requestAnimationFrame(() => {
          activationFrame = window.requestAnimationFrame(() => {
            if (activatedIndex === currentIndex) slides[activatedIndex]?.classList.add("is-active");
          });
        });
      }
      if (restartAutoplay) startAutoplay();
    };

    const stopAutoplay = () => {
      if (autoplayTimer) window.clearInterval(autoplayTimer);
      autoplayTimer = 0;
    };

    const startAutoplay = () => {
      stopAutoplay();
      if (document.hidden || window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
      autoplayTimer = window.setInterval(() => render(currentIndex + 1, false), 6000);
    };

    if (previous) previous.addEventListener("click", () => render(currentIndex - 1));
    if (next) next.addEventListener("click", () => render(currentIndex + 1));
    dots.forEach((dot, index) => dot.addEventListener("click", () => render(index)));

    hero.addEventListener("mouseenter", stopAutoplay);
    hero.addEventListener("mouseleave", startAutoplay);
    hero.addEventListener("focusin", stopAutoplay);
    hero.addEventListener("focusout", (event: FocusEvent) => {
      if (!hero.contains(event.relatedTarget as Node)) startAutoplay();
    });

    hero.addEventListener("touchstart", (event: TouchEvent) => {
      const touch = event.touches[0];
      if (!touch) return;
      touchStartX = touch.clientX;
      touchStartY = touch.clientY;
      stopAutoplay();
    }, { passive: true });

    hero.addEventListener("touchend", (event: TouchEvent) => {
      const touch = event.changedTouches[0];
      if (!touch || touchStartX === null || touchStartY === null) {
        startAutoplay();
        return;
      }
      const deltaX = touch.clientX - touchStartX;
      const deltaY = touch.clientY - touchStartY;
      touchStartX = null;
      touchStartY = null;
      if (Math.abs(deltaX) >= 45 && Math.abs(deltaX) > Math.abs(deltaY)) {
        render(currentIndex + (deltaX < 0 ? 1 : -1));
      } else {
        startAutoplay();
      }
    }, { passive: true });

    hero.addEventListener("touchcancel", () => {
      touchStartX = null;
      touchStartY = null;
      startAutoplay();
    }, { passive: true });

    const handleVisibilityChange = () => {
      if (document.hidden) stopAutoplay();
      else startAutoplay();
    };
    document.addEventListener("visibilitychange", handleVisibilityChange);

    render(0, false, true);
    startAutoplay();

    return () => {
      stopAutoplay();
      document.removeEventListener("visibilitychange", handleVisibilityChange);
    };
  }, [sliders]);

  // If no dynamic sliders exist, hide the entire hero slider section
  if (!sliders || sliders.length === 0) {
    return null;
  }

  return (
    <section className="hero" data-hero-carousel="true" ref={heroRef}>
      <div className="hero-track">
        {sliders.map((slider, index) => (
          <div
            key={slider.id}
            className={`hero-slide ${index === 0 ? 'is-active' : ''} ${slider.image_url ? 'has-media' : ''}`}
            data-hero-slide="true"
            aria-hidden={index !== 0 ? "true" : "false"}
          >
            {slider.image_url && (
              <>
                <div className="hero-media-fallback" aria-hidden="true"></div>
                <picture className="hero-media">
                  {slider.mobile_image_url && (
                    <source media="(max-width: 640px)" srcSet={slider.mobile_image_url} />
                  )}
                  {slider.tablet_image_url && (
                    <source media="(min-width: 641px) and (max-width: 1024px)" srcSet={slider.tablet_image_url} />
                  )}
                  <img src={slider.image_url} alt="" loading={index === 0 ? "eager" : "lazy"} />
                </picture>
                <div className="hero-media-scrim" aria-hidden="true"></div>
              </>
            )}
            <div className="hero-copy">
              {(slider.heading || slider.heading_highlight) && (
                <h1
                  className="display"
                  dangerouslySetInnerHTML={{
                    __html: formatHeadingHtml(slider.heading, slider.heading_highlight),
                  }}
                />
              )}
              {slider.description && <p>{slider.description}</p>}
              {slider.button_text && slider.button_link && (
                <a className="btn" href={slider.button_link}>
                  <span className="cta-d">{slider.button_text}</span>
                  <span className="cta-m">{slider.button_text}</span>
                </a>
              )}
            </div>
          </div>
        ))}
      </div>
      {
        sliders.length > 1 && (
          <>
            <button className="hero-arrow hero-arrow-previous" type="button" aria-label="Previous hero banner">‹</button>
            <button className="hero-arrow hero-arrow-next" type="button" aria-label="Next hero banner">›</button>
            <div className="dots">
              {sliders.map((slider, index) => (
                <button key={slider.id} className={index === 0 ? "active" : ""} aria-current={index === 0 ? "true" : undefined} aria-label={`Show slide ${index + 1}`}></button>
              ))}
            </div>
          </>
        )
      }
    </section>
  );
}
