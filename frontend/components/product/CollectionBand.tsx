"use client";

import React, { useEffect, useRef, useState } from "react";
import Image from "next/image";
import { DecCollection } from "@/types/decorative";

interface CollectionBandProps {
  collection: DecCollection;
}

export default function CollectionBand({
  collection,
}: CollectionBandProps) {
  const [inView, setInView] = useState(false);
  const bandRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          setInView(true);
          observer.disconnect();
        }
      },
      { rootMargin: "0px 0px -100px 0px", threshold: 0.08 }
    );
    if (bandRef.current) observer.observe(bandRef.current);
    return () => observer.disconnect();
  }, []);

  const imageUrl = collection.band_image?.startsWith("http") 
    ? collection.band_image 
    : (collection.band_image ? `${process.env.NEXT_PUBLIC_BACKEND_URL || "http://localhost:8000"}/storage/${collection.band_image.replace(/^storage\//, '')}` : "");

  // Format dynamic collection title (e.g., "quarry" -> "Quarry Collection", "Symphony" -> "Symphony Collection")
  const rawName = (collection.name || "").trim();
  const cleanName = rawName.replace(/\s*collection$/i, "").trim();
  const displayName = cleanName
    ? cleanName.charAt(0).toUpperCase() + cleanName.slice(1)
    : "Collection";

  return (
    <section
      className={`collection-band ${inView ? "collection-inview" : ""}`}
      ref={bandRef}
    >
      {imageUrl && (
        <Image 
          src={imageUrl} 
          alt={`${displayName} Collection`}
          fill
          sizes="100vw"
          className="collection-band-image"
        />
      )}
      <div className="product-reveal is-visible">
        <h2>
          Part of <em>{displayName} Collection</em>
        </h2>
        {collection.short_description && (
          <span>{collection.short_description}</span>
        )}
        <a href={`/collections/${collection.slug || "symphony"}`}>Explore Collection</a>
      </div>
    </section>
  );
}
