'use client';

import { CollectionHeroSection } from '@/types/collection';
import Link from 'next/link';

interface HeroSectionProps {
  heroSection: CollectionHeroSection;
  collectionName: string;
}

export default function HeroSection({ heroSection, collectionName }: HeroSectionProps) {
  const desktopImg = heroSection?.background_image;
  const tabletImg = heroSection?.image_tablet || desktopImg;
  const mobileImg = heroSection?.image_mobile || tabletImg || desktopImg;

  return (
    <section className="s-hero">
      {/* Responsive Background Image */}
      {desktopImg && (
        <picture className="s-hero-picture">
          {mobileImg && <source media="(max-width: 640px)" srcSet={mobileImg} />}
          {tabletImg && <source media="(max-width: 1024px)" srcSet={tabletImg} />}
          <img
            src={desktopImg}
            alt={`${collectionName} Hero`}
            className="s-hero-image"
          />
        </picture>
      )}
      
      {/* Shade overlay */}
      <div className="s-hero-shade" />

      {/* Breadcrumb */}
      <nav className="site-breadcrumb site-breadcrumb--on-dark s-crumb" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <Link href={heroSection?.breadcrumb_parent_link || '/decorative-lights'}>
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
