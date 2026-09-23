// Server-side data fetching functions with Next.js caching
// These functions are designed to run ONLY on the server


import type { Client } from "@/types/client";
import type { Slider } from "@/types/slider";
import type { Project } from "@/types/project";
import type { NewsItem } from "@/types/news-item";
import type { ManufacturingSection } from "@/types/manufacturing-section";
import type { HomeCatalogueSection } from "@/types/home-catalogue-section";
import type { NewArrivalCategory } from "@/types/new-arrival";
import type { LightWorld } from "@/types/light-world";

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';


/**
 * Fetch clients
 * Revalidate: 3600s (1 hour) - client list is stable
 */
export async function fetchClients(): Promise<Client[]> {
  try {
    const response = await fetch(`${API_URL}/api/clients`, {
      cache: 'no-store'
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
 */
export async function fetchSliders(): Promise<{ web: Slider[]; mobile: Slider[] }> {
  try {
    const response = await fetch(`${API_URL}/api/sliders`, {
      cache: 'no-store'
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
 */
export async function fetchProjects(limit: number = 6, featured: boolean = true): Promise<Project[]> {
  try {
    const url = `${API_URL}/api/projects?limit=${limit}${featured ? '&featured=1' : ''}`;
    const response = await fetch(url, {
      cache: 'no-store'
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
 */
export async function fetchNewsItems(): Promise<NewsItem[]> {
  try {
    const response = await fetch(`${API_URL}/api/news-items`, {
      cache: 'no-store'
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
 */
export async function fetchManufacturingSection(): Promise<ManufacturingSection | null> {
  try {
    const response = await fetch(`${API_URL}/api/manufacturing-section`, {
      cache: 'no-store'
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
 */
export async function fetchNewArrivals(): Promise<NewArrivalCategory[]> {
  try {
    const response = await fetch(`${API_URL}/api/products/new-arrivals`, {
      cache: 'no-store'
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
 */
export async function fetchLightWorlds(): Promise<LightWorld[]> {
  try {
    const response = await fetch(`${API_URL}/api/light-worlds`, {
      cache: 'no-store'
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
 * Fetch home catalogue section ("Find the right catalogue" homepage section)
 */
export async function fetchHomeCatalogueSection(): Promise<HomeCatalogueSection | null> {
  try {
    const response = await fetch(`${API_URL}/api/home-catalogue-section`, {
      cache: 'no-store'
    });

    if (!response.ok) {
      console.error('Failed to fetch home catalogue section');
      return null;
    }

    const data = await response.json();
    return data.success && data.data ? data.data : null;
  } catch (error) {
    console.error('Error fetching home catalogue section:', error);
    return null;
  }
}

/**
 * Fetch all home page data in parallel
 * This is the main function to use in page.tsx
 */
export async function fetchHomePageData() {
  const [sliders, projects, clients, newsItems, manufacturingSection, homeCatalogueSection, newArrivalCategories, lightWorlds] = await Promise.all([
    fetchSliders(),
    fetchProjects(6),
    fetchClients(),
    fetchNewsItems(),
    fetchManufacturingSection(),
    fetchHomeCatalogueSection(),
    fetchNewArrivals(),
    fetchLightWorlds()
  ]);

  return {
    sliders: sliders.web, // Use web sliders for desktop
    projects,
    clients,
    newsItems,
    manufacturingSection,
    homeCatalogueSection,
    newArrivalCategories,
    lightWorlds
  };
}
