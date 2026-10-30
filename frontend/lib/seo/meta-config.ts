import type { Metadata } from 'next';

/**
 * ============================================================================
 * ABBY LIGHTING — CENTRAL SEO & METADATA CONFIGURATION
 * ============================================================================
 * 
 * UPDATE PAGE DETAILS HERE:
 * To change titles, descriptions, keywords, OG images, or canonical URLs for any
 * page on the website, simply update the corresponding entry in `sitePagesMeta` below.
 * All frontend pages automatically read from this master configuration file.
 */

export const SITE_URL = (process.env.NEXT_PUBLIC_SITE_URL || 'https://abbylighting.com').replace(/\/$/, '');
export const SITE_NAME = 'Abby Lighting';
export const DEFAULT_OG_IMAGE = `${SITE_URL}/images/figma-update/hero-decorative.png`;
export const DEFAULT_KEYWORDS = [
  'Abby Lighting',
  'architectural lighting',
  'decorative lighting',
  'LED lighting India',
  'commercial lighting fixtures',
  'luxury decorative lights',
  'lighting manufacturer India',
  'recessed ceiling lights',
  'track lighting',
  'smart lighting solutions',
  'pendant lighting',
  'chandeliers',
  'ambient lighting',
  'architectural luminaires',
].join(', ');

export interface PageSEOConfig {
  title: string;
  description: string;
  keywords?: string;
  canonical?: string;
  ogImage?: string;
  ogType?: 'website' | 'article';
  noindex?: boolean;
}

export type PageKey =
  | 'home'
  | 'collections'
  | 'collectionsDetailDefault'
  | 'decorativeLights'
  | 'decorativeDetailDefault'
  | 'catalogues'
  | 'inspiration'
  | 'blogs'
  | 'blogDetailDefault'
  | 'lightCalculator'
  | 'search';

export const sitePagesMeta: Record<PageKey, PageSEOConfig> = {
  home: {
    title: 'Architectural & Decorative Lighting | Abby Lighting',
    description: 'From architectural lighting for office to decorative lights for homes, explore spotlights, downlights, track lights, pendants, wall lights and chandeliers at Abby Lighting.',
    keywords: `${DEFAULT_KEYWORDS}, architectural light worlds, lighting design`,
    canonical: '/',
    ogImage: `${SITE_URL}/images/figma-update/hero-decorative.png`,
    ogType: 'website',
  },

  collections: {
    title: 'Design Collections | Modular Architectural Lighting | Abby Lighting',
    description: 'Explore signature architectural & decorative lighting collections engineered by Abby Lighting. Discover modular systems, curated finishes, and distinct forms.',
    keywords: 'lighting collections, Symphony IV, Circulo, modular architectural lights, luxury fixtures India',
    canonical: '/collections',
    ogImage: `${SITE_URL}/images/symphony-iv-figma-live/collection.png`,
    ogType: 'website',
  },

  collectionsDetailDefault: {
    title: 'Lighting Collection | Abby Lighting',
    description: 'Explore this signature lighting collection with customizable compositions, curated finishes, and precision optics from Abby Lighting.',
    keywords: 'architectural lighting collection, modular luminaires, lighting system, luxury luminaires',
    canonical: '/collections',
    ogImage: `${SITE_URL}/images/symphony-iv-figma-live/collection.png`,
    ogType: 'website',
  },

  decorativeLights: {
    title: 'Decorative Lighting Collection | Pendants, Wall & Table Lamps | Abby Lighting',
    description: 'Discover sculptural decorative lighting designed and hand-finished in India. Explore pendants, chandeliers, wall lights, and floor fixtures for curated spaces.',
    keywords: 'decorative lights, designer pendants, luxury wall lamps, contemporary chandeliers, hospitality lighting',
    canonical: '/decorative-lights',
    ogImage: `${SITE_URL}/images/decorative/hero.png`,
    ogType: 'website',
  },

  decorativeDetailDefault: {
    title: 'Decorative Fixture | Abby Lighting',
    description: 'Handcrafted decorative luminaire by Abby Lighting. Explore custom finishes, mounting details, and photometrics.',
    keywords: 'decorative luminaire, designer fixture, contemporary light, handcrafted lamp',
    canonical: '/decorative-lights',
    ogImage: `${SITE_URL}/images/decorative/hero.png`,
    ogType: 'website',
  },

  catalogues: {
    title: 'Product Catalogues & Specification Downloads | Abby Lighting',
    description: 'Browse and download Abby Lighting’s complete architectural, decorative, outdoor, and smart lighting catalogues, technical cut sheets, and lookbooks.',
    keywords: 'lighting catalogue download, architectural lighting brochure, PDF specifications, Abby Lighting lookbook',
    canonical: '/catalogues',
    ogImage: `${SITE_URL}/images/figma-update/catalogue.png`,
    ogType: 'website',
  },

  inspiration: {
    title: 'Inspiration & Spaces | Light in Place, Reels & Articles | Abby Lighting',
    description: 'Discover real-world lighting applications, watch curated video reels, and explore design ideas from Abby Lighting’s architectural and decorative projects.',
    keywords: 'lighting inspiration, design ideas, lighting in interiors, architectural lighting reels, lighting projects India',
    canonical: '/inspiration',
    ogImage: `${SITE_URL}/images/inspiration/look-02.png`,
    ogType: 'website',
  },

  blogs: {
    title: 'Insights & Articles | Made in India, Designed for the World | Abby Lighting',
    description: 'Stories from Abby Lighting’s integrated manufacturing facility, lighting engineering philosophy, design trends, and architectural insights.',
    keywords: 'lighting design blog, architectural lighting articles, LED manufacturing India, lighting industry insights',
    canonical: '/blogs',
    ogImage: `${SITE_URL}/images/figma-update/manufacturing.png`,
    ogType: 'website',
  },

  blogDetailDefault: {
    title: 'Blog Article | Abby Lighting',
    description: 'Insights and perspectives on lighting design, engineering, and manufacturing from Abby Lighting.',
    keywords: 'lighting article, lighting design, Abby Lighting journal',
    canonical: '/blogs',
    ogImage: `${SITE_URL}/images/figma-update/manufacturing.png`,
    ogType: 'article',
  },

  lightCalculator: {
    title: 'Professional Ambient Lighting Calculator | Abby Lighting',
    description: 'Estimate required lumens, fixture count, spacing, and illuminance for your room dimensions and target lux levels with Abby’s professional calculator.',
    keywords: 'lighting calculator, lux level calculator, room lumens calculator, fixture spacing tool, lighting design software',
    canonical: '/light-calculator',
    ogImage: `${SITE_URL}/images/light-calculator-cta.png`,
    ogType: 'website',
  },

  search: {
    title: 'Search Products & Catalogues | Abby Lighting',
    description: 'Search across Abby Lighting architectural luminaires, decorative fixtures, specifications, and project archives.',
    keywords: 'search lights, find fixtures, lighting catalogue search',
    canonical: '/search',
    ogImage: DEFAULT_OG_IMAGE,
    ogType: 'website',
    noindex: true, // Internal search pages should typically have noindex to avoid duplicate content
  },
};

/**
 * Standard Favicon & Manifest metadata matching Laravel backend
 */
export const siteIconsMetadata: Metadata['icons'] = {
  icon: [
    { url: '/favicon.ico', sizes: 'any' },
    { url: '/favicon/favicon-16x16.png', sizes: '16x16', type: 'image/png' },
    { url: '/favicon/favicon-32x32.png', sizes: '32x32', type: 'image/png' },
    { url: '/favicon/favicon-96x96.png', sizes: '96x96', type: 'image/png' },
  ],
  apple: [
    { url: '/favicon/apple-icon-57x57.png', sizes: '57x57' },
    { url: '/favicon/apple-icon-60x60.png', sizes: '60x60' },
    { url: '/favicon/apple-icon-72x72.png', sizes: '72x72' },
    { url: '/favicon/apple-icon-76x76.png', sizes: '76x76' },
    { url: '/favicon/apple-icon-114x114.png', sizes: '114x114' },
    { url: '/favicon/apple-icon-120x120.png', sizes: '120x120' },
    { url: '/favicon/apple-icon-144x144.png', sizes: '144x144' },
    { url: '/favicon/apple-icon-152x152.png', sizes: '152x152' },
    { url: '/favicon/apple-icon-180x180.png', sizes: '180x180' },
  ],
  shortcut: '/favicon.ico',
  other: [
    {
      rel: 'apple-touch-icon-precomposed',
      url: '/favicon/apple-icon-precomposed.png',
    },
  ],
};

/**
 * Helper to build comprehensive, type-safe Next.js Metadata for any page
 */
export function buildPageMetadata(
  pageKey: PageKey,
  overrides?: Partial<PageSEOConfig>
): Metadata {
  const base = sitePagesMeta[pageKey];
  const title = overrides?.title || base.title;
  const description = overrides?.description || base.description;
  const keywords = overrides?.keywords || base.keywords || DEFAULT_KEYWORDS;
  const canonicalPath = overrides?.canonical || base.canonical || '/';
  const canonicalUrl = canonicalPath.startsWith('http')
    ? canonicalPath
    : `${SITE_URL}${canonicalPath.startsWith('/') ? '' : '/'}${canonicalPath}`;
  const ogImage = overrides?.ogImage || base.ogImage || DEFAULT_OG_IMAGE;
  const ogType = overrides?.ogType || base.ogType || 'website';
  const noindex = overrides?.noindex ?? base.noindex ?? false;

  return {
    metadataBase: new URL(SITE_URL),
    title,
    description,
    keywords,
    alternates: {
      canonical: canonicalUrl,
    },
    openGraph: {
      title,
      description,
      url: canonicalUrl,
      siteName: SITE_NAME,
      locale: 'en_IN',
      type: ogType,
      images: [
        {
          url: ogImage,
          width: 1200,
          height: 630,
          alt: title,
        },
      ],
    },
    twitter: {
      card: 'summary_large_image',
      title,
      description,
      images: [ogImage],
    },
    robots: noindex
      ? {
        index: false,
        follow: false,
      }
      : {
        index: true,
        follow: true,
        googleBot: {
          index: true,
          follow: true,
          'max-image-preview': 'large',
          'max-snippet': -1,
          'max-video-preview': -1,
        },
      },
  };
}

