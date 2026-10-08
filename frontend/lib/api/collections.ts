import { API_URL, API_BASE } from '@/lib/config';

import { CollectionsApiResponse, CollectionDetailApiResponse } from '@/types/collection';



/**
 * Fetch all collections
 */
export async function getCollections(): Promise<CollectionsApiResponse> {
  try {
    const response = await fetch(`${API_URL}/collections`, {
      cache: 'no-store'
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch collections: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error('Error fetching collections:', error);
    throw error;
  }
}

/**
 * Fetch single collection by slug with all sections
 */
export async function getCollection(slug: string): Promise<CollectionDetailApiResponse> {
  try {
    const response = await fetch(`${API_URL}/collections/${slug}`, {
      cache: 'no-store'
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch collection: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error(`Error fetching collection ${slug}:`, error);
    throw error;
  }
}

/**
 * Fetch paginated parameters for a collection
 */
export async function getCollectionParameters(slug: string, page: number = 1, limit: number = 8) {
  try {
    const response = await fetch(`${API_URL}/collections/${slug}/parameters?page=${page}&limit=${limit}`, {
      cache: 'no-store'
    });
    if (!response.ok) {
      throw new Error(`Failed to fetch collection parameters: ${response.statusText}`);
    }
    return response.json();
  } catch (error) {
    console.error(`Error fetching parameters for ${slug}:`, error);
    throw error;
  }
}
