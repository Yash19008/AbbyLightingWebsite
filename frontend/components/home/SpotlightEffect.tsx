"use client";

import { useEffect, useRef } from "react";
import { usePathname } from "next/navigation";

export default function SpotlightEffect() {
  const spotlightRef = useRef<HTMLDivElement>(null);
  const pathname = usePathname();

  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      if (spotlightRef.current) {
        spotlightRef.current.style.left = `${e.clientX}px`;
        spotlightRef.current.style.top = `${e.clientY}px`;
      }
    };

    window.addEventListener("mousemove", handleMouseMove, { passive: true });

    // Global Intersection Observer for .reveal elements
    const revealElements = document.querySelectorAll(".reveal");
    const revealObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      {
        root: null,
        rootMargin: "0px",
        threshold: 0.1,
      }
    );

    revealElements.forEach((el) => revealObserver.observe(el));

    return () => {
      window.removeEventListener("mousemove", handleMouseMove);
      revealObserver.disconnect();
    };
  }, [pathname]);

  return <div id="spotlight" ref={spotlightRef} aria-hidden="true" />;
}
