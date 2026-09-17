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
    : (collection.band_image ? `${process.env.NEXT_PUBLIC_BACKEND_URL || "http://localhost:8000"}/storage/${collection.band_image}` : "");

  return (
    <section
      className={`collection-band ${inView ? "collection-inview" : ""}`}
      ref={bandRef}
    >
      {imageUrl && (
        <Image 
          src={imageUrl} 
          alt={`${collection.name} collection`}
          fill
          sizes="100vw"
          className="collection-band-image"
        />
      )}
      <div className="product-reveal">
        <h2>
          Part of <em>{collection.name} Collection</em>
        </h2>
        {collection.short_description && (
          <span>{collection.short_description}</span>
        )}
        <a href={`/collections/${collection.slug}`}>Explore Collection</a>
      </div>
    </section>
  );
}
