"use client";

import { useState, useEffect, useRef } from 'react';
import Image from 'next/image';
import Link from 'next/link';

interface ProductCardProps {
  roman?: string;
  index: number;
  product?: {
    id: number;
    title: string;
    slug: string;
    featured_image: string | null;
  };
}

const colours = ["#f47829", "#deb34f", "#1e1e1e", "#f2f0ea"];

export default function ProductCard({ roman, index, product }: ProductCardProps) {
  const [view, setView] = useState(0);
  const [finish, setFinish] = useState(0);
  const [revealed, setRevealed] = useState(false);
  const cardRef = useRef<HTMLElement>(null);

  useEffect(() => {
    if (!cardRef.current) return;

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setRevealed(true);
          observer.disconnect();
        }
      },
      { rootMargin: '0px 0px -100px', threshold: 0.01 }
    );

    observer.observe(cardRef.current);
    return () => observer.disconnect();
  }, []);

  const imageSrc = product ? (product.featured_image || '/images/figma-update/catalogue.png') : `/images/symphony/product-${index + 1}.png`;
  const title = product ? product.title : `Symphony ${roman}`;
  const href = product ? `/product-detail/${product.slug}` : `/product-detail/symphony-iv`;

  return (
    <div 
      className="decorative-grid-item-motion"
      style={{ '--filter-delay': `${index * 40}ms` } as React.CSSProperties}
    >
      <article
        ref={cardRef}
        className={`decorative-card ${revealed ? 'is-revealed' : ''}`}
        suppressHydrationWarning
        style={{
          '--card-order': index % 3,
          '--light-delay': `${index * 50}ms`,
          '--motion-delay': `${Math.min(index, 6) * 60}ms`,
        } as React.CSSProperties}
      >
        <div className="decorative-card-image is-lit">
          <Link
            href={href}
            className="decorative-card-main-link"
            aria-label={`View ${title}`}
          >
            <Image
              src={imageSrc}
              alt=""
              width={300}
              height={300}
              className="decorative-product-image is-current is-light-off"
              aria-hidden="true"
              style={{ objectFit: 'cover' }}
            />
            <Image
              src={imageSrc}
              alt={`${title} pendant light`}
              width={300}
              height={300}
              className="decorative-product-image is-current is-light-on"
              style={{ objectFit: 'cover' }}
            />
          </Link>
          <button
            type="button"
            className="decorative-card-arrow decorative-card-prev"
            aria-label={`Previous ${title} image`}
            onClick={() => setView((view + 2) % 3)}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
          <button
            type="button"
            className="decorative-card-arrow decorative-card-next"
            aria-label={`Next ${title} image`}
            onClick={() => setView((view + 1) % 3)}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
          <span className="decorative-gallery-dots">
            {[0, 1, 2].map((i) => (
              <button
                key={i}
                className={view === i ? 'active' : ''}
                type="button"
                aria-label={`Show ${title} view ${i + 1}`}
                onClick={() => setView(i)}
              />
            ))}
          </span>
        </div>
        <div className="decorative-card-copy">
          <h3>{title}</h3>
          <p>Pendant Light</p>
          <div className="decorative-swatches" aria-label={`${title} finishes`}>
            {colours.map((colour, i) => (
              <button
                key={colour}
                type="button"
                className={finish === i ? 'active' : ''}
                style={{ background: colour }}
                aria-label={`Select finish ${i + 1}`}
                onClick={() => setFinish(i)}
              />
            ))}
          </div>
          <span className="decorative-collection">Collection Product</span>
        </div>
      </article>
    </div>
  );
}
