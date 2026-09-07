// Server-side data fetching functions with Next.js caching
// These functions are designed to run ONLY on the server

import type { DecorativeCategory } from "@/types/decorative-category";
import type { Client } from "@/types/client";
import type { Slider } from "@/types/slider";
import type { Project } from "@/types/project";
import type { NewsItem } from "@/types/news-item";
import type { ManufacturingSection } from "@/types/manufacturing-section";
import type { NewArrivalCategory } from "@/types/new-arrival";
import type { LightWorld } from "@/types/light-world";

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

/**
 * Fetch decorative categories with parent-child relationships
 * Revalidate: 3600s (1 hour) - categories rarely change
 */
export async function fetchDecorativeCategories(): Promise<DecorativeCategory[]> {
  try {
    const response = await fetch(`${API_URL}/api/decorative-categories`, {
      next: { revalidate: 3600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch decorative categories');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching decorative categories:', error);
    return [];
  }
}

/**
 * Fetch clients
 * Revalidate: 3600s (1 hour) - client list is stable
 */
export async function fetchClients(): Promise<Client[]> {
  try {
    const response = await fetch(`${API_URL}/api/clients`, {
      next: { revalidate: 3600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch clients');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching clients:', error);
    return [];
  }
}

/**
 * Fetch home sliders
 * Revalidate: 600s (10 min) - sliders may be updated for promotions
 */
export async function fetchSliders(): Promise<{ web: Slider[]; mobile: Slider[] }> {
  try {
    const response = await fetch(`${API_URL}/api/sliders`, {
      next: { revalidate: 600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch sliders');
      return { web: [], mobile: [] };
    }

    const data = await response.json();
    return data.success && data.data ? data.data : { web: [], mobile: [] };
  } catch (error) {
    console.error('Error fetching sliders:', error);
    return { web: [], mobile: [] };
  }
}

/**
 * Fetch projects
 * Revalidate: 1800s (30 min) - projects updated occasionally
 */
export async function fetchProjects(limit: number = 6): Promise<Project[]> {
  try {
    const response = await fetch(`${API_URL}/api/projects?limit=${limit}`, {
      next: { revalidate: 1800 }
    });

    if (!response.ok) {
      console.error('Failed to fetch projects');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching projects:', error);
    return [];
  }
}

/**
 * Fetch news items
 * Revalidate: 300s (5 min) - news is timely content
 */
export async function fetchNewsItems(): Promise<NewsItem[]> {
  try {
    const response = await fetch(`${API_URL}/api/news-items`, {
      next: { revalidate: 300 }
    });

    if (!response.ok) {
      console.error('Failed to fetch news items');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching news items:', error);
    return [];
  }
}

/**
 * Fetch manufacturing section
 * Revalidate: 3600s (1 hour) - rarely changes
 */
export async function fetchManufacturingSection(): Promise<ManufacturingSection | null> {
  try {
    const response = await fetch(`${API_URL}/api/manufacturing-section`, {
      next: { revalidate: 3600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch manufacturing section');
      return null;
    }

    const data = await response.json();
    return data.success && data.data ? data.data : null;
  } catch (error) {
    console.error('Error fetching manufacturing section:', error);
    return null;
  }
}

/**
 * Fetch new arrival categories with products
 * Revalidate: 600s (10 min) - product updates need to show relatively quickly
 */
export async function fetchNewArrivals(): Promise<NewArrivalCategory[]> {
  try {
    const response = await fetch(`${API_URL}/api/products/new-arrivals`, {
      next: { revalidate: 600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch new arrivals');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching new arrivals:', error);
    return [];
  }
}

/**
 * Fetch light worlds ("Four worlds of light" homepage section)
 * Revalidate: 3600s (1 hour) — updated via admin panel only
 */
export async function fetchLightWorlds(): Promise<LightWorld[]> {
  try {
    const response = await fetch(`${API_URL}/api/light-worlds`, {
      next: { revalidate: 3600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch light worlds');
      return [];
    }

    const data = await response.json();
    return data.success && data.data ? data.data : [];
  } catch (error) {
    console.error('Error fetching light worlds:', error);
    return [];
  }
}

/**
 * Fetch single decorative product details by slug
 */
export async function fetchDecorativeProductDetail(slug: string): Promise<any | null> {
  try {
    const response = await fetch(`${API_URL}/api/decorative-products/${slug}`, {
      next: { revalidate: 300 }
    });

    if (!response.ok) {
      return null;
    }

    const data = await response.json();
    return data.success && data.data ? data.data : null;
  } catch (error) {
    console.error(`Error fetching decorative product detail for ${slug}:`, error);
    return null;
  }
}

/**
 * Fetch all home page data in parallel
 * This is the main function to use in page.tsx
 */
export async function fetchHomePageData() {
  const [sliders, projects, clients, newsItems, manufacturingSection, newArrivalCategories, lightWorlds] = await Promise.all([
    fetchSliders(),
    fetchProjects(6),
    fetchClients(),
    fetchNewsItems(),
    fetchManufacturingSection(),
    fetchNewArrivals(),
    fetchLightWorlds()
  ]);

  return {
    sliders: sliders.web, // Use web sliders for desktop
    projects,
    clients,
    newsItems,
    manufacturingSection,
    newArrivalCategories,
    lightWorlds
  };
}
