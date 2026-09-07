"use client";

import { useEffect } from "react";

export default function SpotlightEffect() {
  useEffect(() => {
    const spotlight = document.getElementById('spotlight');
    if (!spotlight) return;

    const handleMouseMove = (e: MouseEvent) => {
      spotlight.style.left = e.clientX + 'px';
      spotlight.style.top = e.clientY + 'px';
    };

    document.addEventListener('mousemove', handleMouseMove);

    return () => {
      document.removeEventListener('mousemove', handleMouseMove);
    };
  }, []);

  return <div id="spotlight" aria-hidden="true" />;
}
