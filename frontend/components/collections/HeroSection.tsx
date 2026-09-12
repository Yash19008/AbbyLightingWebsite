'use client';

import { CollectionHeroSection } from '@/types/collection';
import Image from 'next/image';
import Link from 'next/link';

interface HeroSectionProps {
  heroSection: CollectionHeroSection;
  collectionName: string;
}

export default function HeroSection({ heroSection, collectionName }: HeroSectionProps) {
  return (
    <section className="s-hero">
      {/* Background Image */}
      {heroSection?.background_image && (
        <Image
          src={heroSection.background_image}
          alt={`${collectionName} Hero`}
          fill
          priority
          className="s-hero-image"
          style={{ objectFit: 'cover' }}
        />
      )}
      
      {/* Shade overlay */}
      <div className="s-hero-shade" />

      {/* Breadcrumb */}
      <nav className="site-breadcrumb site-breadcrumb--on-dark s-crumb" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <Link href={heroSection?.breadcrumb_parent_link || '/decorative-products'}>
          {heroSection?.breadcrumb_parent_text || 'Decorative'}
        </Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <span>{collectionName}</span>
      </nav>

      {/* Content */}
      <div className="s-hero-copy">
        {/* Title */}
        <h1>
          {heroSection?.title_prefix && <span>{heroSection.title_prefix}</span>}
          {heroSection?.title_highlight && <em>{heroSection.title_highlight}</em>}
        </h1>

        {/* Description */}
        {heroSection?.description && (
          <p>{heroSection.description}</p>
        )}
      </div>
    </section>
  );
}
