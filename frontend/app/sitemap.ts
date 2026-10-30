import type { MetadataRoute } from 'next';
import { API_URL } from '@/lib/config';
import { SITE_URL } from '@/lib/seo/meta-config';

export const dynamic = 'force-dynamic';
export const revalidate = 3600; // Cache and revalidate hourly

interface SitemapApiItem {
  path: string;
  lastmod?: string;
  priority?: number;
  changefreq?: 'always' | 'hourly' | 'daily' | 'weekly' | 'monthly' | 'yearly' | 'never';
  type?: string;
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const baseUrl = SITE_URL.replace(/\/$/, '');

  try {
    const res = await fetch(`${API_URL}/sitemap-urls`, {
      next: { revalidate: 3600 },
      cache: 'no-store',
    });

    if (res.ok) {
      const json = await res.json();
      if (json.success && Array.isArray(json.data) && json.data.length > 0) {
        return json.data.map((item: SitemapApiItem) => {
          const path = item.path.startsWith('/') ? item.path : `/${item.path}`;
          return {
            url: `${baseUrl}${path}`,
            lastModified: item.lastmod ? new Date(item.lastmod) : new Date(),
            changeFrequency: item.changefreq || 'weekly',
            priority: item.priority ?? 0.7,
          };
        });
      }
    }
  } catch (error) {
    console.error('Error fetching dynamic sitemap URLs from API, falling back to core paths:', error);
  }

  // Fallback if API is unreachable
  const fallbackPaths = [
    { path: '/', priority: 1.0, changeFrequency: 'daily' as const },
    { path: '/collections', priority: 0.9, changeFrequency: 'weekly' as const },
    { path: '/decorative-lights', priority: 0.9, changeFrequency: 'weekly' as const },
    { path: '/products', priority: 0.9, changeFrequency: 'weekly' as const },
    { path: '/catalogues', priority: 0.8, changeFrequency: 'weekly' as const },
    { path: '/inspiration', priority: 0.8, changeFrequency: 'weekly' as const },
    { path: '/blogs', priority: 0.8, changeFrequency: 'daily' as const },
    { path: '/company', priority: 0.8, changeFrequency: 'monthly' as const },
    { path: '/contact', priority: 0.8, changeFrequency: 'monthly' as const },
    { path: '/projects', priority: 0.8, changeFrequency: 'weekly' as const },
    { path: '/clients', priority: 0.7, changeFrequency: 'monthly' as const },
    { path: '/abby-smart', priority: 0.7, changeFrequency: 'monthly' as const },
    { path: '/fair-events', priority: 0.7, changeFrequency: 'monthly' as const },
    { path: '/light-calculator', priority: 0.7, changeFrequency: 'monthly' as const },
    { path: '/career', priority: 0.6, changeFrequency: 'monthly' as const },
    { path: '/privacy-policy', priority: 0.4, changeFrequency: 'yearly' as const },
    { path: '/terms-and-conditions', priority: 0.4, changeFrequency: 'yearly' as const },
  ];

  return fallbackPaths.map((item) => ({
    url: `${baseUrl}${item.path}`,
    lastModified: new Date(),
    changeFrequency: item.changeFrequency,
    priority: item.priority,
  }));
}

